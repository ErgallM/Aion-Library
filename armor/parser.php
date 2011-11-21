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

class Model_Parser
{
    /** @var Zend_Db_Table */
    protected $_db = null;

    /** @var string */
    protected $_folder = '/home/eaglemoor/AionParser/db';
}

$options = Cli::getParams(array('id' => null), $argc, $argv);
$parser = new Model_Noparserdata();
$parser->noparserdataToDb($options['id']);
