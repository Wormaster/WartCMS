<div id="adminpage">
<div id="admin_login">
<h2>Вход в админпанель</h2>
<div class="loginform">
<form action="/<?php echo $GLOBALS['lang'] ?>/admin/login" method="get">
<label>Логин</label>
<input name="Login" type="text"/>
<label>Пороль</label>
<input name="pass" type="password"/>
<input name="confirm" type="submit" value="Войти"/>
</form>
</div>
<div class="login_status"><?php echo $data; ?></div>
</div>
</div>