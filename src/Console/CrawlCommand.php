<?php

namespace Mihaeu\Tarantula\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Mihaeu\Tarantula\Crawler;
use Mihaeu\Tarantula\HttpClient;

use Mihaeu\Tarantula\Action\SaveHashedResultAction;
use Mihaeu\Tarantula\Action\MinifyHtmlAction;
use Mihaeu\Tarantula\Action\MirrorResultAction;
use Mihaeu\Tarantula\Action\XPathTextAction;
use Mihaeu\Tarantula\Action\CssTextAction;

use Mihaeu\Tarantula\Filter\ContainsFilter;
use Mihaeu\Tarantula\Filter\ContainsNotFilter;
use Mihaeu\Tarantula\Filter\RegexFilter;

class CrawlCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('crawl')
            ->setDescription('Crawls a website and applies actions for each hit.')
            ->addArgument(
                'url', InputArgument::REQUIRED, 'The starting point for the crawling process.'
            )
            ->addOption(
                'depth', 'd', InputOption::VALUE_OPTIONAL, 'How deep shall we go? (-1 = infinite)', 1
            )
            ->addOption(
                'user', 'u',  InputOption::VALUE_OPTIONAL, 'Username for HTTP basic auth.'
            )
            ->addOption(
                'password', 'p', InputOption::VALUE_OPTIONAL, 'Password for HTTP basic auth.'
            )
            ->addOption(
                'mirror', null, InputOption::VALUE_REQUIRED, 'Mirror the crawled files to a local directory.'
            )
            ->addOption(
                'save-hashed', 's', InputOption::VALUE_REQUIRED, 'Save crawled results using hashed filenames.'
            )
            ->addOption(
                'minify-html', 'm', InputOption::VALUE_NONE, 'Minify HTML of the crawled results.'
            )
            ->addOption(
                'contains', null, InputOption::VALUE_REQUIRED, 'Accept only URLs that contain the string.'
            )
            ->addOption(
                'contains-not', null, InputOption::VALUE_REQUIRED, 'Accept only URLs that don\'t contain the string.'
            )
            ->addOption(
                'regex', null, InputOption::VALUE_REQUIRED, 'Accept only URLs that match the regular expression. Remember to escape shell characters.'
            )
            ->addOption(
                'xpath', null, InputOption::VALUE_REQUIRED, 'Search for a XPath expression and print the matching text.'
            )
            ->addOption(
                'css', null, InputOption::VALUE_REQUIRED, 'Search for a css path and print the matching text.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // set up client
        $client = new HttpClient($input->getArgument('url'));
        if ($input->getOption('user')) {
            $client->setAuth($input->getOption('user'), $input->getOption('password'));
        }

        // set up crawler
        $crawler = new Crawler($client);
        $this->addFilters($input, $crawler);
        $this->addActions($input, $crawler);

        $depth = (int) $input->getOption('depth');
        $links = $crawler->go($depth);

        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $output->writeln('---------------------------------------------');
            foreach ($links as $hash => $link) {
                $output->writeln(sprintf('Found <info>%s</info>', $link));
            }
            $output->writeln('---------------------------------------------');
        }

        $output->writeln(sprintf('Links found <info>%s</info> (depth: %d)', count($links), $depth));
    }

    /**
     * @param InputInterface $input
     * @param Crawler $crawler
     */
    private function addFilters(InputInterface $input, Crawler $crawler)
    {
        if ($input->getOption('contains')) {
            $crawler->addFilter(new ContainsFilter($input->getOption('contains')));
        }
        if ($input->getOption('contains-not')) {
            $crawler->addFilter(new ContainsNotFilter($input->getOption('contains-not')));
        }
        if ($input->getOption('regex')) {
            $crawler->addFilter(new RegexFilter($input->getOption('regex')));
        }
    }

    /**
     * NOTE: Actions that persist the result should be registered last.
     * NOTE: Order matters.
     *
     * @param InputInterface $input
     * @param Crawler $crawler
     */
    private function addActions(InputInterface $input, Crawler $crawler)
    {
        if ($input->getOption('minify-html')) {
            $crawler->addAction(new MinifyHtmlAction());
        }
        if ($input->getOption('save-hashed')) {
            $crawler->addAction(new SaveHashedResultAction($input->getOption('save-hashed')));
        }
        if ($input->getOption('mirror')) {
            $crawler->addAction(new MirrorResultAction($input->getOption('mirror')));
        }
        if ($input->getOption('xpath')) {
            $crawler->addAction(new XPathTextAction($input->getOption('xpath')));
        }
        if ($input->getOption('css')) {
            $crawler->addAction(new CssTextAction($input->getOption('css')));
        }
    }
}
