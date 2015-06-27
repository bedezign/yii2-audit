<?php

namespace bedezign\yii2\audit\commands;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditTrail;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Task runner commands for Audit.
 *
 * @package bedezign\yii2\audit\commands
 */
class AuditController extends \yii\console\Controller
{

    /**
     * Clean up the audit data according to the settings.
     */
    public function actionCleanup()
    {
        $audit = Audit::getInstance();
        if ($audit->maxAge === null)
            return;

        $entry = AuditEntry::tableName();
        $errors = AuditError::tableName();
        $data = AuditData::tableName();
        $javascript = AuditJavascript::tableName();
        $trail = AuditTrail::tableName();

        $threshold = time() - ($audit->maxAge * 86400);

        AuditEntry::getDb()->createCommand(<<<SQL
DELETE FROM $entry, $errors, $data, $javascript, $trail USING $entry
  INNER JOIN $errors ON $errors.entry_id = $entry.id
  INNER JOIN $data ON $data.entry_id = $entry.id
  INNER JOIN $javascript ON $javascript.entry_id = $entry.id
  INNER JOIN $trail ON $trail.entry_id = $entry.id
  WHERE $entry.created < FROM_UNIXTIME($threshold)
SQL
        )->execute();

    }


    /**
     * Email errors to support email.
     */
    public function actionErrorEmail()
    {
        $email = Yii::$app->params['supportEmail'];

        // find all errors to email
        $batch = AuditError::find()->where(['status' => null])->batch();
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
                $model->status = 'emailed';
                $model->save(false, ['status']);

            }
        }

    }

}
