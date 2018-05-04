<?php
namespace app\base;

/**
 * Базовая модель, содержащая общие методы работы с моделями.
 *
 * Class Model
 * @package app\base
 */
class Model {
	public static $db;
	public $error;
	
	protected $attributes = [];
	
	public function __construct($attributes = []){
		foreach($attributes as $key => $value){
			if(is_numeric($key)) continue;
			$this->attributes[$key] = $value;
		}
	}
	
	/**
	 * Выдает название таблицы, соответствующей этой модели
	 * @return string
	 */
	public static function tableName(){
		return strtolower(basename(str_replace("\\", "/", get_called_class())));
	}
	
	/**
	 * Магический геттер для работы с атрибутами объекта,
	 * если в модели определили обработчик конкретного атрибута, то вызываем его,
	 * иначе просто выводим атрибут
	 * @param $name
	 * @return mixed|null
	 */
	public function __get($name){
//		var_dump([$name, 'get'.ucfirst($name), method_exists($this, 'get'.ucfirst($name)), is_callable([$this, 'get'.ucfirst($name)])]);
		if(method_exists($this, 'get'.ucfirst($name))){
			return $this->{'get'.ucfirst($name)}();
		} else if(isset($this->attributes[$name])) return $this->attributes[$name];
		else return NULL;
		//возможно тут стоит кидать исключение
	}
	
	/**
	 * Магический сеттер для работы с атрибутами объекта,
	 * если в модели определили обработчик конкретного атрибута, то вызываем его,
	 * иначе просто сохраняем атрибут
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value){
		if(method_exists($this, 'set'.ucfirst($name))){
			$this->{'set'.ucfirst($name)}($value);
		} else {
			$this->attributes[$name] = $value;
		}
	}
	
	/**
	 * Выборка строк из таблицы по заданным параметра
	 * @param array $options параметры выборки where, order, limit, offset
	 * @return array Массив объектов моделей
	 */
	public static function find($options = []){
		$query = 'SELECT * FROM '.self::tableName();
		if(isset($options['where'])){
			$query .= " WHERE ";
			if(is_array($options['where'])){
				$wheres = [];
				foreach($options['where'] as $key => $value){
					if(is_numeric($key)) $wheres[] = $value;
					else $wheres[] = "`{$key}`=".self::$db->quote($value)."";
				}
				$query .= implode(" AND ", $wheres);
			} else {
				$query .= $options['where'];
			}
		}
		if(isset($options['order'])){
			$query .= " ORDER BY ".$options['order'];
		}
		if(isset($options['limit'])){
			$query .= " LIMIT ".$options['limit'];
			if(isset($options['offset'])) $query .= " OFFSET ".$options['offset'];
		}
		$rows = [];
		if($result = self::$db->query($query)){
			$className = get_called_class();
			foreach ($result as $row){
				$rows[] = new $className($row);
			}
		}
		return $rows;
	}
	
	/**
	 * Возвращает один объект модели по $id, либо NULL если не найден
	 * @param $options
	 * @return mixed|null
	 */
	public static function findOne($id){
		if($rows = self::find([
			'where' => ['id' => $id]
		])){
			return $rows[0];
		}
		return NULL;
	}
	
	/**
	 * Возвращает общее число объектов по условиям из предыдущей выборки
	 * @param null $where
	 * @return int
	 */
	public static function countAll(){
		$result = self::$db->query('SELECT COUNT(*) FROM '.self::tableName());
//		$rows->execute();
		return $result->fetch()[0];
		
		return 0;
	}
	
	/**
	 * Сохранение объекта в БД
	 * @return bool
	 */
	public function save(){
		if($this->id){
			$query = 'UPDATE '.self::tableName().' SET ';
		} else {
			$query = 'INSERT INTO '.self::tableName().' SET ';
		}
		$comma = false;
		foreach($this->attributes as $key => $value){
			if($comma) $query .= ", ";
			$comma = true;
			$query .= "`{$key}`=".self::$db->quote($value)."";
		}
		
		if($this->id){
			$query .= " WHERE id=".$this->id;
		}
		$result = self::$db->query($query);
		if($error = self::$db->errorInfo()){
			$this->error = $error[2];
		}
		if(!$this->id){
			$this->id = self::$db->lastInsertId();
			return $this->id;
		}
		return !!$result;
	}
	
	/**
	 * Удаление объекта из БД
	 */
	public function delete(){
		$this->id += 0;
		if($this->id && $this->table){
			$query = "DELETE FROM ".self::tableName()." WHERE id=".$this->id;
			self::$db->query($query);
		}
	}
}