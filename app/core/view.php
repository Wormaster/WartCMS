<?php
namespace Core;
class View
{
    private $template;
    private $twig;
    private $loader;
    protected $reg;
    private $sitename;
    public $controller;
    private $extension;
    private $lang;
    private $cache;
	
	/*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы.
	*/

    function __construct()
    {
        $this->reg = Registry::instance();
        $this->template = $this->reg['template'];
        $this->admintemplate = $this->reg['admintemplate'];
        $this->sitename = $this->reg['sitename'];
        $this->lang = new Language();
        $this->debug = $this->reg['debug'];
        if ($this->reg['cache']){
            $this->cache = site_path . DS. 'cache';
        }
        else {
            $this->cache = false;
        }


        require_once lib_path . 'Twig/Autoloader.php';
        \Twig_Autoloader::register(true);



    }
    // $headdata - оставленно для совместимости, пока пусть живет
	function generate($content_view, $data = null)
	{
        $this->loader = new \Twig_Loader_Filesystem(site_path .'templates'.DS.$this->template);
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => $this->cache,
            'auto_reload' => true,
            'debug' => $this->debug
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        //Дописьки
        self::extendtwig();

        $data['templatefolder'] = $this->template;
        $data['lang'] = $this->reg['language'];
        $data['sitename'] = $this->reg['sitename'];
        $data['controller'] = $this->controller;


        if ($this->reg['debug']){
            $profiler = \Generic\Profiler::instance();
            $profiler->end_count();
            $data['loadtime'] = $profiler->get_result();
        }
        try {
            $template = $this->twig->loadTemplate($content_view);
            echo $template->render($data);
        }
        catch (Twig_Error $error){
            echo 'Ошибка шаблонизатора! Такой шаблон не существует.';
               $message = $error->getMessage();
            if ($this->reg['debug']){
                echo '<pre>'.$message.'</pre>';
            }
        }

	}
    function admingenerate($content_view, $data = null)
    {
        $this->loader = new \Twig_Loader_Filesystem(site_path .'templates'.DS.$this->admintemplate);
        $this->twig = new \Twig_Environment($this->loader, array(
            //'cache' => site_path . DS. 'cache',
            //'auto_reload' => true,
            'debug' => true
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        //Дописьки
        self::extendtwig();

        $template = $this->twig->loadTemplate($content_view);
        $unicon = new \Admin\Models\Model_Unicon();

        $data['alltypes'] = $unicon->get_content_type();
        $data['templatefolder'] = $this->admintemplate;
        $data['lang'] = $this->reg['language'];
        $data['sitename'] = $this->reg['sitename'];
        $data['controller'] = $this->controller;
        $data['userlevel'] = $_SESSION['userlevel'];

        echo $template->render($data);

    }

    private function extendtwig(){

        $this->extension['lang'] = new \Twig_SimpleFilter('lang', array($this->lang, 'get'));


        $this->extension['cut'] = new \Twig_SimpleFilter('cut', function ($string, $length) {
            $string = filter_var($string, FILTER_SANITIZE_STRIPPED, FILTER_FLAG_NO_ENCODE_QUOTES);
            $result = implode(array_slice(explode('<hr>',wordwrap($string,$length,'<hr>',false)),0,1));
            return $result;
        });

        $this->extension['rdate'] = new \Twig_SimpleFilter('rdate', function ($modifier, $param) {
            $time = time() + strtotime($modifier);
            if(intval($time)==0)$time=time();
            $MonthNames=array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
            if(strpos($param,'M')===false) return date($param, $time);
            else return date(str_replace('M',$MonthNames[date('n',$time)-1],$param), $time);
        });
        $this->twig->addFilter($this->extension['lang']);
        $this->twig->addFilter($this->extension['rdate']);
        $this->twig->addFilter($this->extension['cut']);
    }
}