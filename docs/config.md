# Tight Framework: Configuration
***
The configuration can be set using the `Tight\TightConfig` class or an array with all the values

## Tight\TightConfig class

```php
<?php
require_once 'vendor/autoload.php';
$conf = new Tight\TightConfig;
$conf->basePath = __DIR__;
$conf->smarty["template_dir"] = "/var/www/templates";
$app = new Tight\Tight($conf);
// work with $app...
```

## Using an array

```php
require_once 'vendor/autoload.php';
$conf = [
    "basePath" =>__DIR__,
    "smarty" =>[
        "template_dir"=>"/var/www/templates"
    ]
];
$app = new Tight\Tight($conf);
// work with $app...
```
Even when using `Tight\TightConfig` class you can set the configuration throgh an array

```php
require_once 'vendor/autoload.php';
$confArray = [
    "basePath" =>__DIR__,
    "smarty" =>[
        "template_dir"=>"/var/www/templates"
    ]
];
$conf = new Tight\TightConf($confArray);
$app = new Tight\Tight($conf);
// work with $app...
```

## List of available settings

Any setting can be represented as a key of a section, which can be accessed using `$config->sectionName[$keyName]` once `Tight\TightConfig` has been instanciated.

| Section | Key Name | Description | Default Value|
|---|---|---|---|
| -| basePath | Base Path for the router module | null |
| smarty | template_dir | Smarty's templates directory | "./templates" |
| smarty | compile_dir | Smarty's compile directory | "./templates_c" |
| smarty | cache_dir | Smarty's cache directory | "./cache/"|
| router | using_mvc | Checks if router module must create routes dinamically using the request URI and MVC | false|
| mvc | controller_dir | Directory where controllers can be found | "./controllers/"|
| mvc | model_dir | Directory where MVC models can be found | "./models/" |
| mvc | view_dir | Directory where MVC views can be found (do not confuse with templates) | "./views/"|

This settings, with their default values, can be represente as the following array
```php
$config = [
    "basePath"=>null,
    "smarty"=> [
        "template_dir" => "./templates",
        "compile_dir" => "./templates_c",
        "config_dir" => "./configs",
        "cache_dir" => "./cache"
    ],
    "router"=> [
        "using_mvc" => false
    ],
    "mvc" => [
        "controller_dir" => "./controllers/",
        "model_dir" => "./models/",
        "view_dir" => "./views/",
    ]
]
````





