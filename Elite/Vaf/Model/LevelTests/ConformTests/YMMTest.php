<?php
class Elite_Vaf_Model_LevelsTests_ConformTests_YMMTest extends Elite_Vaf_TestCase
{
    protected function doSetUp()
    {
        $this->switchSchema('year,make,model');
    }
    
    function testConformsLevelMake()
    {
        return $this->markTestIncomplete();
        
        $honda = new Elite_Vaf_Model_Level( 'make' );
        $honda->setTitle('Honda');
        $honda->save();
        
        $honda2 = new Elite_Vaf_Model_Level( 'make' );
        $honda2->setTitle('Honda');
        $honda2->save();
        
        $this->assertEquals( $honda->getId(), $honda2->getId(), 'when saving two makes with same title, they should get the same id' );
    }     
}
