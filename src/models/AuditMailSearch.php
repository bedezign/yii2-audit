<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\DbHelper;
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

        $likeOperator = DbHelper::likeOperator(AuditMail::class);

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['entry_id' => $this->entry_id]);
        $query->andFilterWhere(['successful' => $this->successful]);
        $query->andFilterWhere([$likeOperator, 'to', $this->to]);
        $query->andFilterWhere([$likeOperator, 'from', $this->from]);
        $query->andFilterWhere([$likeOperator, 'reply', $this->reply]);
        $query->andFilterWhere([$likeOperator, 'cc', $this->cc]);
        $query->andFilterWhere([$likeOperator, 'bcc', $this->bcc]);
        $query->andFilterWhere([$likeOperator, 'subject', $this->subject]);
        $query->andFilterWhere(['like', 'created', $this->created]);

        return $dataProvider;
    }

}
