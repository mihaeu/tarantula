<?php

class RegexFilterTest extends PHPUnit_Framework_TestCase
{
    public function testFiltersUrlsThatDoNotMatchRegex()
    {
        $filter = new Mihaeu\Tarantula\Filter\RegexFilter('/^(https?:\/\/)?(www\.)?example\.com\/posts.*/');
        $this->assertFalse($filter->filter('http://test.com'));

        $this->assertTrue($filter->filter('https://example.com/posts/test.php'));
        $this->assertTrue($filter->filter('example.com/posts/1231'));

        $filter = new Mihaeu\Tarantula\Filter\RegexFilter('/(part)|(\d+)/');
        $this->assertTrue($filter->filter('http://mike-on-a-bike.com/catching-up-part-3'));
        $this->assertTrue($filter->filter('http://mike-on-a-bike.com/100-days-on-the-road'));
    }
}
