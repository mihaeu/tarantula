<?php

namespace Mihaeu\Tarantula\Tests\Filter;

use Mihaeu\Tarantula\Tests\BaseUnitTest;
use Mihaeu\Tarantula\Filter\ContainsNotFilter;

class ContainsNotFilterTest extends BaseUnitTest
{
    public function testFiltersUrlsThatContainAString()
    {
        $filter = new ContainsNotFilter('test');
        $this->assertFalse($filter->filter('http://test.com'));
        $this->assertFalse($filter->filter('http://example.com/test.php'));
        $this->assertFalse($filter->filter('http://example.com/test/more/image.jpg'));
        $this->assertTrue($filter->filter('http://google.com'));
    }
}
