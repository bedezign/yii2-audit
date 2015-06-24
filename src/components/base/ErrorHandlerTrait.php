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

            // Make sure not to interfere with out of memory errors, there's not enough spare room to load everything
            if (strncmp($exception->getMessage(), 'Allowed memory size of', 22) == 0) {
                return;
            }

            /** @var Audit $audit */
            $audit = Yii::$app->getModule(Audit::findModuleIdentifier());
            if ($audit) {
                $entry = $audit->getEntry(true);
                if ($entry) {
                    AuditError::log($entry, $exception);
                    $entry->finalize();
                }
            }

        } catch (\Exception $e) {
        }

        parent::logException($exception);
    }
}