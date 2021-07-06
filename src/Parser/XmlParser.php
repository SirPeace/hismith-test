<?php

namespace App\Parser;

class XmlParser implements ParserInterface
{
    public function parse(string $file): array
    {
        $xmlRoot = simplexml_load_file($file);
        $data = [];

        $i = 0;
        foreach($xmlRoot->channel->item as $item) {
            $data[$i]['title'] = (string) $item->title;
            $data[$i]['description'] = (string) $item->description;
            $data[$i]['link'] = (string) $item->link;
            $data[$i]['pubDate'] = (string) $item->pubDate;
            $data[$i]['author'] = (string) $item->author;

            $j = 0;
            foreach ($item->enclosure as $enclosure) {
                $data[$i]['enclosures'][$j]['url'] = (string) $enclosure['url'];
                $data[$i]['enclosures'][$j]['type'] = (string) $enclosure['type'];
                
                $j++;
            }

            $i++;
        }

        return $data;
    }
}