<?php
namespace app\models;

/**
 * Модель для работы с пользователями
 * Class User
 * @package app\models
 */
class User extends \app\base\Model {
	/**
	 * Проверка, авторизован ли пользователь
	 * @return bool
	 */
	public static function authed(){
		return isset($_SESSION['user']) && isset($_SESSION['user']['id']) && $_SESSION['user']['id'];
	}
}