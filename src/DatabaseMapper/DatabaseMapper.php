<?php

namespace App\DatabaseMapper;

use App\Entity\News;
use App\Entity\Enclosure;
use App\Repository\EnclosureRepository;
use App\Repository\NewsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseMapper
{
    private array $data;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private NewsRepository $newsRepository,
        private EnclosureRepository $enclosureRepository,
    ) {}

    public function persist(array $data)
    {
        $this->data = $data;
        $newRecords = $this->checkNewRecords($data);

        foreach ($newRecords as $record) {
            $this->addNews($record);
        }

        return count($newRecords);
    }

    private function addNews(array $record)
    {
        $news = new News();
        $news->setTitle($record['title']);
        $news->setLink($record['link']);
        $news->setDescription($record['description']);
        $news->setPubDate(new DateTimeImmutable($record['pubDate']));
        $news->setAuthor($record['author']);

        $this->addEnclosuresToNews($news, $record);

        $this->entityManager->persist($news);
        $this->entityManager->flush();
    }

    private function addEnclosuresToNews(News $news, array $record)
    {
        $enclosuresCollection = $this->enclosureRepository->findAll();
        $enclosureUrlsCollection = array_map(
            fn ($enc) => $enc->getUrl(), 
            $enclosuresCollection
        );

        if (array_key_exists('enclosures', $record)) {
            foreach ($record['enclosures'] as $enclosure) {
                if (in_array($enclosure['url'], $enclosureUrlsCollection)) {
                    continue;
                }
    
                $entity = new Enclosure;
                $entity->setType($enclosure['type']);
                $entity->setUrl($enclosure['url']);
                $news->addEnclosure($entity);
            }
        }
    }

    private function checkNewRecords(): array
    {
        $newsCollection = $this->newsRepository->findBy(
            [], 
            ['pubDate' => 'DESC']
        );
        $newsLinks = array_map(fn ($news) => $news->getLink(), $newsCollection);

        $newRecords = [];

        foreach ($this->data as $record) {
            if (!in_array($record['link'], $newsLinks)) {
                $newRecords[] = $record;
            } else {
                break;
            }
        }

        return $newRecords;
    }
}