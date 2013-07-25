<?php 
	$menu = '<ul id="leftmenu">';
	$logos = '<div class="projectlogos">';
	if (!empty($data)){
		$i=0;
		foreach ($data as $item){
			/* Чет-нечет */
			if ($i%2) {
				$dopclass = 'odd';
				}
			else {
				$dopclass = 'even';
				}
			/* Генерим меню */
			$menu .= '<li class="project-'.$item['id'].'">';
			$menu .= '<a href="/'.$GLOBALS['lang'].'/furniture/show?id='.$item['id'].'">'.$item['menu'].'</a>';
			$menu .= '</li>';
			
			/* И тут же логотипы в центр */
            if (!empty($item['logo'])) {
                $image['name'] = $item['logo'];
            }
            else {
                $image['name'] = 'nologo';
            }
			$image['patch']  = images_folder .'furniture'.DS. $item['imagefolder'] .DS. $image['name'];
			if (file_exists($image['patch'])) {
				$image['size'] = getimagesize($image['patch']);
                } else {
				$image['name'] = 'nologo.png';
				$item['imagefolder'] = '..';
                $image['size'] = getimagesize(images_folder .$image['name']);
				}
			$logos .= '<div class="singleproj '.$dopclass.'">';
			$logos .= '<div class="in project-'.$item['id'].'"><div class="bwwrapper" style="width:'.$image['size'][0].'px;">';
			$logos .= '<a href="/'.$GLOBALS['lang'].'/furniture/show?id='.$item['id'].'">';
			$logos .= '<img src="/images/furniture/'.$item['imagefolder'].'/'.$image['name'].'" width="'.$image['size'][0].'" height="'.$image['size'][1].'" alt="'.$item['name'].'"/>';
			$logos .= '</a>';
			$logos .= '</div></div></div>';
			$i++;
			}
		}
	$logos .= '</div>';
	$menu  .= '</ul>';





?>

<div id="projectspage">
<div class="leftside"><?php echo $menu ?></div>
<div class="rightside"><?php echo $logos ?></div>
</div>








<script type="text/javascript">
	 
$(window).load(function(){
    $('.bwwrapper').BlackAndWhite({
        hoverEffect : true, // default true
        // set the path to BnWWorker.js for a superfast implementation
        webworkerPath : false,
        // for the images with a fluid width and height 
        responsive:true,
        // to invert the hover effect
        invertHoverEffect: false,
        speed: { //this property could also be just speed: value for both fadeIn and fadeOut
            fadeIn: 200, // 200ms for fadeIn animations
            fadeOut: 800 // 800ms for fadeOut animations
        }
    });
	projlight()
});
</script>