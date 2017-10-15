<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\components\panels\RendersSummaryChartTrait;
use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\models\AuditMailSearch;
use Swift_Message;
use Swift_Mime_Attachment;
use Swift_Mime_MimePart;
use Yii;
use yii\base\Event;
use yii\grid\GridViewAsset;
use yii\mail\BaseMailer;
use yii\mail\MessageInterface;
use yii\swiftmailer\Message;

/**
 * MailPanel
 * @package bedezign\yii2\audit\panels
 */
class MailPanel extends Panel
{
    use RendersSummaryChartTrait;

    /**
     * Store full email data
     *
     * /!\ Set this to true will increase database size /!\
     */
    public $storeData = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(BaseMailer::className(), BaseMailer::EVENT_AFTER_SEND, function ($event) {
            $this->record($event);
        });
    }

    /**
     * @param Event $event
     * @return null|static
     */
    public function record($event)
    {
        /* @var $message MessageInterface */
        $message = $event->message;
        $entry = Audit::getInstance()->getEntry(true);

        $mail = new AuditMail();
        $mail->entry_id = $entry->id;
        $mail->successful = $event->isSuccessful;
        $mail->from = self::convertParams($message->getFrom());
        $mail->to = self::convertParams($message->getTo());
        $mail->reply = self::convertParams($message->getReplyTo());
        $mail->cc = self::convertParams($message->getCc());
        $mail->bcc = self::convertParams($message->getBcc());
        $mail->subject = $message->getSubject();

        // add more information when message is a SwiftMailer message
        if ($message instanceof Message) {
            /* @var $swiftMessage Swift_Message */
            $swiftMessage = $message->getSwiftMessage();
            if ($swiftMessage->getContentType() == 'text/html') {
                $mail->html = $swiftMessage->getBody();
            } else {
                $mail->text = $swiftMessage->getBody();
            }
            foreach ($swiftMessage->getChildren() as $part) {
                /* @var $part Swift_Mime_MimePart */
                if ($part instanceof Swift_Mime_Attachment) {
                    continue;
                }
                $contentType = $part->getContentType();
                if ($contentType == 'text/plain') {
                    $mail->text = $part->getBody();
                } elseif ($contentType == 'text/html') {
                    $mail->html = $part->getBody();
                }
            }
        }

        // makes full email available for download
        if ($this->storeData) {
            $mail->data = $message->toString();
        }

        return $mail->save(false) ? $mail : null;
    }

    /**
     * @param $attr
     * @return string
     */
    private static function convertParams($attr)
    {
        if (is_array($attr)) {
            $attr = implode(', ', array_keys($attr));
        }
        return $attr;
    }

    /**
     * @inheritdoc
     */
    public function hasEntryData($entry)
    {
        return count($entry->mails) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('audit', 'Mails');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->mails) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new AuditMailSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditMailSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/mail/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getIndexUrl()
    {
        return ['mail/index'];
    }

    /**
     * @inheritdoc
     */
    protected function getChartModel()
    {
        return AuditMail::className();
    }

    /**
     * @inheritdoc
     */
    public function getChart()
    {
        return \Yii::$app->view->render('panels/mail/chart', [
            'panel' => $this,
            'chartData' => $this->getChartData()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

    /**
     * @inheritdoc
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditMail::deleteAll([
            '<=', 'created', date('Y-m-d 23:59:59', strtotime("-$maxAge days"))
        ]);
    }
}