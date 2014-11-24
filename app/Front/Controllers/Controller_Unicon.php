<?php

namespace Front\Controllers;

class Controller_Unicon extends \Core\Controller
{
    function __construct()
    {
        parent::__construct();
        $this->view->controller = ('unicon');
        $this->model = new \Front\Models\Model_Unicon();
    }

    function action_index($params)
    {
        if ($params[0] == 'catalog'){
            $data = array(
                'menu' => $this->model->getmenu('mainmenu'),
                'categories' => $this->model->get_cat_by_type('products'),
                'text' => $this->model->get_materials_by_alias('static', $params[0]),
                'references' => $this->model->get_materials_by_cat('references'),
                'stati' => $this->model->get_materials_by_cat('articles'),
            );
        }
        elseif ($params[0] == 'materials'){
            $data = array(
                'cat' => $this->model->get_cat_by_alias($params[0]),
                'menu' => $this->model->getmenu('mainmenu'),
                'categories' => $this->model->get_cat_by_type('products'),
                'items' => $this->model->get_materials_by_cat($params[0]),
                'furniture' => $this->model->get_materials_by_cat('furnitura'),
                'text' => $this->model->get_materials_by_alias('static', $params[0]),
                'references' => $this->model->get_materials_by_cat('references'),
                'stati' => $this->model->get_materials_by_cat('articles'),
            );
        }
        else {
            \Core\Router::ErrorPage404();
            die;
        }

        if ($this->reg['debug']) {
            $this->firephp->log($data, 'Data');
        }
        $templatename = $params[0].'.html.twig';
        $this->view->generate($templatename, $data);
    }

    public function action_material($params)
    {
        if ($params[0]){
            $cat = $params[0];
            $cat = $this->model->get_cat_by_alias($cat);

        }

        if ($cat && $params[1]){
            $alias = $params[1];
            $item = $this->model->get_materials_by_alias($cat['alias'], $alias);
            if(!$item){
                \Core\Router::ErrorPage404();
                die;
            }
            $data = array(
                'menu' => $this->model->getmenu('mainmenu'),
                'categories' => $this->model->get_cat_by_type('products'),
                'item' => $item,
                'cat' => $cat,
                'references' => $this->model->get_materials_by_cat('references'),
                'neighbours' => $this->model->get_neighbours($item['id'], $cat),
                'stati' => $this->model->get_materials_by_cat('articles'),
            );

            if ($this->reg['debug']) {
                $this->firephp->log($data, 'Data');
            }
            $templatename = $cat['type'].'-single.html.twig';
            $this->view->generate($templatename, $data);

        }
        else {
            \Core\Router::ErrorPage404();
            die;
        }



    }
    public function action_category($params)
    {
        if ($params[0]){
            $cat = $params[0];
            $cat = $this->model->get_cat_by_alias($cat);

        }
        if ($cat){

            $data = array(
                'menu' => $this->model->getmenu('mainmenu'),
                'categories' => $this->model->get_cat_by_type('products'),
                'cat' => $cat,
                'items' => $this->model->get_materials_by_cat($cat['alias']),
                'text' => $this->model->get_materials_by_alias('static', $cat['alias']),
                'references' => $this->model->get_materials_by_cat('references'),
                'stati' => $this->model->get_materials_by_cat('articles'),
            );

            if ($this->reg['debug']) {
                $this->firephp->log($data, 'Data');
            }
            $templatename = $cat['type'].'.html.twig';
            $this->view->generate($templatename, $data);

        }
        else {
            \Core\Router::ErrorPage404();
            die;
        }



    }
    public function action_parametricone($params){

        if ($params[0]){
            $ctype = $params[0];
            $mat = $params[1];
            $mat = $this->model->get_materials_by_field($ctype,'alias',$mat);
            $mat = $mat[0];
        }
        if ($mat){
            $data = array(
                'menu' => $this->model->getmenu('mainmenu'),
                'categories' => $this->model->get_cat_by_type('products'),
                'items' => $this->model->get_materials_by_field('products', 'prodbrand',$mat['id']),
                'slider' => $this->model->get_materials_by_cat('slider_main'),
                'brands' => $this->model->get_models_by_brand('brands'),
                'mat' => $mat
            );

            if ($this->reg['debug']) {
                $this->firephp->log($data, 'Data');
            }
            $templatename = $ctype.'.html.twig';
            $this->view->generate($templatename, $data);

        }
        else {
            \Core\Router::ErrorPage404();
            die;
        }
    }

    public function action_parametricman($params){
        //var_dump($_REQUEST);
        $regcat = '/^cat-([\d]{0,3})/';
        $regbrand = '/^cbrand-([\d]{0,3})/';
        foreach ($_REQUEST as $key => $req){
            if ($key == 'brand'){
                $brand = filter_var($req, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            }
            elseif(preg_match($regbrand, $key, $matches)){
                $brands[] = $matches[1];
            }
            elseif(preg_match($regcat, $key, $matches)){
                $categories[] = $matches[1];
            }
            elseif($key == 'model'){
                $model = (int)$req;
            }
            elseif($key == 'year'){
                $year = (int)$req;
            }

        }
        if ($brands){
            $tmpmats = $this->model->get_materials_by_field('products', 'prodbrand',$brands, $categories);
        }
        elseif ($brand){
            //Костыль!!!!!! хотя бля кто сумеет без него то МАЛАДЕЦ!
            $ctype = $this->model->get_content_type('brands', 'alias');
            $mat = $this->model->get_materials_by_field($ctype,'id',$brand);
            $mat = $mat[0];
            $tmpmats = $this->model->get_materials_by_field('products', 'prodbrand',$mat['id'], $categories);
            //var_dump($mat);
        }
        elseif ($categories){
            $tmpmats = array();
            foreach ($categories as $cat){
                $catmats = $this->model->get_materials_by_cat($cat, 'id');
                if ($catmats){
                    $tmpmats = array_merge($tmpmats, $catmats);
                }


            }
            //var_dump($tmpmats);
        }
        else {

        }


        if (is_array($tmpmats) && count($tmpmats) >= 1){
            if ($model){
                $materials = $this->model->parametrize_materials($tmpmats, 'suitable', $model);

                if ($materials){

                }
                else {
                    $materials = false;
                }
            }
            else {
                $materials = $tmpmats;
            }
        }
        else {
            $materials = false;
        }


        $data = array(
            'menu' => $this->model->getmenu('mainmenu'),
            'categories' => $this->model->get_cat_by_type('products'),
            'items' => $materials,
            'slider' => $this->model->get_materials_by_cat('slider_main'),
            'brands' => $this->model->get_models_by_brand('brands'),
            'mat' => $mat
        );

        if ($this->reg['debug']) {
            $this->firephp->log($data, 'Data');
        }
        $templatename = 'search.html.twig';
        $this->view->generate($templatename, $data);
    }
}