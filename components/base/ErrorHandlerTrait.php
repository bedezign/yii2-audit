<?php
/**
 * Error Handler as a replacement for the existing one.
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category  components.base
 * @copyright 2014 B&E DeZign
 */

namespace bedezign\yii2\audit\components\base;

use bedezign\yii2\audit\Auditing;
use bedezign\yii2\audit\models\AuditError;

trait ErrorHandlerTrait
{
    protected function logException($exception)
    {
        try {
            $auditing = Auditing::current();
            // Make sure not to interfere with out of memory errors, there's not enough spare room to load everything
            if ($auditing && strncmp($exception->getMessage(), 'Allowed memory size of', 22)) {
                $entry = $auditing->getEntry(true);
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