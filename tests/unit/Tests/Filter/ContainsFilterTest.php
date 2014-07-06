<?php

namespace Mihaeu\Tarantula\Tests\Filter;

use Mihaeu\Tarantula\Tests\BaseUnitTest;
use Mihaeu\Tarantula\Filter\ContainsFilter;

class ContainsFilterTest extends BaseUnitTest
{
    public function testFiltersUrlsThatDoNotContainAString()
    {
        $filter = new ContainsFilter('test');
        $this->assertTrue($filter->filter('http://test.com'));
        $this->assertTrue($filter->filter('http://example.com/test.php'));
        $this->assertTrue($filter->filter('http://example.com/test/more/image.jpg'));
        $this->assertFalse($filter->filter('http://google.com'));
    }
}
