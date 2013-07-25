<?php

?>
<div id="adminpage">
<div class="deletpage"><form id="form1" name="form1" method="post" action="/<?php echo $GLOBALS["lang"] ?>/admin/materialdelete">
    <div class="left">
   		<h2>Основные параметры</h2>
        <h3><?php echo $data['status'] ?></h3>
        <h6>ID: <?php echo $item['id']?></h6>
        <input type="hidden" name="id" id="id" value="<?php echo $item['id']?>" />
        <h6>Название:</h6>
       <?php echo $item['name']?>
     <h6>Статус:</h6>
      <?php echo $item['status'] == 1? 'Опубликован':'Неопубликован'; ?>
        <label>Изображение</label>
        <img src="/images/materials/<?php echo $item['image']?>" alt="<?php echo $item['name']?>" />
     <h6>Содержание:</h6>
     <div><?php echo $item['description']?></div>
    </div>
     <input type="submit" name="confirm" id="submit" value="Удалить" />
</form>
<a href="/<?php echo $GLOBALS["lang"] ?>/admin/materials">Отмена</a>
</div></div>


