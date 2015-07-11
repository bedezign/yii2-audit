# Creating a Custom Panel to Log Rendered Views

You can create your own panels that collect and display the specific data you want. Below we'll describe the process of creating a simple custom panel that:

* Collects the views rendered during a request
* Shows the number of views rendered in the toolbar
* Allows you to check the view names in the debugger

The assumption is that you're using the basic application template.

## Panel Class

First we need to implement the `Panel` class in `panels/ViewsPanel.php`:


```php
<?php
namespace app\panels;

use bedezign\yii2\audit\components\panels\Panel;
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
     * @var array This will store a list of views that have been rednered.
     */
    private $_viewFiles = [];

    /**
     * Add an event listener to the View::EVENT_BEFORE_RENDER event to capture the view filenames.
     */
    public function init()
    {
        parent::init();
        Event::on(View::className(), View::EVENT_BEFORE_RENDER, function (ViewEvent $event) {
            $this->_viewFiles[] = $event->sender->getViewFile();
        });
    }
    
    /**
     * Returns the data that will be saved into the `audit_data` table.
     */
    public function save()
    {
        return $this->_viewFiles;
    }

    /**
     * Get the name of the panel.
     */
    public function getName()
    {
        return \Yii::t('app', 'Views');
    }

    /**
     * Get the label that will be used on the tab in the entry view page.
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * Get the HTML output that will be rendered into the tab view area on the entry view page.
     */
    public function getDetail()
    {
        return '<ol><li>' . implode('<li>', $this->data) . '</ol>';
    }
}
```

The workflow for the code above is:

1. `init` is executed before any controller action is run. This method is the best place to attach handlers that will collect data during the controller action's execution.
2. `save` is called after controller action is executed. The data returned by this method will be stored in a data file. If nothing is returned by this method, the panel won't be rendered.
3. The data from the data file is loaded into `$this->data`. For the toolbar, this will always represent the latest data, For the debugger, this property may be set to be read from any previous data file as well.

## Configuration

Now it's time to tell audit to use the new panel. In `config/web.php`, the audit configuration is modified to:

```php
$config = [
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'panels' => [
                // panel_id => array of options or string containing class
                'app/views' => 'app\panels\ViewsPanel',
            ],
        ],
    ],
];
```

## Screenshot

![Custom Panel View](https://cloud.githubusercontent.com/assets/51875/8370372/149aa0de-1c06-11e5-8229-6fd53142b7c2.png)


## What else can I do with panels?

Check out the [source for the existing panels](https://github.com/bedezign/yii2-audit/tree/master/src/panels) to see some of the ways panels can be used.

If you create a useful panel please [let us know](https://github.com/bedezign/yii2-audit/issues/new)!
