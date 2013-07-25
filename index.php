<?php
define ( 'WENGINE', 1 );

define ( 'DS', DIRECTORY_SEPARATOR );

$site_path = realpath ( dirname ( __FILE__ ) . DS ) . DS;

$app_patch = realpath ( dirname ( __FILE__ ) . DS ) . DS . 'app' . DS;

$image_patch = realpath (dirname ( __FILE__ ) .DS. 'images').DS;

define ( 'site_path', $site_path );

define ( 'app_patch', $app_patch );
define ( 'images_folder', $image_patch );
define ( 'site_path', $site_path );
define ( 'SITENAME', 'Factura' );
include app_patch . 'starter.php';
// ini_set('display_errors', 1);