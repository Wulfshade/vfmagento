<?php
class Elite_Vaf_Model_SchemaTests_MMY_NextLevelsTest extends Elite_Vaf_TestCase
{
    function doSetUp()
    {
        $this->switchSchema('make,model,year');
    }
    
    function testNextLevelsYear()
    {
        $schema = new Elite_Vaf_Model_Schema(); 
        $this->assertEquals( array(), $schema->getNextLevels('year') );
    }
    
    function testNextLevelsModel()
    {
        $schema = new Elite_Vaf_Model_Schema(); 
        $this->assertEquals( array('year'), $schema->getNextLevels('model') );
    }
    
    function testNextLevelsMake()
    {
        $schema = new Elite_Vaf_Model_Schema(); 
        $this->assertEquals( array('model','year'), $schema->getNextLevels('make') );
    }
}