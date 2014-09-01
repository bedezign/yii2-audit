<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category  components.console
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\components\console;

use bedezign\yii2\audit\components\base\ErrorHandlerTrait;

class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}