<?php

namespace App\Command;

use App\DatabaseMapper\DatabaseMapper;
use App\Entity\Enclosure;
use App\Entity\News;
use App\Parser\ParserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ListenForNewsCommand extends Command
{
    protected static $defaultName = 'app:listen-for-news';
    protected const NEWS_REQUEST_INTERVAL = 3600; // in seconds

    public function __construct(
        private HttpClientInterface $http,
        private EntityManagerInterface $entityManager,
        private ParserInterface $parser,
        private DatabaseMapper $databaseMapper,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('Listen for news')
            ->setDescription('Check specified resource for news updates on interval');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->http->request(
            'GET', 
            'http://static.feed.rbc.ru/rbc/logical/footer/news.rss'
        );

        file_put_contents('news.rss', $response->toStream());
        $data = $this->parser->parse('news.rss');
        unlink('news.rss');

        $persistedRecordsCount = $this->databaseMapper->persist($data);

        if ($persistedRecordsCount > 0) {
            $io->success("Successfuly stored $persistedRecordsCount new records!");
        } else {
            $io->writeln("No new records were stored");
        }

        return Command::SUCCESS;
    }
}
