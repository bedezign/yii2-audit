# Application Views

Audit comes with several handy views to display data inside your application.


## Audit Log for a Model

Model:
```php
    /** get trails for this model */
    public function getAuditTrails()
    {
        return $this->hasMany(AuditTrail::className(), ['model_id' => 'id'])
            ->andOnCondition(['model' => get_class($this)]);
    }
    /** get trails for this model and all related comment models */
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
```

Controller:
```php
    public function actionLog($id)
    {
        $model = $this->findModel($id);
        return $this->render('log', ['model' => $model]);
    }
```

View
```php
echo $this->render('@bedezign/yii2/audit/views/_audit_trails', [
    // model to display audit trais for, must have a getAuditTrails() method
    'model' => $model,
    // params for the AuditTrailSearch::search() (optional)
    'params' => [
        'AuditTrailSearch' => [
            'field' => 'status', // in this case we only want to show trails for the "status" field
        ]
    ],
]);
```

## Render AuditEntry.id in Layout

It is often useful for users to be able to report the AuditEntry.id to the developer.  To render the ID to the page include the partial provided:

```php
<?= $this->render('@bedezign/yii2/audit/views/_audit_entry_id'); ?>
```

Please note, this will not create an audit entry and will only display if an audit entry exists.
