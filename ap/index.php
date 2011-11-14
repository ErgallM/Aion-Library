<?php
require '../lang.php';
$lang = new Lang(); $lang->addLangDb('ap');
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
    <link href='/css/ap.css' rel='stylesheet' type='text/css'>

    <script language="javascript" src="/js/mootools-core-1.4.1-full-nocompat-yc.js"></script>
    <script language="javascript" src="/js/Element.serialize.js"></script>
    <script language="javascript" src="/js/ap.js"></script>
</head>
<body>

<div id="navigation">
    <ul class="navigation">
        <li><a href="#" class="f"><span class="left-hc"></span>Web Spirit</a></li>
        <li><a href="/" style="z-index: 8;">Aion Library</a></li>
        <li><a href="#"><?= $lang->translate('АП Калькулятор'); ?></a></li>
    </ul>
</div>

<div class="content span-18">
    <div class="content-items">
        <form id="items">
        <!-- Icon -->
        <div class="span-7 content-items-block">
            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Простая древняя икона'); ?>
                    <span>300 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/icon_0.png" />
                    <input type="text" name="items[icon][0]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Обычная древняя икона'); ?>
                    <span>600 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/icon_1.png" />
                    <input type="text" name="items[icon][1]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Дорогая древняя икона'); ?>
                    <span>900 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/icon_2.png" />
                    <input type="text" name="items[icon][2]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Бесценная древняя икона'); ?>
                    <span>1200 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/icon_3.png" />
                    <input type="text" name="items[icon][3]" value="0" />
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <!-- Ceal -->
        <div class="span-7 content-items-block">
            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Простая древняя печать'); ?>
                    <span>600 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/seal_0.png" />
                    <input type="text" name="items[seal][0]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Обычная древняя печать'); ?>
                    <span>1200 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/seal_1.png" />
                    <input type="text" name="items[seal][1]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Дорогая древняя печать'); ?>
                    <span>1800 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/seal_2.png" />
                    <input type="text" name="items[seal][2]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Бесценная древняя печать'); ?>
                    <span>2400 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/seal_3.png" />
                    <input type="text" name="items[seal][3]" value="0" />
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <hr class="space" />

        <!-- Cup -->
        <div class="span-7 content-items-block">
            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Простая древняя чаша'); ?>
                    <span>1200 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/cup_0.png" />
                    <input type="text" name="items[cup][0]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Обычная древняя чаша'); ?>
                    <span>2400 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/cup_1.png" />
                    <input type="text" name="items[cup][1]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Дорогая древняя чаша'); ?>
                    <span>3600 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/cup_2.png" />
                    <input type="text" name="items[cup][2]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Бесценная древняя чаша'); ?>
                    <span>4800 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/cup_3.png" />
                    <input type="text" name="items[cup][3]" value="0" />
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <!-- Crown -->
        <div class="span-7 content-items-block">
            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Простая древняя корона'); ?>
                    <span>2400 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/crown_0.png" />
                    <input type="text" name="items[crown][0]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Обычная древняя корона'); ?>
                    <span>4800 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/crown_1.png" />
                    <input type="text" name="items[crown][1]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Дорогая древняя корона'); ?>
                    <span>7200 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/crown_2.png" />
                    <input type="text" name="items[crown][2]" value="0" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="content-items-cell">
                <div class="left content-items-infoblock">
                    <?= $lang->translate('Бесценная древняя корона'); ?>
                    <span>9600 APs</span>
                </div>
                <div class="right content-items-imageblock">
                    <img src="/images/ap/crown_3.png" />
                    <input type="text" name="items[crown][3]" value="0" />
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
        </form>
    </div>

    <hr class="space" />

    <div class="content-group">
        <div>
            <label>
                <?= $lang->translate('Выберите количество игроков в группе'); ?>
                <select id="userCound">
                    <option>2</option><option>3</option><option>4</option><option>5</option><option>6</option>
                </select>
            </label>
            &mdash; <a href="#" class="calcPeople"><?= $lang->translate('Распределить на группу'); ?></a>
        </div>

        <div id="content-group-legend">
        </div>

        <div class="clear"></div>
    </div>
</div>

<div class="right-menu">
    <h3><?= $lang->translate('Ваши очки бездны'); ?></h3>
    <p id="aps-all" class="aps-all">0</p>
    <p>
        <a href="#" id="clear" class="button b2" style="margin-bottom: 1em;"><?= $lang->translate('Сброс'); ?></a><br />
        <a href="#" class="calcPeople button b2" style="margin-bottom: 1em;"><?= $lang->translate('Распределить на группу'); ?></a><br />
        <a href="#" id="calcForUser" class="button b2"><?= $lang->translate('Отдать одному'); ?></a>
    </p>
    <!--
    <p>
        <a href="#">Получить ссылку на билд</a>
    </p>
    -->

    <p>&nbsp;</p>

    <div>
        <h3><?= $lang->translate('Сейчас на руках'); ?>:</h3>
        <div id="groupNowAp">
        </div>
    </div>
</div>

<div id="spiner" class="hide"></div>
<div id="forUser" class="hide">
    <div><a href="#" class="close">X</a></div>
    <p><?= $lang->translate('Выберите пользователя которому собираетесь отдать все указанные итемы'); ?></p>
    <div class="u-list">
        <button data-user="0" class="button"><?= $lang->translate('Пользователь'); ?> 1<br /><span>0</span>AP</button>
        <button data-user="1" class="button"><?= $lang->translate('Пользователь'); ?> 2<br /><span>0</span>AP</button>
        <button data-user="2" class="button"><?= $lang->translate('Пользователь'); ?> 3<br /><span>0</span>AP</button>
        <button data-user="3" class="button"><?= $lang->translate('Пользователь'); ?> 4<br /><span>0</span>AP</button>
        <button data-user="4" class="button"><?= $lang->translate('Пользователь'); ?> 5<br /><span>0</span>AP</button>
        <button data-user="5" class="button"><?= $lang->translate('Пользователь'); ?> 6<br /><span>0</span>AP</button>
    </div>
</div>

<script language="javascript">
    window.addEvent('domready', function() {
        window.a = new Ap({
            'formId':'items',
            'resultId': 'aps-all',
            'usersCoundId': 'userCound',
            'usersGroupLegend': 'content-group-legend',
            'groupNowApId': 'groupNowAp',
            'buttons': {
                'calcPeople': $$('.calcPeople'),
                'calcForUser': $$('#forUser button')
            },
            'itemsName': {
                'icon'  : {
                    '300'   : '<?= $lang->translate('Простая древняя икона'); ?>',
                    '600'   : '<?= $lang->translate('Обычная древняя икона'); ?>',
                    '900'   : '<?= $lang->translate('Дорогая древняя икона'); ?>',
                    '1200'  : '<?= $lang->translate('Бесценная древняя икона'); ?>'
                },
                'seal'  : {
                    '600'   : '<?= $lang->translate('Простая древняя печать'); ?>',
                    '1200'  : '<?= $lang->translate('Обычная древняя печать'); ?>',
                    '1800'  : '<?= $lang->translate('Дорогая древняя печать'); ?>',
                    '2400'  : '<?= $lang->translate('Бесценная древняя печать'); ?>'
                },
                'cup'   : {
                    '1200'  : '<?= $lang->translate('Простая древняя чаша'); ?>',
                    '2400'  : '<?= $lang->translate('Обычная древняя чаша'); ?>',
                    '3600'  : '<?= $lang->translate('Дорогая древняя чаша'); ?>',
                    '4800'  : '<?= $lang->translate('Бесценная древняя чаша'); ?>'
                },
                'crown' : {
                    '2400'  : '<?= $lang->translate('Простая древняя корона'); ?>',
                    '4800'  : '<?= $lang->translate('Обычная древняя корона'); ?>',
                    '7200'  : '<?= $lang->translate('Дорогая древняя корона'); ?>',
                    '9600'  : '<?= $lang->translate('Бесценная древняя корона'); ?>'
                }
            }
        });
    });
</script>
</body>
</html>