<?php
class Model_Item extends Zend_Db_Table
{
    protected $_name = 'items';
    protected $_cols = array('id', 'name', 'lvl', 'type', 'slot', 'q', 'skills', 'manastoneLvl', 'manastoneCount', 'godstone', 'longattack', 'complect', 'price', 'icon', 'image', 'links', 'textBlock');

    public function addItem($data)
    {
        $saveData = array(
            'name'              => $this->filter($data['name']),
            'lvl'               => $this->filter($data['lvl'], 'int'),
            'type'              => $this->filter($data['type'], 'int'),
            'slot'              => $this->filter($data['slot'], 'int'),
            'q'                 => $this->filter($data['q'], 'int'),
            'skills'            => $this->filter($data['q']),
            'manastoneLvl'      => $this->filter($data['manastoneLvl'], 'int'),
            'manastoneCount'    => $this->filter($data['manastoneCount'], 'int'),
            'godstone'          => $this->filter($data['godstone'], 'bool'),
            'longattack'        => $this->filter($data['longattack'], 'bool'),
            'complect'          => $this->filter($data['complect']),
            'price'             => $this->filter($data['price']),
            'icon'              => $this->filter($data['icon']),
            'image'             => $this->filter($data['image']),
            'links'             => $this->filter($data['links']),
            'textBlock'         => $this->filter($data['textBlock']),
        );

        $data['skills'] = serialize($data['skills']);
        $data['price'] = serialize($data['price']);
        $data['complect'] = serialize($data['complect']);
        $data['textBlock'] = serialize($data['textBlock']);

        $row = $this->createRow($data);
        return $row->save();
    }

    public function getItem($id)
    {
        $row = $this->fetchRow($this->select()->where('id = ?', (int) $id));
        if (null != $row) {
            /** @var Zend_Db_Table_Row $row */
            $data = $row->toArray();
            $data['skills'] = unserialize($data['skills']);
            $data['price'] = unserialize($data['price']);
            $data['complect'] = unserialize($data['complect']);
            $data['textBlock'] = unserialize($data['textBlock']);

            return $data;
        }
        return array();
    }

    /**
     * @param $data
     * @return array
     */
    public function getItemsList($data)
    {
        $start = (!empty($data['start'])) ? (int) $data['start'] : 0;
        $count = (!empty($data['count'])) ? (int) $data['count'] : 100;
        unset($data['start'], $data['count']);

        $sql = $this->getAdapter()->select()->from($this->_name);
        /** @var Zend_Db_Select $sql */
        foreach ($data as $key => $value) {
            if (array_search($key, $this->_cols)) {
                $value = $this->filter($value);
                $sql->where("{$key} LIKE ?", '%' . $value . '%');
            }
        }
        $sql->limit($count, $start)->order('type')->order('q DESC')->order('lvl DESC')->order('name');
        $result = $this->getAdapter()->query($sql)->fetchAll();
        foreach ($result as &$row) {
            $row['skills'] = unserialize($row['skills']);
            $row['price'] = unserialize($row['price']);
            $row['complect'] = unserialize($row['complect']);
            $row['textBlock'] = unserialize($row['textBlock']);
        }
        return $result;
    }

    public function filter($data, $type = 'string')
    {
        switch ($type) {
            case 'string':
            default:
                $filterStringTrim = new Zend_Filter_StringTrim();
                $filterStripTags = new Zend_Filter_StripTags();

                    $data = $filterStripTags->filter($filterStringTrim->filter($data));
                break;

            case 'int':
                $filterInt = new Zend_Filter_Int();

                    $data = $filterInt->filter($data);
                break;

            case 'bool':
                $filterBool = new Zend_Filter_Boolean();

                    $data = $filterBool->filter($data);
                break;
        }
        return $data;
    }
}

class Model_Item_User extends Model_Item
{
    protected $_name = 'items-useradd';
}