<?php
/**
 * Error Handler as a replacement for the existing one.
 */

namespace bedezign\yii2\audit\components\base;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditError;

trait ErrorHandlerTrait
{
    public function logException($exception)
    {
        try {
            $audit = Audit::current();
            // Make sure not to interfere with out of memory errors, there's not enough spare room to load everything
            if ($audit && strncmp($exception->getMessage(), 'Allowed memory size of', 22)) {
                $entry = $audit->getEntry(true);
                if ($entry) {
                    AuditError::log($entry, $exception);
                    $entry->finalize();
                }
            }
        }
        catch (\Exception $e) {}

        parent::logException($exception);
    }
}