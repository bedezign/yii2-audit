<?php

namespace bedezign\yii2\audit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuditTrailSearch extends AuditTrail
{
    public function rules()
    {
        // Note: The model is used by both the entry and the trail index pages, hence the separate use of `id` and `audit_id`
        return [
            [['id', 'user_id', 'audit_id', 'action', 'model', 'model_id', 'field', 'stamp'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = parent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $userId = $this->user_id;
        if (strlen($this->user_id))
            $userId = intval($this->user_id) ?: 0;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['audit_id' => $this->audit_id]);
        $query->andFilterWhere(['user_id' => $userId]);
        $query->andFilterWhere(['action' => $this->action]);
        $query->andFilterWhere(['like', 'model', $this->model]);
        $query->andFilterWhere(['model_id' => $this->model_id]);
        $query->andFilterWhere(['field' => $this->field]);
        $query->andFilterWhere(['like', 'stamp', $this->stamp]);

        return $dataProvider;
    }

    static public function actionFilter()
    {
        return \yii\helpers\ArrayHelper::map(
            parent::find()->select('action')->groupBy('action')->orderBy('action ASC')->all(),
            'action',
            'action'
        );
    }
}
