<?php

class ContainsNotFilterTest extends PHPUnit_Framework_TestCase
{
    public function testFiltersUrlsThatContainAString()
    {
        $filter = new Mihaeu\Tarantula\Filter\ContainsNotFilter('test');
        $this->assertFalse($filter->filter('http://test.com'));
        $this->assertFalse($filter->filter('http://example.com/test.php'));
        $this->assertFalse($filter->filter('http://example.com/test/more/image.jpg'));
        $this->assertTrue($filter->filter('http://google.com'));
    }
}
