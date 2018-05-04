<?php
require __DIR__ . '/.app/config.php';

try {
	session_start();
	
	//Регистрирую функцию подключения классов проекта
	spl_autoload_register(function($className){
		if(preg_match('%^app(\\\\.*?)$%ims', $className, $m)){
			$fileName = _APPDIR . str_replace('\\', '/', $m[1]).'.php';
			if(file_exists($fileName)) {
				include_once $fileName;
			}
		}
	});
	
	$db = new PDO('mysql:host=localhost;dbname='._DB_NAME, _DB_USER, _DB_PASS);
	$db->exec("SET NAMES 'utf8'");
	\app\base\Model::$db = $db;
	
	//по умолчанию использую контроллер main
	$controllerId = $_GET['controller'] ?: 'main';
	if(strpos($controllerId, ".")!==false) $controllerId = '';
	$controllerClassName = 'app\\controllers\\'.ucfirst($controllerId).'Controller';
	//флаг на случай, если не найден выбранный контроллер или в нем не будет запрошенного действия
	$pageFound = false;
	if(class_exists($controllerClassName)){
		$controller = new $controllerClassName($db);
		$actionId = $_GET['action'] ?: 'index';
		$actionName = 'action'.ucfirst($actionId);
		if(method_exists($controller, $actionName)){
			$response = $controller->$actionName();
			//отработало нужное действие
			$pageFound = true;
		}
	}
	if(!$pageFound) {
		//если запрошенное действие не найдено, то выводим страницу ошибки
		$controller = new app\controllers\MainController($db);
		$response = $controller->actionError();
	}
	echo $response;
} catch(Exception $exception){
	echo '<pre>';
	echo 'ERROR: '.$exception->getMessage()."\n";
	echo 'In file '.$exception->getFile().' on line '.$exception->getLine();
	echo '</pre>';
}

