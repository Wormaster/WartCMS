<?php

    $reg = \Core\Registry::instance();



    $reg['dbhost'] = 'localhost';
    $reg['dbname'] = 'wcmsriot';
    $reg['dbuser'] = 'mysql';
    $reg['dbpass'] = '';
    $reg['sitename'] = 'RiotZone';
    $reg['template'] = 'mebelnz';
    $reg['admintemplate'] = 'neu';
    $reg['adminmail'] = 'info@mebel-na-balkon.ru';
    $reg['salt'] = '56gr5hhr7j';
    $reg['online'] = true;
    // Анонимная загрузка файлов через фидбэк
    $reg['fileupload'] = false;
    $reg['cache'] = false;
    $reg['routes'] = array (
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-z]+)[\/]{1}([a-z]+)[\?][a-z]{0,9}[\=]?([0-9]{0,3})[\&]?([a-z=]+[0-9]{0,3})' => '$1/admin/$2/$3/$4',
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-z]+)[\/]{1}([a-z]+)[\?]?[a-z]{0,9}[\=]?([0-9]{0,3})' => '$1/admin/$2/$3/$4',
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-z]+)[\?]{1}[a-z]{0,9}[\=]?([0-9]{0,3})&[a-z]{0,9}[\=]([0-9]{0,3})' => '$1/admin/$2/index',
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-z]+)[\?]{1}[a-z]{0,9}[\=]?([0-9]{0,3})' => '$1/admin/$2/index',
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-z]+)' => '$1/admin/$2/index',
        '^(ru|en)?[\/]{0,1}admin[\/]{1}([a-zA-Z]+)[\?][a-z]+[\=]([0-9]+$)' => '$1/admin/$2/$3',
        '^(ru|en)?[\/]{0,1}admin' => '$1/admin/index/index',

        '^(ru|en)?[\/]{0,1}feedback[\/]{1}message' => '$1/feedback/message',
        '^(ru|en)?[\/]{0,1}(catalog)[\/]([a-z\_\-\d]+)[\/]([a-z\-\_\d]+)[\/]?' => '$1/unicon/material/$3/$4/$2',
        '^(ru|en)?[\/]{0,1}(catalog)[\/]([a-z\_\-\d]+)[\/]?$' => '$1/unicon/category/$3/$4/$2',
        '^(ru|en)?[\/]{0,1}(catalog)[\/]?$' => '$1/unicon/index/$2',
        //Материалы
        '^(ru|en)?[\/]{0,1}(materials)[\/]?$' => '$1/unicon/index/$2',
        //Обработка статичных текстовых материалов, да костыль но пока так...

        '^(ru|en)?[\/]{0,1}okompanii' => '$1/unicon/material/static/okompanii',
        '^(ru|en)?[\/]{0,1}contacts' => '$1/unicon/material/static/contacts',
        '^(ru|en)?[\/]{0,1}dostavkaioplata' => '$1/unicon/material/static/dostavkaioplata',


        '^(ru|en)?[\/]{0,1}([a-z\_\-]+)[\/]([a-z]+)[\/]?' => '$1/unicon/material/$2/$3',
        '^(ru|en)?[\/]{0,1}([a-z\_\-]+)[\/]?' => '$1/unicon/category/$2',

        '^(ru|en)?[\/]{0,1}$' => '$1/main/index',
        //@todo Сделать ченить с этим безобразием - а то косяк бля...
        '' => 'ru/main/index'
    );

// Debug mode
    $reg['debug'] = true;
    //error_reporting('E_All');