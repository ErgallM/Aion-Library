<?php
require 'lang.php';
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
</head>
<body>

<div id="navigation">
    <ul class="navigation">
        <li><a href="#" class="f"><span class="left-hc"></span>Web Spirit</a></li>
        <li><a href="/">Aion Library</a></li>
    </ul>
</div>
<div class="c-menu">
    <div><a href="/ap/" class="button"><?= $lang->translate('АП Калькулятор'); ?></a></div>
    <div><a href="/armor/" class="button"><?= $lang->translate('Билд Шмота'); ?></a></div>
</div>
</body>
</html>