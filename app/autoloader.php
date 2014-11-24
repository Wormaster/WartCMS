<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Wormaster
 * Date: 24.11.13
 * Time: 19:29
 * To change this template use File | Settings | File Templates.
 */
    class Autoloader
    {
        private static $_lastLoadedFilename;

        public static function loadPackages($className)
        {
            // буковка S в пути нужна чтоб папка называлась controllerS, modelS etc. криво бля но так исторически сложилось..
            $classfolder = explode('_', strtolower($className));
            self::$_lastLoadedFilename = app_patch . $classfolder['0'].'s' .DS. strtolower($className) . '.php';
            if (file_exists(self::$_lastLoadedFilename)) {
            require_once(self::$_lastLoadedFilename);
        }
            else {
                Router::ErrorPage404();
            }
        }
        public static function loadPackagesN($className)
        {
            // буковка S в пути нужна чтоб папка называлась controllerS, modelS etc. криво бля но так исторически сложилось..
            $patch = str_replace('\\', '/', $className) . '.php';
            self::$_lastLoadedFilename = app_patch . $patch;
            if (file_exists(self::$_lastLoadedFilename)) {
                require_once(self::$_lastLoadedFilename);
            }
            else {
                echo 'Искали в '.app_patch . $patch.' но к сожалению не нашли...';
            }
        }
        public static function loadAdminPackages($className)
        {
            $classfolder = explode('_', strtolower($className));
            self::$_lastLoadedFilename = app_patch . 'admin' . DS.$classfolder['0'] .DS. strtolower($className) . '.php';
            if (file_exists(self::$_lastLoadedFilename)) {
            require_once(self::$_lastLoadedFilename);
            }
        }

        public static function loadPackagesAndLog($className)
        {
            self::loadPackages($className);
            printf("Class %s was loaded from %sn", $className, self::$_lastLoadedFilename);
        }
    }