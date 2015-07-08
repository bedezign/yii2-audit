<?php

namespace tests\codeception\_fixtures;

class ActiveFixture extends \yii\test\ActiveFixture
{
    public function beforeLoad()
    {
        parent::beforeLoad();
        $this->db->createCommand()->checkIntegrity(false)->execute();
    }

    public function afterLoad()
    {
        parent::afterLoad();
        $this->db->createCommand()->checkIntegrity(true)->execute();
    }
}
