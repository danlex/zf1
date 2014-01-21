<?php

include_once 'MapperTestCase.php';
class GuestbookMapperTest extends MapperTestCase
{
    protected $_initialSeedFile = 'guestbookSeed.xml';
    
    
    public function testInsertIntoGuestbook()
    {
        $guestbookData = array(
            'email' => 'user5@example.com',
            'comment' => 'Comment5',
            'created' => '2104-01-01 05:00:00',
            'modified' => '2104-01-01 05:00:00'
        );

        $guestbookModel = new Application_Model_Guestbook();
        $guestbookModel->setOptions($guestbookData);

        $guestbookMapper = new Application_Model_GuestbookMapper();
        $guestbookMapper->save($guestbookModel);

        // get data from the testing database
        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('guestbook', 'SELECT * FROM guestbook');

        // verify expected vs actual
        $this->assertDataSetsMatchXML('guestbookInsertIntoAssertion.xml', $dataSet);
    }
    
    public function testFindGuestbook()
    {
        $id = 1;
        $guestbookMapper = new Application_Model_GuestbookMapper();
        $guestbookModel = $guestbookMapper->find($id);
        $this->assertEquals(
            $guestbookModel->getId(), $id
        );
    }
    
    public function testDeleteGuestbook()
    {
        $id = 4;

        $guestbookMapper = new Application_Model_GuestbookMapper();
        $guestbookMapper->delete($id);

        // get data from the testing database
        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('guestbook', 'SELECT * FROM guestbook');

        // verify expected vs actual
        $this->assertDataSetsMatchXML('guestbookDeleteAssertion.xml', $dataSet);
    }
    
}

