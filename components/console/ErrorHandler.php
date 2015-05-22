<?php
/**
 * Console compatible error handler
 */

namespace bedezign\yii2\audit\components\console;

use bedezign\yii2\audit\components\base\ErrorHandlerTrait;

class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}