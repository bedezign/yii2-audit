<?php

namespace bedezign\yii2\audit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use bedezign\yii2\audit\models\AuditEntry;

/**
 * Class AuditEntrySearch
 * @package bedezign\yii2\audit\models
 */
class AuditEntrySearch extends AuditEntry
{
    /**
     * @return array
     */
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'user_id', 'created', 'duration', 'memory_max', 'route', 'request_method', 'ajax'], 'safe'],
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
        $query = AuditEntry::find();

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
        $userId = $this->user_id;
        if (strlen($this->user_id))
            $userId = intval($this->user_id) ?: 0;
        $query->andFilterWhere(['user_id' => $userId]);
        $query->andFilterWhere(['route' => $this->route]);
        $query->andFilterWhere(['request_method' => $this->request_method]);
        $query->andFilterWhere(['ajax' => $this->ajax]);
        $query->andFilterWhere(['duration' => $this->duration]);
        $query->andFilterWhere(['memory_max' => $this->memory_max]);
        $query->andFilterWhere(['like', 'created', $this->created]);
        $query->with(['linkedErrors', 'javascript']);

        return $dataProvider;
    }

    /**
     * @return array
     */
    static public function routeFilter()
    {
        $routes = AuditEntry::getDb()->cache(function () {
            return AuditEntry::find()->distinct(true)
                ->select('route')->where(['is not', 'route', null])
                ->groupBy('route')->orderBy('route ASC')->column();
        }, 30);
        return array_combine($routes, $routes);
    }
}
