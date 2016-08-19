# Tight Framework: MVC
***

## Dynamic routing
Setting `mvc['enableRoute']` to `true` creates a dynamic routing using MVC

```php
<?php
$conf = [
    "basePath" => __DIR__,
    "mvc"=>[
        "enableRouter"=>true // Default value is FALSE
        "indexName" => "Root", // Clase name for '/' route
        "controller_dir" => "./controllers/", // Controllers directory
        "model_dir" => "./models/", // Models directory
        "view_dir" => "./views/", // Views directory
    ]
];
$app = new Tight\Tight($conf);
$app->run();
```

When accessing to `basePath` directory 3 classes will be executed: Model, view and controller.
Defining `Root` as `mvc['indexName']` will execute `RootModel`, `RootView` and `RootController` at `/` route.
The classes, methods and arguments that will be instanciated depends on the URL route: `http://example.com/className/methodName/args`

Then, if you access to `http://example.com/admin/auth/` `AdminModel`, `AdminView` and `AdminController` will be instanciated and the method `AdminController::auth` will be executed