<?php

class MapperTestCase extends Zend_Test_PHPUnit_DatabaseTestCase
{
	const DEFAULT_CONNECTION_SCHEMA = 'main';
	protected $_connectionMock;
	protected $_configuration;
	protected $_connectionSchema = self::DEFAULT_CONNECTION_SCHEMA;
    protected $_initialSeedFile;
    protected $_seedFilesPath;
	
	    /**
	* Returns the test database connection.
	*
	* @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	*/
    protected function getConnection()
    {
        if ($this->_connectionMock == NULL) {
            $dbAdapterName = $this->getConfiguration()->resources->db->adapter;
            $dbAdapterParams = $this->getConfiguration()->resources->db->params->toArray();
            $connection = Zend_Db::factory($dbAdapterName, $dbAdapterParams);
            $this->_connectionMock = $this->createZendDbConnection(
                $connection, $this->_connectionSchema
            );

            Zend_Db_Table_Abstract::setDefaultAdapter($connection);
        }
        return $this->_connectionMock;
    }
    
	public function getConfiguration()
    {
        if ($this->_configuration == NULL) {
            $this->_configuration = Zend_Registry::get('config');
        }
        return $this->_configuration;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createFlatXmlDataSet(
            $this->getSeedFilesPath() . $this->_initialSeedFile
        );
    }
    
	public function getSeedFilesPath()
    {
        if ($this->_seedFilesPath == NULL) {
            $this->_seedFilesPath = dirname(__FILE__) . '/fixtures/';
        }

        return rtrim($this->_seedFilesPath, '/') . '/';
    }
    
     /**
     * Convert a Rowset to a Dataset
     *
     * @param  Zend_Db_Table_Rowset_Abstract $rowset
     * @param  string $tableName
     * @return PHPUnit_Extensions_Database_DataSet_DefaultDataSet
     */
    public function convertRowsetToDataSet($rowset, $tableName = NULL)
    {
        $rowsetDataSet = new Zend_Test_PHPUnit_Db_DataSet_DbRowset($rowset, $tableName);
        return new PHPUnit_Extensions_Database_DataSet_DefaultDataSet(array($rowsetDataSet));
    }

    /**
     * Convert a Record to a Dataset
     *
     * @param  array $data
     * @param  string $tableName
     * @return PHPUnit_Extensions_Database_DataSet_DefaultDataSet
     */
    public function convertRecordToDataSet(Array $data, $tableName)
    {
        $rowset = new Zend_Db_Table_Rowset(array('data' => array($data)));
        return $this->convertRowsetToDataSet($rowset, $tableName);
    }

    /**
     * Compare dataset with data stored in the file
     *
     * @param  string $filename
     * @param  PHPUnit_Extensions_Database_DataSet_IDataSet $expected
     * @return boolean
     */
    public function assertDataSetsMatchXML($filename, PHPUnit_Extensions_Database_DataSet_IDataSet $actual)
    {
        if (empty($filename) || !is_string($filename))
                throw new InvalidArgumentException(
                  'Second parameter "filename" is not a valid string.'
                );
        $expected = $this->createFlatXmlDataSet($this->getSeedFilesPath() . $filename);
        return $this->assertDataSetsEqual($expected, $actual);
    }
}

