<?php
/* @var $panel yii\debug\panels\LogPanel */

use yii\helpers\Html;
use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\components\web\Helper as WebHelper;

if (!function_exists('formatDataString')) {
    function formatDataString($types, $data, $preformatted, &$tabs) { foreach ($types as $function => $title) { $result = Helper::$function($data); if ($result) { $tabs[] = ['label' => $title, 'content' => Html::tag('div', $result, $preformatted)]; break; }}}
}

$post    = empty($request['post']) ? false : $request['post'];
$headers = empty($request['headers']) ? false : $request['headers'];
$content = empty($request['content']) ? false : $request['content'];
$log     = empty($request['log']) ? false : $request['log'];
unset($request['post'], $request['content'], $request['headers'], $request['log']);

$preformatted = ['class' => 'well', 'style' => 'overflow: auto; white-space: pre'];
$formatter = \Yii::$app->formatter;

$tabs = [
    [
        'label' => \Yii::t('audit', 'Info'),
        'content' => $this->render('info_table', ['request' => $request]),
        'active' => true
    ]
];

if ($post) {
    $tabs[] = [
        'label' => \Yii::t('audit', 'POST'),
        'content' => Html::tag('div', $post, $preformatted)
    ];
    formatDataString(
        ['formatAsQuery' => \Yii::t('audit', 'POST - Query'), 'formatAsJSON' => \Yii::t('audit', 'POST - JSON'),
            'formatAsXML' => \Yii::t('audit', 'POST - XML'), 'formatAsHTML' => \Yii::t('audit', 'POST - HTML')],
        $post, $preformatted, $tabs
    );
}

if ($headers)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Headers'),
        'content' => Html::tag('div', $formatter->asNtext(implode('', $headers)), ['class' => 'well'])
    ];

if ($content) {
    $tabs[] = [
        'label' => \Yii::t('audit', 'Content'),
        'content' => Html::tag('div', $formatter->asText($content), $preformatted)
    ];
    formatDataString(
        ['formatAsQuery' => \Yii::t('audit', 'Content - Query'), 'formatAsJSON' => \Yii::t('audit', 'Content - JSON'),
            'formatAsXML' => \Yii::t('audit', 'Content - XML'), 'formatAsHTML' => \Yii::t('audit', 'Content - HTML')],
        $content, $preformatted, $tabs
    );
}

if ($log)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Log'),
        'content' => Html::tag('div', $formatter->asText($log), $preformatted)
    ];


echo Html::tag('h2', \Yii::t('audit', 'Request #{id}', ['id' => $index])),
        WebHelper::bootstrap('Tabs', 'widget', ['items' => $tabs]);
