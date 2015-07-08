<?php

namespace tests\app\panels;

use bedezign\yii2\audit\components\panels\Panel;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\web\View;

/**
 * ViewsPanel
 * @package tests\app\panels
 */
class ViewsPanel extends Panel
{
    /**
     * @var array
     */
    private $_viewFiles = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(View::className(), View::EVENT_BEFORE_RENDER, function (ViewEvent $event) {
            $this->_viewFiles[] = $event->sender->getViewFile();
        });
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('audit', 'Views');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return '<ol><li>' . implode('<li>', $this->data) . '</ol>';
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->_viewFiles;
    }
}