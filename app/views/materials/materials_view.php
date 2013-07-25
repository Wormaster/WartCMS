<?php
    $menu = '<ul id="leftmenu">';
    if (!empty($data['menu'])){
        foreach ($data['menu'] as $one){
            if ($data['category']['id'] == $one['id']){
                $active = 'active';}
            else {
                $active = '';
            }
            /* Генерим меню */
            $menu .= '<li class="'.$active.' category-'.$one['id'].'">';
            $menu .= '<a href="/'.$GLOBALS['lang'].'/materials/show?category='.$one['id'].'">'.$one['name'].'</a>';
            $menu .= '</li>';
        }
    }
    $menu  .= '</ul>';


    $items = '<div class="materials_in">';
    $i= 0;
    $line = 1;
    $total = count($data['items']);
    foreach ($data['items'] as $item) {
        if ($i==0 || ($i%5== 0 )) {
            $items .= '<div class="row materials-line-'.$line.'">';
        }
        $items .= '<div class="material_iner"><div class="material_pic">';
        /*
не понадобилось но пусть лежит
    $patch = images_folder .'materials'.DS. $item['image'];
    if (file_exists($patch)) {
        $imgsize = getimagesize($patch);
    } else {
        $image['image'] = 'noimage.jpg';
    }*/
    $items .= '<img src="/images/materials/'.$item['image'].'" width="102" height="102" alt="'.$item['name'].'"/>';
    $items .= '</div><div class="material_name">'.$item['name'].'</div>';
    $items .= '<div class="material_label">'.$item['description'].'</div>';
    $items .= '</div>';
        if ((($i+1)%5== 0 ) || ($i+1) == $total) {
            $items .= '</div>';
            $line++;
        }
    $i++;
    }
    $items .= '</div>';

?>

<div class="material_page" id="wrapper">
    <div id="header">
        <div id="phone">
            <div class="number">
                <span class="code">+7(495)&nbsp;</span><span class="num">517&nbsp;85&nbsp;48</span>
            </div>
        </div>
        <div id="menuwrp">
            <div id="logo"><a href="/"><img src="/images/logo.png" width="108" height="105" alt="Factura" longdesc="Factura" /></a></div>
            <div id="topmenu">
               <?php echo $this->menu() ?>
            </div>
        </div>
    </div>

    <div id="middle">
        <div id="zamershik">
            <div class="zinner">
                <h2>Вызов замерщика</h2>
                <div class="zform">
                    <form action="zamershik" method="post" enctype="multipart/form-data" name="zamershik">
                        <div class="floatleft">

                            <div class="form-1">
                                <input name="name" type="text" value="Ваше имя" />
                                <input name="phone" type="text" value="Телефон" />
                            </div>
                            <div class="form-2">
                                <textarea name="comment" cols="" rows="">Комментарий</textarea>
                                <input class="button" name="send" type="submit" value="Отправить" />
                            </div>
                        </div>
                        <div class="floatright">
                            <div class="text">
                                На сайте представлена как относительно не дорогая мебель из средней ценовой категории, так и элитная мебель от ведущих мировых брендов.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="zbutton">
                <a id="zambutton" href="#zamershik">Вызвать замерщика</a>
            </div>
        </div>
        <div class="contentpage"> <div class="leftside">
                <?php echo $menu ?>
                <div class="clr"></div>
            </div>
            <div class="rightside">
                <div class="materials_carrier">
                <?php echo $items ?>
                <div class="category_description">
                <?php echo $data['category']['description'] ?>
                </div></div>
            </div></div>
    </div>

</div>

<script>
    $(window).load(function() {
        zamerhsik()
    $('.material_pic').hover(function(){
        $('img', this).css({'z-index':100}).stop().animate({'height':150, 'width':150,'margin-left': -25, 'margin-top': -25}, 200);
    });
        $('.material_pic').mouseleave(function(){
            $('img', this).css({'z-index':1}).stop().animate({'height':102, 'width':102, 'margin':0}, 200);
        })

    });
</script>