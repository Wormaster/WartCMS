<?php

class Controller_Materials extends Controller
{
    function __construct()
    {
        $this->model = new Model_Materials();
        $this->view = new View();
        $this->view->controller = ('materials');
    }
    function action_index()
    {
        self::action_show();
    }
    function action_show()
    {
        /* Сомнительынй кусок */
        $this->view->mainmenu = $this->model->getmenu(mainmenu);
        /* Конец сомнительного куска... работать то работает но нихера ни изящно ))) */

        $data['items'] = $this->model->get_all();
        if (empty($data['items'])) {
            Router::ErrorPage404();
            die;
        }
        $data['menu'] = $this->model->get_allcat();
        $cat = $this->model->get_cat();
        $data['category'] = $cat[0];
        $headdata = array('title' => $data['menu']['name'], 'keywords' => $data['item']['seokeys'],'description' => $data['item']['seodescription']);
        $this->view->generate('materials_view.php', 'template_view.php', $data, $headdata);
    }
}