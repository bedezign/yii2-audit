<?php
namespace bedezign\yii2\audit\panels;

use Yii;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\components\panels\DataStoragePanel;
use yii\data\ArrayDataProvider;

/**
 * Class CurlPanel
 * @package bedezign\yii2\audit\src\panels
 */
class SoapPanel extends DataStoragePanel
{
    public function getName()
    {
        return Yii::t('audit', 'SOAP');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * Receives a bunch of information about a SOAP request and logs it.
     * If you are unable to use the modules' SoapClient class you can call this function manually to log the data.
     *
     * @param array $data
     */
    public function logSoapRequest($data)
    {
        $this->module->registerPanel($this);

        if (!is_array($this->data))
            $this->data = [];

        if (isset($data['error'])) {
            $error = $this->module->exception($data['error']);
            $data['error'] = [$data['error']->faultcode, $error ? $error->id : null];
        }
        $this->data[] = array_filter($data);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $dataProvider = new ArrayDataProvider();
        $dataProvider->allModels = $this->data;

        return Yii::$app->view->render('panels/soap/index', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
        ]);
    }
}