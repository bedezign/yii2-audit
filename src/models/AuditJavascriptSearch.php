<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\DbHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuditJavascriptSearch
 * @package bedezign\yii2\audit\models
 */
class AuditJavascriptSearch extends AuditJavascript
{
    /**
     * @return array
     */
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'entry_id', 'type', 'message', 'origin', 'created'], 'safe'],
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
        $query = AuditJavascript::find();

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

        $likeOperator = DbHelper::likeOperator(AuditJavascript::class);
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['entry_id' => $this->entry_id]);
        $query->andFilterWhere([$likeOperator, 'message', $this->message]);
        $query->andFilterWhere([$likeOperator, 'origin', $this->origin]);
        $query->andFilterWhere(['like', DbHelper::convertIfNeeded(AuditJavascript::class, 'created', 'text'), $this->created]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function typeFilter()
    {
        $types = AuditJavascript::getDb()->cache(function () {
            return AuditJavascript::find()->distinct(true)
                ->select('type')
                ->where(['entry_id' => $this->entry_id])
                ->andWhere(['is not', 'type', null])
                ->groupBy('type')->orderBy('type ASC')->column();
        }, 30);
        return array_combine($types, $types);
    }

    /**
     * @return array
     */
    public function originFilter()
    {
        $origin = AuditJavascript::getDb()->cache(function () {
            return AuditJavascript::find()->distinct(true)
                ->select('origin')
                ->where(['entry_id' => $this->entry_id])
                ->andWhere(['is not', 'origin', null])
                ->groupBy('origin')->orderBy('origin ASC')->column();
        }, 30);
        return array_combine($origin, $origin);
    }

}
