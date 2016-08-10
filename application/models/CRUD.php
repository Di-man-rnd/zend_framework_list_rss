<?php
class Application_Model_CRUD extends Zend_Db_Table_Abstract
{
    protected $_name  = 'like'; // db table
    
    /**
     * Определяем добавляем новыю запись или обновляем
     */
    function auto($name, $int){
        $row = $this->fetchRow($this->select()->where('name = ?', Class_Rss::Filter($name)));       
        if($row === NULL){
            self::add($name);
        }else{
            self::upd($name, $int);
        }
    } 
    /**
     * add new caption and +1 like
     * @link https://framework.zend.com/manual/1.12/ru/zend.db.adapter.html commit 
     * @param string $name
     */
    public function add($name) { 
        $data = array(
            'id'=>'',
            'name' => Class_Rss::Filter($name),
            'count' => 1
        );
        $this->getAdapter()->beginTransaction();
        try {
            $this->insert($data);
            $this->getAdapter()->commit();
        } catch (Exception $exc) {
            /**
             * @TODO добавить лог ошибок
             */
            echo $exc->getMessage();
            $this->getAdapter()->rollback();
        }
    }
    /**
     * delete records, like =0 
     * @param string $name
     */
    function del($name) {  
        $where = $this->getAdapter()->quoteInto('name = ?', Class_Rss::Filter($name)); 
        $this->delete($where);
    }
    /**
     * ++ count 
     * @link https://framework.zend.com/manual/1.12/ru/zend.db.adapter.html commit 
     * @param string $name
     * @param int $int
     */
    function upd($name, $int) {
        $int =(int)$int;
        $data = array('count' => ++$int);
        $where = $this->getAdapter()->quoteInto('name = ?', Class_Rss::Filter($name));
        $this->getAdapter()->beginTransaction();
        try {
            $this->update($data, $where);
            $this->getAdapter()->commit();
        } catch (Exception $exc) {
            /**
             * @TODO добавить лог ошибок
             */
            echo $exc->getMessage();
            $this->getAdapter()->rollback();
        }
    }
}

