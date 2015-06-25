<?php

namespace bedezign\yii2\audit\panels;

/**
 * JavascriptPanel
 * @package bedezign\yii2\audit\panels
 */
class JavascriptPanel extends AuditBasePanel
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('audit', 'Javascript');
    }

    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->javascripts) . ')</small>';
    }
}