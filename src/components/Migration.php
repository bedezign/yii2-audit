<?php

namespace bedezign\yii2\audit\components;

use bedezign\yii2\audit\Audit;


/**
 * Class Migration
 * @package bedezign\yii2\audit\components
 */
class Migration extends \yii\db\Migration
{

    /**
     *
     */
    public function init()
    {
        /** @var Audit $audit */
        $audit = \Yii::$app->getModule('audit');
        if ($audit) {
            $this->db = $audit->db;
        }
        parent::init();
    }

}