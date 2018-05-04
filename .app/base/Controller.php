<?php
namespace app\base;

/**
 * Class Controller
 * Базовый контроллер, в который вынесены общие функции для разных контроллеров
 * @package app\base
 */
class Controller {
	protected $db;
	
	public function __construct($db){
		$this->db = $db;
	}
	
	/**
	 * Отрисовывает представление
	 * @param string $template к шаблону в папке app/views/
	 * @param array $vars массив с параметрами
	 * @return string
	 */
	protected function render($template, $vars = [], $noLayout = false){
		ob_start();
		$templateFile = _APPDIR.'/views/'.$template.'.php';
		if(!file_exists($templateFile)){
			return '';
		}
		extract($vars);
		include $templateFile;
		$content = ob_get_clean();
		if($noLayout) return $content;
		
		$vars['content'] = $content;
		return $this->render('layout', $vars, true);
	}
	
	/**
	 * Переадресация к другой странице. Так же можно задать сообщение для отображения на новой странице
	 * @param string $to
	 * @param string $message
	 * @param string $messageType
	 */
	public function redirect($to = "/", $message = "", $messageType = "info"){
		ob_end_clean();
		if($message){
			$_SESSION['message'] = $message;
			$_SESSION['messageType'] = $messageType;
		}
		header("Location: ".$to);
		exit;
	}
}