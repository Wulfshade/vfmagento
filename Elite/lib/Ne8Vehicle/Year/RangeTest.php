<?php
class Ne8Vehicle_Year_RangeTest extends Elite_Vaf_TestCase
{
    function testShouldUseSingle4DigitYearForStartValue()
    {
        $range = new Ne8Vehicle_Year_Range('2004');
        $this->assertEquals( 2004, $range->start(), 'should use single 4 digit year as start year' );
    }
    
    function testShouldUseSingle4DigitYearForEndValue()
    {
        $range = new Ne8Vehicle_Year_Range('2004');
        $this->assertEquals( 2004, $range->end(), 'should use single 4 digit year as end year' );
    }
    
    function testSingle2DigitYearShouldBeValid()
    {
        $range = new Ne8Vehicle_Year_Range('04');
        $this->assertTrue($range->isValid(), 'single 2 digit year should be valid');
    }
    
    function testShouldUseSingle2DigitYearForStartValue()
    {
        $range = new Ne8Vehicle_Year_Range('04');
        $this->assertEquals( 2004, $range->start(), 'should use single 2 digit year as start year' );
    }
    
    function testShouldUseSingle2DigitYearForEndValue()
    {
        $range = new Ne8Vehicle_Year_Range('04');
        $this->assertEquals( 2004, $range->end(), 'should use single 2 digit year as end year' );
    }
    
    function testShouldBeTrimSpacesOnStartYear()
    {
        $range = new Ne8Vehicle_Year_Range(' 02 - 03 ');
        $this->assertEquals( '02', $range->startInput(), 'should trim spaces on start year' );
    }
    
    function testShouldBeTrimSpacesOnEndYear()
    {
        $range = new Ne8Vehicle_Year_Range(' 02 - 03 ');
        $this->assertEquals( '03', $range->endInput(), 'should trim spaces on end year' );
    }
    
    function testShouldGetStart4Digit()
    {
        $range = new Ne8Vehicle_Year_Range('2004-2005');
        $this->assertEquals( 2004, $range->start(), 'should get start year' );
    }
    
    function testShouldGetEnd4Digit()
    {
        $range = new Ne8Vehicle_Year_Range('2004-2005');
        $this->assertEquals( 2005, $range->end(), 'should get end year' );
    }
    
    function testShouldGetStart2Digit()
    {
        $range = new Ne8Vehicle_Year_Range('04-05');
        $this->assertEquals( 2004, $range->start(), 'should get start year' );
    }
    
    function testShouldGetEnd2Digit()
    {
        $range = new Ne8Vehicle_Year_Range('04-05');
        $this->assertEquals( 2005, $range->end(), 'should get end year' );
    }
    
    function testShouldUseStartWhenEndBlank()
    {
        $range = new Ne8Vehicle_Year_Range('04-');
        $this->assertEquals( 2004, $range->end(), 'should use start when end is blank' );
    }
    
    function testShouldUseEndWhenStartBlank()
    {
        $range = new Ne8Vehicle_Year_Range('-04');
        $this->assertEquals( 2004, $range->start(), 'should use end when start is blank' );
    }
    
    function testShouldUseCenturyThreshold_EndYear()
    {
        $range = new Ne8Vehicle_Year_Range('20-40');
        $range->setThreshold(41);
        $this->assertEquals( 2040, $range->end(), 'should use century threshold on end year' );
    }
    
    function testShouldUseCenturyThreshold_StartYear()
    {
        $range = new Ne8Vehicle_Year_Range('20-40');
        $range->setThreshold(21);
        $this->assertEquals( 2020, $range->start(), 'should use century threshold on start year' );
    }
}