<?php
/**
 * Base implementation to be used in the controllers, mainly security wise
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\components;

use yii\filters\AccessControl;

trait ControllerTrait
{
    /**
     * Default behaviors function, you can always override this
     */
    public function behaviors()
    {
        return $this->getAccessControlFilter();
    }

    public function getAccessControlFilter()
    {
        return
        [
            'access' =>
            [
                'class' => \yii\filters\AccessControl::className(),
                'rules' =>
                [
                    [
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => [$this, 'checkAccess']
                    ]
                ]
            ]
        ];
    }

    public function checkAccess()
    {
        $user = \Yii::$app->user;
        if ($user->isGuest)
            return false;

        $module = \Yii::$app->getModule('auditing');
        if (!$module)
            return false;

        if (is_array($module->accessUsers) && in_array($user->id, $module->accessUsers))
            return true;

        if (is_array($module->accessRoles))
            foreach ($module->accessRoles as $role)
                if ($user->can($role))
                    return true;

        return false;
    }
}