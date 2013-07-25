<?php

?>
<div id="adminpage">
<form id="form1" name="form1" method="post" action="/<?php echo $GLOBALS["lang"] ?>/admin/articlesave">
    <div class="left">
   		<h2>Основные параметры</h2>
        <h3><?php echo $data['status'] ?></h3>
        <p>ID: <?php echo $item['id']?></p>
        <input type="hidden" name="id" id="id" value="<?php echo $item['id']?>" />
        <label for="menu">Пункт меню</label>
        <input name="menu" type="text" id="menu" value="<?php echo stripslashes($item['menu'])?>" size="100" maxlength="255" />
        <label for="alias">Псевдоним</label>
        <input name="alias" type="text" id="alias" value="<?php echo $item['alias']?>" size="100" maxlength="255" />
      <label for="alias">Статус</label>
        <select name="status" size="1">
          <option value="1" <?php echo $item['status'] == 1? 'selected="selected"':''; ?>>Опубликованно</option>
          <option value="0" <?php echo $item['status'] == 1? '':'selected="selected"'; ?>>Неопубликованно</option>
        </select>
        <label for="name">Заголовок</label>
        <input name="name" type="text" id="name" value="<?php echo $item['name']?>" size="100" maxlength="255" />
        <label for="text">Текст статьи</label>
      <textarea name="text" id="text" cols="45" rows="5"><?php echo stripslashes($item['text']) ?></textarea>
    </div>
    <div class="right">
    <h2>SEO</h2>
        <label for="seotitle">Заголовок браузера</label>
        <input name="seotitle" type="text" id="seotitle" value="<?php echo $item['seotitle']?>" size="100" maxlength="255" />
        <label for="seokeys">Ключевые слова</label>
        <textarea name="seokeys" id="seokeys" cols="100" rows="5"><?php echo $item['seokeys']?></textarea>
        <label for="seodescription">Meta-Description</label>
        <textarea name="seodescription" id="seodescription" cols="100" rows="5"><?php echo $item['seodescription']?></textarea>
        <input type="submit" name="confirm" id="submit" value="Изменить" />
    </div>
</form>
<a href="/<?php echo $GLOBALS["lang"] ?>/admin/articles">Отмена</a>
</div>


<script type="text/javascript">
$(window).load(function(){
	tinymce.init({
    selector: "#text",
	plugins: "code visualblocks link",
	toolbar: 'undo redo | bold italic  underline strikethrough outdent indent cut copy paste selectall removeformat blockquote bullist alignleft aligncenter alignright alignjustify | code | visualblocks link',
	menu : 'newdocument undo redo visualaid cut copy paste selectall bold italic underline strikethrough subscript superscript removeformat formats',
	height : 300
 });

});
</script>

