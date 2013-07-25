<?php
//var_dump($item);
?>
<div id="adminpage">
    <form id="form1" name="form1" method="post" action="/<?php echo $GLOBALS["lang"] ?>/admin/projectsave" ENCTYPE="multipart/form-data">
        <div class="left">
            ID Объекта: <?php echo $item[id]?>
            <input type="hidden" name="id" id="id" value="<?php echo $item[id]?>" />
            <input type="hidden" name="imagefolder" id="imagefolder" value="<?php echo $item['imagefolder']?>" />
            <label for="name">Название</label>
            <input name="name" type="text" id="name" value ="<?php echo $item[name]?>" />
            <label for="alias">Псевдоним</label>
            <input name="alias" type="text" id="alias" value ="<?php echo $item[alias]?>" />
            <label for="menu">Пункт меню</label>
            <input name="menu" type="text" id="menu" value ="<?php echo $item[menu]?>" />
            <label for="status">Статус</label>
            <select name="status" size="1">
                <option value="1" <?php echo $item['status'] == 1? 'selected="selected"':''; ?>>Опубликованно</option>
                <option value="0" <?php echo $item['status'] == 1? '':'selected="selected"'; ?>>Неопубликованно</option>
            </select>
            <label>Режим изменения изображений</label>
            <select name="imagemode" size="1">
                <option value="add"  selected="selected">Добавлять</option>
                <option value="replace">Заменить на новые</option>
            </select>
            <p>Внимание! При установке режима замены все текущие изображения будут удалены!</p>
            <label>Изображения галереи</label>
            <div class="aux_images_new">
                <div class="aux-image">
                    <input class="img-field" name="aux_images[]" type="file" multiple/>
                    <span class="delimg"><a href="#del">Удалить</a></span>
                </div>
                <div id="imgcontrols"><span class="newimg"><a href="#new">Добавить</a></span></div>
            </div>
            <p>Текущие загруженные изображения</p>
            <div class="aux_images"><ul>
                    <?php if (!empty($aux_images)) foreach ($aux_images as $image) {
                        echo '<li><a href="/images/furniture/'.$item['imagefolder'] .'/'.$image['image'].'" rel="aux" ><img src="/images/furniture/'.$item['imagefolder'] .'/thumbs/'.$image['thumb'].'" alt="'.$item['name'].'" /></a></li>';
                    } ?>
                </ul></div>
            <label for="thumbs">Мини-изображения</label>
            <select name="thumbs" size="1">
                <option value="1" <?php echo $item['thumbs'] == 1? 'selected="selected"':''; ?>>Показывать мини-изображения</option>
                <option value="0" <?php echo $item['thumbs'] == 1? '':'selected="selected"'; ?>>Не показывать мини-изображения</option>
            </select>
            <label for="address">Заголовок снизу</label>
            <input name="address" type="text" id="address" value ="<?php echo $item[address]?>" size="100" maxlength="255"/>
            <label for="description">Описание</label>
            <textarea name="description" id="description" cols="45" rows="5"><?php echo $item['description'] ?></textarea>
            <label for="extra">Характеристики</label>
            <textarea name="extra" id="extra" cols="45" rows="5"><?php echo $item['extra'] ?></textarea>

        </div>
        <div class="right">
            <h2>SEO</h2>
            <label for="seotitle">Заголовок браузера</label>
            <input name="seotitle" type="text" id="seotitle" value="<?php echo $item['seotitle'] ?>" size="100" maxlength="255" />
            <label for="seokeys">Ключевые слова</label>
            <textarea name="seokeys" id="seokeys" cols="100" rows="5"><?php echo $item['seokeys'] ?> </textarea>
            <label for="seodescription">Meta-Description</label>
            <textarea name="seodescription" id="seodescription" cols="100" rows="5"><?php echo $item['seodescription'] ?></textarea>
        </div>
        <input type="submit" name="confirm" id="submit" value="Изменить" />
        <a href="/<?php echo $GLOBALS["lang"] ?>/admin/projects">Отмена</a>

    </form>
</div>



<script type="text/javascript">
    $(window).load(function(){
        adminimg();
        tinymce.init({
            selector: ".left textarea",
            plugins: "code visualblocks link paste",
            toolbar: 'undo redo | bold italic  underline strikethrough outdent indent cut copy paste selectall removeformat blockquote bullist alignleft aligncenter alignright alignjustify | code | visualblocks link',
            menu : 'newdocument undo redo visualaid cut copy paste selectall bold italic underline strikethrough subscript superscript removeformat formats',
            height : 300
        });
    });
    //$('.aux_images a').colorbox({rel:'aux', innerHeight:'90%'});
</script>