<?php
if (!empty($categories))
{
    $select   = '<label for="category">Категория материала</label>';
    $select  .= '<select name="category">';
    foreach ($categories as $cat) {
        $current = $cat['id'] == $item['category']? ' selected':'';
        $select .= '<option value="'.$cat['id'].'" '.$current.'>'.$cat['name'].'</option>';
    }

    $select .= '</select>';

}
?>
<div id="adminpage">
<form id="form1" name="form1" method="post" action="/<?php echo $GLOBALS["lang"] ?>/admin/materialsave"  ENCTYPE="multipart/form-data">
    <div class="left">
   		<h2>Основные параметры</h2>
        <h3><?php echo $data['status'] ?></h3>
        <p>ID: <?php echo $item['id']?></p>
        <input type="hidden" name="id" id="id" value="<?php echo $item['id']?>" />
      <label for="alias">Статус</label>
        <select name="status" size="1">
          <option value="1" <?php echo $item['status'] == 1? 'selected="selected"':''; ?>>Опубликованно</option>
          <option value="0" <?php echo $item['status'] == 1? '':'selected="selected"'; ?>>Неопубликованно</option>
        </select>
        <label for="name">Заголовок</label>
        <input name="name" type="text" id="name" value="<?php echo stripslashes($item['name'])?>" size="100" maxlength="255" />
        <?php echo $select ?>
        <label>Изображение</label>
        <input name="image" type="file" />
        <img src="/images/materials/<?php echo $item['image']?>" alt="<?php echo $item['name']?>" />
        <hr />
        <label for="description">Короткое описание</label>
        <textarea name="description" id="description" cols="30" rows="3"><?php echo stripslashes($item['description']) ?></textarea>
    </div>
    <div class="right">
        <input type="submit" name="confirm" id="submit" value="Изменить" />
    </div>
</form>
<a href="/<?php echo $GLOBALS["lang"] ?>/admin/materials">Отмена</a>
</div>


<script type="text/javascript">
$(window).load(function(){
    tinymce.init({
        selector: "#description",
        plugins: "code visualblocks link",
        toolbar: 'undo redo | bold italic  underline strikethrough cut copy paste selectall removeformat | code | visualblocks link',
        menu : 'newdocument undo redo visualaid cut copy paste selectall bold italic underline strikethrough subscript superscript removeformat formats',
        height : 300
    });

});
</script>

