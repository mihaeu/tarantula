<?php

namespace Mihaeu\Tarantula\Tests\Action;

use Mihaeu\Tarantula\Action\XPathTextAction;
use Mihaeu\Tarantula\Result;
use Mihaeu\Tarantula\Tests\BaseUnitTest;

class XPathTextActionTest extends BaseUnitTest
{
    public function testFiltersAllLinks()
    {
        $action = new XPathTextAction('//a[@href]');

        // 4 anchors!
        $html = <<<EOT
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h1><a href="/test">link</a></h1>
    <header></header>
    <main>
        <a href="back">back</a>
        <a href="http://google.com/gmail">back</a>
        <a href="http://test2">FOREIGN URL SHOULD BE IGNORED</a>
    </main>
</body>
</html>
EOT;
        $result = new Result('0123', 'http://fb.com', $html);

        ob_start();
        $action->execute($result);
        $output = trim(ob_get_clean());
        $this->assertCount(4, explode(PHP_EOL, $output));
    }
}
