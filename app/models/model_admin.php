<?php
class Model_Admin extends Model {
    public $name;
    public $pass;
    function __construct() {
        parent::__construct();

    }
    function user() {
        if ($_SESSION['admin'] == 1) {
            return true;
        } else {
            return false;
        }
    }
    function authenticate($user) {
        if ($this->pass == $user[0][pass])
        {
            $_SESSION['admin'] = 1;
            header('Location: /'.$GLOBALS["lang"].'/admin/');
        }
        else {
            return 'К сожалению такой логин и/или пароль не найден в системе';
        }
    }

    function get_user() {
        $this->query = 'SELECT * FROM `user` WHERE `name` LIKE :name';
        $this->name = array ( 'name' => $this->name );
        $user = self::get_data($this->name);
        return $user;
    }
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
    /* Articles */
    function list_articles($lang = null,$status = null) {
        if (!isset($lang)) {
            $lang = $GLOBALS["lang"];
        }
        if ($status == true) {
            $prequery = ' WHERE `status` = 1';
        }
        else {
            $prequery = '';
        }
        $this->query = 'SELECT `id`, `menu`, `status`, `order` FROM `articles_'.$lang.'`'.$prequery;
        $result = self::get_data();
        return $result;
    }
    function get_article($id) {
        $this->query = 'SELECT * FROM `articles_'.$GLOBALS["lang"].'` WHERE `id` = ? ';
        $result = self::get_data(array($id));
        return $result;
    }
    function  update_article() {
        if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
            $alias = self::aliasgen($_REQUEST['menu']);
        }
        else {
            $alias = $_REQUEST['alias'];
        }
        $this->query = 'UPDATE `articles_'.$GLOBALS["lang"].'` SET `menu` = :menu, `alias` = :alias, `name` = :name, `text` = :text, `seotitle` = :seotitle, `seokeys` = :seokeys, `seodescription` = :seodescription, `status` = :status  WHERE `id` = :id LIMIT 1';

        $datas = array('menu' => $_REQUEST['menu'], 'alias' => $alias, 'name' => $_REQUEST['name'], 'text' => $_REQUEST['text'], 'seotitle' => $_REQUEST['seotitle'], 'seokeys' => $_REQUEST['seokeys'], 'seodescription' => $_REQUEST['seodescription'], 'status' => $_REQUEST['status'], 'id' => $_REQUEST['id']);

        $result = self::set_data($datas);
        return $result;
    }
    function delete_article() {
        $item = $_REQUEST['id'];
        $this->query = 'DELETE FROM `articles_'.$GLOBALS["lang"].'` WHERE `articles_'.$GLOBALS["lang"].'`.`id` = ? ';
        $result = self::set_data(array($item));
        return $result;
    }
    function  new_article() {
        if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
            $alias = self::aliasgen($_REQUEST['menu']);
        } else {
            $alias = $_REQUEST['alias'];
        }
        $this->query = 'INSERT INTO `articles_'.$GLOBALS["lang"].'` (`id`, `menu`, `name`, `alias`, `text`, `category`, `status`, `seotitle`, `seokeys`, `seodescription`) VALUES (NULL, :menu , :name, :alias, :text, "1", :status, :seotitle, :seokeys, :seodescription)';
        $datas = array('menu' => $_REQUEST['menu'], 'name' => $_REQUEST['name'], 'alias' => $alias, 'text' => $_REQUEST['text'], 'status' => $_REQUEST['status'], 'seotitle' => $_REQUEST['seotitle'], 'seokeys' => $_REQUEST['seokeys'], 'seodescription' => $_REQUEST['seodescription']);
        $result = self::set_data($datas);
        return $result;
    }

    /* Projects */

    function list_projects($lang = null,$status = null) {
        if (!isset($lang)) {
            $lang = $GLOBALS["lang"];
        }
        if ($status == true) {
            $prequery = ' WHERE `status` = 1';
        }
        else {
            $prequery = '';
        }
        $this->query = 'SELECT `id`, `menu`, `status`, `order` FROM `projects_'.$lang.'`'.$prequery;
        $result = self::get_data();
        return $result;
    }
    function get_project($id) {
        $this->query = 'SELECT * FROM `projects_'.$GLOBALS["lang"].'` WHERE `id` = ? ';
        $result = self::get_data(array($id));
        return $result;
    }
    function delete_project() {
        $item = self::get_project($_REQUEST['id']);
        $item = $item[0];
        $itemdir =  images_folder . 'furniture' . DS . $item['imagefolder'] . DS ;
        try {
            self::RemoveDir($itemdir);
        } catch (Exception $e) {
        }
        $this->query = 'DELETE FROM `projects_'.$GLOBALS["lang"].'` WHERE `projects_'.$GLOBALS["lang"].'`.`id` = ? ';
        $result = self::set_data(array($item['id']));
        return $result;
    }
    function update_project() {
        if (empty($_REQUEST['menu']) || ($_REQUEST['menu'] == '')) {
            $menu = $_REQUEST['name'];
        } else {
            $menu = $_REQUEST['menu'];
        }
        if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
            $alias = self::aliasgen($menu);
        } else {
            $alias = $_REQUEST['alias'];
        }
        $datas = array('id' => $_REQUEST['id'], 'name' => $_REQUEST['name'], 'menu' => $menu, 'alias' => $alias, 'address' => $_REQUEST['address'], 'description' => $_REQUEST['description'], 'extra' => $_REQUEST['extra'], 'seotitle' => $_REQUEST['seotitle'], 'seokeys' => $_REQUEST['seokeys'], 'seodescription' => $_REQUEST['seodescription'], 'status' => $_REQUEST['status'], 'thumbs' => $_REQUEST["thumbs"]);
        $query_prep = 'UPDATE `projects_'.$GLOBALS["lang"].'` SET `id`= :id, `menu`=:menu, `name`=:name, `alias`=:alias, `address` =:address,`description`=:description, `extra`=:extra,`seotitle`=:seotitle, `seokeys`=:seokeys, `seodescription`=:seodescription, `status`=:status, `thumbs`=:thumbs';
        $uploaddir = images_folder . 'furniture' . DS . $_REQUEST['imagefolder'] . DS ;
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
            $logo = self::checkimage($_FILES['logo'], $uploaddir);
            $query_prep .= ', `logo` = :logo';
            $datas['logo'] = $logo;
        }
        if  (isset($_FILES['aux_images']['name'])) {
            $images = self::rearrange($_FILES['aux_images']);
            $i = 0;
            foreach ($images as $image) {
                if (!empty($image['name'])) {
                    $tmpimg = self::checkimage($image, $uploaddir);
                    $image = self::imageresize($tmpimg, $uploaddir, 660, 494);
                    $aux_images_thumb = self::imagethumb($tmpimg, $uploaddir, 130);
                    $aux_images[$i] = array ('image' => $image, 'thumb' => $aux_images_thumb);
                    $i++;
                }
            }
            if (!empty($aux_images)){

                if ($_REQUEST['imagemode'] == 'add') {
                    $this->query = 'SELECT `images` FROM `projects_'.$GLOBALS["lang"].'` WHERE `id` = ?';
                    $serialized = self::get_data(array($_REQUEST['id']));
                    $existimages = unserialize($serialized['0']['images']);
                    if (!empty($existimages)) {
                        $aux_images = array_merge($aux_images, $existimages);
                    }
                }
                $aux_images = serialize($aux_images);
                $query_prep .= ', `images` = :images';
                $datas['images'] = $aux_images;
            }
        };
        $query_prep .= ' WHERE `projects_'.$GLOBALS["lang"].'`.`id` = :id LIMIT 1';
        $this->query = $query_prep;
        $result = self::set_data($datas);
        return $result;
    }



    function new_project() {
        if (empty($_REQUEST['menu']) || ($_REQUEST['menu'] == '')) {
            $menu = $_REQUEST['name'];
        } else {
            $menu = $_REQUEST['menu'];
        }
        if (!empty($_REQUEST['imagefolder'])) {
            $imagefolder = $_REQUEST['imagefolder'];
        }
        else {
            $imagefolder = self::filenamegen($_REQUEST['name']);
        }
        if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
            $alias = self::aliasgen($menu);
        } else {
            $alias = $_REQUEST['alias'];
        }
        $datas = array('name' => $_REQUEST['name'], 'menu' => $menu, 'alias' => $alias, 'address' => $_REQUEST['address'], 'description' => $_REQUEST['description'], 'extra' => $_REQUEST['extra'], 'seotitle' => $_REQUEST['seotitle'], 'seokeys' => $_REQUEST['seokeys'], 'seodescription' => $_REQUEST['seodescription'], 'imagefolder' => $imagefolder, 'status' => $_REQUEST['status'], 'thumbs' => $_REQUEST["thumbs"]);
        $query_prep_s = 'INSERT INTO `projects_'.$GLOBALS["lang"].'` (`id`, `menu`, `name`, `alias`, `address` ,`description`, `extra`,`seotitle`, `seokeys`, `seodescription`, `imagefolder`, `status`, `thumbs`, `category`';
        $query_prep_e = 'VALUES (NULL ,:menu, :name,:alias, :address, :description, :extra,:seotitle,:seokeys, :seodescription, :imagefolder, :status, :thumbs, 1';
        $uploaddir = images_folder . 'furniture' . DS . $imagefolder . DS ;
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0755);
        }
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
            $logo = self::checkimage($_FILES['logo'], $uploaddir);
            $query_prep_s .= ' , `logo`';
            $query_prep_e .= ', :logo';
            $datas['logo'] = $logo;
        }
        if  (isset($_FILES['aux_images']['name'])) {
            $images = self::rearrange($_FILES['aux_images']);
            $i = 0;
            foreach ($images as $image) {
                if (!empty($image['name'])) {
                    $tmpimg = self::checkimage($image, $uploaddir);
                    $image = self::imageresize($tmpimg, $uploaddir, 660, 494);
                    $aux_images_thumb = self::imagethumb($tmpimg, $uploaddir, 130);
                    $aux_images[$i] = array ('image' => $image, 'thumb' => $aux_images_thumb);
                    $i++;
                }
            }
            if (!empty($aux_images)){
                $query_prep_s .= ', `images`';
                $query_prep_e .= ', :images';
                $datas['images'] = serialize($aux_images);
            }
        };
        $query_prep_s .= ') ';
        $query_prep_e .= ')';

        $query_prep = $query_prep_s . $query_prep_e;
        $this->query = $query_prep;
        $result = self::set_data($datas);
        return $result;
    }
    /* Materials */
	/* Articles */
    function list_materials($category, $lang = null,$status = null) {
        if (!isset($lang)) {
            $lang = $GLOBALS["lang"];
        }
        if ($status == true) {
            $prequery = ' AND `status` = ?';
        }
        else {
            $prequery = '';
        }
        $this->query = 'SELECT `id`, `name`, `status`, `order` FROM `materials_'.$lang.'` WHERE `category` = ?'.$prequery;
        $result = self::get_data(array($category));
        return $result;
    }
    function get_material($id) {
        $this->query = 'SELECT * FROM `materials_'.$GLOBALS["lang"].'` WHERE `id` = ? ';
        $result = self::get_data(array($id));
        return $result;
    }

    function  update_material() {
        $uploaddir = images_folder . 'materials' . DS ;
        $datas = array('name' => $_REQUEST['name'], 'description' => $_REQUEST['description'], 'category' => $_REQUEST['category'], 'status' => $_REQUEST['status'], 'id' => $_REQUEST['id']);
        if (!empty($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $tmpimg = self::checkimage($_FILES['image'], $uploaddir);
            $datas['image'] = self::imageresize($tmpimg, $uploaddir, 150, 150);
            $query_prep = ' `image` = :image ,';
        }
        else {
            $query_prep = '';
        }

        $this->query = 'UPDATE `materials_'.$GLOBALS["lang"].'` SET `name` = :name, '.$query_prep.' `description` = :description, `category` = :category, `status` = :status  WHERE `id` = :id';
        $result = self::set_data($datas);
        return $result;
    }

    function delete_material() {
        $item = $_REQUEST['id'];
        $this->query = 'DELETE FROM `materials_'.$GLOBALS["lang"].'` WHERE `id` = ? ';
        $result = self::set_data(array($item));
        return $result;
    }

    function  new_material() {
        $uploaddir = images_folder . 'materials' . DS ;
        $this->query = 'INSERT INTO `materials_'.$GLOBALS["lang"].'` (`id`, `name`, `image`,`category`, `description`, `status`) VALUES (NULL, :name, :image, :category, :description, :status)';
        $datas = array('name' => $_REQUEST['name'], 'category' => $_REQUEST['category'], 'status' => $_REQUEST['status'], 'description' => $_REQUEST['description']);
        if (!empty($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $tmpimg = self::checkimage($_FILES['image'], $uploaddir);
            $datas['image'] = self::imageresize($tmpimg, $uploaddir, 150, 150);
        }
        else {
            $datas['image'] = '';
        }
        $result = self::set_data($datas);
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
        $this->query = 'SELECT `id`, `name`, `type` FROM `categories` WHERE `type` = ?';
        $result = self::get_data($datas);
        return $result;
    }
}
	