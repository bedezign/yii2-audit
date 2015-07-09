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

$preformatted = ['class' => 'well', 'style' => 'overflow: auto; white-space: pre'];
$formatter = \Yii::$app->formatter;

$tabs = [
    [
        'label' => \Yii::t('audit', 'Info'),
        'content' => $this->render('info_table', ['request' => $request]),
        'active' => true
    ]
];

$post = http_build_query(['test' => 'var', 'test2' => 'value1', 'test3' => ['value3', 'value2']]);
if ($post) {
    $tabs[] = [
        'label' => \Yii::t('audit', 'POST'),
        'content' => Html::tag('div', $post, $preformatted)
    ];
    checkString(
        ['asQuery' => \Yii::t('audit', 'POST - Query'), 'asJSON' => \Yii::t('audit', 'POST - JSON'), 'asXML' => \Yii::t('audit', 'POST - XML')],
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
    checkString(
        ['asQuery' => \Yii::t('audit', 'Content - Query'), 'asJSON' => \Yii::t('audit', 'Content - JSON'), 'asXML' => \Yii::t('audit', 'Content - XML')],
        $content, $preformatted, $tabs
    );
}

if ($log)
    $tabs[] = [
        'label' => \Yii::t('audit', 'Log'),
        'content' => Html::tag('div', $formatter->asNtext($log), $preformatted)
    ];


echo Html::tag('h2', \Yii::t('audit', 'Request #{id}', ['id' => $index])),
        Tabs::widget(['items' => $tabs]);


function checkString($types, $data, $preformatted, &$tabs)
{
    foreach ($types as $function => $title) {
        $result = $function($data);
        if ($result)
            $tabs[] = ['label' => $title, 'content' => Html::tag('div', $result, $preformatted)];
    }
}

function asQuery($data)
{
    $data = rawurldecode($data);
    if (!preg_match('/^([\w\d\-\[\]]+(=[\w-]*)?(&[\w\d\-\[\]]+(=[\w-]*)?)*)?$/', $data))
        return null;

    $result = [];
    parse_str($data, $result);
    return VarDumper::dumpAsString($result, 15);
}

function asJSON($data)
{
    $decoded = @json_decode($data);
    return $decoded ? json_encode($decoded, JSON_PRETTY_PRINT) : null;
}

function asXML($data)
{
    $doc = new \DOMDocument('1.0');
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    if (@$doc->loadXML($data))
        return htmlentities($doc->saveXML(), ENT_COMPAT, 'UTF-8');
    return null;
}