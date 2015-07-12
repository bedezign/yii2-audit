<?php

namespace tests\codeception\_fixtures;

class ActiveFixture extends \yii\test\ActiveFixture
{
    public function beforeLoad()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();
        parent::beforeLoad();
    }

    public function afterLoad()
    {
        parent::afterLoad();
        $this->db->createCommand()->checkIntegrity(true)->execute();
    }

    protected function getData()
    {
        $data = parent::getData();
        $object = new $this->modelClass;
        if ($object instanceof \bedezign\yii2\audit\components\db\ActiveRecord) {
            $reflection = new \ReflectionClass($object);

            $autoSerialize = $reflection->getProperty('autoSerialize');
            $autoSerialize->setAccessible(true);

            if ($autoSerialize->getValue($object)) {
                $attributes = $reflection->getProperty('serializeAttributes');
                $attributes->setAccessible(true);
                $attributes = $attributes->getValue($object);
                foreach ($data as $key => $row) {
                    foreach ($attributes as $attribute) {
                        if (isset($data[$key][$attribute]))
                            $data[$key][$attribute] = [$data[$key][$attribute], \PDO::PARAM_LOB];
                    }
                }
            }
        }

        return $data;
    }


}
