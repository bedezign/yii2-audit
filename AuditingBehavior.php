<?php
namespace bedezign\yii2\audit;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

use bedezign\yii2\audit\models\AuditTrail;

class AuditingBehavior extends Behavior
{

    private $_oldattributes = [];
    /**
     * Array with fields to save
     * You don't need to configure both `allowed` and `ignored`
     * @var array
     */
    public $allowed = [];
    /**
     * Array with fields to ignore
     * You don't need to configure both `allowed` and `ignored`
     * @var array
     */
    public $ignored = [];
    /**
     * Array with classes to ignore
     * @var array
     */
    public $ignoredClasses = [];
    /**
     * Skip fields where bouth old and new values are NULL
     * @var boolean
     */
    public $skipNulls = true;
    /**
     * Is the behavior is active or not
     * @var boolean
     */
    public $active = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterDelete($event)
    {
        $this->leaveTrail('DELETE');
    }

    public function afterFind($event)
    {
        $this->setOldAttributes($this->owner->getAttributes());
    }

    public function afterInsert($event)
    {
        $this->audit(true);
    }

    public function afterUpdate($event)
    {
        $this->audit(false);
    }

    public function audit($insert)
    {

        $allowedFields = $this->allowed;
        $ignoredFields = $this->ignored;
        $ignoredClasses = $this->ignoredClasses;

        $newattributes = $this->owner->getAttributes();
        $oldattributes = $this->getOldAttributes();

        // Lets check if the whole class should be ignored
        if (sizeof($ignoredClasses) > 0) {
            if (array_search(get_class($this->owner), $ignoredClasses) !== false)
                return;
        }

        // Lets unset fields which are not allowed
        if (sizeof($allowedFields) > 0) {
            foreach ($newattributes as $f => $v) {
                if (array_search($f, $allowedFields) === false)
                    unset($newattributes[$f]);
            }

            foreach ($oldattributes as $f => $v) {
                if (array_search($f, $allowedFields) === false)
                    unset($oldattributes[$f]);
            }
        }

        // Lets unset fields which are ignored
        if (sizeof($ignoredFields) > 0) {
            foreach ($newattributes as $f => $v) {
                if (array_search($f, $ignoredFields) !== false)
                    unset($newattributes[$f]);
            }

            foreach ($oldattributes as $f => $v) {
                if (array_search($f, $ignoredFields) !== false)
                    unset($oldattributes[$f]);
            }
        }


        // If no difference then WHY?
        // There is some kind of problem here that means "0" and 1 do not diff for array_diff so beware: stackoverflow.com/questions/12004231/php-array-diff-weirdness :S
        if (count(array_diff_assoc($newattributes, $oldattributes)) <= 0)
            return;

        // If this is a new record lets add a CREATE notification
        if ($insert) {
            $this->leaveTrail('CREATE');
        }

        // Now lets actually write the attributes
        $this->auditAttributes($insert, $newattributes, $oldattributes);

        // Reset old attributes to handle the case with the same model instance updated multiple times
        $this->setOldAttributes($this->owner->getAttributes());
    }

    public function auditAttributes($insert, $newattributes, $oldattributes = [])
    {
        foreach ($newattributes as $name => $value) {
            $old = isset($oldattributes[$name]) ? $oldattributes[$name] : '';

            // If we are skipping nulls then lets see if both sides are null
            if ($this->skipNulls && empty($old) && empty($value)) {
                continue;
            }

            // If they are not the same lets write an audit log
            if ($value != $old) {
                $trail = $this->leaveTrail($insert ? 'SET' : 'CHANGE', $name, $value, $old);
            }
        }
    }

    public function leaveTrail($action, $name = null, $value = null, $old_value = null)
    {
        if ($this->active) {
            $log = new AuditTrail;
            $log->audit_id = Auditing::current()->getEntry(true)->id;
            $log->old_value = $old_value;
            $log->new_value = $value;
            $log->action = $action;
            $log->model = $this->owner->className(); // Gets a plain text version of the model name
            $log->model_id = (string) $this->getNormalizedPk();
            $log->field = $name;
            $log->stamp = new Expression('NOW()');

            return $log->save();
        }
        return true;
    }

    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldattributes = $value;
    }

    protected function getNormalizedPk()
    {
        $pk = $this->owner->getPrimaryKey();
        return is_array($pk) ? json_encode($pk) : $pk;
    }
}
