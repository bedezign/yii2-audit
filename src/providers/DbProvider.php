<?php

namespace bedezign\yii2\audit\providers;


use bedezign\yii2\audit\Provider;
use Yii;
use yii\log\Logger;

/**
 * Class DbProvider
 * @package bedezign\yii2\audit\providers
 */
class DbProvider extends Provider
{

    public function finalize()
    {

        //debug(Yii::$app->log->targets['audit']); die;
        //
        //$target = $this->module->auditTarget;
        //debug($target); die;
        //$messages = $target->filterMessages($target->messages, Logger::LEVEL_PROFILE, ['yii\db\Command::query', 'yii\db\Command::execute']);
        //
        //debug($messages); die;
        //Yii::getLogger()->calculateTimings($this->data['messages']);
    }

}