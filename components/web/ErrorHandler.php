<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category  components.web
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\components\web;

use bedezign\yii2\audit\components\base\ErrorHandlerTrait;

class ErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}