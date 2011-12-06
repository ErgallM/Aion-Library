<?php
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/library');

require_once 'ZF.php';
require_once 'Cli.php';
require_once 'Dom/Query.php';

$adapter = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host' => 'localhost',
    'username'  => 'root',
    'password' => '25459198',
    'charset' => 'UTF8',
    'dbname' => 'aion_library'
));

Zend_Db_Table::setDefaultAdapter($adapter);

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

    public function getSize()
    {
        $sql = $this->getAdapter()->query("SELECT COUNT(*) as count FROM `{$this->_name}`")->fetch();
        return (!empty($sql['count'])) ? $sql['count'] : 0;
    }
}

class Model_Parser extends Zend_Db_Table
{
    protected $_name = 'items';
    protected $_cols = array('id', 'name', 'lvl', 'type', 'slot', 'q', 'skills', 'manastoneLvl', 'manastoneCount', 'godstone', 'longattack', 'complect', 'price', 'icon', 'image', 'links', 'textBlock');

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

        35 => 'ЛВК',

        36 => 'PvP Атака Отношение',
        37 => 'PvP множитель'
    );

    public static $_errors = array();

    /**
     * @var Model_Noparserdata
     */
    public $noParserDataTable;

    /**
     * @todo Нужно сделать систему сетов
     */

    public function parserDb()
    {
        $size = $this->noParserDataTable->getSize();
        if (!$size) return false;

        $i = $pos = 1;
        $endId = $size;
        $limit = 500;
        while ($pos && $pos <= $endId) {
            /** @var Zend_Db_Table_Rowset_Abstract $rows */
            $rows = $this->noParserDataTable->fetchAll(null, null, $limit, $pos);

            echo "Start parser by size " . $rows->count() . "\n\n";
            foreach ($rows as $row) {
                /** @var Zend_Db_Table_Row $row */
                echo "parser #{$i} id: {$row->id} - " . ($endId - $i);
                $rowData = $this->parserItem($row);
                $i++;
                if ($i > $endId) break;

                if (is_array($rowData) && sizeof($rowData) && $rowData['slot'] && $rowData['type']) {
                    $rowData['skills'] = (!empty($rowData['skills'])) ? serialize($rowData['skills']) : '';
                    $rowData['complect'] = (!empty($rowData['complect'])) ? serialize($rowData['complect']) : '';
                    $rowData['price'] = (!empty($rowData['price'])) ? serialize($rowData['price']) : '';
                    $rowData['textBlock'] = (!empty($rowData['textBlock'])) ? serialize($rowData['textBlock']) : '';
                    $rowData['godstone'] = ($rowData['godstone']) ? 1 : 0;
                    $rowData['longattack'] = ($rowData['longattack']) ? 1 : 0;

                    /** @var Zend_Db_Table_Row_Abstract $sRow  */
                    unset($rowData['id']);
                    $sRow = $this->fetchRow('id = ' . $row->id);
                    if ($sRow) $sRow->setFromArray($rowData);
                    else $sRow = $this->createRow($rowData);
                    $sRow->id = $row->id;
                    $save = $sRow->save();
                    echo (($save) ? ' - 1' : ' - 0');
                }
                echo PHP_EOL;
            }
            $pos+= 500;
        }
        echo PHP_EOL . 'END' . PHP_EOL;
    }

    public function parserItem($itemId)
    {
        if ($itemId instanceof Zend_Db_Table_Row_Abstract) {
            $itemParse = $itemId;
        } else {
            $itemParse = $this->noParserDataTable->fetchRow('id = ' . $itemId);
        }
        if (!$itemParse) return false;

        $result = array(
            'name'              => '',
            'lvl'               => 0,
            'type'              => 0,
            'slot'              => 0,
            'q'                 => 0,
            'skills'            => array(),
            'manastoneLvl'      => 0,
            'manastoneCount'    => 0,
            'godstone'          => false,
            'longattack'        => false,
            'complect'          => array(),
            'price'             => array(),
            'icon'              => '',
            'image'             => '',
            'links'             => '',
            'textBlock'         => array()
        );

        $ru = (!empty($itemParse->ru)) ? $this->parserItemRu($itemParse->ru, $result, $itemParse->id) : false;
        $ruCompare = (!empty($itemParse->ru_compare)) ? $this->parserItemRuCompare($itemParse->ru_compare, $result, $itemParse->id) : false;

        return (false !== $ru) ? $result : array();
    }

    private function parserItemRu($data, &$result, $itemId)
    {
        $q = new Zend_Dom_Query($data);
        $table = $q->query('table.aion_tooltip_container table tr td');
        if (!$table->count()) return false;

        $errors = array();

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

        // name
        $result['name'] = array_shift($blocks[1]);

        // Парсим только шмотки
        if (false !== strpos($result['name'], 'Рецепт')) {
            return false;
        }

        $skills = array();
        $skillsType = 'main';

        $flashParseComplect = false;
        $flashParseComplectPice = false;

        foreach ($blocks as $blockId => $block) {
            while (sizeof($block)) {
                $tag = array_shift($block);
                //echo "$blockId - $tag\n";

                if (false !== strpos($tag, 'Для тестирования')) {
                    return false;
                }

                // type
                if ('Тип' == $tag) {
                    $type = array_shift($block);
                    if (false !== ($typeId = array_search($type, self::$_type))) {
                        $result['type'] = $typeId;
                    } else {
                        $errors[] = "PARSER ERROR {#$itemId, blockId: $blockId, tag: $tag, value: $type, not found in types\n";
                    }
                    continue;
                }

                // block 1
                if (1 == $blockId) {
                    if (0 === strpos($tag, 'Можно использовать с ')) {
                        // lvl
                        $result['lvl'] = (int) str_replace(array('Можно использовать с ', '-го уровня.'), '', $tag);
                    } elseif (false !== strpos($tag, '[')) {
                        // other text
                        if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag;
                        else $result['textBlock'][$blockId] .= $tag;
                    }
                    continue;
                }

                if (false !== ($skillId = array_search($tag, self::$_skills))) {
                    if (!isset($skills[$skillsType])) $skills[$skillsType] = array();

                    $value = array_shift($block);
                    $skills[$skillsType][$skillId] = $value;
                    continue;
                }

                if (0 === strpos($tag, 'Можно усилить магическими камнями ')) {
                    $result['manastoneLvl'] = (int) str_replace(array('Можно усилить магическими камнями ', '-го уровня и ниже.'), '', $tag);
                    continue;
                }

                if (false !== strpos($tag, 'оружие, кол-во ударов')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag;
                    else $result['textBlock'][$blockId] .= $tag;
                    continue;
                }

                if ('Можно вставить божественный камень.' == $tag) {
                    $result['godstone'] = true;
                    continue;
                }

                if ('Дистанция ударов ближнего боя увеличена.' == $tag) {
                    $result['longattack'] = true;
                    continue;
                }

                if ('Надев все предметы комплекта, вы получите дополнительный эффект.' == $tag) {
                    continue;
                }

                if (false !== strpos(mb_strtolower($tag, 'utf-8'), 'комплект')) {
                    $result['complect']['name'] = $tag;
                    $result['complect']['items'] = array();

                    while (sizeof($block)) {
                        $complectItemName = array_shift($block);
                        $result['complect']['items'][] = $complectItemName;
                    }
                    $flashParseComplectPice = true;
                    continue;
                }
                if ($flashParseComplectPice) {
                    $pice = (0 === strpos($tag, 'Полный Сет')) ? 'all' : (int) substr($tag, 0, strpos($tag, ' '));

                    if (!isset($skills['set'])) $skills['set'] = array();
                    if (!isset($skills['set'][$pice])) $skills['set'][$pice] = array();

                    $piceSkill = explode(',', substr($tag, strpos($tag, ':') + 1));
                    foreach ($piceSkill as $skill) {
                        $skill = trim($skill);
                        $skillValue = (int) substr($skill, 0, strpos($skill, ' '));
                        $skillName = array_search(substr($skill, strpos($skill, ' ') + 1), self::$_skills);
                        if (!$skillName || !$skillValue) {
                            $errors[] =  "PARSER ERROR {#$itemId, blockId: $blockId, tag: $tag, value: $skill, not found set skill in skills\n";
                            continue;
                        }

                        $skills['set'][$pice][$skillName] = $skillValue;
                    }
                    continue;
                }

                if (false !== strpos(mb_strtolower($tag, 'utf-8'), 'Крылья')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                    continue;
                } elseif (false !== strpos($tag, 'энергия Сиэли')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                    continue;
                } elseif (false !== strpos($tag, 'силы Сиэли')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                    continue;
                } elseif (false !== strpos($tag, 'Изначальные свойства восстановлены.')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                    continue;
                } elseif (false !== strpos($tag, 'Вы можете изменить вид')) {
                    if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                    continue;
                }

                switch ($tag) {
                    case 'Кажется, это оружие сделано на скорую руку к Дню дурака.':
                    case 'Используется, чтобы изменить внешний вид. Для этого необходимо обратиться к мастеру изменений в любом городе.':
                    case 'Кажется, это оружие сделано на скорую руку к Хэллоуину.':
                    case 'Создается ощущение, что энергия, которой наделено оружие, несовершенна. Возможно, существует способ получить подлинную силу предмета, если раскрыть весь потенциал, скрытый в оружии.':
                    case 'Крыло, несущее в себе силу ядра древнего исполина. От него исходит другая сила - оно не похоже на крылья, произведенные в Элизиуме.':
                    case 'Подарок в честь годовщины Aion в России.':
                    case 'Исчезает через 1 минуту после получения.':
                        if (!isset($result['textBlock'][$blockId])) $result['textBlock'][$blockId] = $tag; else $result['textBlock'][$blockId] .= $tag;
                        break;
                    default:
                        $errors[] =  "UNKNOWN DATA {#$itemId, blockId: $blockId, tag: $tag\n";
                        break;
                }
            }

            if (isset($skills[$skillsType]) && sizeof($skills[$skillsType])) {
                if ('main' == $skillsType) $skillsType = 'other';
            }
        }
        $result['skills'] = $skills;

        // manastoneCount
        $result['manastoneCount'] = (empty($result['manastoneLvl'])) ? 0 : $q->query('td.aion_item_manastone')->count();

        // Доп данные
        $table2 = $q->query('table.infobox-table table[width="100%"] tr td');
        if ($table2->count()) {
            $blocks2 = array();
            foreach ($table2 as $tr) {
                /** @var $tr DOMElement */
                $trimText = trim($tr->textContent, ' ');
                $noEmpty = !empty($trimText);

                //echo "$blockId - \{$tr->textContent\} - $noEmpty\n";

                if ($noEmpty) {
                    $blocks2[] = $trimText;
                }
            }

            while (sizeof($blocks2)) {
                $tag = array_shift($blocks2);
                if (false !== strpos($tag, 'Цвет по умолчанию')) continue;

                switch ($tag) {
                    case 'Уровень:':
                    case 'Количество в Стеке:':
                    case 'Цена торговца:':
                    case 'Продается за:':
                    case 'Можно покрасить:':
                    case 'Сет вещей:':
                    case 'Инфо:':
                        $value = array_shift($blocks2);
                        break;

                    case 'Нужно очков бездны:':
                        $value = array_shift($blocks2);
                        if (!isset($result['price'])) $result['price'] = array();
                        $result['price']['ap'] = (int) $value;
                        break;

                    case 'Покупается с:':
                        if (!isset($result['price'])) $result['price'] = array();
                        $value = array_shift($blocks2);
                        $result['price']['item'] = array(
                            'name' => trim(substr($value, strpos($value, ' '))),
                            'value' => (int) substr($value, 0, strpos($value, ' '))
                        );
                        break;

                    case 'Слот инвентаря:':
                        $value = array_shift($blocks2);
                        if ('Обе' == $value) $value = 'Главная или Вторая Рука';
                        $slot = array_search($value, self::$_slot);
                        if (false === $slot) {
                            $errors[] =  "PARSER ERROR {#$itemId, blockId: dop-block, tag: $tag, value: $value, not found slot\n";
                        } else {
                            $result['slot'] = $slot;
                        }
                        break;

                    default:
                        $errors[] =  "UNKNOWN DATA {#$itemId, blockId: dop-block, tag: $tag\n";
                        break;
                }
            }
        }

        // Картинки
        $images = $q->query('body>img');
        if ($images->count()) {
            foreach($images as $image) {
                /** @var $image DOMElement */
                $src = $image->getAttribute('src');

                if (0 === strpos($src, '/res/images/')) {
                    $result['image'] = trim(substr($src, strrpos($src, '/') + 1));
                } else {
                    $result['icon'] = trim(substr($src, strrpos($src, '/') + 1));
                }
            }
        }

        if (empty($result['skills']['main'])) {
            return false;
        }

        self::$_errors = array_merge(self::$_errors, $errors);
        return $result;
    }

    private function parserItemRuCompare($data, &$result)
    {
        if (empty($data)) return false;

        $data = (array) json_decode($data);
        $result['q'] = (!empty($data['q'])) ? $data['q'] - 2 : 0;
        //$result['icon'] = (!empty($data['i'])) ? $data['i'] : '';

        // PvP Атака Отношение
        $key = array_search('48', $data['fields']);
        if (false !== $key) $result['skills']['main'][36] = $data['values'][$key];

        // PvP множитель
        $key = array_search('49', $data['fields']);
        if (false !== $key) $result['skills']['main'][37] = $data['values'][$key];

        return $result;
    }

    public function getErrors()
    {
        return self::$_errors;
    }

    public function displayErrors()
    {
        foreach ($this->getErrors() as $error) echo $error;
    }
}

$options = Cli::getParams(array('id' => 1, 'end' => null), $argc, $argv);
/*
$parser = new Model_Noparserdata();
$parser->noparserdataToDb($options['id']);
*/

$parser = new Model_Parser();
$parser->noParserDataTable = new Model_Noparserdata();
$parser->setDefaultAdapter($adapter);

//$parser->parserItem('100500698'); // Орб Рудры
//$parser->parserItem('110101071'); // Туника Рудры
//$parser->parserItem('100500725'); // Орб акана-капитана гарнизона
//$parser->parserItem('110101095'); // Туника капитана гарнизона
//$parser->parserItem('101300591'); // Копье легата 47-го отряда
//var_dump($parser->parserItem('122000932'));
//$parser->displayErrors();

$parser->parserDb($options['id'], $options['end']);
file_put_contents('log.txt', implode($parser->getErrors(), ''));