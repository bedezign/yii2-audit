<?php
/* @var $panel yii\debug\panels\LogPanel */

use yii\helpers\Html;
use yii\grid\GridView;

echo Html::tag('h1', $panel->name);

foreach ($dataProvider->allModels as $index => $request)
    echo $this->render('detail', ['panel' => $panel, 'request' => $request, 'index' => $index + 1]);
