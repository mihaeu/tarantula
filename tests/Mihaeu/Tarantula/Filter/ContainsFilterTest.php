<?php

class ContainsFilterTest extends PHPUnit_Framework_TestCase
{
    public function testFiltersUrlsThatDoNotContainAString()
    {
        $filter = new Mihaeu\Tarantula\Filter\ContainsFilter('test');
        $this->assertTrue($filter->filter('http://test.com'));
        $this->assertTrue($filter->filter('http://example.com/test.php'));
        $this->assertTrue($filter->filter('http://example.com/test/more/image.jpg'));
        $this->assertFalse($filter->filter('http://google.com'));
    }
}
