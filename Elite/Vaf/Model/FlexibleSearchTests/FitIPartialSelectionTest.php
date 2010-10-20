<?php
class Elite_Vaf_Model_FlexibleSearchTests_FitPartialSelectionTest extends Elite_Vaf_Helper_DataTestCase
{    
    function testShouldReturnDefinition()
    {
        $vehicle = $this->createMMY();
        $requestParams = array(
        	'make'=>$vehicle->getLevel('make')->getId(),
        	'model'=>'loading',
        	'year'=>'loading'
        );
        $helper = $this->getHelper( array(), $requestParams );
        
        $this->assertEquals( $vehicle->getLevel('make')->getId(), $helper->getFit()->getValue('make') );
        $this->assertFalse( $helper->getFit()->getValue('model') );
        $this->assertFalse( $helper->getFit()->getValue('year') );
    }
    
    function testShouldStoreInSession()
    {
        $_SESSION = array('make'=>null, 'model'=>null, 'year'=>null);
        $vehicle = $this->createMMY();
        $requestParams = array(
        	'make'=>$vehicle->getLevel('make')->getId(),
        	'model'=>'loading',
        	'year'=>'loading'
        );
        $helper = $this->getHelper( array(), $requestParams );
        $helper->storeFitInSession();
        
        $this->assertEquals( $vehicle->getLevel('make')->getId(), $_SESSION['make'] );
        $this->assertFalse( $_SESSION['model'] );
        $this->assertFalse( $_SESSION['year'] );
    }
        
    function testShouldOverwriteAFullSelection()
    {
        $vehicle = $this->createMMY();
        $_SESSION = $vehicle->toValueArray();
        $helper = $this->getHelper( array(), $vehicle->toValueArray() );
        $helper->storeFitInSession();
        
        $requestParams = array(
        	'make'=>$vehicle->getLevel('make')->getId(),
        	'model'=>'loading',
        	'year'=>'loading'
        );
        $helper = $this->getHelper( array(), $requestParams );
        $helper->storeFitInSession();
        
        $this->assertEquals( $vehicle->getLevel('make')->getId(), $_SESSION['make'] );
        $this->assertFalse( $_SESSION['model'] );
        $this->assertFalse( $_SESSION['year'] );
    }
    
    function testGetFitIdIncompleteWithALeafFirstRequestShouldReturn0()
    {
        $vehicle = $this->createMMY();
        $helper = $this->getHelper( array(), array('make'=>'loading', 'model'=>'loading', 'year'=>$vehicle->getLevel('year')->getId()) );
        $this->assertFalse( $helper->getFit() );
    }
}