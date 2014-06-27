# Tarantula

Tarantula is a web crawler written in PHP. It utilizes the amazing work of the people behind Guzzle and Symfony's DOMCrawler.

## Installation (global)

Make sure `~/.composer/bin` is in your `$PATH` and then simply execute:

```bash
composer global require mihaeu/tarantula:1.*
```

## Installation (library)

Assuming you are using [Composer](http://getcomposer.org), add the following to your `composer.json` file:

```json
{
    "require": {
        "mihaeu/tarantula": "1.*"
    }
}
```

or use Composer's cli tool `composer require mihaeu/tarantula:1.*`.

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

## License

MIT, see `LICENSE` file.