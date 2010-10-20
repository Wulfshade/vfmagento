<?php
class Elite_Vaf_Model_Level_FinderTests_ChildrenTest extends Elite_Vaf_TestCase
{
    
    function testFindChildren()
    {
        return $this->markTestIncomplete();
        //$vehicle1 = $this->createMMY('Make', 'Model1');
//        $vehicle2 = $this->createMMY('Make', 'Model2');
//        $make = new Elite_Vaf_Model_Level('make',$vehicle1->getValue('make'));
//        $children = $make->getChildren();
//        $children = $make->getChildren();
    }
    
    function testGetChildCountReturns0ForLeafLevel()
    {
        $entity = new Elite_Vaf_Model_Level('year');
        $this->assertSame( 0, $entity->getChildCount(), 'get child count should return 0 when no children have been inserted' );
    }
    
    /**
    * @expectedException Exception
    */
    function testGetChildrenThrowsExceptionForLeafLevel()
    {
        $year = new Elite_Vaf_Model_Level( 'year' );
        $year->getChildren();
    }
    
    function testGetChildren()
    {
        $vehicle = $this->createMMY();        
        $make = $this->findMakeById( $vehicle->getLevel('make')->getId() );
        $children = $make->getChildren();
        $this->assertTrue( $vehicle->getLevel('model')->getId()  == $children[0]->getId(), 'gets back the right make' );
        $this->assertEquals( 1, count($children), 'gets back only the right make' );
    }
    
    function testGetChildCount()
    {
        $vehicle = $this->createMMY();
        $this->assertSame( 1, $vehicle->getLevel('make')->getChildCount(), 'get child count should count the model we just inserted' );
    }
    
}
