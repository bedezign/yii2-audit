<?php

namespace bedezign\yii2\audit\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * AuditErrorSearch
 * @package bedezign\yii2\audit\models
 */
class AuditErrorSearch extends AuditError
{
    /**
     * @return array
     */
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'entry_id', 'file', 'line', 'message', 'code', 'hash', 'created'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuditError::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['entry_id' => $this->entry_id]);
        $query->andFilterWhere(['like', 'file', $this->file]);
        $query->andFilterWhere(['line' => $this->line]);
        $query->andFilterWhere(['like', 'message', $this->message]);
        $query->andFilterWhere(['code' => $this->code]);
        $query->andFilterWhere(['hash' => $this->hash]);
        $query->andFilterWhere(['like', 'created', $this->created]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    static public function fileFilter()
    {
        $files = ArrayHelper::getColumn(self::filterData(), 'file');
        sort($files);
        return array_combine($files, $files);
    }

    /**
     * @return array
     */
    static public function messageFilter()
    {
        $messages = ArrayHelper::getColumn(self::filterData(), 'message');
        sort($messages);
        return array_combine($messages, $messages);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    static protected function filterData()
    {
        return AuditEntry::getDb()->cache(function () {
            return AuditError::find()->distinct(true)
                ->select(['hash', 'message', 'file'])->asArray()->all();
        }, 30);
    }
}
