<?php

namespace bedezign\yii2\audit\components;

use bedezign\yii2\audit\Audit;
use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\User;

class Access extends Component
{

    /**
     * @return array
     */
    public static function getAccessControlFilter()
    {
        return [
            'class' => AccessControl::className(),
            'rules' => [['allow' => self::checkAccess()]]
        ];

        /*
        $audit = Audit::getInstance();
        if ($audit->accessUsers === null && $audit->accessRoles === null && $audit->accessIps === null) {
            // No user authentication active, skip adding the filter
            return [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [['allow' => true]]
            ];
        }

        $rule = ['allow' => 'allow'];

        if (!empty($audit->accessIps)) {
            // Add allowed ips
            $rule['ips'] = $audit->accessIps;
        }

        if (!empty($audit->accessRoles)) {
            // Add allowed roles
            $rule['roles'] = $audit->accessRoles;
        }

        if (!empty($audit->accessUsers)) {
            $users = $audit->accessUsers;
            // Specific users only? Use callback
            $rule['matchCallback'] = function () use ($users) {
                return in_array(Yii::$app->user->id, $users);
            };
        }

        return ['class' => \yii\filters\AccessControl::className(), 'rules' => [$rule]];
        */
    }

    /**
     * Check if the current user has access to the audit functionality
     * @return bool
     */
    public static function checkAccess()
    {
        $audit = Audit::getInstance();
        if ($audit->accessIps === null && $audit->accessRoles === null && $audit->accessUsers === null) {
            return true;
        }
        if (self::checkAccessIps($audit->accessIps)) {
            return true;
        }
        if (self::checkAccessRoles($audit->accessRoles)) {
            return true;
        }
        if (self::checkAccessUsers($audit->accessUsers)) {
            return true;
        }
        return false;
    }

    /**
     * @param array $users
     * @return bool
     */
    private static function checkAccessUsers($users)
    {
        if (!empty($users)) {
            $users = ArrayHelper::toArray($users);
            if (in_array(Yii::$app->user->id, $users)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $roles
     * @return bool
     */
    private static function checkAccessRoles($roles)
    {
        if (empty($roles)) {
            return false;
        }
        /** @var User $user */
        $user = Instance::ensure('user', User::className());
        $roles = ArrayHelper::toArray($roles);
        foreach ($roles as $role) {
            if ($role === '?' && $user->getIsGuest()) {
                return true;
            } elseif ($role === '@' && !$user->getIsGuest()) {
                return true;
            } elseif ($user->can($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $ips
     * @return bool
     */
    private static function checkAccessIps($ips)
    {
        if (!empty($ips)) {
            $ips = ArrayHelper::toArray($ips);
            if (in_array(Yii::$app->request->getUserIP(), $ips)) {
                return true;
            }
        }
        return false;
    }

}