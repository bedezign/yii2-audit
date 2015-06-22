<?php

namespace bedezign\yii2\audit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use bedezign\yii2\audit\models\AuditEntry;

class AuditEntrySearch extends AuditEntry
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'user_id', 'created', 'route'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AuditEntry::find();

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
        $query->andFilterWhere(['user_id' => $userId]);
        $query->andFilterWhere(['route' => $this->route]);
        $query->andFilterWhere(['like', 'created', $this->created]);
        $query->with(['linkedErrors', 'javascript']);

        return $dataProvider;
    }

    static public function routeFilter()
    {
        $entries = AuditEntry::find()->groupBy('route')->orderBy('route ASC')->all();

        $routeFilter = [];
        foreach ($entries as $entry) {
            if (!empty($entry->route)) {
                $routeFilter[$entry->route] = $entry->route;
            }
        }

        return $routeFilter;
    }
}
