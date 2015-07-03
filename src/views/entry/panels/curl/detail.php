<?php
/* @var $panel yii\debug\panels\LogPanel */

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\VarDumper;


$post    = empty($request['post']) ? false : $request['post'];
$headers = empty($request['headers']) ? false : $request['headers'];
$content = empty($request['content']) ? false : $request['content'];
$log     = empty($request['log']) ? false : $request['log'];
unset($request['post'], $request['content'], $request['headers'], $request['log']);

$formatter = \Yii::$app->formatter;

$tabs = [
    [
        'label' => \Yii::t('audit', 'Info'),
        'content' => $this->render('info_table', ['request' => $request]),
        'active' => true
    ]
];

if ($post)
    $tabs[] = [
        'label' => \Yii::t('audit', 'POST'),
        'content' => Html::tag('div', VarDumper::dumpAsString($post, 15, true), ['class' => 'well', 'style' => 'overflow: auto; white-space: pre'])
    ];

if ($headers)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Headers'),
        'content' => Html::tag('div', $formatter->asNtext(implode('', $headers)), ['class' => 'well'])
    ];

if ($content)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Content'),
        'content' => Html::tag('div', $formatter->asText($content), ['class' => 'well', 'style' => 'overflow: auto; white-space: pre'])
    ];

if ($log)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Log'),
        'content' => Html::tag('div', $formatter->asNtext($log), ['class' => 'well', 'style' => 'overflow: auto'])
    ];


echo Html::tag('h2', \Yii::t('audit', 'Request #{id}', ['id' => $index])),
        Tabs::widget(['items' => $tabs]);

