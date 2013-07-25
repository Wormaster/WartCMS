<?php
class View
{
    public $controller;
    public $template;
    public $mainmenu;
    public $currentitem;
	
	/*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы.
	*/
	function generate($content_view, $template_view, $data = null, $headdata = null)
	{
		

		if(is_array($data)) {
			
			extract($data);
		}
        $inner_view = app_patch . 'views' . DS . $this->controller;
		include app_patch . 'views' . DS .$template_view;
	}
    /* Генерим заголовок страницы */
	function head($headdata = null) {
		if (!empty($headdata['title'])) {
			$html  = '<title>'.$headdata['title'].' - '.SITENAME.'</title>';
		} else {
			$html  = '<title>'.SITENAME.'</title>';
			}
		if (!empty($headdata['description'])) {
			$html .= '<meta name="description" content="'.$headdata['description'].'" />';
		}
		if (!empty($headdata['keywords'])) {
			$html .= '<meta name="keywords" content="'.$headdata['keywords'].'" />';
		}	
		return $html;
			
	}
    /* Генерим меню */
	function menu() {

        $numbers = array('first','second','third','fourth', 'fifth', 'sixth', 'seventh');

        $menu = '<ul id="mainmenu">';
        $i = 0;
        foreach ($this->mainmenu as $item) {
            if ($item['controller'] == $this->controller)
            {
                if (!empty($this->currentitem)) {
                    if ($this->currentitem == $item['itemid']) {
                        $active = ' active';
                    }
                    else {
                        $active = '';
                    }
                }
                else {
                    $active = ' active';
                }

            }
            else {
                $active = '';
            }


            if (!empty($item['itemid']) && $item['itemid'] != '') {
                $itemid = '/'.$item['itemid'].'/'.$item['alias'];
            }
            else {
                $itemid = '';
            }
            $class = ' class="'.$numbers[$i].$active.'"';


            $menu .= '<li'.$class.'><a href="/'.$item['lang'].'/'.$item['controller'] . $itemid.'">'.$item['name'].'</a></li>';
            $i++;
        }
        $menu .= '</ul>';

        return $menu;
    }
    /* Функция выбора языка - переписать при первой же возможности. */
    function langselector() {
        $selector = '<ul>';
        $selector .= '<li class="'.($GLOBALS["lang"]=='ru'? 'current':'').'"><a href="/ru/'.$this->controller.'">RU</a></li>';
        $selector .= ' <li class="'.($GLOBALS["lang"]=='en'? 'current':'').'"><a href="/en/'.$this->controller.'">EN</a></li>';
        $selector .= '</ul>';

        return $selector;
    }
}