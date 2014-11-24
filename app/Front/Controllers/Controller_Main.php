<?php

namespace Front\Controllers;

class Controller_Main extends \Core\Controller
{
		function __construct()
	{
        parent::__construct();
        $this->view->controller = ('main');
        $this->model = new \Front\Models\Model_Main();
	}
	
	function action_index()
	{
        $unicon = new \Front\Models\Model_Unicon();

        $data = array(
            'menu' => $this->model->getmenu('mainmenu'),
            'articles' => $unicon->get_materials_by_cat('main'),
            'slider' => $unicon->get_materials_by_cat('slider_main'),
            'categories' => $unicon->get_cat_by_type('products'),
            'references' => $unicon->get_materials_by_cat('references'),
            'stati' => $unicon->get_materials_by_cat('articles'),
            'mainpage' => true
        );
        $this->view->generate('index.html.twig', $data);

        if ($this->reg['debug']) {
             $this->firephp->log($data, 'Data');
        }
	}
}