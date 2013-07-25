<?php
?>
<div id="adminpage">
                    <form id="form1" name="form1" method="post" action="/<?php echo $GLOBALS["lang"] ?>/admin/newproject" ENCTYPE="multipart/form-data">
                        <div class="left"><h2>Основные параметры</h2>
                            <h3><?php echo $data['status'] ?></h3>
                            <label for="name">Заголовок*</label>
                            <input name="name" type="text" id="name" value="" size="100" maxlength="255"/>
                            <label for="menu">Пункт меню</label>
                            <input name="menu" type="text" id="menu" value="" size="100" maxlength="255"/>
                            <label for="alias">Псевдоним</label>
                            <input name="alias" type="text" id="alias" value="" size="100" maxlength="255"/>
                            <label for="alias">Статус</label>
                            <select name="status" size="1">
                                <option value="1" selected="selected">Опубликованно</option>
                                <option value="0">Неопубликованно</option>
                            </select>
                            <label for="imagefolder">Директория для изображений<span>(необязательно)</span></label>
                            <input name="imagefolder" type="text" id="imagefolder" value="" size="100" maxlength="255"/>
                            <label>Логотип</label>
                            <input name="logo" type="file"/>
                            <label>Изображения галереи</label>
                            <div class="aux_images_new">
                                <div class="aux-image">
                                    <input class="img-field" name="aux_images[]" type="file"/>
                                    <span class="delimg"><a href="#del">Удалить</a></span>
                                </div>
                                <div id="imgcontrols"><span class="newimg"><a href="#new">Добавить</a></span></div>
                            </div>
                            <label for="thumbs">Мини-изображения</label>
                            <select name="thumbs" size="1">
                                <option value="1" selected="selected">Показывать мини-изображения</option>
                                <option value="0" >Не показывать мини-изображения</option>
                            </select>
                            <label for="address">Адрес</label>
                            <input name="address" type="text" id="address" value ="" size="100" maxlength="255"/>
                            <label for="description">Описание</label>
                            <textarea name="description" id="description" cols="45" rows="5"></textarea>
                            <label for="extra">Характеристики</label>
                            <textarea name="extra" id="extra" cols="45" rows="5"></textarea></div>
                        <div class="right">
                            <h2>SEO</h2>
                            <label for="seotitle">Заголовок браузера</label>
                            <input name="seotitle" type="text" id="seotitle" value="" size="100" maxlength="255" />
                            <label for="seokeys">Ключевые слова</label>
                            <textarea name="seokeys" id="seokeys" cols="100" rows="5"></textarea>
                            <label for="seodescription">Meta-Description</label>
                            <textarea name="seodescription" id="seodescription" cols="100" rows="5"></textarea>
                        </div>
        <input type="submit" name="confirm" id="submit" value="Создать"/>
    </form>
    <a href="/<?php echo $GLOBALS["lang"] ?>/admin/projects">Отмена</a></div>


<script type="text/javascript">
    $(window).load(function(){
    adminimg();
    tinymce.init({
        selector: ".left textarea",
        plugins: "code visualblocks link",
        toolbar: 'undo redo | bold italic  underline strikethrough outdent indent cut copy paste selectall removeformat blockquote bullist alignleft aligncenter alignright alignjustify | code | visualblocks link',
        menu : 'newdocument undo redo visualaid cut copy paste selectall bold italic underline strikethrough subscript superscript removeformat formats',
        height : 300
    });
    });
    //$('.aux_images a').colorbox({rel:'aux', innerHeight:'90%'});
</script>