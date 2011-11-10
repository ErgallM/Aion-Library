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
    <link href='css/cssreset-min.css' rel='stylesheet' type='text/css'>
    <link href='css/main.css' rel='stylesheet' type='text/css'>
    <link href='css/ap.css' rel='stylesheet' type='text/css'>

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
                    Простая древняя иконка
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
                    Обычная древняя икона
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
                    Дорогая древняя икона
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
                    Бесценная древняя икона
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
                    Простая древняя печать
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
                    Обычная древняя печать
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
                    Дорогая древняя печать
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
                    Бесценная древняя печать
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
                    Простая древняя чаша
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
                    Обычная древняя чаша
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
                    Дорогая древняя чаша
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
                    Бесценная древняя чаша
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
                    Простая древняя корона
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
                    Обычная древняя корона
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
                    Дорогая древняя корона
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
                    Бесценная древняя корона
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
                Выберите количество игроков в группе
                <select id="userCound">
                    <option>2</option><option>3</option><option>4</option><option>5</option><option>6</option>
                </select>
            </label>
            &mdash; <a href="#" class="calcPeople">Распределить на группу</a>
        </div>

        <div id="content-group-legend">
        </div>

        <div class="clear"></div>
    </div>
</div>

<div class="right-menu">
    <h3>Ваши очки бездны</h3>
    <p id="aps-all" class="aps-all">0</p>
    <p>
        <a href="#" id="clear" class="button b2" style="margin-bottom: 1em;">Сброс</a><br />
        <a href="#" class="calcPeople button b2">Распределить на группу</a>
    </p>
    <!--
    <p>
        <a href="#">Получить ссылку на билд</a>
    </p>
    -->

    <p>&nbsp;</p>

    <div>
        <h3>Сейчас на руках:</h3>
        <div id="groupNowAp">
        </div>
    </div>
</div>

<script language="javascript">
    window.addEvent('domready', function() {
        var a = new Ap({
            'formId':'items',
            'resultId': 'aps-all',
            'usersCoundId': 'userCound',
            'usersGroupLegend': 'content-group-legend',
            'groupNowApId': 'groupNowAp',
            'buttons': {
                'calcPeople': $$('.calcPeople')
            }
        });
    });
</script>
</body>
</html>