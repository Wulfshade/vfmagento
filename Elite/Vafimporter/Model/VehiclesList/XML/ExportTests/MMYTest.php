<?php
class Elite_Vafimporter_Model_VehiclesList_XML_MMYTest extends Elite_Vafimporter_Model_VehiclesList_XML_TestCase
{
    protected $csvData;
    protected $csvFile;

    function doSetUp()
    {
        $this->csvData = '<?xml version="1.0" encoding="UTF-8"?>   
<vehicles>
    <definition>
        <make id="4">Honda</make>
        <model id="5">Civic</model>
        <year id="8">2000</year>
    </definition>        
</vehicles>';
        $this->csvFile = TESTFILES . '/definitions.xml';
        file_put_contents( $this->csvFile, $this->csvData );
        
        $this->switchSchema('make,model,year');
        
        $importer = $this->getDefinitions( $this->csvFile );
        $importer->import();
    }
    
    function testImportsMakeTitle()
    {
        $exporter = new Elite_Vafimporter_Model_VehiclesList_XML_Export;

        $this->assertEquals( '<?xml version="1.0"?>
<vehicles version="1.0">
    <definition>
        <make id="4">Honda</make>
        <model id="5">Civic</model>
        <year id="8">2000</year>
    </definition>
</vehicles>', $exporter->export() );
    }
    
}
