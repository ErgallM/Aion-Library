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
    echo json_encode(array('status' => $table->addItem($_POST)));
} elseif ('get-user-item' == $t) {
    $table = new Model_Item_User();
    $id = $_GET['id'];

    echo json_encode($table->getItem($id));
} elseif ('get' == $t && isset($_POST['data'])) {
    // Список итемов для armor/index.php

    $table = new Model_Item();
    echo json_encode($table->getItemsList($_POST['data']));
}