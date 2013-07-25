<?php

 $menu = '<ul id="leftmenu">';
 if (!empty($data['items'])) {
	 foreach ($data['items'] as $item) {
		 if ($item['id'] == $data['article']['id']) { $active = ' class="active"';}
		 else {$active = '';}
		 $menu .= '<li'.$active.'><a href="/'.$GLOBALS['lang'].'/articles/'.$item['id'].'/'.$item['alias'].'">'.$item["menu"].'</a></li>';
		 }
	 };

 $menu .= '</ul>';
 
?>
<div class="articles" id="wrapper">
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
        <div class="page"><div class="page-inner">
            <div class="page-top">
                <h1><?php echo $data['article']['name'] ?></h1>
                <div class="page-text">


                    <?php echo $data['article']['text'] ?>

                </div>
            </div>
            <div class="page-mid">
                <?php echo $images ?>
            </div>
            <div class="page-bottom">
                <div class="bottom-text"><?php echo $item['extra'] ?></div>
            </div>
            </div>
        </div>
    </div></div>
</div>

</div>
</div>

    <script>
        $(window).load(function() {
            zamerhsik()
           
        });
    </script>