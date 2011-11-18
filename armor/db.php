<?php
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/library');
$t = (!empty($_GET['t'])) ? (string) $_GET['t'] : null;
//if (null == $t) exit();

require_once 'ZF.php';
require_once 'item.php';

Zend_Db_Table::setDefaultAdapter(new Zend_Db_Adapter_Pdo_Mysql(array(
    'host' => 'localhost',
    'username'  => 'root',
    'password' => '25459198',
    'charset' => 'UTF8',
    'dbname' => 'aion_library'
)));

if ('add' == $t) {

    $table = new Model_Item_User();
    return $table->addItem($_POST);
}