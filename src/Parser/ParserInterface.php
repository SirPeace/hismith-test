<?php

namespace App\Parser;

interface ParserInterface
{
    /**
     * Parse file entries to the array equivalent
     *
     * @param string $file
     * @return array
     */
    public function parse(string $file): array;
}