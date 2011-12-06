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
    <div><form action="db.php?t=get" id="searchItemsForm">
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

<div id="compare" class="compare hide">
    <div class="close"><button></button></div>
    <div class="block">
        <h3 class="q4">
            Орб Рудры
            <span><i>+1</i> <button class="up"></button><button class="down"></button></span>
        </h3>
        <div class="type"><span>Тип</span>Орбы</div>
        <div>[Обмен невозможен], [Невозможно положить на склад аккаунта], [Невозможно положить на склад легиона]</div>
        <div>Можно использовать с 55-го уровня.</div>
    </div>

    <div class="block">
        <div class="title">Медленное оружие, кол-во ударов: 1 (Стихия огня)</div>
        <div class="skills"><span>Атака</span> 270 - 330</div>
        <div class="skills"><span>Скор. атаки</span> 2.2</div>
        <div class="skills"><span>Сила магии</span> 860</div>
        <div class="skills"><span>Точн. магии</span> 391</div>
        <div class="clear"></div>
    </div>

    <div class="block">
        <div class="skills"><span>Макс. HP</span> 509</div>
        <div class="skills"><span>Сила магии</span> 85</div>
        <div class="skills"><span>М. крит.</span> 20</div>
        <div class="skills"><span>Скор. магии</span> 20%</div>
        <div class="skills"><span>Макс. MP</span>898</div>
        <div class="clear"></div>
    </div>

    <div class="block">
        <div class="title">Можно усилить магическими камнями 60-го уровня и ниже.</div>
        <div class="manastone"></div>
        <div class="manastone"></div>
        <div class="manastone"></div>
        <div class="manastone"></div>
        <div class="manastone"></div>
        <div class="clear"></div>
    </div>

    <div class="block">
        Можно вставить божественный камень.
    </div>

    <div class="block buttonsPanel">
        <button class="button">Очистить</button>
        <button class="button">Одеть</button>
        <button class="button">Снять</button>
    </div>
</div>
<script language="javascript">
    window.addEvent('domready', function() {
        window.a = new Armor({
            searchItems: {
                panel: $('searchItems'),
                items: $$('.item'),
                container: $('posts'),
                filterForm: $('searchItemsForm')
            },
            skills: {
                1: 'Атака',
                2: 'Физическая атака',
                3: 'Маг. атака',
                4: 'Скор. атаки',
                5: 'Скор. магии',
                6: 'Точность',
                7: 'Точн. магии',
                8: 'Ф. крит.',
                9: 'М. крит.',
                10: 'Сила магии',
                11: 'Сила исцелен.',

                12: 'Парир.',
                13: 'Уклонение',
                14: 'Концентрац.',
                15: 'Блок урона',
                16: 'Блок щитом',
                17: 'Блок ф. крит.',
                18: 'Блок м. крит.',

                19: 'Физ. защита',
                20: 'Маг. защита',
                21: 'Защ. от земли',
                22: 'Защ. от возд.',
                23: 'Защ. от воды',
                24: 'Защ. от огня',
                25: 'Защита от ф. крит.',

                26: 'Сопротивление оглушению',
                27: 'Сопротивление опрокидыванию',
                28: 'Сопротивление отталкиванию',

                29: 'Макс. HP',
                30: 'Макс. MP',

                31: 'Скор. полета',
                32: 'Время полета',
                33: 'Скор. движ.',

                34: 'Агрессия',

                35: 'ЛВК',

                36: 'PvP Атака Отношение',
                37: 'PvP множитель'
            },
            types: {
                // Доспехи
                1: 'Тканые доспехи',
                2: 'Кожаные доспехи',
                3: 'Кольчужные доспехи',
                4: 'Латные доспехи',
                5: 'Щиты',
                6: 'Головной убор',

                // Оружие
                7: 'Копья',
                8: 'Двуручные мечи',
                9: 'Мечи',
                10: 'Кинжалы',
                11: 'Булавы',
                12: 'Посохи',
                13: 'Луки',
                14: 'Орбы',
                15: 'Гримуары',

                // Бижа
                16: 'Серьги',
                17: 'Ожерелья',
                18: 'Кольца',
                19: 'Пояса'
            }
        })
    });
</script>
</body>
</html>