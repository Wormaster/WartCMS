<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
echo $this->head($headdata);
include 'head.php' ?>
</head>
<body>
<div id="shadowwrp">
        <?php include  $inner_view . DS . $content_view; ?>
    </div>
<div id="footer">
    <?php include 'footer.php' ?>
</div>
</body>
</html>