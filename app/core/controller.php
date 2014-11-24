<?php
namespace Core;
class Controller {
	
	public $model;
	public $view;
    protected $reg;
    public $cart;
    protected $firephp;
	
	function __construct()
	{
        $this->reg = Registry::instance();
        $this->view = new \Core\View();
        $this->model = new \Core\Model();
        //Debug
        $this->firephp = \FirePHP::getInstance(true);
        if ($this->reg['debug']) {
            $this->firephp->setEnabled(true);
        }
        else {
            $this->firephp->setEnabled(false);
        }
	}
	
	// действие (action), вызываемое по умолчанию
	function action_index()
	{

	}
}
