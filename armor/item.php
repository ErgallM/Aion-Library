<?php
class Model_Item extends Zend_Db_Table
{
    protected $_name = 'items';
    protected $_cols = array('id', 'name', 'lvl', 'type', 'slot', 'q', 'skills', 'manastoneLvl', 'manastoneCount', 'godstone', 'price', 'icon', 'image');

    public function addItem($data)
    {
        $data['skills'] = serialize($data['skills']);
        $data['price'] = serialize($data['price']);

        $row = $this->createRow($data);
        return $row->save();
    }
}

class Model_Item_User extends Model_Item
{
    protected $_name = 'items-useradd';
}