<?php
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/library');

require_once 'ZF.php';
require_once 'Cli.php';
require_once 'Dom/Query.php';

Zend_Db_Table::setDefaultAdapter(new Zend_Db_Adapter_Pdo_Mysql(array(
    'host' => 'localhost',
    'username'  => 'root',
    'password' => '25459198',
    'charset' => 'UTF8',
    'dbname' => 'aion_library'
)));

class Model_Noparserdata extends Zend_Db_Table
{
    protected $_name = 'noparserdata';
    protected $_cols = array('id', 'ai', 'en', 'en_compare', 'en_js', 'ru', 'ru_compare', 'ru_js');

    /** @var string */
    protected $_folder = '/home/eaglemoor/AionParser/db';

    protected $_selectors = array(
        'ai' => '.hbody>table',
        'en' => 'table.aion_tooltip_container',
        'en_2' => '.map-box-content-wrapper',
        'en_image' => '.infobox-table .map_tooltip_border img',
        'en_compare' => '*',
        'en_icon' => '/res/icons/40/{name}.png'
    );

    public function parserAI($id)
    {
        $file = @file_get_contents($this->_folder . "/ai/$id.html");
        if (empty($file)) return '';

        $q = new Zend_Dom_Query($file);
        $data = $q->query($this->_selectors['ai']);
        if ($data->count()) {
            $dom = new DOMDocument();
            $dom->appendChild($dom->importNode($data->current(), true));
            return $dom->saveHTML();
        } else {
            return '';
        }
    }

    public function parserItem($id, $lang = 'en')
    {
        $file = @file_get_contents($this->_folder . "/$lang/$id.html");
        if (empty($file)) return '';

        $q = new Zend_Dom_Query($file);
        $data = $q->query($this->_selectors['en']);

        $dom = new DOMDocument();
        
        if ($data->count()) {
            $dom->appendChild($dom->importNode($data->current(), true));
        } else {
            return '';
        }

        $data = $q->query($this->_selectors['en_2'], true);
        if ($data->count()) $dom->appendChild($dom->importNode($data->current(), true));

        $data = $q->query($this->_selectors['en_image'], true);
        if ($data->count()) $dom->appendChild($dom->importNode($data->current(), true));

        $data = $q->query('img');
        foreach ($data as $image) {
            /** @var \DOMElement $image */
            if (0 === strpos($image->getAttribute('src'), '/res/icons/40/')) {
                $dom->appendChild($dom->importNode($image, true));
                break;
            }
        }
        
        return $dom->saveHTML();
    }

    public function parserCompare($id, $lang = 'en')
    {
        $data = @file_get_contents($this->_folder . "/$lang/compare/$id.txt");
        return (string) $data;
    }

    public function parserJs($id, $lang = 'en')
    {
        $data = @file_get_contents($this->_folder . "/$lang/js/$id.js");
        $data = str_replace('aionInjector.addTooltip(', '', $data);
        $data = str_replace('});', '}', $data);
        return (string) $data;
    }

    public function noparserdataToDb($id = null)
    {
        if (null !== $id) {
            $saveData = array(
                'id'    => $id,
                'ai'    => $this->parserAI($id),
                'en'    => $this->parserItem($id),
                'en_compare' => $this->parserCompare($id),
                'en_js' => $this->parserJs($id),
                'ru'    => $this->parserItem($id, 'ru'),
                'ru_compare' => $this->parserCompare($id, 'ru'),
                'ru_js' => $this->parserJs($id, 'ru')
            );

            $this->createRow($saveData)->save();
            return true;
        }

        $idArray = array();

        if ($handle = opendir($this->_folder . '/ai')) {
            echo "Дескриптор каталога: $handle\n";

            while (false !== ($file = readdir($handle))) {
                if ('.' != $file && '..' != $file) {
                    $id = (int) str_replace('.html', '', $file);
                    if (!$id) continue;
                    $idArray[$id] = $id;
                }
            }

            closedir($handle);
        }

        echo "Обнаружено " . sizeof($idArray) . " элементов.\nНачинаю парсинг...\n";
        $i = 0;
        foreach ($idArray as $id) {
            $i++;
            $saveData = array(
                'id' => $id,
                'ai' => $this->parserAI($id),
                'en' => $this->parserItem($id),
                'en_compare' => $this->parserCompare($id),
                'en_js' => $this->parserJs($id),
                'ru' => $this->parserItem($id, 'ru'),
                'ru_compare' => $this->parserCompare($id, 'ru'),
                'ru_js' => $this->parserJs($id, 'ru')
            );

            try {
                $this->createRow($saveData)->save();
            } catch (\Exception $e) {
                if (false === strpos($e->getMessage(), 'Duplicate entry')) echo 'ERROR #' . $e->getCode() . ' ' . $e->getMessage() . PHP_EOL;
            }

            echo $id . ' - ' . sizeof($idArray) . ' / ' . (sizeof($idArray) - $i) . PHP_EOL;
        }
        echo "PARSER END";
    }
}

class Model_Parser extends Zend_Db_Table
{
    protected $_db = 'items';
    protected $_cols = array('id', 'name', 'lvl', 'type', 'slot', 'q', 'skills', 'manastoneLvl', 'manastoneCount', 'godstone', 'price', 'icon', 'image', 'links');

    public static $_type = array(
        // Доспехи
        1 => 'Тканые доспехи',
        2 => 'Кожаные доспехи',
        3 => 'Кольчужные доспехи',
        4 => 'Латные доспехи',
        5 => 'Щиты',
        6 => 'Головной убор',

        // Оружие
        7 => 'Копья',
        8 => 'Двуручные мечи',
        9 => 'Мечи',
        10 => 'Кинжалы',
        11 => 'Булавы',
        12 => 'Посохи',
        13 => 'Луки',
        14 => 'Орбы',
        15 => 'Гримуары',

        // Бижа
        16 => 'Серьги',
        17 => 'Ожерелья',
        18 => 'Кольца',
        19 => 'Пояса',
    );
    public static $_slot = array(
        1 => 'Голова',
        2 => 'Торс',
        3 => 'Штаны',
        4 => 'Ботинки',
        5 => 'Наплечники',
        6 => 'Перчатки',
        7 => 'Ожерелья',
        8 => 'Серьги',
        9 => 'Кольца',
        10 => 'Пояс',
        11 => 'Крыло',
        12 => 'Главная или Вторая Рука',
        13 => 'Главная Рука'
    );
    public static $_skills = array(
        1 => 'Атака',
        2 => 'Физическая атака',
        3 => 'Маг. атака',
        4 => 'Скор. атаки',
        5 => 'Скор. магии',
        6 => 'Точность',
        7 => 'Точн. магии',
        8 => 'Ф. крит.',
        9 => 'М. крит.',
        10 => 'Сила магии',
        11 => 'Сила исцелен.',

        12 => 'Парир.',
        13 => 'Уклонение',
        14 => 'Концентрац.',
        15 => 'Блок урона',
        16 => 'Блок щитом',
        17 => 'Блок ф. крит.',
        18 => 'Блок м. крит.',

        19 => 'Физ. защита',
        20 => 'Маг. защита',
        21 => 'Защ. от земли',
        22 => 'Защ. от возд.',
        23 => 'Защ. от воды',
        24 => 'Защ. от огня',
        25 => 'Защита от ф. крит.',

        26 => 'Сопротивление оглушению',
        27 => 'Сопротивление опрокидыванию',
        28 => 'Сопротивление отталкиванию',

        29 => 'Макс. HP',
        30 => 'Макс. MP',

        31 => 'Скор. полета',
        32 => 'Время полета',
        33 => 'Скор. движ.',

        34 => 'Агрессия',

        35 => 'ЛВК'
    );

    /**
     * @var Model_Noparserdata
     */
    public $noParserDataTable;

    /**
     * @todo Нужно сделать систему сетов
     */

    public function parserItem($itemId)
    {
        $itemParse = $this->noParserDataTable->fetchRow('id = ' . $itemId);
        if (!$itemParse) return false;

        $ru = $this->parserItemRu($itemParse->ru);
    }

    private function parserItemRu($data)
    {
        $q = new Zend_Dom_Query($data);
        $table = $q->query('table.aion_tooltip_container table tr td');
        if (!$table->count()) return false;

        $blockId = 1;
        $blocks = array();
        foreach ($table as $tr) {
            /** @var $tr DOMElement */
            $trimText = trim($tr->textContent, ' ');
            $noEmpty = !empty($trimText);

            //echo "$blockId - \{$tr->textContent\} - $noEmpty\n";

            if ($noEmpty) {
                if (!isset($blocks[$blockId])) $blocks[$blockId] = array();
                $blocks[$blockId][] = $trimText;
            }

            if ($tr->getElementsByTagName('hr')->length) $blockId++;
        }

        $result = array();

        // name
        $result['name'] = array_shift($blocks[1]);

        $skills = array();
        $skillsType = 'main';

        $complect = array(
            'name' => '',
            'items' => ''
        );

        $flashParseComplect = false;

        foreach ($blocks as $blockId => $block) {
            while (sizeof($block)) {
                $tag = array_shift($block);
                //echo "$blockId - $tag\n";

                // type
                if ('Тип' == $tag) {
                    $type = array_shift($block);
                    if (false !== ($typeId = array_search($type, self::$_type))) {
                        $result['type'] = $typeId;
                    } else {
                        echo "PARSER ERROR {blockId: $blockId, tag: $tag, value: $type, not found in types\n";
                    }
                }

                // block 1
                if (1 == $blockId) {
                    if (0 === strpos($tag, 'Можно использовать с ')) {
                        // lvl
                        $result['lvl'] = (int) str_replace(array('Можно использовать с ', '-го уровня.'), '', $tag);
                    } elseif (false !== strpos($tag, '[')) {
                        // other text
                    }
                }

                if (false !== ($skillId = array_search($tag, self::$_skills))) {
                    if (!isset($skills[$skillsType])) $skills[$skillsType] = array();

                    $value = array_shift($block);
                    $skills[$skillsType][$skillId] = $value;
                }

                if (0 === strpos($tag, 'Можно усилить магическими камнями ')) {
                    $result['manastoneLvl'] = (int) str_replace(array('Можно усилить магическими камнями ', '-го уровня и ниже.'), '', $tag);
                }

                if ('Можно вставить божественный камень.' == $tag) {
                    $result['godstone'] = true;
                }

                if ('Надев все предметы комплекта, вы получите дополнительный эффект.' == $tag) {
                    $flashParseComplect = true;
                }

                if ($flashParseComplect && false !== strpos($tag, 'комплект')) {
                    $complect['name'] = $tag;
                    // @todo парсинг комплекта
                }
            }

            if (sizeof($skills[$skillsType])) {
                if ('main' == $skillsType) $skillsType = 'other';
            }
        }
        $result['skills'] = $skills;

        // manastoneCount
        $result['manastoneCount'] = (empty($result['manastoneLvl'])) ? 0 : $q->query('td.aion_item_manastone')->count();

        var_dump($result);
    }
}

$options = Cli::getParams(array('id' => null), $argc, $argv);
/*
$parser = new Model_Noparserdata();
$parser->noparserdataToDb($options['id']);
*/

$parser = new Model_Parser();
$parser->noParserDataTable = new Model_Noparserdata();

$parser->parserItem('100500698');