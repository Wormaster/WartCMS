<?php 
class Router {
	static function start()
	{
        global $lang;
        $lang = 'ru';
		$controller_name = 'Main';
		$action_name = 'index';
		$controllers = glob(app_patch . 'controllers' . DS . 'controller_*.php');
		
		//$routes = explode('/', $_SERVER['REQUEST_URI']);
   		$routes = preg_split ( '/[\/\?\&]+/',  $_SERVER['REQUEST_URI']);
		//var_dump ($routes);
		// получаем имя контроллера
        if (!empty($routes[1])) {
            if ($routes[1] == 'en') {
                $lang = 'en';
            }
            else $lang = 'ru';
        }
		if ( !empty($routes[2]) )
		{	
			if ($routes[2] == 'furniture') {
				if ($routes[3] != 'show') {
					$_REQUEST['id'] = $routes['3'];
					$routes[3] = 'show';
					}
				}
			elseif ($routes[2] == 'articles')
			{
				$_REQUEST['id'] = $routes['3'];
				$routes[3] = 'show';
				}
			$controller_name = $routes[2];
		}
		
		// получаем имя экшена
		if ( !empty($routes[3]) )
		{
			$action_name = $routes[3];
		}
			

		// добавляем префиксы
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;
		/*
		echo "Model: $model_name <br>";
		echo "Controller: $controller_name <br>";
		echo "Action: $action_name <br>";
		*/

		// подцепляем файл с классом модели (файла модели может и не быть)

		$model_file = strtolower($model_name).'.php';
		$model_path = app_patch . 'models' . DS .$model_file;
		if(file_exists($model_path))
		{
			include app_patch . 'models' . DS .$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = app_patch . 'controllers' . DS .$controller_file;
		if(file_exists($controller_path))
		{
			include app_patch . 'controllers' . DS .$controller_file;
			$controller = new $controller_name;
			$action = $action_name;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Router::ErrorPage404();
		}
		
		// создаем контроллер
		 
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			Router::ErrorPage404();
		}
	
	}

	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		//header('Location:'.$host.'404');
		$controller_file = 'controller_404.php';
		$controller_path = app_patch . 'controllers' . DS .$controller_file;
		require_once app_patch . 'controllers' . DS .$controller_file;
		$controller_name = 'Controller_404';
		$controller = new $controller_name;
		$action = 'action_index';
		$controller->$action();
		//exit('К сожалению данная страница не доступна...');
    }
	function NoDb()
	{
		$controller_file = 'controller_404.php';
		$controller_path = app_patch . 'controllers' . DS .$controller_file;
		require_once app_patch . 'controllers' . DS .$controller_file;
		$controller_name = 'Controller_404';
		$controller = new $controller_name;
		$action = 'action_index';
		$controller->$action();
		//exit('К сожалению данная страница не доступна...');
    }
    
}