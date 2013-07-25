<?php 

$item = $data['item'];
if ($item['thumbs'] == 1) {
    $controls = 'true';
}
else {
    $controls = 'false';
}
/* Генерим меню */
	/*$menu = '<ul id="leftmenu">';
	if (!empty($data)){
		$i=0;
		foreach ($data['menu'] as $one){
			/* Чет-нечет */
			/*if ($i%2) {
				$dopclass = 'odd';
				}
			else {
				$dopclass = 'even';
				}
			if ($item['id'] == $one['id']){
				$active = 'active';}
			else {
				$active = '';
				}

			$menu .= '<li class="'.$active.' project-'.$one['id'].'">';
			$menu .= '<a href="/'.$GLOBALS['lang'].'/furniture/show?id='.$one['id'].'">'.$one['menu'].'</a>';
			$menu .= '</li>';
			$i++;
			}
		}
	$menu  .= '</ul>';*/

	
	//var_dump($data)
	$images = '<div class="slider-wrapper"><div id="slider" class="nivoSlider">';
	$num=1;
	if (!empty($data['images'])) foreach ($data['images'] as $image) {
		$patch = images_folder .'furniture'.DS. $item['imagefolder'] .DS. $image['image'];
		if (file_exists($patch)) {
				$imgsize = getimagesize($patch);
			} else {
				$image = 'noimage.jpg';
				$item['imagefolder'] = '..';
				}
		$images .= '<img src="/images/furniture/'.$item['imagefolder'].'/'.$image['image'].'" alt="'.$item['name'].'" data-thumb="/images/furniture/'.$item['imagefolder'].'/thumbs/'.$image['thumb'].'" alt="'.$item['name'].'" />';
		}
	else {$images .=  '<img src="/images/noimage.jpg" alt="No image" />';}
	$images .= '</div></div>';
?>
<div class="furniture single" id="wrapper">
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
   <div class="contentpage">
    <div class="rightside <?php echo $item['imagefolder'] ?>">
        <div class="page"><div class="page-inner">
            <div class="page-top">
                <h1><?php echo $item['name'] ?></h1>
                <div class="page-text">


                    <?php echo $item['address'] ?>
                    <?php echo $item['description'] ?>

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
        <div class="bottomart"></div>
    </div></div>
</div>

</div>
</div>

    <script>
        $(window).load(function() {
            zamerhsik()
            var images = $('#slider img').length;
            $('#slider').nivoSlider({
                effect: 'sliceUpDownLeft',
                manualAdvance: true,
                controlNav: <?php echo $controls?>,
                controlNavThumbs: false,
                afterLoad: function(){
                    $('.nivo-directionNav').prependTo('.page-mid').append('<div id="counter">1/'+images+'</div>');

                },
                afterChange: function(){
                    $('#counter').text( $('.nivo-controlNav .active').text()+'/'+images );
                }
            });
        });
    </script>