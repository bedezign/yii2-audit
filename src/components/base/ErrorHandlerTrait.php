<?php
/**
 * Error Handler allows errors to be logged to the audit_error table.
 */

namespace bedezign\yii2\audit\components\base;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditError;
use Exception;
use Yii;

/**
 * ErrorHandlerTrait
 * @package bedezign\yii2\audit\components\base
 */
trait ErrorHandlerTrait
{
    /**
     * @param Exception $exception
     */
    public function logException($exception)
    {
        try {

            $isMemoryError = strncmp($exception->getMessage(), 'Allowed memory size of', 22) === 0;

            $module = Audit::getInstance();
            if (!$module && !$isMemoryError) {
                // Only attempt to load the module if this isn't an out of memory error, not enough room otherwise
                $module = \Yii::$app->getModule(Audit::findModuleIdentifier());
            }

            if (!$module) {
                throw new \Exception('Audit module cannot be loaded');
            }

            $entry = $module->getEntry(!$isMemoryError);
            if ($entry) {
                AuditError::log($entry, $exception);
                $entry->finalize();
            }

        } catch (\Exception $e) {
            // if we catch an exception here, let it slide, we don't want recursive errors killing the script
        }

        parent::logException($exception);
    }
}