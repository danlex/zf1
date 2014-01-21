<?php

class Application_Model_JsonGrid {

    protected $_gridTotal = 0;
    protected $_gridPage = 0;
    protected $_gridRecords = 0;
    protected $_gridRows = array();

    public function setGridTotal($total){
        $this->_gridTotal = $total;
    }

    public function setGridPage($page){
        $this->_gridPage = $page;
    }

    public function setGridRecords($records){
        $this->_gridRecords = $records;
    }

    public function setGridRows( array $gridRows = array()){
        $this->_gridRows = $gridRows;
    }

    public function getGridResponse(){
        return array(    'total' => $this->_gridTotal,
                        'page'=> $this->_gridPage,
                        'records'=> $this->_gridRecords,
                        'rows'=> $this->_gridRows,
                );
    }
}