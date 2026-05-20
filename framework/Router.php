<?php

// сначала создадим класс под один маршрут
class Route {
    public string $route_regexp; // тут получается шаблона url
    public $controller; // а это класс контроллера
    public array $middlewareList = [];

    public function middleware(BaseMiddleware $m) : Route {
        array_push($this->middlewareList, $m);
        return $this;
    }

    // ну и просто конструктор
    public function __construct($route_regexp, $controller)
    {
        $this->route_regexp = $route_regexp;
        $this->controller = $controller;
    }
}

class Router {
    /**
     * @var Route[]
     */
    protected $routes = []; // создаем поле -- список под маршруты и привязанные к ним контроллеры

    protected $twig; // переменные под twig и pdo
    protected $pdo;

    // конструктор
    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    // функция с помощью которой добавляем маршрут
    public function add($route_regexp, $controller) : Route {
        // создаем экземпляр маршрута
        $route = new Route("#^$route_regexp$#", $controller);
        array_push($this->routes, $route);
        
        // возвращаем как результат функции
        return $route;
    }

    // функция которая должна по url найти маршрут и вызывать его функцию get
    // если маршрут не найден, то будет использоваться контроллер по умолчанию
    public function get_or_default($default_controller) {
        $url = $_SERVER["REQUEST_URI"];
        $path = parse_url($url, PHP_URL_PATH); // вытаскиваем адрес

        // фиксируем в контроллер $default_controller
        $controller = $default_controller;
        $newRoute = null;
        // проходим по списку $routes 
        $matches = [];
        foreach($this->routes as $route) {
            // проверяем подходит ли маршрут под шаблон
            if (preg_match($route->route_regexp, $path, $matches)) {
                // если подходит, то фиксируем привязанные к шаблону контроллер 
                $controller = $route->controller;
                $newRoute = $route;
               // и выходим из цикла
                break;
            }
        }

        // создаем экземпляр контроллера
        $controllerInstance = new $controller();
        $controllerInstance->setPDO($this->pdo);
        $controllerInstance->setParams($matches);
        
        // проверяем не является ли controllerInstance наследником TwigBaseController
        // и если является, то передает в него twig
        if ($controllerInstance instanceof TwigBaseController) {
            $controllerInstance->setTwig($this->twig);
        }

        if ($newRoute) {
            foreach ($newRoute->middlewareList as $m) {
                $m->apply($controllerInstance, []);
            }
        }

        // вызываем
        return $controllerInstance->process_response(); 
    }
}