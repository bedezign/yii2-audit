<?php

namespace bedezign\yii2\audit\commands;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Task runner commands for Audit.
 *
 * @package bedezign\yii2\audit\commands
 */
class AuditController extends Controller
{

    /**
     * @var bool True to cleanup the AuditEntry.
     */
    public $entry;

    /**
     * @var string|null Comma separated list of panels to cleanup.
     */
    public $panels;

    /**
     * @var int|null Max age in days to cleanup, if null then the panel settings are used.
     */
    public $age;

    /**
     * @var bool True to assume Yes to all queries and do not prompt
     */
    public $assumeYesAllQuery;

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ($actionID == 'cleanup') ? ['entry', 'panels', 'age', 'assumeYesAllQuery'] : []
        );
    }

    /**
     * Cleanup the Audit data
     *
     * @return int|void
     */
    public function actionCleanup()
    {
        /** @var Audit $audit */
        $audit = Yii::$app->getModule(Audit::findModuleIdentifier());
        $panels = $this->panels !== null ? explode(',', $this->panels) : array_keys($audit->panels);

        // summary
        $this->preCleanupSummary($this->entry, $panels, $this->age, $this->assumeYesAllQuery);
        $assumeYesAllQuery = strtolower($this->assumeYesAllQuery) == "true";

        // confirm
        if ($assumeYesAllQuery || $this->confirm('Cleanup the above data?')) {
            // cleanup panels
            foreach ($panels as $id) {
                if (!$this->cleanupPanel($id, $this->age)) {
                    $this->stdout("\nCleanup failed. The rest of the cleanups are canceled.\n", Console::FG_RED);
                    return self::EXIT_CODE_ERROR;
                }
            }
            // cleanup audit_entry
            if ($this->entry) {
                if (!$this->cleanupEntry($this->age)) {
                    $this->stdout("\nCleanup failed.\n", Console::FG_RED);
                    return self::EXIT_CODE_ERROR;
                }
            }
            // success!
            $this->stdout("\nCleanup was successful.\n", Console::FG_GREEN);
        }
        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Displays a summary of the data and dates to clean
     *
     * @param bool $entry
     * @param array $panels
     * @param int|null $maxAge
     */
    protected function preCleanupSummary($entry, $panels, $maxAge)
    {
        $audit = Audit::getInstance();

        // heading
        $n = count($panels);
        $this->stdout("Total $n " . ($n === 1 ? 'cleanup' : 'cleanups') . " to be applied:\n", Console::FG_YELLOW);
        $this->stdout("\t" . 'DATA                      CLEANUP TO DATETIME' . "\n");
        $this->stdout("\t" . '---------------------------------------------' . "\n");

        // audit panels
        foreach ($panels as $id) {
            /** @var Panel $panel */
            $panel = $audit->getPanel($id);
            $age = $maxAge !== null ? $maxAge : $panel->maxAge;
            $dots = str_repeat('.', 24 - strlen($id));
            if ($age !== null) {
                $date = date('Y-m-d 23:59:59', strtotime("-$age days"));
                $this->stdout("\t" . $id . ' ' . $dots . ' ' . $date . "\n");
            } else {
                $this->stdout("\t" . $id . ' ' . $dots . ' no maxAge, skipping' . "\n");
            }
        }

        // audit entry
        if ($entry) {
            $maxAge = $maxAge !== null ? $maxAge : $audit->maxAge;
            $date = $maxAge !== null ? date('Y-m-d 23:59:59', strtotime("-$maxAge days")) : 'no maxAge, skipping';
            $this->stdout("\t" . 'AuditEntry .............. ' . $date . "\n");
        }

        $this->stdout("\n");
    }

    /**
     * Cleans the AuditEntry data
     *
     * @param $maxAge
     * @return bool
     */
    protected function cleanupEntry($maxAge)
    {
        $maxAge = $maxAge !== null ? $maxAge : Audit::getInstance()->maxAge;
        if ($maxAge === null) {
            $this->stdout("\n*** skipped AuditEntry\n", Console::FG_PURPLE);
            return true;
        }
        $date = date('Y-m-d 23:59:59', strtotime("-$maxAge days"));
        $this->stdout("\n*** cleaning AuditEntry", Console::FG_YELLOW);
        $start = microtime(true);
        $count = AuditEntry::deleteAll(['<=', 'created', $date]);
        if ($count !== false) {
            $time = microtime(true) - $start;
            $this->stdout("\n*** cleaned AuditEntry (records: " . $count . ",time: " . sprintf("%.3f", $time) . "s)\n", Console::FG_GREEN);
            return true;
        }
        $time = microtime(true) - $start;
        $this->stdout("\n*** failed to clean AuditEntry (time: " . sprintf("%.3f", $time) . "s)\n", Console::FG_RED);
        return false;
    }

    /**
     * Cleans the Panel data
     *
     * @param $id
     * @param $maxAge
     * @return bool
     */
    protected function cleanupPanel($id, $maxAge)
    {
        /** @var Panel $panel */
        $panel = Audit::getInstance()->getPanel($id);
        $age = $maxAge !== null ? $maxAge : $panel->maxAge;
        if ($age === null) {
            $this->stdout("\n*** skipped $id\n", Console::FG_PURPLE);
            return true;
        }
        $this->stdout("\n*** cleaning $id", Console::FG_YELLOW);
        $start = microtime(true);
        $count = $panel->cleanup($maxAge);
        if ($count !== false) {
            $time = microtime(true) - $start;
            $this->stdout("\n*** cleaned $id (records: " . $count . ", time: " . sprintf("%.3f", $time) . "s)\n", Console::FG_GREEN);
            return true;
        }
        $time = microtime(true) - $start;
        $this->stdout("\n*** failed to clean $id (time: " . sprintf("%.3f", $time) . "s)\n", Console::FG_RED);
        return false;
    }

    /**
     * Email errors to support email.
     *
     * @param string|null $email
     * @return int
     */
    public function actionErrorEmail($email = null)
    {
        $email = $email ? $email : Yii::$app->params['supportEmail'];

        // find all errors to email
        $batch = AuditError::find()->where(['emailed' => 0])->batch();
        foreach ($batch as $auditErrors) {
            /** @var AuditError $model */
            foreach ($auditErrors as $model) {

                // define params and message
                $url = ['audit/default/view', 'id' => $model->entry_id];
                $params = [
                    'entry_id' => $model->entry_id,
                    'message' => $model->message,
                    'file' => $model->file,
                    'line' => $model->line,
                    'url' => Url::to($url),
                    'link' => Html::a(Yii::t('audit', 'view audit entry'), $url),
                ];
                $message = [
                    'subject' => Yii::t('audit', 'Audit Error in Audit Entry #{entry_id}', $params),
                    'text' => Yii::t('audit', '{message}' . "\n" . 'in {file} on line {line}.' . "\n" . '-- {url}', $params),
                    'html' => Yii::t('audit', '<b>{message}</b><br />in <i>{file}</i> on line <i>{line}</i>.<br/>-- {link}', $params),
                ];

                // send email
                Yii::$app->mailer->compose()
                    ->setFrom([$email => 'Audit :: ' . Yii::$app->name])
                    ->setTo($email)
                    ->setSubject($message['subject'])
                    ->setTextBody($message['text'])
                    ->setHtmlBody($message['html'])
                    ->send();

                // mark as emailed
                $model->emailed = 1;
                $model->save(false, ['emailed']);

            }
        }
        return self::EXIT_CODE_NORMAL;
    }

}
