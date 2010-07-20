<?php
require_once 'PHPUnit/Framework.php';
require_once(dirname(__FILE__).'/../../config.php');


class ColumnFamilyTestObject extends PandraColumnFamily {

    public function init() {
        $this->setKeySpace('Keyspace1');
        $this->setName('Standard1');

        $this->addColumn('column1', 'notempty');
        $this->column_column1 = 'VALUE';
    }
}

/**
 * Test class for PandraColumnFamily.
 * Generated by PHPUnit on 2010-01-09 at 11:52:22.
 */
class PandraColumnFamilyTest extends PHPUnit_Framework_TestCase {
    /**
     * @var    PandraColumnFamily
     * @access protected
     */
    protected $obj;

    private $_keyID = 'PandraCFTest';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
        $this->obj = new ColumnFamilyTestObject($this->_keyID);
        //$this->obj->setKeyID($this->_keyID);
        PandraCore::connect('default', 'localhost');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
        PandraCore::disconnectAll();
    }

    public function testSaveLoadDelete() {
        $this->assertTrue($this->obj->save(), $this->obj->lastError());

        // reload the object, test that column1 is the same
        $newObj = new ColumnFamilyTestObject($this->_keyID);
        $newObj->load();
        $this->assertTrue($this->obj->column_column1 == $newObj->column_column1,
                "Column didn't match after reload.  Expected '".$this->obj->column_column1."', received '".$newObj->column_column1."'");

        $this->assertTrue($newObj->isLoaded());

        $this->obj->delete();
        $this->obj->save();
    }

    public function testSaveLoadDeleteUUID() {
        $cf = new PandraColumnFamily($this->_keyID, 'Keyspace1', 'StandardByUUID1', PandraColumnContainer::TYPE_UUID);
        $column = $cf->addColumn(UUID::v1());

        $uuidName = UUID::toStr($column->getName());
        $cValue = 'test value';
        $column->setValue($cValue);
        //$this->assertTrue($cf->save());
        $cf->save();

        unset($cf);

        $newCF = new PandraColumnFamily($this->_keyID, 'Keyspace1', 'StandardByUUID1', PandraColumnContainer::TYPE_UUID);
        $newCF->load();
        $this->assertEquals($cValue, $newCF[$uuidName]);
    }

    public function testSetKeyValidated() {
        $cf = new PandraColumnFamily($this->_keyID, 'Keyspace1', 'StandardByUUID1', PandraColumnContainer::TYPE_UUID);

        $cf->setKeyValidator(array('uuid'));
        $newKey = 'abc123';
        $this->assertFalse($cf->setKeyID($newKey));
        $this->assertFalse($cf->getKeyID() == $newKey);
    }

    /**
     * Tests that resets() after deletes or column changes revert flags
     */
    public function testReset() {

        $this->obj->reset();
        $this->obj->delete();

        $columns = $this->obj->listColumns();
        foreach ($columns as $column) {
            $this->obj->getColumn($column)->setValue('NEW VALUE');
            $this->obj->getColumn($column)->delete();
        }

        $this->assertTrue($this->obj->isModified());

        // make sure everything is marked for delete
        $columns = $this->obj->listColumns();
        foreach ($columns as $column) {
            $this->assertTrue($this->obj->getColumn($column)->isModified());
            $this->assertTrue($this->obj->getColumn($column)->isDeleted());
        }

        $this->assertTrue($this->obj->reset());

        $columns = $this->obj->listColumns();
        foreach ($columns as $column) {
            $this->assertFalse($this->obj->getColumn($column)->isDeleted());
            $this->assertFalse($this->obj->getColumn($column)->isModified());
        }

        $this->assertFalse($this->obj->isDeleted());
    }

    /**
     * Tests that the ColumnFamily columns can be populated by a two dimensional
     * associative array or from a JSON string
     */
    public function testPopulate() {
        $c1NewValue = 'TEST POST VALUE';

        $_POST = array(
                'column1' => $c1NewValue
        );

        $this->assertTrue($this->obj->populate($_POST));
        $this->assertEquals($this->obj->column_column1, $c1NewValue);

        $c1NewValue = 'TEST JSON VALUE';

        $json = '{"column1":"'.$c1NewValue.'"}';
        $this->assertTrue($this->obj->populate($json));
        $this->assertEquals($this->obj->column_column1, $c1NewValue);
    }

    /**
     * Tests column is correctly set and modified flag is true
     */
    public function testSetColumn() {
        $c1NewValue = 'TEST POST VALUE';

        $this->obj->getColumn('column1')->reset();
        $this->assertFalse($this->obj->getColumn('column1')->isModified());

        $this->obj->setColumn('column1', $c1NewValue);
        $this->assertEquals($this->obj->column_column1, $c1NewValue);
        $this->assertTrue($this->obj->getColumn('column1')->isModified());
    }

    /**
     * @todo Implement testAddColumn().
     */
    public function testAddGetColumn() {

        $columnName = 'newColumn';

        // CF
        $column = $this->obj->addColumn($columnName);
        $this->assertTrue($column instanceof PandraColumn);

        $column = $this->obj->getColumn($columnName);
        $this->assertTrue($column->name == $columnName, 'Column name mismatch, expected '.$columnName.', received ',$column->name);
    }


    /**
     * @todo Implement testListColumns().
     */
    public function testListColumns() {
        $columns = $this->obj->listColumns();
        $this->assertTrue(is_array($columns) && !empty($columns));
    }

    /**
     * Tests notations
     */
    public function testNotations() {

        $colName = 'column1';

        // Array Access
        $newValue = 'ASDKFWIOER23';
        $this->obj[$colName] = $newValue;
        $this->assertTrue($this->obj[$colName] == $newValue);
        $this->assertFalse($this->obj[$colName] != $newValue);

        // Magic Method
        $newValue = 'OIWERUWINCN@$';
        $this->obj->column_column1 = $newValue;
        $this->assertTrue($this->obj->column_column1 == $newValue);
        $this->assertFalse($this->obj->column_column1 != $newValue);

        // Accessors/Mutators
        $newValue = 'OIWERUWINCN@$';
        $this->obj->getColumn($colName)->setValue($newValue);
        $this->assertTrue($this->obj->getColumn($colName)->value == $newValue);
        $this->assertFalse($this->obj->getColumn($colName)->value != $newValue);

        // test unset
        unset($this->obj['column1']);
        $this->assertFalse($this->obj->getColumn('column1') instanceof PandraColumn);
    }
}
?>