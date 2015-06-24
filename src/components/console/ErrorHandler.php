<?php
/**
 * Console compatible error handler
 */

namespace bedezign\yii2\audit\components\console;

use bedezign\yii2\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package bedezign\yii2\audit\components\console
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}