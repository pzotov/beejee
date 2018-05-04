<?php
namespace app\models;

/**
 * Модель для работы с задачами
 * Class Task
 * @package app\models
 */
class Task extends \app\base\Model {
	const IMAGE_MAX_WIDTH = 320;
	const IMAGE_MAX_HEIGHT = 240;
	
	protected static $allowed_types = [
		IMAGETYPE_GIF => 'gif',
		IMAGETYPE_JPEG => 'jpg',
		IMAGETYPE_PNG => 'png'
	];
	
	const SORT_DEFAULT = 0;
	const SORT_NAME = 1;
	const SORT_EMAIL = 2;
	const SORT_STATUS = 3;
	public static $sortings = [
		self::SORT_DEFAULT => 'id',
		self::SORT_NAME => 'name',
		self::SORT_EMAIL => 'email',
		self::SORT_STATUS => 'done',
	];
	
	/**
	 * Возвращает путь к загруженной картинке либо NULL, если нет картинки
	 * @return null|string
	 */
	public function getImage(){
		if($this->attributes['image']) return '/images/'.$this->id.'.'.$this->attributes['image'];
		else return NULL;
	}
	
	/**
	 * Возвращает путь для сохранения картинки на сервере
	 * @return string
	 */
	protected function imagePath(){
		return realpath(_APPDIR.'/..').'/images/'.$this->id.'.'.$this->attributes['image'];
	}
	
	/**
	 * Сохраняет загруженный файл на сервер. Если надо меняет размеры картинки
	 * @param $file
	 * @return bool
	 */
	public function saveImage($file){
		//проверяем, есть ли файл
		if(!file_exists($file['tmp_name'])) return false;
		$size = getimagesize($file['tmp_name']);
		//проверяем тип файла, пропускаем только gif, jpg, png
		if(!in_array($size[2], array_keys(self::$allowed_types))) return false;
		//записываем, какое будет расширение у файла
		$this->attributes['image'] = self::$allowed_types[$size[2]];
		
		//если размеры укладываются в заданные рамки, то просто сохраняем файл
		if($size[0]<=self::IMAGE_MAX_WIDTH && $size[1]<=self::IMAGE_MAX_HEIGHT){
			move_uploaded_file($file['tmp_name'], $this->imagePath());
			$this->save();
			return true;
		}
		//вычисляем, насколько надо уменьшить картинку
		$kW = $size[0]/self::IMAGE_MAX_WIDTH;
		$kH = $size[1]/self::IMAGE_MAX_HEIGHT;
		
		if($kW>$kH){
			$newWidth = self::IMAGE_MAX_WIDTH;
			$newHeight = round($size[1]/$kW);
		} else {
			$newWidth = round($size[0]/$kH);
			$newHeight = self::IMAGE_MAX_HEIGHT;
		}
		$srcImage = imagecreatefromstring(file_get_contents($file['tmp_name']));
		$dstImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $size[0], $size[1]);
		//сохраняем в оригинальном формате
		switch($size[2]){
			case IMAGETYPE_GIF:
				imagegif($dstImage, $this->imagePath());
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($dstImage, $this->imagePath());
				break;
			case IMAGETYPE_PNG:
				imagepng($dstImage, $this->imagePath());
				break;
		}
		$this->save();
		return true;
	}
	
	/**
	 * Удаляем картинку
	 */
	public function deleteImage(){
		@unlink($this->imagePath());
		$this->attributes['image'] = '';
		$this->save();
	}
	
}