<?php 
    require_once "../vendor/autoload.php";
    require_once "../framework/autoload.php";
    require_once "../controllers/MainController.php"; 
    require_once "../controllers/Controller404.php";
    require_once "../controllers/ObjectController.php";
    require_once "../controllers/SearchController.php";
    require_once "../controllers/PlantCreateController.php";
    require_once "../controllers/PlantTypeCreateController.php";
    require_once "../controllers/PlantDeleteController.php";
    require_once "../controllers/PlantUpdateController.php";
    require_once "../controllers/LoginController.php";
    require_once "../controllers/LogoutController.php";
    require_once "../middlewares/LoginRequiredMiddleware.php";
    require_once "../middlewares/HistoryMiddleware.php";
    require_once "../controllers/SetWelcomeController.php";


    $pdo = new PDO("mysql:host=localhost;dbname=plants;charset=utf8", "root", "");

    $loader = new \Twig\Loader\FilesystemLoader('../views');
    $twig = new \Twig\Environment($loader, [
      "debug" => true // добавляем тут debug режим
    ]);

    $twig->addExtension(new \Twig\Extension\DebugExtension()); 

    session_set_cookie_params(60*60*10);
    session_start();
    
    $router = new Router($twig, $pdo);
    $router->add("/login", LoginController::class);
    $router->add("/logout", LogoutController::class);
    $router->add("/", MainController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/trees/(?P<id>\d+)", ObjectController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/search", SearchController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/add", PlantCreateController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/createType", PlantTypeCreateController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/trees/(?P<id>\d+)/delete", PlantDeleteController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/trees/(?P<id>\d+)/edit", PlantUpdateController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());
    $router->add("/set-welcome/", SetWelcomeController::class)
        ->middleware(new HistoryMiddleware())
        ->middleware(new LoginRequiredMiddleware());

    $router->get_or_default(Controller404::class);