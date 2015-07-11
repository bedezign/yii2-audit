---
layout: default
title: Application Views
permalink: /docs/application-views/
---

# Application Views

Audit comes with several handy views to display data inside your application.


## Audit Trail View for one Model

### Screenshots

#### One Field
![Audit Trail Application View](https://cloud.githubusercontent.com/assets/51875/8373348/24c00646-1c2b-11e5-8266-df1b17cdfdb7.png)

#### All Fields
![Audit Trail Application View](https://cloud.githubusercontent.com/assets/51875/8373390/72c8023a-1c2b-11e5-9589-02fe6974ee45.png)

### Model

```php
class Post extends \yii\db\ActiveRecord
{
    /** 
     * get trails for this model 
     */
    public function getAuditTrails()
    {
        return $this->hasMany(AuditTrail::className(), ['model_id' => 'id'])
            ->andOnCondition(['model' => get_class($this)]);
    }
    /** 
     * get trails for this model and all related comment models 
     */
    public function getAuditTrails()
    {
        return AuditTrail::find()
            ->orOnCondition([
                'audit_trail.model_id' => $this->id, 
                'audit_trail.model' => get_class($this),
              ])
            ->orOnCondition([
                'audit_trail.model_id' => ArrayHelper::map($this->getComments()->all(), 'id', 'id'), 
                'audit_trail.model' => 'app\models\Comment',
            ]);
    }
}    
```

### Controller

```php
class PostController extends \yii\web\Controller
{
    public function actionLog($id)
    {
        $model = $this->findModel($id);
        return $this->render('log', ['model' => $model]);
    }
}
```

### View

simple:
```php
echo $this->render('@bedezign/yii2/audit/views/_audit_trails', ['query' => $model->getAuditTrails()]);
```

all options:
```php
echo $this->render('@bedezign/yii2/audit/views/_audit_trails', [
    // model to display audit trais for, must have a getAuditTrails() method
    'query' => $model->getAuditTrails(),
    // params for the AuditTrailSearch::search() (optional)
    'params' => [
        'AuditTrailSearch' => [
            // in this case we only want to show trails for the "status" field
            'field' => 'status',
        ]
    ],
    // which columns to show
    'columns' => ['user_id', 'entry_id', 'action', 'model', 'model_id', 'old_value', 'new_value', 'diff', 'created'],
    // set to false to hide filter
    'filter' => false,
]);
```

## Render AuditEntry.id in Layout

It is often useful for users to be able to report the AuditEntry.id to the developer.  To render the ID to the page include the partial provided:

```php
<?= $this->render('@bedezign/yii2/audit/views/_audit_entry_id'); ?>
```

Please note, this will not create an audit entry and will only display if an audit entry exists.

{% include page-edit.html %}
