<?php

class Controller_admin extends Controller
{
		function __construct()
	{
		$this->model = new Model_Admin();
		$this->view = new View();
        $this->view->controller = ('admin');
		session_start();
	}
	
	function action_index()
	{	
		if ($this->model->user() == true) {
			$this->view->generate('admin_view.php', 'admin_template_view.php');
			}
		else $this->action_login();
		
	}
	
	function action_login()
	{	
	if ($this->model->user() == true) {
				$data = $_SESSION['admin'];
				$this->view->generate('admin_view.php', 'admin_template_view.php', $data);
				}
	else {
	if (isset($_REQUEST['confirm'])) {
		if (!empty($_REQUEST["Login"]) && !empty($_REQUEST["pass"])){
			$this->model->name = $_REQUEST["Login"];
			$this->model->pass = $_REQUEST["pass"];
			$user = $this->model->get_user();
			$data = $this->model->authenticate($user);
			}
		else {
			$data = 'А логины пароли писать кто будет?';
			
			}
	}}
		$this->view->generate('admin_login_view.php', 'admin_template_view.php', $data);
	}
	function action_logout()
	{

		unset($_SESSION['admin']);
		header('Location: /'.$GLOBALS['lang'].'/admin/');
		exit;
	}

    /* Сортировка */
    function action_saveorder() {
        if ($this->model->user() == true) {
            $result = $this->model->saveorder();
            var_dump($result);
        }
        else $this->action_login();
    }
	/* Проекты */
	function action_projects($status = null) {
		if ($this->model->user() == true) {
		$data['items'] = $this->model->list_projects();
            if (isset($status)) {
                $data['status'] = $status;
            }
		$this->view->generate('admin_projects_view.php', 'admin_template_view.php', $data);
		}
		else $this->action_login();
		}
    function action_newproject($status = null) {
        if ($this->model->user() == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                if (empty($_REQUEST['name']) || ($_REQUEST['name'] == '')) {
                    self::action_projects('У материала должно быть хотя-бы название');
                    die;
                }
                $result = $this->model->new_project();
                $status = is_numeric($result)? 'Операция выполненна успешно, материал создан ID:'.$result : $result;
                $_SESSION['done'] = 1;
                self::action_projects($status);
            }
            else {
                $this->view->generate('admin_newproject_view.php', 'admin_template_view.php', $status);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else {
        $this->action_login();
        }
    }
	function action_editproject()
	{	
		if (!empty($_REQUEST['id'])) {
		$item = $_REQUEST['id'];
		}
		else {
			echo 'Гдето закралась проблемка...';
            die;
			}
		$item = $this->model->get_project($item);
		$aux_images = unserialize($item[0]['images']);
		$data = array ('item'=>$item[0], 'aux_images' => $aux_images);
		$this->view->generate('admin_editproject_view.php', 'admin_template_view.php', $data);
		$_SESSION['done'] = 0;
	}
	function action_projectsave(){
		if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
		$result = $this->model->update_project();
		$_SESSION['done'] = 1;
        $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
		self::action_projects($status);
		}
		
	}
	function action_deleteproject(){
        if ($this->model->user() == true) {
            if (empty($_REQUEST['id'])) {
                self::action_projects('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                $result = $this->model->delete_project();
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                $_SESSION['done'] = 1;
                self::action_projects($status);
            }
            else {
                $item = $this->model->get_project($_REQUEST['id']);
                // $data = array ('item'=>$item[0], 'status' => $status); - статус вывести бы
                $data = array ('item'=>$item[0]);
                $this->view->generate('admin_projectdelet_view.php', 'admin_template_view.php', $data);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else $this->action_login();
		}
	/* Статьи */
	function action_articles($status = null) {
		if ($this->model->user() == true) {
		$data = array ('items' => $this->model->list_articles(),'status' => $status );
		$this->view->generate('admin_articles_view.php', 'admin_template_view.php', $data);
		}
		else $this->action_login();
		}
	function action_editarticle($status = null)
	{	
	if ($this->model->user() == true) {
		if (!empty($_REQUEST['id'])) {
		$item = $_REQUEST['id'];
		}
		else {
			self::action_articles('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
			die;
			}
		$item = $this->model->get_article($item);
		$data = array ('item'=>$item[0], 'status' => $status);
		$this->view->generate('admin_articleedit_view.php', 'admin_template_view.php', $data);
		$_SESSION['done'] = 0;
		}
		else $this->action_login();
	}
	function action_articlesave(){
		if ($this->model->user() == true) {
			if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
				if (empty($_REQUEST['id']) || empty($_REQUEST['menu'])) {
					self::action_editarticle('У материала должно быть хотя-бы название');
					die;
				} 
			$result = $this->model->update_article();
			$_SESSION['done'] = 1;
			$status = $result==true? 'Операция выполненна успешно': $result;
			self::action_articles($status);
			}
			else { self::action_articles('Операция уже выполненна'); }
		}
		else $this->action_login();
	}
	function action_articledelete() {
		
		if ($this->model->user() == true) {
			if (empty($_REQUEST['id'])) {
					self::action_editarticle('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
					die;
				}
			if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
			$result = $this->model->delete_article();
			$status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
			$_SESSION['done'] = 1;
			self::action_articles($status);
			}
			else {
				$item = $this->model->get_article($_REQUEST['id']);
				//$data = array ('item'=>$item[0], 'status' => $status);
                $data = array ('item'=>$item[0]);
				$this->view->generate('admin_articledelet_view.php', 'admin_template_view.php', $data);
				$_SESSION['done'] = 0;
				die;
			}
			/*else { self::action_articles('Операция уже выполненна'); }*/
		}
		else $this->action_login();
	}
	function action_newarticle($status = null) {
		
		if ($this->model->user() == true) {
			if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
				if (empty($_REQUEST['menu']) || ($_REQUEST['menu'] == '')) {
					self::action_articles('У материала должно быть хотя-бы название');
					die;
				}
			$result = $this->model->new_article();
			$status = $result==true? 'Операция выполненна успешно, материал создан': $status;
			$_SESSION['done'] = 1;
			self::action_articles($status);
			}
			else {
				$this->view->generate('admin_newarticle_view.php', 'admin_template_view.php', $status);
				$_SESSION['done'] = 0;
				die;
			}
		}
		else $this->action_login();
	}
		/* Материалы */
    function action_materials($status = null) {
       if (!empty($_REQUEST['category']))  {
            $cat = $_SESSION['category'] = $_REQUEST['category'];
        }
        else {
            if (!empty($_SESSION['category'])) {
                $cat = $_SESSION['category'];
            }
            else {
            $cat = 1;
            }
        }
        if ($this->model->user() == true) {
            $data = array ('items' => $this->model->list_materials($cat),'categories' => $this->model->get_categories('materials'),'status' => $status );
            $headdata = array('title' => 'Редактирование материалов');
            $this->view->generate('admin_materials_view.php', 'admin_template_view.php', $data, $headdata);
        }
        else $this->action_login();
    }
    function action_editmaterial($status = null)
    {
        if ($this->model->user() == true) {
            if (!empty($_REQUEST['id'])) {
                $item = $_REQUEST['id'];
            }
            else {
                self::action_materials('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }
            $item = $this->model->get_material($item);
            $data = array ('item'=>$item[0],'categories' => $this->model->get_categories('materials') ,'status' => $status);
            $headdata = array('title' => $data['item']['name'].' - редактирование материала');
            $this->view->generate('admin_materialedit_view.php', 'admin_template_view.php', $data, $headdata);
            $_SESSION['done'] = 0;
        }
        else $this->action_login();
    }
    function action_materialsave(){
        if ($this->model->user() == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                if (empty($_REQUEST['id']) || empty($_REQUEST['name'])) {
                    self::action_editmaterial('У материала должно быть хотя-бы название');
                    die;
                }
                $result = $this->model->update_material();
                $_SESSION['done'] = 1;
                $status = $result==true? 'Операция выполненна успешно': $result;
                self::action_materials($status);
            }
            else { self::action_materials('Операция уже выполненна'); }
        }
        else $this->action_login();
    }
    function action_materialdelete() {

        if ($this->model->user() == true) {
            if (empty($_REQUEST['id'])) {
                self::action_editarticle('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                $result = $this->model->delete_material();
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                $_SESSION['done'] = 1;
                self::action_materials($status);
            }
            else {
                $item = $this->model->get_material($_REQUEST['id']);
                $data = array ('item'=>$item[0]);
                $this->view->generate('admin_materialdelet_view.php', 'admin_template_view.php', $data);
                $_SESSION['done'] = 0;
                die;
            }
            /*else { self::action_articles('Операция уже выполненна'); }*/
        }
        else $this->action_login();
    }
    function action_newmaterial($status = null) {

        if ($this->model->user() == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                if (empty($_REQUEST['name']) || ($_REQUEST['name'] == '')) {
                    self::action_materials('У материала должно быть хотя-бы название');
                    die;
                }
                $result = $this->model->new_material();
                $status = $result==true? 'Операция выполненна успешно, материал создан': $status;
                $_SESSION['done'] = 1;
                self::action_materials($status);
            }
            else {
                $data = array('categories' => $this->model->get_categories('materials') ,'status' => $status);
                $this->view->generate('admin_newmaterial_view.php', 'admin_template_view.php', $data);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else $this->action_login();
    }
    function action_categories() {

    }


		/* Всякая хуйня */
}