<?php
namespace Core;
class Router {
    private $routes;

    function __construct() {
        $this->reg = Registry::instance();
        $this->routes = $this->reg['routes'];

        //Debug
        if ($this->reg['debug']) {
            $this->firephp = \FirePHP::getInstance(true);
        }
    }

    function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if(!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
        return false;
    }

	function start()
	{
        //для совместимости с многоязычность от старых версий
        global $lang;


        $uri = $this->getURI();
        if ($this->reg['debug']) {
            $this->firephp->log($uri, 'URI');
        }
        foreach($this->routes as $pattern => $route){
            // Если правило совпало.

            if(preg_match("~$pattern~", $uri)){
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$pattern~", $route, $uri);
                // Разбиваем внутренний путь на сегменты.
                $segments = explode('/', $internalRoute);
                // Первый сегмент — язык.
                if ($this->reg['debug']) {
                $this->firephp->log($segments, 'Segments');
                $this->firephp->log($internalRoute);
                }
                $lang = array_shift($segments);
                if ($lang == ''){
                    $lang = 'ru';
                }
                $this->reg['language'] = $lang;

                // Второй сегмент - контроллер
                if ($segments[0] == 'admin') {
                    $place = array_shift($segments);
                    $place = ucwords($place);
                }
                else {
                    $place = 'Front';
                }
                $controller_name = array_shift($segments);
                $model = '\\'.$place . '\\' . 'Models' . '\\' . 'Model_'.ucfirst($controller_name);
                $controller_shortname = $controller_name;
                $controller_name = '\\'.$place . '\\' . 'Controllers' . '\\' . 'Controller_'.ucfirst($controller_name);
                // Второй — действие.
                $action_shortname = array_shift($segments);
                $action = 'action_'.$action_shortname;
                // Остальные сегменты — параметры.
                $parameters = $segments;

                //Положим всю инфу в реестр для дальнейшей нафигации
                $this->reg['route'] = array('controller' => $controller_shortname, 'model' => $model, 'action' => $action_shortname, 'params' => $parameters);
                //Говнокостыль для совместимости
                /*if (!$_REQUEST['id']) {
                $_REQUEST['id'] = $parameters[0];
                }*/


                if ($this->reg['debug']){
                    $profiler = \Generic\Profiler::instance();
                    $profiler->start_count();
                }



                //Debug роутера
                if ($this->reg['debug']) {
                    $this->firephp->log($place, 'Place');
                    $this->firephp->log($controller_name, 'Controller');
                    $this->firephp->log($model, 'Model');
                    $this->firephp->log($action, 'Action');
                    $this->firephp->log($parameters, 'Parameters');
                    $autoload = spl_autoload_functions();
                    $this->firephp->log($autoload, 'Autoload');
                }

                if (($this->reg['online'] != true) && ($place != 'admin')){
                    session_start();
                    if ($_SESSION['admin']){

                    }
                    else {
                        self::Offline();
                        die;
                    }

                }
                elseif($place == 'admin'){

                }


                $controller = new $controller_name;
                if(method_exists($controller, $action))
                {
                    // вызываем действие контроллера
                    $controller->$action($parameters);
                    return;
                }
                else
                {
                    // здесь также разумнее было бы кинуть исключение
                    self::ErrorPage404();
                    return;
                }
                break;
            }

        }
        self::ErrorPage404();
        return;
    }

	static function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		//header('Location:'.$host.'404');
		$controller_file = 'Controller_404.php';
		$controller_name = '\Front\Controllers\Controller_404';
		$controller = new $controller_name;
		$action = 'action_index';
		$controller->$action();
		//exit('К сожалению данная страница не доступна...');
    }
	static function NoDb($error = null)
	{
        $controller_file = 'Controller_404.php';
        $controller_name = '\Front\Controllers\Controller_404';
		$controller = new $controller_name;
		$action = 'action_nodb';
		$controller->$action($error);
		//exit('К сожалению данная страница не доступна...');
    }
    static function Offline()
    {
        $controller_file = 'controller_404.php';
        $controller_path = app_patch . 'controllers' . DS .$controller_file;
        require_once app_patch . 'controllers' . DS .$controller_file;
        $controller_name = 'Controller_404';
        $controller = new $controller_name;
        $action = 'action_offline';
        $controller->$action('К сожалению сайт на данный момент отключен');
        //exit('К сожалению данная страница не доступна...');
    }
    
}