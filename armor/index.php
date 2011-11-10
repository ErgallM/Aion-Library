<?php
require '../lang.php';
$lang = new Lang();
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="webspirit, web spirit" />
    <meta name="description" content="WebSpirit" />

    <title></title>

    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='/css/cssreset-min.css' rel='stylesheet' type='text/css'>
    <link href='/css/main.css' rel='stylesheet' type='text/css'>
    <link href='/css/armor.css' rel='stylesheet' type='text/css'>
</head>
<body>

<div id="navigation">
    <ul class="navigation">
        <li><a href="#" class="f"><span class="left-hc"></span>Web Spirit</a></li>
        <li><a href="/" style="z-index: 8;">Aion Library</a></li>
        <li><a href="/armor/"><?= $lang->translate('Билд Шмота'); ?></a></li>
    </ul>
</div>

<div id="man">
    <div class="weapon-1">
        <div data-type="1" data-slot="1" class="item"></div>
        <a href="#">=</a>
        <div data-type="1" data-slot="1" class="item"></div>
    </div>

    <div data-type="1" data-slot="1" class="item"></div>

    <div class="weapon-2">
        <div data-type="1" data-slot="1" class="item"></div>
        <a href="#">=</a>
        <div data-type="1" data-slot="1" class="item"></div>
    </div>
    <div class="clear"></div>

    <div class="itemPanel">
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
    </div>
    <div class="itemPanel-2">
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
        <div data-type="1" data-slot="1" class="item"></div>
    </div>

    <div class="clear"></div>
</div>

</body>
</html>