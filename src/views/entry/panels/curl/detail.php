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

if ($post) {
    $prepend = '';
    $hide = false;
    if (is_string($post)) {
        // If the post was specified as a string, make the expanded (array) version available)
        $id = 'toggle_expanded_'. $index;
        $prepend =
            Html::checkbox($id, false, ['id' => $id, 'class' => 'audit_curl_post_toggle']) . ' ' . Html::label(\Yii::t('audit', 'Expand'), $id) .  Html::tag('div', $post);
        $result = [];
        parse_str($post, $result);
        $post = $result;
        $hide = true;
    }
    $tabs[] = [
        'label' => \Yii::t('audit', 'POST'),
        'content' => Html::tag('div', $prepend .
            Html::tag('div', VarDumper::dumpAsString($post, 15), ['style' => 'display: ' . ($hide ? 'none' : 'block')]),
            ['class' => 'well', 'style' => 'overflow: auto; white-space: pre'])
    ];
}

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

