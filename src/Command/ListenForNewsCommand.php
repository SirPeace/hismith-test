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
            ->setDescription(
                'Check specified resource for news updates on given interval'
            )
            ->addArgument(
                'interval',
                InputArgument::OPTIONAL,
                'Interval on which news updates should be requested',
                10
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $loop = \React\EventLoop\Factory::create();

        $loop->addPeriodicTimer(
            $input->getArgument('interval'), 
            function () use ($io) {
                $this->listenForNews($io);
            }
        );

        $loop->run();
    }

    public function listenForNews(SymfonyStyle $io): void
    {
        $response = $this->http->request(
            'GET', 
            'http://static.feed.rbc.ru/rbc/logical/footer/news.rss'
        );

        // Upload response into the file, parse it, and delete.
        file_put_contents('news.rss', $response->toStream());
        $data = $this->parser->parse('news.rss');
        unlink('news.rss');

        $persistedRecordsCount = $this->databaseMapper->persist($data);

        if ($persistedRecordsCount > 0) {
            $io->success(
                "Successfuly stored $persistedRecordsCount new records!"
            );
        } else {
            $io->writeln("No new records were stored");
        }
    }
}
