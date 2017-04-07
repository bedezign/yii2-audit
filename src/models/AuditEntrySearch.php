<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * AuditEntrySearch
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
            [['id', 'user_id', 'ip', 'created', 'duration', 'memory_max', 'route', 'request_method', 'ajax'], 'safe'],
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
        $this->filterUserId($this->user_id, $query);
        $query->andFilterWhere(['ip' => $this->ip]);
        $query->andFilterWhere(['route' => $this->route]);
        $query->andFilterWhere(['request_method' => $this->request_method]);
        $query->andFilterWhere(['ajax' => $this->ajax]);
        $query->andFilterWhere(['duration' => $this->duration]);
        $query->andFilterWhere(['memory_max' => $this->memory_max]);
        $query->andFilterWhere(['like', 'created', $this->created]);
        $query->with(['linkedErrors', 'javascripts']);

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

    /**
     * @return array
     */
    static public function methodFilter()
    {
        $methods = AuditEntry::getDb()->cache(function () {
            return AuditEntry::find()->distinct(true)
                ->select('request_method')->where(['is not', 'request_method', null])
                ->groupBy('request_method')->orderBy('request_method ASC')->column();
        }, 240);
        return array_combine($methods, $methods);
    }

    /**
     * @param $userId
     * @param ActiveQuery $query
     */
    protected function filterUserId($userId, $query)
    {
        if (strlen($userId)) {
            if (!is_numeric($userId) && $callback = Audit::getInstance()->userFilterCallback) {
                $userId = call_user_func($callback, $userId);
            } else {
                $userId = intval($userId) ?: 0;
            }
            $query->andWhere(['user_id' => $userId]);
        }
    }
}
