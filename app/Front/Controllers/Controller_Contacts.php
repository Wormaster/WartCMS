<?php

namespace Front\Controllers;

class Controller_Contacts extends \Core\Controller
{
		function __construct()
	{
        $this->model = new Model_Contacts;
		$this->view = new View();
        $this->reg = Registry::instance();
        $this->view->controller = ('contacts');
	}
	
	function action_index()
	{
        $data = array('title' => 'Contacts', 'sitename' => $this->reg['sitename'], 'menu' => $this->model->getmenu('mainmenu'));
		$this->view->generate('contacts.html.twig', $data);
	}
}