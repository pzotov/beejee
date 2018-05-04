<?php
namespace app\controllers;

use app\models\Task;
use app\models\User;

/**
 * Контроллер работы с задачами
 * Class MainController
 * @package app\controllers
 */
class MainController extends \app\base\Controller {
	const ROWS_PER_PAGE = 3;
	
	/**
	 * Страница со список задач
	 * @return string
	 */
	public function actionIndex(){
		$page = $_GET['page'] ?: 0;
		$page += 0;
		$totalCount = Task::countAll();
		$pagesCount = ceil($totalCount / self::ROWS_PER_PAGE);
		//Если запрошена страница слишком далеко, то перекидываем на главную
		if($page && $page >= $pagesCount){
			$this->redirect("/");
		}
		
		$sortBy = $_SESSION['sortBy'] ?: Task::SORT_DEFAULT;
		$sortOrder = $_SESSION['sortOrder'] ?: SORT_ASC;
		
		if(isset($_GET['sortBy']) && in_array($_GET['sortBy'], array_keys(Task::$sortings))) $sortBy = $_GET['sortBy'];
		if(isset($_GET['sortOrder']) && in_array($_GET['sortOrder'], [SORT_ASC, SORT_DESC])) $sortOrder = $_GET['sortOrder'];
		
		$_SESSION['sortBy'] = $sortBy;
		$_SESSION['sortOrder'] = $sortOrder;
		
		$tasks = Task::find([
			'order' => Task::$sortings[$sortBy].' '.($sortOrder==SORT_ASC ? 'ASC' : 'DESC'),
			'limit' => self::ROWS_PER_PAGE,
			'offset' => $page * self::ROWS_PER_PAGE
		]);
		
		return $this->render('tasks', [
			'title' => 'Список задач',
			'rows' => $tasks,
			'page' => $page,
			'totalCount' => $totalCount,
			'pagesCount' => $pagesCount,
			'sortBy' => $sortBy,
			'sortOrder' => $sortOrder,
		]);
	}
	
	/**
	 * Отображает страницу с ошибкой
	 * @return string
	 */
	public function actionError(){
		return $this->render('error', [
			'title' => 'Ошибка 404: Страница не найдена'
		]);
	}
	
	/**
	 * Создание новой задачи
	 * @return string
	 */
	public function actionNew(){
		$task = new Task();
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$task->name = $_POST['name'];
			$task->email = $_POST['email'];
			$task->text = $_POST['text'];
			if(User::authed()){
				$task->done = intval(@$_POST['done']);
			}
			if($task->save()){
				if($_FILES['image'] && !$_FILES['image']['error']){
					$task->saveImage($_FILES['image']);
				}
				$this->redirect("/", "Задача успешно сохранена", "success");
			}
		}
		return $this->render('task', [
			'row' => $task,
			'title' => 'Новая задача'
		]);
	}
	
	/**
	 * Редактирование существующей задачи
	 * При обращении проверяем, что пользователь авторизован
	 * @return string
	 */
	public function actionEdit(){
		$task_id = $_GET['id'];
		$task_id += 0;
		if(!User::authed()){
			$_SESSION['returnUrl'] = '/index.php?action=edit&id='.$task_id;
			$this->redirect("/index.php?controller=user&action=login", "Сначала надо авторизоваться");
		}
		if(!$task_id ||
			!($task = Task::findOne($task_id))){
			$this->redirect("/", "Некорректный ID задачи", "danger");
		}
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$task->name = $_POST['name'];
			$task->email = $_POST['email'];
			$task->text = $_POST['text'];
			if(User::authed()){
				$task->done = intval($_POST['done']);
			}
			if($task->save()){
				if(@$_POST['imageDelete']){
					$task->deleteImage();
				}
				if($_FILES['image'] && !$_FILES['image']['error']){
					$task->saveImage($_FILES['image']);
				}
				$this->redirect("/", "Задача успешно изменена", "success");
			}
		}
		return $this->render('task', [
			'row' => $task,
			'title' => 'Редактирование задачи'
		]);
	}
}