<?php

class Application_Model_GuestbookMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Guestbook');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Guestbook $guestbook)
    {
        $data = $guestbook->toArray();
        if (null === ($id = $guestbook->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        $this->getCacheManager()->remove($this->getCacheKey($id));
    }
    

    public function delete($id)
    {
        $this->getDbTable()->delete(array('id = ?' => $id));
        $this->getCacheManager()->remove($this->getCacheKey($id));
    }
    
    public function find($id)
    {
        $guestbook = new Application_Model_Guestbook();
        $guestbookCacheKey = $this->getCacheKey($id);
        $guestbookData = $this->getCacheManager()->load($guestbookCacheKey);
        if(false === $guestbookData){
            $result = $this->getDbTable()->find($id);
            if (0 == count($result)) {
                return;
            }
            $guestbookData = $result->current()->toArray();
            $this->getCacheManager()->save($guestbookData, $guestbookCacheKey);
        }
        $guestbook->setOptions($guestbookData);
        return $guestbook;
    }
 
    public function fetchAll($filter = array())
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Guestbook();
            $entry->setOptions($row->toArray());
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function getPaginatorJsonGrid($filter = array(), $page, $pageSize)
    {
        $select = $this->getDbTable()->select();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($pageSize);
        
        $total = ceil($paginator->getTotalItemCount() / $paginator->getItemCountPerPage());
        
        $json = new Application_Model_JsonGrid();
        $json->setGridTotal($total);
        $json->setGridRecords($paginator->getTotalItemCount());
        $json->setGridRows($paginator->getCurrentItems()->toArray());
        
        $response = $json->getGridResponse();
        
        return $response;
    }
    
    public function getNewGuestbook()
    {
        return new Application_Model_Guestbook();
    }
    
    public function beginTransaction()
    {
        $this->getDbTable()->getAdapter()->beginTransaction();
    }
    
    public function rollBack()
    {
        $this->getDbTable()->getAdapter()->rollBack();
    }
    
    public function commit()
    {
        $this->getDbTable()->getAdapter()->commit();
    }
    
    public function getCacheKey($id)
    {
        return 'model_guestbook_'.$id;
    }
    
    public function getCacheManager()
    {
        return Zend_Registry::get('Cache');
    }
}