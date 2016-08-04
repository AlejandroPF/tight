# Tight Framework: Router
## Usage

Router can be used in 2 ways:

- Getting the router from the framework.
- Generating one specific router for our app.

Router uses a base path directory from where the routes are created.

## Get router

As simple as this

```php
<?php
require_once 'vendor/autoload.php';
$config = [
    "basePath"=>__DIR__ // Router base path directory
]
$app = new Tight\Tight($config);
$router = $app->getRouter();
// Work with router...
```

## Creating new router

Just create a new `Tight\Router` instance.

```php
<?php
require_once 'vendor/autoload.php';
$basePath = __DIR__;
$router = new Tight\Router($basePath);
// work with router...
```

## Creating routes

You can create routes in several ways depending on the http method.

```php
<?php
require_once 'vendor/autoload.php';
$router = new Tight\Router(__DIR__);
$router->get("/",function(){
    echo "Get method for '/' pattern";
});
$router->post("/",function(){
    echo "Post method for '/' pattern";
});
$router->map(['get','post'],"/hello/:world",function($arg){
    echo "Get and post method for '/hello/:world' pattern";
});
$app->run(); //or $router->run()
```

## Not found handler

For adding a custom not found handler you must call the function `Tight\Router::notFound()` using as argument a callable for the error handling

```php
<?php
require_once 'vendor/autoload.php';
$router = new Tight\Router(__DIR__);
$router->get("/",function(){
    echo "Get method for '/' pattern";
});
$router->notFound(function(){
    echo "404: Page not found!";
});
$app->run(); //or $router->run()
```

## Class method

For more info read the [PHPDocs](phpdoc)