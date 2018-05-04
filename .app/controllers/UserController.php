<?php
namespace app\controllers;

use app\models\User;

/**
 * Контроллер работы с пользователями. В данной задаче только авторизоваться/выйти
 * Class UserController
 * @package app\controllers
 */
class UserController extends \app\base\Controller {
	/**
	 * Попытка авторизации пользователя
	 * @return string
	 */
	public function actionLogin(){
		if(User::authed()) $this->redirect("/");
		
		if($_SERVER['REQUEST_METHOD']=="POST"){
			if($users = User::find([
				'where' => [
					'login' => $_POST['login'],
					'password' => md5($_POST['password'])
				],
				'order' => 'id',
				'limit' => 1
			])){
				$_SESSION['user'] = [
					'id' => $users[0]->id,
					'login' => $users[0]->login,
				];
				if(isset($_SESSION['returnUrl'])){
					$returnUrl = $_SESSION['returnUrl'];
					unset($_SESSION['returnUrl']);
				} else {
					$returnUrl = "/";
				}
				$this->redirect($returnUrl, "Успешно авторизовались", "success");
			}
			$_SESSION['message'] = 'Неверный логин/пароль';
			$_SESSION['messageType'] = 'danger';
		}
		return $this->render('login', [
			'login' => @$_POST['login'],
			'title' => 'Авторизация'
		]);
	}
	
	/**
	 * Завершение сеанс админа
	 */
	public function actionLogout(){
		unset($_SESSION['user']);
		$this->redirect("/");
	}
}