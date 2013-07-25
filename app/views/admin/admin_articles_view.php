<?php 
	$projects = '<ul class="articles">';
    $projects .= '<li class="description"><div class="id">ID</div><div class="name">Название</div><div class="edit-order"><a class="editorder" href="#order">Изменить порядок</a></div><div class="delete"></div></li>';
	if (!empty($data['items'])){
		$i=0;
		foreach ($data['items'] as $item){
			/* Чет-нечет */
			$dopclass = $i%2?'odd':'even';
			
			/* Генерим меню */
			$projects .= '<li class="'.$dopclass.' project-'.$item['id'].'">';
			$projects .= '<div class="id">'.$item['id'].'</div>';
			$projects .= '<div class="name">';
			$projects .= '<a href="/'.$GLOBALS["lang"].'/admin/editarticle?id='.$item['id'].'">'.$item['menu'].'</a>';
			$projects .= '</div>';
            $projects .= '<div class="order"><input name="id-'.$item['id'].'" type="text" id="order-'.$item['id'].'" value="'.$item['order'].'" size="3" maxlength="3" disabled="true" /></div>';
            $projects .= '<div class="delete"><a href="/'.$GLOBALS["lang"].'/admin/articledelete?id='.$item['id'].'">Удалить</a></div>';
            $projects .= '</li>';
			$i++;
			}
		}
		else {
			 $projects .= '<li>В этом разделе ничего нет</li>';
			}
	$projects  .= '</ul>';





?>

<div id="adminpage">
        <div class="leftside">
        <?php include 'admin_menu.php' ?>
        </div>
        <div class="rightside">
        <?php if (!empty($data['status'])){ ?>
        <div class="status-message"><?php echo $data['status'] ?></div>
        <?php } ?>
            <form id="order" name="order" method="post" action="saveorder">
		<?php echo $projects ?>
            </form>
            <div class="notification"></div>
        </div>
</div>










<script type="text/javascript">
	 
$(window).load(function(){
	editorder("articles");
});
</script>