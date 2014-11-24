<?php
namespace Core;
class Model
{
    protected $query;
    private $pdo;
    protected $firephp;
    protected $reg;
    private $ctypes;
    private $categories;
    private $fields;
    private $querycount;

	function __construct() {
		$this->reg = Registry::instance();

            $dbhost = $this->reg['dbhost'];
            $dbname = $this->reg['dbname'];
            $dbuser = $this->reg['dbuser'];
            $dbpass = $this->reg['dbpass'];
        try {
        $this->pdo = new \PDO ( "mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass );
        $this->pdo->exec('SET NAMES UTF8');
        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
		}
		catch(\PDOException $e) {
    	$error = $e->getMessage();
		Router::NoDb($error);
        exit;
		}
        //Enable debug
        $this->firephp = \FirePHP::getInstance(true);
        if ($this->reg['debug']) {
            $this->firephp->setEnabled(true);
        }
        else {
            $this->firephp->setEnabled(false);
        }
	}
	/* DB Functions */
    /* @todo придумать методы обработки ошибок от бд */

	protected function get_data($params = null)
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
        catch(\PDOException $e) {
            echo 'Ошибка '.$e->getMessage();
        }
        }
        $data = '';
	$query->setFetchMode(\PDO::FETCH_ASSOC);
	while ($row = $query->fetch()){
	 foreach ( $row as $key => $value ) {
				$data [$i] [$key] = $value;
			}
			$i ++;
		}
        if ($this->reg['debug']) {
            if (isset($this->querycount['get'])){
                $this->querycount['get']++;
            }
            else {
                $this->querycount['get'] = 1;
            }
        $this->firephp->log($this->querycount['get'], 'Get Queries');
        $this->firephp->log($query, 'Query');
        }
        if ($data){
            return $data;
        }
	}
	
	
	protected function set_data($params)
	{
	$query = $this->pdo->prepare($this->query);
	try { 
	$query->execute($params);
	$data = $this->pdo->lastInsertId();
        if ($data == 0) {
            $data = 1;
        }
	} catch(\PDOException $e) {
        $this->pdo->rollback();
        $data = "Error!: " . $e->getMessage() . "</br>";
    }
        if ($this->reg['debug']) {
            if (isset($this->querycount['set'])){
                $this->querycount['set']++;
            }
            else {
                $this->querycount['set'] = 1;
            }
        $this->firephp->log($this->querycount['set'], 'Set Queries');
        $this->firephp->log($query, 'Query');
        }
		return $data;
	 
	}
    /* Генератор INSERT запросов для PDO,
       ключи массива $data передаваемого функции должны соответствовать полям таблицы $tablename
    */
    protected function insert_query_generator($data, $tablename){

        if ((is_array($data)) && (count($data) >= 1)){
            $fields = array_keys($data);
            $query_prep_s = '';
            $query_prep_e = '';
            $last = array_pop($fields);
            foreach ($fields as $field){
                $query_prep_s .= '`'.$field.'`,';
                $query_prep_e .= ':'.$field.', ';
            }
            $query_prep_s .= '`'.$last.'`';
            $query_prep_e .= ':'.$last;


            $query = 'INSERT INTO `'.$tablename.'` ('.$query_prep_s.') ';
            $query .= 'VALUES ('.$query_prep_e.')';
            return $query;

        }
        else return false;

    }
    protected function update_query_generator($data, $where ,$tablename, $limit = 1){
        if ((is_array($data)) && (count($data) >= 1)
            &&
            (is_array($where)) && (count($where) >= 1)
        ){
            /* Сначала SET */
            $fields = array_keys($data);
            $query_prep_s = '';
            $last = array_pop($fields);
            if ($fields){
                foreach ($fields as $field){
                    $query_prep_s .= '`'.$field.'` = :'.$field.', ';
                }
            }
            $query_prep_s .= '`'.$last.'` = :'.$last;

            /* Теперь WHERE */

            $fields = array_keys($where);
            $query_prep_w = '';
            $last = array_pop($fields);
            if ($fields){
                foreach ($fields as $field){
                    $query_prep_w .= '`'.$field.'` = :'.$field.' AND ';
                }
            }
            $query_prep_w .= '`'.$last.'` = :'.$last;

            $query = 'UPDATE `'.$tablename.'` SET '.$query_prep_s;
            $query .= ' WHERE '.$query_prep_w.' LIMIT '.$limit;

            return $query;
        }
        else {
            return false;
        }
    }
    /* End DB Functions */


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
            "sh", "y", "e", "yu", "ya", "", "", "i",
            "i", "e",    "A", "B", "V", "G", "G", "D",
            "E", "E", "ZH", "Z", "I", "J", "K", "L",
            "M", "N", "O", "P", "R", "S", "T", "U",
            "F", "H", "C", "CH", "SH", "SH", "Y", "E",
            "YU", "YA", "", "", "I", "I", "E");
        return preg_replace($rus, $lat, $string);
    }
    public function escapestring($string, $space = null)
    {
        if ($space == true) {
            $string = preg_replace("/[\s]+/", "-", $string);
        }
        return preg_replace("/([\\x00-\\x1f\/\!\@\#\$\%\^\s\&\*\(\)\№\;\%\"\«\»\:\?\,\.\\\])/e", "", $string);
    }
    function randomgen($length) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";
        for ($x = 0; $x < $length; $x++):
            $string .= $characters[mt_rand(0, strlen($characters)-1)];
        endfor;
        return $string;
    }

    /* Unicon */
    function get_content_type($id = null, $by = 'id') {
        if ($id){
            if ($by == 'id'){
                if (is_array($this->ctypes)){
                    //var_dump($this->ctypes);
                    foreach ($this->ctypes as $ctype){
                        if ($ctype['id'] == $id){
                            return $ctype;
                        }
                    }
                }
                $this->query = 'SELECT * FROM `content_types` WHERE `id` = ?';
            }
            elseif ($by == 'alias'){
                if (!isset($this->ctypes[$id])){
                    $this->query = 'SELECT * FROM `content_types` WHERE `alias` = ?';
                }
                else {
                    $result = $this->ctypes[$id];
                    return $result;
                }
            }
            else {
                $this->query = 'SELECT * FROM `content_types` WHERE `'.$by.'` = ?';
            }
            $result = self::get_data(array($id));
        }
        else {
            $this->query = 'SELECT * FROM `content_types`';
            $result = self::get_data();
        }

        if($result){
            if (count($result) > 1){
                foreach ($result as $ctype){
                    $this->ctypes[$ctype['alias']] = $ctype;
                }
                return $result;
            }
            else {
                $ctype = $result[0];
                $this->ctypes[$ctype['alias']] = $ctype;
                return $ctype;
            }
        }
        else {
            return false;
        }
    }
    function get_fields($id, $wvalues = false) {
        //Нечто вроде кеширования запросов...
        if (isset($this->fields[$id]) && !$wvalues){
            return $this->fields[$id];
        }

            $this->query = '
        SELECT `t1`.*, `t2`.`name` AS `type_name`, `t2`.`htmltag`, `t2`.`alias`
        FROM `content_fields` as `t1`
        LEFT JOIN `field_types` AS `t2`
        ON `t1`.`field_type` = `t2`.`id`
        WHERE `t1`.`content_id` = ? ORDER BY `order` ASC';

        $fields = self::get_data(array($id));
        if($fields){
            if ($wvalues){
                foreach ($fields as &$field){
                    if ($field['assign_id']){
                        $ctype = $this->get_content_type($field['assign_id']);
                        if($ctype){
                            $this->query = 'SELECT * FROM `'.$ctype['alias'].'`';
                            $values = $this->get_data(array($field['assign_id']));
                            if ($values){
                                $field['values'] = $values;
                            }
                        }

                    }
                    if ($field['field_type'] == 7){
                        $field['values'] = explode(';', $field['custom']);
                        foreach ($field['values'] as &$i){
                            $i = array(
                                'string' => $i
                            );
                        }
                    }
                }
            }
            $this->fields[$id] = $fields;
            return $fields;
        }
        else {
            return false;
        }
    }



    /* End of Unicon */

    public function getmenuitem($id) {
        $this->query = 'SELECT * FROM `menu` WHERE `itemid` = ? AND `lang` = ?';
        $items = self::get_data(array($id, $GLOBALS['lang']));
        return $items;
    }
    public function getmenu($menutype) {
        $this->query = 'SELECT * FROM `menu` WHERE `menutype` = ? AND `lang` = ?';
        $tempitems = self::get_data(array($menutype, $GLOBALS['lang']));
        $items = array(); $childs = array();
        if ($tempitems) {
            foreach ($tempitems as $item) {
                if ($item['parent'] == '0') {
                    array_push($items, $item);
                }
                else {
                    array_push($childs, $item);
                }

            }
            foreach ($childs as $item) {
                $key = $item['parent'];
                $i = 0;
                foreach ($items as $tmp) {
                    if ($tmp['id'] == $key) {
                        if (!isset($items[$i]['submenu'])) {
                            $items[$i]['submenu'] = array();
                        }
                        array_push( $items[$i]['submenu'], $item);
                    }
                    $i++;
                }


            }
            //Определяем активный пункт

            foreach ($items as &$item) {
                $route = $this->reg['route'];
                if(isset($route['params'][1])){
                    $itemid = $route['params'][1];
                }


                $this->firephp->log($item, 'menuitem - start');
                $this->firephp->log($route, 'menuroute - start');


                if ($item['controller'] == 'unicon'){
                    if (isset($route['params'][0])){
                        if ($item['action'] == $route['params'][0]){
                            if ($item['alias'] == $route['params'][1]){
                                $item['active'] = true;
                            }

                        }
                        elseif ($item['action'] == 'category'){
                            if ($item['alias'] == $route['params'][0]){
                                $item['active'] = true;
                            }
                            elseif ($item['alias'] == $route['params'][2]){
                                $item['active'] = true;
                            }

                        }
                    }
                }
                elseif ($item['controller'] == $route['controller']){
                    $this->firephp->log($item, 'menuitem - ctrl');
                    $this->firephp->log($route, 'menuroute - ctrl');
                    if ($item['action']) {
                        if ($item['action'] == $route['action']) {
                            if ($item['alias']) {
                                if ($item['alias'] == $itemid){
                                    $item['active'] = true;
                                    $this->firephp->log($item, 'menuitem');
                                    $this->firephp->log($route, 'menuroute');
                                    break;
                                }
                            }
                            elseif ($item['itemid']) {
                                if ($item['itemid'] == $itemid){
                                    $item['active'] = true;
                                    $this->firephp->log($item, 'menuitem');
                                    $this->firephp->log($route, 'menuroute');
                                    break;
                                }

                            }
                            else {
                                $item['active'] = true;
                                $this->firephp->log($item, 'menuitem');
                                $this->firephp->log($route, 'menuroute');
                                break;
                            }
                        }

                    }
                    else{
                        $item['active'] = true;
                        $this->firephp->log($item, 'menuitem');
                        $this->firephp->log($route, 'menuroute');
                        break;
                    }
                }

            }
            return $items;
        }
        else {
            return false;
        }

    }
    function get_cat_by_id($cat) {
        $this->query = 'SELECT * FROM `categories` WHERE `id` = ?';
        $result = self::get_data(array($cat));
        if ($result){
            if (is_string($result[0]['image'])){
                $result[0]['image'] = unserialize($result[0]['image']);
            }
            return $result['0'];
        }
        else {
            return array('error'=> true, 'message' => 'Такой категории не существует');
        }


    }
    function get_cat_by_alias($cat) {
        if (isset($this->categories[$cat])){
            return $this->categories[$cat];
        }
        $this->query = 'SELECT * FROM `categories` WHERE `alias` = ?';
        $result = self::get_data(array($cat));
        if ($result){
            if (is_string($result[0]['image'])){
                $result[0]['image'] = unserialize($result[0]['image']);
            }
            $this->categories[$cat] = $result['0'];
            return $result['0'];
        }
        else {
            return false;
        }

    }
    function get_allcat($full = false) {
        if ($full){
            $this->query = '
            SELECT `t1`.*, `t2`.`name` AS `typename`
            FROM `categories` AS `t1`
            LEFT JOIN `content_types` AS `t2`
            ON `t1`.`type` = `t2`.`alias`';
        }
        else {
            $this->query = 'SELECT `id`, `name` FROM `categories`';
        }

        $result = self::get_data();
        return $result;
    }

	public function aliasgen($alias) {
		$alias = self::transliterize($alias);
		$alias = self::escapestring($alias, true);
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
    function unserialise_images($items){
        foreach ($items as &$item){
            if ($item['images']){
                if (is_string($item['images'])){
                $item['images'] = unserialize($item['images']);
                }
                if ($item['name']){
                    $item['name'] = htmlspecialchars(stripcslashes($item['name']));
                }
            }
        }
        return $items;
    }
	public function checkimage($file, $uploaddir, $filename = null){
        /*Для совместимости с предыдущими версиями
        @todo Выпилить после того как все контроллеры будут пофикшены.
        */
        if (!$filename){
            $filename = $_REQUEST['name'];
        }
			$filename = basename($file['name']);
			if ($file['type'] == 'image/jpeg') {
				$extn = '.jpg';
			}
			if ($file['type'] == 'image/png') {
				$extn = '.png';
			}
			$newfilename = self::filenamegen($filename) . $extn;
			$uploadfile = $uploaddir . $newfilename;
			if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                return $newfilename;
			} else {
				return false;
			}
	}
	public function imagethumb($img, $imagedir, $width, $height) {
		$thumbdir = $imagedir . 'thumbs' . DS;
		if (!file_exists($thumbdir)) {
			mkdir($thumbdir, 0755);
		}
		$image = $imagedir . $img;
		$thumbimage = $thumbdir .'sm_' .$img;
        $thumb = new \Generic\SimpleImage();
        $thumb->load($image);
        $thumb->best_fit($width, $height);
        $thumb->save($thumbimage);
		return basename($thumbimage);
		}
    public function imageresize($img, $imagedir, $width, $height, $crop = null) {
        $image = $imagedir . $img;
        $thumb = new \Generic\SimpleImage();
        $thumb->load($image);
        $orientation = $thumb->get_orientation();
        switch ($orientation) {
            case 'portrait': {
                $thumb->fit_to_height($height);
                $thumb->fill_to_width($width, $height, '#f0f3f5');
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
        if ($crop){
            $thumb->smart_crop($width, $height);
        }
        $thumb->save($image);
        return basename($image);
    }
    public function desaturate($img, $imagedir, $level = 1, $color = null) {
        $image = $imagedir . $img;
        $newimage = $imagedir . 'ds_' .$img;
        $render = new \Generic\SimpleImage();
        $render->load($image);
        $render->desaturate();
        if ($color) {
            $render->colorize($color, $level);
        }
        $render->save($newimage);
        return basename($newimage);
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
    if(!rmdir($path)) echo 'error ', 'Не удалось удалить папку «'.$path.'»!';

    } else {
    echo 'error', 'Папки «'.$path.'» не существует!';
    }
    }
    //Временные функции
    /* Ordering */
    function saveorder() {
        var_dump($GLOBALS["lang"]);
        $razdel = $_REQUEST['razdel'];
        var_dump($razdel);
        $query_prep = 'UPDATE `'.$razdel.'_'.$GLOBALS["lang"].'` SET `order` = :order WHERE `id` =:id';
        $this->query = $query_prep;
        $i=0;
        while (list($key, $val) = each($_REQUEST)) {
            $item = preg_split ( '/[-]+/',  $key);
            if ($item[0] != 'razdel') {
                $datas = array('id' =>  $item[1], 'order' => (int)$val);
                $result[$i] = self::set_data($datas);
                //print_r ($datas);
                $i++;
            }
        }
        return $result;
    }
    /* Управление категориями !!!!экспериментально!!!! */
    function get_categories($type = null) {
        if (!empty($type)) {
            $datas = array($type);
        }
        else {
            $datas = array('*');
        }
        $this->query = 'SELECT `id`, `name`,`alias`, `type` FROM `categories` WHERE `type` = ?';
        $result = self::get_data($datas);
        return $result;
    }

    //пока тут поживет. и блядь да захуярь себе щелбан в следующий раз когда решишь написать какую нибудь подобную уёбу
    function user($type = null) {
        if ($type) {
            switch ($type) {
                case 'admin' : {
                    if ($_SESSION['admin'] == 1){
                        $_SESSION['manager'] = 1;
                        $_SESSION['user'] = 1;
                        return true;
                    }
                    else {
                        return false;
                    }
                    break;
                }
                case 'manager' : {
                    if ($_SESSION['manager'] == 1){
                        $_SESSION['user'] = 1;
                        return true;
                    }
                    else {
                        return false;
                    }
                    break;
                }
                case 'user' : {
                    if ($_SESSION['user'] == 1){
                        return true;
                    }
                    else {
                        return false;
                    }
                    break;
                }
                    default : {
                        return false;
                    }
            }
        }
        else {
                if ($_SESSION['admin'] == 1 || $_SESSION['manager'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
    }
    function userlevel(){

    }
    function get_user($name = null) {
        if ($name) {
            $this->query = 'SELECT * FROM `user` WHERE `name` = ? LIMIT 1';
            $user = self::get_data(array($name));
        }
        else {
            $this->query = 'SELECT * FROM `user` WHERE `name` LIKE :name LIMIT 1';
            $this->name = array ( 'name' => $this->name );
            $user = self::get_data($this->name);
        }
        return $user['0'];
    }
    //Ублюдская костылячья функция, переписать при первой же возможности
    function authenticate($level = 'user') {
        $login = filter_var($_REQUEST['login'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        $pass = $_REQUEST['pass'];

        $user = $this->get_user($login);
        $pass = $this->passtohash($pass);

        if ($pass == $user['pass'] && $user['status'] == '1')
        {
            $_SESSION['userid'] = $user['id'];
            switch ($user['level']) {
                case 'admin' : {
                    $_SESSION['admin'] = 1;
                    $_SESSION['manager'] = 1;
                    $_SESSION['user'] = 1;
                    $_SESSION['userlevel'] = 'admin';
                    return true;
                    break;
                }
                case 'manager' : {
                    if ($level == 'manager'){
                        $_SESSION['manager'] = 1;
                        $_SESSION['user'] = 1;
                        $_SESSION['userlevel'] = 'manager';
                        return true;
                    }
                    else {
                        return false;
                    }
                    break;
                }
                case 'user' : {
                    $_SESSION['user'] = 1;
                    $_SESSION['userlevel'] = 'user';
                    return true;
                    break;
                }
            }
        }
        else {
            return false;
        }
    }
    function passtohash($pass){
        $salt = $this->reg['salt'];
        $hash = md5($salt.$pass);
        return $hash;
    }
    function get_settings($type){
        $this->query = 'SELECT * FROM `settings` WHERE `type` = ?';
        $result = $this->get_data(array($type));
        return $result[0]['value'];
    }
}