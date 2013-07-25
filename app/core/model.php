<?php
class Model
{
	public $query;
	private $pdo;
	function __construct() {
		try {
		require_once app_patch . DS . 'config.php';
        $this->pdo = new PDO ( "mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass );
		$this->pdo->exec('SET NAMES UTF8');
		$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		catch(PDOException $e) {  
    	echo 'Ошибка '.$e->getMessage();  
		Router::NoDb(); 
		}
	}
	
	public function get_data($params = null)
	{
	$i=0;
	$query = $this->pdo->prepare($this->query);
	if ($params != null) {
		//var_dump($params);
		$query->execute($params);
	}
	else {
        try {
	$query->execute();
        }
        catch(PDOException $e) {
            echo 'Ошибка '.$e->getMessage();
        }
        }
	$query->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $query->fetch()){
	 foreach ( $row as $key => $value ) {
				$data [$i] [$key] = $value;
			}
			$i ++;
		}
		return $data;
	 
	}
	
	
	public function set_data($params)
	{
      //  var_dump($params);
	$query = $this->pdo->prepare($this->query);
	try { 
	$query->execute($params);
	$data = $this->pdo->lastInsertId();
        if ($data == 0) {
            $data = 1;
        }
	} catch(PDOExecption $e) {
        $this->pdo->rollback();
        $data = "Error!: " . $e->getMessage() . "</br>";
    } 
		return $data;
	 
	}
    public function transliterize($string)
    {
        $rus = array("/а/", "/б/", "/в/",
            "/г/", "/ґ/", "/д/", "/е/", "/ё/", "/ж/",
            "/з/", "/и/", "/й/", "/к/", "/л/", "/м/",
            "/н/", "/о/", "/п/", "/р/", "/с/", "/т/",
            "/у/", "/ф/", "/х/", "/ц/", "/ч/", "/ш/",
            "/щ/", "/ы/", "/э/", "/ю/", "/я/", "/ь/",
            "/ъ/", "/і/", "/ї/", "/є/", "/А/", "/Б/",
            "/В/", "/Г/", "/ґ/", "/Д/", "/Е/", "/Ё/",
            "/Ж/", "/З/", "/И/", "/Й/", "/К/", "/Л/",
            "/М/", "/Н/", "/О/", "/П/", "/Р/", "/С/",
            "/Т/", "/У/", "/Ф/", "/Х/", "/Ц/", "/Ч/",
            "/Ш/", "/Щ/", "/Ы/", "/Э/", "/Ю/", "/Я/",
            "/Ь/", "/Ъ/", "/І/", "/Ї/", "/Є/");
        $lat = array("a", "b", "v",
            "g", "g", "d", "e", "e", "zh", "z", "i",
            "j", "k", "l", "m", "n", "o", "p", "r",
            "s", "t", "u", "f", "h", "c", "ch", "sh",
            "sh'", "y", "e", "yu", "ya", "", "", "i",
            "i", "e",    "A", "B", "V", "G", "G", "D",
            "E", "E", "ZH", "Z", "I", "J", "K", "L",
            "M", "N", "O", "P", "R", "S", "T", "U",
            "F", "H", "C", "CH", "SH", "SH'", "Y", "E",
            "YU", "YA", "", "", "I", "I", "E");
        return preg_replace($rus, $lat, $string);
    }
    public function escapestring($string, $space = null)
    {
        if ($space == true) {
            $string = preg_replace("/[\s]+/", "-", $string);
        }
        return preg_replace("/([\\x00-\\x1f\/\!\@\#\$\%\^\s\&\*\(\)\№\;\%\"\:\?\\\])/e", "", $string);
    }
    function randomgen($length) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";
        for ($x = 0; $x < $length; $x++):
            $string .= $characters[mt_rand(0, strlen($characters))];
        endfor;
        return $string;
    }
    public function getmenuitem($id) {
        $this->query = 'SELECT * FROM `menu` WHERE `itemid` = ? AND `lang` = ?';
        $items = self::get_data(array($id, $GLOBALS['lang']));
        return $items;
    }
    public function getmenu($menutype) {
        $this->query = 'SELECT * FROM `menu` WHERE `menutype` = ? AND `lang` = ?';
        $items = self::get_data(array($menutype, $GLOBALS['lang']));
        return $items;
    }
	public function aliasgen($alias) {
		$alias = self::transliterize($alias);
		$alias = self::escapestring($alias);
		$alias = mb_strtolower($alias);
		return $alias;
		}
    public function filenamegen($name){
        $filename = self::transliterize($name);
        $filename = self::escapestring($filename);
        $filename = substr($filename, 0 , 7);
        $filename = $filename . self::randomgen(4);
        $filename = mb_strtolower($filename);
        return $filename;
    }
	public function rearrange( $arr ){
		foreach( $arr as $key => $all ){
			foreach( $all as $i => $val ){
				$new[$i][$key] = $val;   
			}   
		}
		return $new;
	}
	public function checkimage($file, $uploaddir){
			$filename = basename($file['name']);
			if ($file['type'] == 'image/jpeg') {
				$extn = '.jpg';
			}
			if ($file['type'] == 'image/png') {
				$extn = '.png';
			}
			$filename = self::filenamegen($_REQUEST['name']) . $extn;
			$uploadfile = $uploaddir . $filename;
			if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
			} else {
				echo "Возможная атака с помощью файловой загрузки!\n";
			}
			return $filename;
	}
	public function imagethumb($img, $imagedir, $width) {
		$thumbdir = $imagedir . 'thumbs' . DS;
		if (!file_exists($thumbdir)) {
			mkdir($thumbdir, 0755);		   
		}
		$image = $imagedir . $img;
		$thumbimage = $thumbdir .'sm_' .$img;
        require_once app_patch . 'core' . DS . 'SimpleImage.php';
        $thumb = new SimpleImage();
        $thumb->load($image);
        $thumb->fit_to_width($width);
        $thumb->save($thumbimage);
		return basename($thumbimage);
		}
    public function imageresize($img, $imagedir, $width, $height) {
        $image = $imagedir . $img;
        require_once app_patch . 'core' . DS . 'SimpleImage.php';
        $thumb = new SimpleImage();
        $thumb->load($image);
        $orientation = $thumb->get_orientation();
        switch ($orientation) {
            case 'portrait': {
                $thumb->fit_to_height($height);
                $thumb->fill_to_width($width, $height, '#FDFDFD');
                break;
            }
            case 'landscape': {
                $thumb->smart_crop($width, $height);
                break;
            }
            case 'square': {
                $thumb->fit_to_height($height);
                break;
            }
        }
        $thumb->save($image);
        return basename($image);
    }
	
	public function RemoveDir($path){
    if(file_exists($path) && is_dir($path)){
    $dirHandle = opendir($path);
    while(false!==($file = readdir($dirHandle))){
    if($file!='.' && $file!='..'){
    $tmpPath = $path.'/'.$file;
    chmod($tmpPath, 0777);
    if(is_dir($tmpPath)){
    self::RemoveDir($tmpPath);
    } else {
    if(!unlink($tmpPath)) echo 'Не удалось удалить файл «'.$path.'»!';
    }
    }
    }
    closedir($dirHandle);
     
    // удаляем текущую папку
    if(!rmdir($path)) echo 'error', 'Не удалось удалить папку «'.$path.'»!';
     
    } else {
    echo 'error', 'Папки «'.$path.'» не существует!';
    }
    }
}