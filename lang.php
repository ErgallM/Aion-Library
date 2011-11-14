<?php
class Lang
{
    protected $_db = array();

    protected $_langs = array('ru', 'en');

    public function __construct()
    {
        $this->_db = include('langs/main.php');
    }

    public function addLangDb($name)
    {
        $db = include('langs/' . $name . '.php');
        $this->_db = array_merge($this->_db, $db);
        return this;
    }

    public function translate($name, $lang = null)
    {
        if (null === $lang) {
            $lang = $this->getLang();
        }

        if ('ru' == $lang) return $name;

        $lname = mb_strtolower($name, 'utf-8');
        foreach ($this->_db as $key => $words) {
            if (empty($words[$lang])) continue;
            if (mb_strtolower($key, 'utf-8') == $lname) return $words[$lang];
        }

        return $name;
    }

    public function getLang()
    {
        $isPost = (strtolower($_SERVER['REQUEST_METHOD']) === "post") ? true : false;

        $lang = 'ru';
        if ($isPost) {
            if (!empty($_POST['lang']) && in_array($_POST['lang'], $this->_langs)) $lang = $_POST['lang'];
        } else {
            if (!empty($_GET['lang']) && in_array($_GET['lang'], $this->_langs)) $lang = $_GET['lang'];
        }
        return $lang;
    }
}