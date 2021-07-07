<?php

namespace App\Parser;

interface ParserInterface
{
    /**
     * Parse file entries to the array equivalent
     *
     * @param string $filepath
     * @return array
     */
    public function parse(string $filepath): array;
}