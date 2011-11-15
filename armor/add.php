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
        <li><a href="/armor/" style="z-index: 7;"><?= $lang->translate('Билд Шмота'); ?></a></li>
        <li><a href="/armor/add.php"><?= $lang->translate('Добавить вещь'); ?></a></li>
    </ul>
</div>

<div><form action="add.php">
    <div><label for="name">Название: </label><input type="text" name="name" id="name"></div>
    <div><label for="q">Качество: </label><select id="q" name="q">
        <option value="0">Обычный</option>
        <option value="1">Редкий</option>
        <option value="2">Легендарный</option>
        <option value="3">Уникальный</option>
        <option value="4">Эпический</option>
    </select></div>
    <div><label for="lvl">Уровень (от 1 до 55): </label><input type="text" name="lvl" id="lvl"></div>
    <div><label for="type">Тип предмета: </label><select id="type" name="type">
        <optgroup label="Доспехи">
            <option value="1">Тканые доспехи</option>
            <option value="2">Кожаные доспехи</option>
            <option value="3">Кольчужные доспехи</option>
            <option value="4">Латные доспехи</option>
            <option value="5">Щиты</option>
            <option value="6">Головной убор</option>
        </optgroup>

        <optgroup label="Оружие">
            <option value="7">Копья</option>
            <option value="8">Двуручные мечи</option>
            <option value="9">Мечи</option>
            <option value="10">Кинжалы</option>
            <option value="11">Булавы</option>
            <option value="12">Посохи</option>
            <option value="13">Луки</option>
            <option value="14">Орбы</option>
            <option value="15">Гримуары</option>
        </optgroup>
        
        <optgroup label="Бижа">
            <option value="16">Серьги</option>
            <option value="17">Ожерелья</option>
            <option value="18">Кольца</option>
            <option value="19">Пояса</option>
        </optgroup>
    </select></div>

    <div><label for="slot">Слот</label><select name="slot" id="slot">
        <option value="1">Голова</option>
        <option value="2">Торс</option>
        <option value="3">Штаны</option>
        <option value="4">Ботинки</option>
        <option value="5">Наплечники</option>
        <option value="6">Перчатки</option>
        <option value="7">Ожерелья</option>
        <option value="8">Серьги</option>
        <option value="9">Кольца</option>
        <option value="10">Пояс</option>
        <option value="11">Крыло</option>
        <option value="12">Главная или Вторая Рука</option>
        <option value="13">Главная Рука</option>
    </select></div>

    <div>
        <table><caption>Основные скилы</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr><td><select name="skills[main][0][name]" id="skills-main-0-name">
                <option value="1">Атака</option>
                <option value="2">Физическая атака</option>
                <option value="3">Маг. атака</option>
                <option value="4">Скор. атаки</option>
                <option value="5">Скор. магии</option>
                <option value="6">Точность</option>
                <option value="7">Точн. магии</option>
                <option value="8">Ф. крит.</option>
                <option value="9">М. крит.</option>
                <option value="10">Сила магии</option>
                <option value="11">Сила исцелен.</option>

                <option value="12">Парир.</option>
                <option value="13">Уклонение</option>
                <option value="14">Концентрац.</option>
                <option value="15">Блок урона</option>
                <option value="16">Блок щитом</option>
                <option value="17">Блок ф. крит.</option>
                <option value="18">Блок м. крит.</option>

                <option value="19">Физ. защита</option>
                <option value="20">Маг. защита</option>
                <option value="21">Защ. от земли</option>
                <option value="22">Защ. от возд.</option>
                <option value="23">Защ. от воды</option>
                <option value="24">Защ. от огня</option>
                <option value="25">Защита от ф. крит.</option>

                <option value="26">Сопротивление оглушению</option>
                <option value="27">Сопротивление опрокидыванию</option>
                <option value="28">Сопротивление отталкиванию</option>

                <option value="29">Макс. HP</option>
                <option value="30">Макс. MP</option>

                <option value="31">Скор. полета</option>
                <option value="32">Время полета</option>
                <option value="33">Скор. движ.</option>

                <option value="34">Агрессия</option>

                <option value="35">ЛВК</option>
            </select></td>
                <td><input type="text" name="skills[main][0][value]" id="skills-main-0-value"></td>
                <td><a href="#" class="button">Удалить</a></td>
            </tr>

            <tr><td colspan="3"><a href="#" class="button">Добавить</a></td></tr>

            </tbody>
        </table>

        <table><caption>Дополнительные скилы</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr><td><select name="skills[other][0][name]" id="skills-other-0-name">
                <option value="1">Атака</option>
                <option value="2">Физическая атака</option>
                <option value="3">Маг. атака</option>
                <option value="4">Скор. атаки</option>
                <option value="5">Скор. магии</option>
                <option value="6">Точность</option>
                <option value="7">Точн. магии</option>
                <option value="8">Ф. крит.</option>
                <option value="9">М. крит.</option>
                <option value="10">Сила магии</option>
                <option value="11">Сила исцелен.</option>

                <option value="12">Парир.</option>
                <option value="13">Уклонение</option>
                <option value="14">Концентрац.</option>
                <option value="15">Блок урона</option>
                <option value="16">Блок щитом</option>
                <option value="17">Блок ф. крит.</option>
                <option value="18">Блок м. крит.</option>

                <option value="19">Физ. защита</option>
                <option value="20">Маг. защита</option>
                <option value="21">Защ. от земли</option>
                <option value="22">Защ. от возд.</option>
                <option value="23">Защ. от воды</option>
                <option value="24">Защ. от огня</option>
                <option value="25">Защита от ф. крит.</option>

                <option value="26">Сопротивление оглушению</option>
                <option value="27">Сопротивление опрокидыванию</option>
                <option value="28">Сопротивление отталкиванию</option>

                <option value="29">Макс. HP</option>
                <option value="30">Макс. MP</option>

                <option value="31">Скор. полета</option>
                <option value="32">Время полета</option>
                <option value="33">Скор. движ.</option>

                <option value="34">Агрессия</option>

                <option value="35">ЛВК</option>
            </select></td>
                <td><input type="text" name="skills[other][0][value]" id="skills-other-0-value"></td>
                <td><a href="#" class="button">Удалить</a></td>
            </tr>

            <tr><td colspan="3"><a href="#" class="button">Добавить</a></td></tr>

            </tbody>
        </table>

        <a href="#">1 Зачарование</a><br />
        <a href="#">2 Зачарование</a>
    </div>

    <div>
        <label>Магические камни</label>
        <div><label for="manastoneLvl">Уровень</label><input id="manastoneLvl" name="manastoneLvl" type="text"></div>
        <div><label for="manastoneCount">Количество</label><input id="manastoneCount" name="manastoneCount" type="text"></div>
    </div>

    <div>
        <label for="godstone"><input id="godstone" name="godstone" type="checkbox"> Можно вставить божественный камень</label>
    </div>
</form></div>

<script language="javascript">
    window.addEvent('domready', function() {
        
    });
</script>
</body>
</html>