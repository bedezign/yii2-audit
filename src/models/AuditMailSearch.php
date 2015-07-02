<?php

namespace bedezign\yii2\audit\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuditMailSearch
 * @package bedezign\yii2\audit\models
 */
class AuditMailSearch extends AuditMail
{
    /**
     * @return array
     */
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'entry_id', 'successful', 'to', 'from', 'reply', 'cc', 'bcc', 'subject', 'created'], 'safe'],
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
        $query = AuditMail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
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
        $query->andFilterWhere(['successful' => $this->successful]);
        $query->andFilterWhere(['like', 'to', $this->to]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        $query->andFilterWhere(['like', 'reply', $this->reply]);
        $query->andFilterWhere(['like', 'cc', $this->cc]);
        $query->andFilterWhere(['like', 'bcc', $this->bcc]);
        $query->andFilterWhere(['like', 'subject', $this->subject]);
        $query->andFilterWhere(['like', 'created', $this->created]);

        return $dataProvider;
    }

}
