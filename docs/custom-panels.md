# Creating Custom Panels

You can create your own panels that collect and display the specific data you want. Below we'll describe the process of creating a simple custom panel that:

* Collects the views rendered during a request
* Shows the number of views rendered in the toolbar
* Allows you to check the view names in the debugger

The assumption is that you're using the basic application template.

First we need to implement the `Panel` class in `panels/ViewsPanel.php`:


```php
<?php
namespace app\panels;

use bedezign\yii2\audit\panels\Panel;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\web\View;

/**
 * ViewsPanel
 * @package app\panels
 */
class ViewsPanel extends Panel
{
    /**
     * @var array
     */
    private $_viewFiles = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(View::className(), View::EVENT_BEFORE_RENDER, function (ViewEvent $event) {
            $this->_viewFiles[] = $event->sender->getViewFile();
        });
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('audit', 'Views');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return '<ol><li>' . implode('<li>', $this->data) . '</ol>';
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->_viewFiles;
    }
}
```

The workflow for the code above is:

1. `init` is executed before any controller action is run. This method is the best place to attach handlers that will collect data during the controller action's execution.
2. `save` is called after controller action is executed. The data returned by this method will be stored in a data file. If nothing is returned by this method, the panel won't be rendered.
3. The data from the data file is loaded into `$this->data`. For the toolbar, this will always represent the latest data, For the debugger, this property may be set to be read from any previous data file as well.


Now it's time to tell audit to use the new panel. In `config/web.php`, the audit configuration is modified to:

```php
$config = [
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'panels' => [
                'views' => 'app\panels\ViewsPanel',
            ],
        ],
    ],
];
```
