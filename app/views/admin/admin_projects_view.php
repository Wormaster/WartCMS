<?php
	$projects = '<ul class="articles">';
	$projects .= '<li class="description"><div class="id">ID</div><div class="name">Название</div><div class="edit-order"><a class="editorder" href="#order">Изменить порядок</a></div><div class="delete"></div></li>';

	if (!empty($data['items'])){
		$i=0;
		foreach ($data['items'] as $item){
			/* Чет-нечет */
			if ($i%2) {
				$dopclass = 'odd';
				}
			else {
				$dopclass = 'even';
				}
			/* Генерим меню */
			$projects .= '<li class="project-'.$item['id'].'">';
			$projects .= '<div class="id">'.$item['id'].'</div>';
			$projects .= '<div class="name">';
			$projects .= '<a href="/'.$GLOBALS["lang"].'/admin/editproject?id='.$item['id'].'">'.$item['menu'].'</a>';
			$projects .= '</div>';
			$projects .= '<div class="order"><input name="id-'.$item['id'].'" type="text" id="order-'.$item['id'].'" value="'.$item['order'].'" size="3" maxlength="3" disabled="true" /></div>';
			$projects .= '<div class="delete"><a href="/'.$GLOBALS["lang"].'/admin/deleteproject?id='.$item['id'].'">Удалить</a></div>';			$projects .= '</li>';
			$i++;
			}
		}
	$projects  .= '</ul>';





?>

<div id="adminpage">
        <div class="leftside">
        <?php include 'admin_menu.php' ?>
        </div>
        <div class="rightside">
        <?php if (!empty($data['status'])) echo '<h5>'.$data['status'].'</h5>';?>
		<?php echo $projects ?>
            <div class="notification">Материал с ID:1 невозможно удалить, он требуется для корректной работы сайта.</div>
        </div>
</div>










<script type="text/javascript">
	 
$(window).load(function(){
	editorder("projects");
});
</script>