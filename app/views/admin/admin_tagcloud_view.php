<?php
    function tablegen($data, $razdel, $lang) {
        $category = '<ul class="'.$razdel.' '.$lang.'">';
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
                $category .= '<li class="'.$razdel.'-'.$item['id'].'">';
                $category .= '<div class="id">'.$item['id'].'</div>';
                $category .= '<div class="name">';
                $category .= $item['menu'];
                $category .= '</div>';
                $category .= '<div class="edit"><input type="checkbox" name="'.$lang.'-'.$razdel.'-'.$item['id'].'" id="'.$lang.'-'.$razdel.'-'.$item['id'].'" value="'.$item['menu'].'" /></div>';
                $category .= '</li>';
                $i++;
        }
        $category  .= '</ul>';
        return $category;

    };

if (!empty($data['projectsru'])){
    $projectsru = tablegen($data[projectsru], 'furniture', 'ru');
}
if (!empty($data['projectsen'])){
    $projectsen = tablegen($data[projectsen], 'furniture', 'en');
}
if (!empty($data['articlesru'])){
    $articlesru = tablegen($data[articlesru], 'articles', 'ru');
}
if (!empty($data['articlesen'])){
    $articlesen = tablegen($data[articlesen], 'articles', 'en');
}

?>
<div id="adminpage">
    <div class="leftside">
        <?php include 'admin_menu.php' ?>
    </div>
    <div class="rightside">
    <form id="form2" name="form2" method="post" action="tagcloud" ENCTYPE="multipart/form-data">
        <div class="left">
            <h2>Текстовые - русские</h2>
                <?php echo $articlesru; ?>
            <h2>Текстовые - английские</h2>
            <?php echo $articlesen; ?>
            <h2>Проекты - русские</h2>
                <?php echo $projectsru; ?>
            <h2>Проекты - английские</h2>
            <?php echo $projectsen; ?>
            <h2>Меню - русское</h2>
            <ul class="projects">
                <li><div class="id">ru1</div><div class="name">О Компании</div><div class="edit"><input type="checkbox" id="ru-articles" name="ru-articles" value="О Компании"/></div></li>
                <li><div class="id">ru2</div><div class="name">Проекты</div><div class="edit"><input type="checkbox" id="ru-projects" name="ru-projects" value="Проекты"/></div></li>
                <li><div class="id">ru3</div><div class="name">Контакты</div><div class="edit"><input type="checkbox" id="ru-contacts" name="ru-contacts" value="Контакты"/></div></li>
            </ul><h2>Меню - английское</h2>
            <ul class="projects">
                <li><div class="id">ru1</div><div class="name">About</div><div class="edit"><input type="checkbox" id="en-articles" name="en-articles" value="About"/></div></li>
                <li><div class="id">ru2</div><div class="name">Projects</div><div class="edit"><input type="checkbox" id="en-projects" name="en-projects" value="Projects"/></div></li>
                <li><div class="id">ru3</div><div class="name">Contacts</div><div class="edit"><input type="checkbox" id="en-contacts" name="en-contacts" value="Contacts"/></div></li>
            </ul>
            </div>
        <div class="right">

        </div>
        <input type="submit" name="confirm" id="submit" value="Изменить"/>
    </form>
    </div>
</div>
<script type="text/javascript">
    $(window).load(function(){

    });
</script>