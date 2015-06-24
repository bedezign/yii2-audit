<?php
/**
 * Error handler version for web based modules
 */

namespace bedezign\yii2\audit\components\web;

use bedezign\yii2\audit\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package bedezign\yii2\audit\components\web
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}