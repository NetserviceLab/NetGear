# NetGear
Abstract Wordpress plugin

This is a simple OOP structure for create plugins.

## NetGear Plugin
All you need is to enable the plugin in Wordpress and write the plugin in the right way.

## Develope new plugin
Create a new plugin with NetGear structure requre to be write in a certain way.

Firt of all the folder structure:

    /plugins/
    ·······/[your-plugin-name]/
    ··························/controller/
    ··························/views/
    ··························/public/
    ··························/[your-plugin-class-file].php
    ··························/plugin.php

### plugin.php
Is the file that use Wordpress for read plugin information and load all the hook and function.
The base plugin.php could be like this:
```php
<?php
/*##
 * Plugin Name:       [Your Plugin Name]
 * Description:       ...
 * Version:           0.1
 * Author:            You
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


if ( ! defined( 'WPINC' ) ) {
    die;
}

function your_bootstrap_function_name(){
    NetGear::bootstrapPlugin(__FILE__);
}

if(class_exists('NetGear')){
    your_bootstrap_function_name();
}else{
    add_action('netgear_loaded','your_bootstrap_function_name');
}

```
The file is basicaly always the same execpt for the name of function "your_bootstrap_function_name" thas have to be unique.

### The plugin Class [your-plugin-class-file].php
```php
<?php

class YourPluginClass extends NetGear{
    protected function init(){
      // initialize your plugin
    }
}
```

The plugin class name have to be the camel case version of the plugin folder name and the file have to have the same name of the class. This is the minimal request to build a plugin.

### HelloWorld example
**Plugin Class File**
```php
<?php

class MyHelloWorldPlugin extends NetGear{
    protected function init(){
        $this->addPageController(new MyFirstController(__FILE__));
        $this->enqueueStyle('public/css/helloword.css');
    }
}
```
**MyFirstController Class File**

*controller/MyFirstController.php*
```php
class MyFirstController extends NetGearPageController{
    public function getMenuTitle(){
        return "Hello world";
    }
    public function getPageTitle(){
        return $this->getMenuTitle();
    }

    public function init(){
    }

    public function defaultAction(){
        echo "<div id="my-hello-world">Hello world!</div>"
    }
  }
```
