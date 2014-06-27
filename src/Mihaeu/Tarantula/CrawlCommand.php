<?php

namespace Mihaeu\Tarantula;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Mihaeu\Tarantula\Crawler;
use Mihaeu\Tarantula\HttpClient;

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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new HttpClient($input->getArgument('url'));
        if ($input->getOption('user')) {
            $client->setAuth($input->getOption('user'), $input->getOption('password'));
        }
        $crawler = new Crawler($client);

        $depth = $input->getOption('depth') ? $input->getOption('depth') : 1;
        $links = $crawler->go($depth);

        foreach ($links as $link) {
            $output->writeln(sprintf('Found <info>%s</info>', $link['target']));
        }
        $output->writeln(sprintf(
            '------------------------------'.PHP_EOL
            .'Links crawled <info>%s</info>', count($links))
        );
    }
}