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
    <!-- <script language="javascript" src="/js/armor.js"></script> -->
    <script language="javascript" src="/js/armor-add.js"></script>
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

<div id="addItem"><form action="db.php?t=add" id="addItemForm">
    <div><label for="name">Название: </label><input type="text" name="name" id="name"></div>
    <div><label for="q">Качество: </label><select id="q" name="q">
        <option value="0" class="q">Обычный</option>
        <option value="1" class="q1">Редкий</option>
        <option value="2" class="q2">Легендарный</option>
        <option value="3" class="q3">Уникальный</option>
        <option value="4" class="q4">Эпический</option>
    </select></div>
    <div><label for="lvl">Уровень (от 1 до 55): </label><input type="text" name="lvl" id="lvl"></div>
    <div><label for="type">Тип предмета: </label><select id="type" name="type">
        <optgroup label="Доспехи">
            <option value="1" data-slot="-2-3-4-5-6-">Тканые доспехи</option>
            <option value="2" data-slot="-2-3-4-5-6-">Кожаные доспехи</option>
            <option value="3" data-slot="-2-3-4-5-6-">Кольчужные доспехи</option>
            <option value="4" data-slot="-2-3-4-5-6-">Латные доспехи</option>
            <option value="5" data-slot="-12-">Щиты</option>
            <option value="6" data-slot="-1-">Головной убор</option>
        </optgroup>

        <optgroup label="Оружие">
            <option value="7" data-slot="-13-">Копья</option>
            <option value="8" data-slot="-13-">Двуручные мечи</option>
            <option value="9" data-slot="-13-">Мечи</option>
            <option value="10" data-slot="-12-">Кинжалы</option>
            <option value="11" data-slot="-13-">Булавы</option>
            <option value="12" data-slot="-13-">Посохи</option>
            <option value="13" data-slot="-13-">Луки</option>
            <option value="14" data-slot="-13-">Орбы</option>
            <option value="15" data-slot="-13-">Гримуары</option>
        </optgroup>
        
        <optgroup label="Бижа">
            <option value="16" data-slot="-8-">Серьги</option>
            <option value="17" data-slot="-7-">Ожерелья</option>
            <option value="18" data-slot="-9-">Кольца</option>
            <option value="19" data-slot="-10-">Пояса</option>
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
        <table class="skills" data-items="0" data-type="main"><caption>Основные скилы</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr data-nodel="1"><td colspan="3"><a href="#" class="button add">Добавить</a></td></tr>
            </tbody>
        </table>
    </div>
    <div>
        <table class="skills" data-items="0" data-type="other"><caption>Дополнительные скилы</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr data-nodel="1"><td colspan="3"><a href="#" class="button add">Добавить</a></td></tr>
            </tbody>
        </table>
    </div>
    <div><p><a href="#" id="skills-enchantment-1">1 Зачарование</a></p>
        <table class="skills hide" data-items="0" data-type="enchantment-1"><caption>Зачарование 1</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr data-nodel="1"><td colspan="3"><a href="#" class="button add">Добавить</a></td></tr>
            </tbody>
        </table>
    </div>

    <div><p><a href="#" id="skills-enchantment-2">2 Зачарование</a></p>
        <table class="skills hide" data-items="0" data-type="enchantment-2"><caption>Зачарование 2</caption><thead><tr><td>Название</td><td colspan="2">Значение</td></tr></thead>
            <tbody>

            <tr data-nodel="1"><td colspan="3"><a href="#" class="button add">Добавить</a></td></tr>
            </tbody>
        </table>
    </div>

    <div><label>Магические камни</label></div>
    <div style="margin-left:20px; width: 580px;"><label for="manastoneLvl">Уровень</label><input id="manastoneLvl" name="manastoneLvl" type="text"></div>
    <div style="margin-left:20px; width: 580px;"><label for="manastoneCount">Количество</label><input id="manastoneCount" name="manastoneCount" type="text"></div>

    <div><label for="godstone"><input id="godstone" name="godstone" type="checkbox" value="1"> Можно вставить божественный камень</label></div>

    <div><label for="icon">Иконка</label><input id="icon" name="icon" type="text"></div>
    <div><label for="image">Картинка</label><input id="image" name="image" type="text"></div>

    <div><input type="submit" class="button" value="Сохранить"></div>
</form></div>

<script language="javascript">
    window.addEvent('domready', function() {
        $('lvl').maskInt(60);
        $('manastoneLvl').maskInt(50);
        $('manastoneCount').maskInt(6);
        
        $('type').addEvent('change', function() {
            var slots = this.getSelected()[0].get('data-slot');
            var value = null;
            $$('#slot option').each(function(el) {
                if (slots.indexOf('-' + el.get('value') + '-') < 0) {
                    el.set('disabled', true);
                } else {
                    el.set('disabled', false);
                    if (null == value) value = el;
                }
            });
            if (null != value) $('slot').set('value', value.get('value'));
        });
        $('type').fireEvent('change');
        
        window.a = new ArmorAdd({
            skillTables: $$('.skills'),
            skillsEnchantment: $$('#skills-enchantment-1, #skills-enchantment-2'),
            addItemForm: $('addItemForm'),
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

                36:'PvP Атака Отношение',
                37:'PvP множитель'
            }
        })
    });
</script>
</body>
</html>