<?php

namespace bedezign\yii2\audit;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Bootstrap
 * @package bedezign\yii2\audit
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
        // Make sure to register the base folder as alias as well or things like assets won't work anymore
        \Yii::setAlias('@bedezign/yii2/audit', __DIR__);

        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['audit'] = 'bedezign\yii2\audit\commands\AuditController';
        }

        $moduleName = Audit::findModuleIdentifier();
        if ($moduleName) {
            // The module was added in the configuration, make sure to add it to the application bootstrap so it gets loaded
            $app->bootstrap[] = $moduleName;
            $app->bootstrap = array_unique($app->bootstrap, SORT_REGULAR);
        }

        if ($app->has('i18n')) {
            $app->i18n->translations['audit'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath'       => '@bedezign/yii2/audit/messages',
            ];
        }
    }
}
