<?php
class Elite_Vaf_Model_SchemaTest_MMY_GlobalTest extends Elite_Vaf_TestCase
{
    function doSetUp()
    {
    }
    
    function tearDown()
    {
        $this->schemaGenerator()->dropExistingTables();
    }
    
    function testShouldHaveGlobal()
    {
        $this->schemaGenerator()->dropExistingTables();
        $this->schemaGenerator()->execute(array('year','make'=>array('global'=>true),'model'));
        $schema = new Elite_Vaf_Model_Schema();
        $this->assertTrue($schema->hasGlobalLevel());
    }
    
    function testShoulNotdHaveGlobal()
    {
        $this->schemaGenerator()->dropExistingTables();
        $this->schemaGenerator()->execute(array('year','make','model'));
        $schema = new Elite_Vaf_Model_Schema();
        $this->assertTrue($schema->hasGlobalLevel());
    }
}