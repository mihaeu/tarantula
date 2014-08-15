# [![Logo](https://cdn1.iconfinder.com/data/icons/desktop-halloween/48/Spider.png)](https://github.com/mihaeu/tarantula) Tarantula 

[![Build Status](https://travis-ci.org/mihaeu/tarantula.svg?branch=develop)](https://travis-ci.org/mihaeu/tarantula)
[![Coverage Status](https://coveralls.io/repos/mihaeu/tarantula/badge.png)](https://coveralls.io/r/mihaeu/tarantula)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4fbb06d2-4465-4ed9-9917-c9626dded801/mini.png)](https://insight.sensiolabs.com/projects/4fbb06d2-4465-4ed9-9917-c9626dded801)

Tarantula is a web crawler written in PHP. It utilizes the amazing work of the people behind Guzzle and Symfony's DomCrawler.

## Installation

### Global tool

Make sure `~/.composer/bin` is in your `$PATH` and then simply execute:

```bash
composer global require mihaeu/tarantula:1.*
```

### Library

Assuming you are using [Composer](http://getcomposer.org), add the following to your `composer.json` file:

```json
{
    "require": {
        "mihaeu/tarantula": "1.*"
    }
}
```

or use Composer's cli tool `composer require mihaeu/tarantula:1.*`.

## Usage

### Global tool

Right now the only command available is `crawl`. Some usage examples would be:

```bash
# most basic use case
tarantula crawl "http://google.com"

# go deeper
tarantula crawl "http://products.com/categories" --depth=4

# mirror
tarantula crawl "http://myblog.com" --mirror=/tmp/blog-backup

# filters
tarantula crawl "http://myblog.com" --contains=yolo
tarantula crawl "http://myblog.com" --regex="(post)\|(\d+)"

# dump crawled file in hashed files
tarantula crawl "http://myblog.com" --save-hashed=/tmp/blog-backup --minify-html

# HTTP basic auth
tarantula crawl "http://secure.com" --user=admin --password=admin

# search for "Avatar" on imdb
bin/tarantula crawl "http://www.imdb.com/find?q=avatar&s=all" --depth=0 --quiet --css=".findSection td.result_text"

# today's weather in seattle
bin/tarantula crawl --depth=0 "http://www.weather.com/weather/today/Seattle+WA+USWA0395:1:US" --css=".wx-first" | head -n 2
```

For all arguments and options use the `help` command:

```bash
tarantula help                    # displays all available commands
tarantula help crawl              # all arguments and options for the crawler
tarantula crawl "..." --verbose   # switch on debugging output
```

### Library

Have a look at the tests to see what's possible or just try the following in your code:

```php
use Mihaeu\Tarantula\Crawler;
use Mihaeu\Tarantula\HttpClient;

$crawler = new Crawler(new HttpClient('http://google.com'));
$links = $crawler->go(1);
```

All HTTP requests go through `Guzzle` and you can add any configuration for `Guzzle`'s request object also to Tarantula's `HttpClient`.

## Tests

Test coverage is not at 100%, the reason being that this was an afternoon project and testing a crawler takes a lot of time due to the testing setup.

If you want to get a quick overview of the project, I recommend running the test suite with the `--testdox` flag:

```bash
vendor/bin/phpunit --testdox
```

## To Do

 - [ ] filters (url, filetype, etc.)
 - [ ] allow for Guzzle to be configured via command line
 - [ ] more actions (save plain result, crawl via DOM/XPath, ...)

## Troubleshooting

### Composer global install fails

This is most likely due to a conflict with some requirements of other global installs. Unfortunately Composer's architecture doesn't offer a solution for this yet. I tried to keep the requirements Tarantula loose to avoid this problem.

If you want to have Tarantula available throughout your system, just install to another directory (e.g. using `composer create-project`) and symlink `bin/tarantula` into a folder in your `$PATH`.

## Thanks to

 - [Symfony](http://symfony.com/)/[SensioLabs](http://sensiolabs.com/en) and especially [Fabien Potencier](http://fabien.potencier.org/) for what he does for PHP (for this particular project the [DomCrawler](https://github.com/symfony/DomCrawler))
 - the [Guzzle](http://guzzlephp.org/) team for their awesome HTTP client
 - [Aha Soft](http://www.aha-soft.com/) for the logo
 - the [Composer](https://getcomposer.org/) team for revolutionizing the way I and many others write PHP
 - [GitHub](https://github.com) for redefining collaboration
 - [Travis CI](https://travis-ci.org/) for improving the quality and compatibility of thousands of open source projects
 - [Sebastian Bergmann](http://sebastian-bergmann.de/) for [PHPUnit](http://phpunit.de) and many other awesome QA tools

## License

MIT, see `LICENSE` file.