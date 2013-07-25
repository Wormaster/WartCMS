<?php
    if (!empty($categories))
    {
    $select  = '<select name="category">';
    foreach ($categories as $cat) {
        $current = $cat['id'] == $_SESSION['category']? ' selected':'';
        $select .= '<option value="'.$cat['id'].'" '.$current.'>'.$cat['name'].'</option>';
    }

    $select .= '</select>';
    $select .= '<input name="" type="submit" value="Ok" />';

    }


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
			$projects .= '<a href="/'.$GLOBALS["lang"].'/admin/editmaterial?id='.$item['id'].'">'.$item['name'].'</a>';
			$projects .= '</div>';
            $projects .= '<div class="order"><input name="id-'.$item['id'].'" type="text" id="order-'.$item['id'].'" value="'.$item['order'].'" size="3" maxlength="3" disabled="true" /></div>';
            $projects .= '<div class="delete"><a href="/'.$GLOBALS["lang"].'/admin/materialdelete?id='.$item['id'].'">Удалить</a></div>';
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
            <div class="topselector">
            <label for="categories">Выберите категорию</label>
            <form id="categories" name="categories" method="get" action="materials">
                <?php echo $select ?>
            </form>
            </div>
            <form id="order" name="order" method="post" action="saveorder">
		<?php echo $projects ?>
            </form>
            <div class="notification"></div>
        </div>
</div>










<script type="text/javascript">
	 
$(window).load(function(){
	editorder("materials");
});
</script>