<?php

namespace dwes\core;

use dwes\app\exceptions\NotFoundException;
use dwes\app\exceptions\AppException;
use dwes\app\exceptions\AuthenticationException;
use dwes\core\App;


require_once __DIR__ . "/../app/exceptions/NotFoundException.php";
class Router
{

    private function __construct()
    {
        $this->routes = [
            'GET' => [],
            'POST' => []
        ];
    }

    /**
     * @param string $file
     * @return Router
     */
    public static function load(string $file): Router
    {
        $router = new Router();
        require $file;
        return $router;
    }
    private $routes;
    /**
     * @param array $routes
     * @return void
     */
    public function get(string $uri, string $controller, $role = 'ROLE_ANONYMOUS'): void
    {
        $this->routes['GET'][$uri] = [
            'controller' => $controller,
            'role' => $role
        ];
    }
    public function post(string $uri, string $controller, $role = 'ROLE_ANONYMOUS'): void
    {
        $this->routes['POST'][$uri] = [
            'controller' => $controller,
            'role' => $role
        ];
    }

    /**
     * @param string $uri
     * @param string $method
     * @return void
     * @throws NotFoundException
     * @throws AppException
     */
    public function direct(string $uri, string $method): void
    {
        // Recorremos las rutas y separamos las dos partes: las rutas y sus controladores respectivamente
        foreach ($this->routes[$method] as $route => $routerData) {
            $controller = $routerData['controller'];
            $minRole = $routerData['role'];

            // Se cambia el contenido de la ruta por una forma que nos vendrá mejor
            $urlRule = $this->prepareRoute($route);
            if (preg_match($urlRule, $uri, $matches) === 1) {
                if (Security::isUserGranted($minRole) === false) {
                    if (!is_null(App::get('appUser'))) // Comprobamos si se está logueado
                        throw new AuthenticationException('Acceso no autorizado');
                    else
                        $this->redirect('login'); // Si el usuario no se ha logueado, redireccionamos al login
                } else {
                    $parameters = $this->getParametersRoute($route, $matches);
                    // Extraemos el nombre del controlador (nombre de la clase) del nombre del
                    // action (nombre del método a llamar) y los pasamos a 2 variables
                    list($controller, $action) = explode('@', $controller);
                    // Se encarga de crear un objeto de la clase controller y llama al action adecuado
                    if ($this->callAction($controller, $action, $parameters) === true)
                        return;
                }
            }
        }
        throw new NotFoundException("No se ha definido una ruta para la uri solicitada");
    }

    private function prepareRoute(string $route): string
    {
        // Se busca todo lo que comienze por /: para sustituir p.e. :id
        $urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $route);
        $urlRule = str_replace('/', '\/', $urlRule);
        return '/^' . $urlRule . '\/*$/s';
    }
    private function getParametersRoute(string $route, array $matches)
    {
        preg_match_all('/:([^\/]+)/', $route, $parameterNames);
        $parameterNames = array_flip($parameterNames[1]);
        // Obtenemos el array de parámetros que hay que pasar al controlador
        return array_intersect_key($matches, $parameterNames);
    }


    /**
     * @param string $controller
     * @param string $action
     * @return void
     * @throws NotFoundException
     * @throws AppException
     */
    private function callAction(string $controller, string $action, array $parameters): bool
    {
        try {
            $controller = App::get('config')['project']['namespace'] . '\\app\\controllers\\' . $controller;
            $objController = new $controller();
            if (!method_exists($objController, $action))
                throw new NotFoundException("El controlador $controller no responde al action $action");
            //$objController->$action();
            // Llamamo al action del controlador pasándole los parámetros
            call_user_func_array(array($objController, $action), $parameters);
            return true;
        } catch (\TypeError $typeError) {
            return false;
        }
    }


    public function redirect(string $path)
    {
        header('location: /' . $path);
        exit();
    }
}
