<?php

namespace bedezign\yii2\audit;

use yii\base\BootstrapInterface;
use yii\console\Application;


/**
 * Bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $app->controllerMap['auditing'] = 'bedezign\yii2\audit\commands\AuditController';
        }
    }
}
