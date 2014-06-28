<?php

use Mihaeu\Tarantula\Action\MinifyHtmlAction;
use Mihaeu\Tarantula\Result;

class MinifyHtmlActionTest extends PHPUnit_Framework_TestCase
{
    public function testStripsLineEndingsAndOtherWhitespaces()
    {
        $result = new Result('', '', $this->originalHtml);
        $expectedHtml = '<section><h1>Example of paragraphs</h1>This is the<em>first</em>paragraph in this example.<p>This is the second.</p><!--[if expression]> HTML <![endif]--><![if expression]>HTML<![endif]></section>';
        $action = new MinifyHtmlAction();
        $processedResult = $action->execute($result);
        $this->assertEquals($expectedHtml, $processedResult->getData());
    }

    private $originalHtml = '<section>
        <h1>Example of paragraphs</h1>
        This is the <em>first</em> paragraph in this example.
        <p>
            This is the second.
        </p>
        <!-- This is not a paragraph. -->
        <!--[if expression]> HTML <![endif]-->
        <![if expression]> HTML <![endif]>
    </section>';
}
