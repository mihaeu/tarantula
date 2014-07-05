<?php

class RegexFilterTest extends PHPUnit_Framework_TestCase
{
    public function testFiltersUrlsThatDoNotMatchRegex()
    {
        $filter = new Mihaeu\Tarantula\Filter\RegexFilter('/^(https?:\/\/)?(www\.)?example\.com\/posts.*/');
        $this->assertFalse($filter->filter('http://test.com'));

        $this->assertTrue($filter->filter('https://example.com/posts/test.php'));
        $this->assertTrue($filter->filter('example.com/posts/1231'));
    }
}
