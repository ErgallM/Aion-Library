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

    <script language="javascript" src="/js/mootools-core-1.4.1-full-nocompat-yc.js"></script>
    <script language="javascript" src="/js/Element.serialize.js"></script>
    <script language="javascript" src="/js/ScrollSpy-yui-compressed.js"></script>
    <script language="javascript" src="/js/armor.js"></script>
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

<div id="searchItems">
    <div><form action="db.php" id="searchItemsForm">
        <input id="start" name="start" value="0" type="hidden">
        
        <label>Название предмена: <input type="text" name="name" value="Введите название" data-default-text="Введите название"></label>
        <img src="" id="loadSeparator" />
        <input type="submit" value="Найти" class="button">
        </form></div>

    <div id="posts-container">
        <div id="posts"></div>
        <div id="load-more" class="button">Load More</div>
    </div>
</div>
<script language="javascript">
    window.addEvent('domready', function() {
        window.s = new SearchItems({
            panel: $('searchItems'),
            items: $$('.item'),
            container: $('posts'),
            filterForm: $('searchItemsForm')
        })
    });
</script>
</body>
</html>