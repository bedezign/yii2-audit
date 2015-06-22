<?php

namespace bedezign\yii2\audit\commands;

use bedezign\yii2\audit\models\AuditError;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Task runner commands for Auditing.
 *
 * @package bedezign\yii2\audit\commands
 */
class AuditController extends \yii\console\Controller
{

    /**
     * Email errors to support email.
     */
    public function actionErrorEmail()
    {
        $email = Yii::$app->params['supportEmail'];

        // find all errors to email
        $batch = AuditError::find()->where(['emailed' => 0])->batch();
        foreach ($batch as $auditErrors) {
            /** @var AuditError $model */
            foreach ($auditErrors as $model) {

                // define params and message
                $url = ['auditing/default/view', 'id' => $model->audit_id];
                $params = [
                    'audit_id' => $model->audit_id,
                    'message' => $model->message,
                    'file' => $model->file,
                    'line' => $model->line,
                    'url' => Url::to($url),
                    'link' => Html::a(Yii::t('audit', 'view audit entry'), $url),
                ];
                $message = [
                    'subject' => Yii::t('audit', 'Audit Error in Audit Entry #{audit_id}', $params),
                    'text' => Yii::t('audit', '{message}' . "\n" . 'in {file} on line {line}.' . "\n" . '{url}.', $params),
                    'html' => Yii::t('audit', '<b>{message}</b><br />in <i>{file}</i> on line <i>{line}</i>.<br/>{link}.', $params),
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

    }

}
