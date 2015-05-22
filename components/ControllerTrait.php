<?php
/**
 * Base implementation to be used in all user interaction controllers, mainly security wise
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
        if ($this->module->accessUsers === null && $this->module->accessRoles === null)
            // No user authentication active, skip adding the filter
            return [];

        $rule = ['allow' => 'allow'];
        if ($this->module->accessRoles) {
            // Add allowed roles
            $rule['roles'] = $this->module->accessRoles;
        }

        if ($this->module->accessUsers) {
            // Specific users only? Use callback
            $rule['matchCallback'] = function ($rule, $action) {
                return in_array(\Yii::$app->user->id, $action->controller->module->accessUsers);
            };
        }

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $rule
                ],
            ],
        ];
    }
}