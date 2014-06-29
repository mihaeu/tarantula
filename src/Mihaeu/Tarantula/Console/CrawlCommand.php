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
                'mirror', null, InputOption::VALUE_OPTIONAL, 'Mirror the crawled files to a local directory.', sys_get_temp_dir()
            )
            ->addOption(
                'save-hashed', 's', InputOption::VALUE_OPTIONAL, 'Save crawled results using hashed filenames.', sys_get_temp_dir()
            )
            ->addOption(
                'minify-html', 'm', InputOption::VALUE_NONE, 'Minify HTML of the crawled results.'
            );
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

        // add actions
        // Order matters: actions that persist the result should be registered last
        if ($input->getOption('minify-html')) {
            $crawler->addAction(new MinifyHtmlAction());
        }
        if ($input->getOption('save-hashed')) {
            $crawler->addAction(new SaveHashedResultAction($input->getOption('save-hashed')));
        }

        $depth = $input->getOption('depth') ? $input->getOption('depth') : 1;
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
}