<?php

namespace App\Preprocessor;

use Sastrawi\Stemmer\StemmerFactory;

class Preprocessor
{
    protected $stemmer;

    public function __construct()
    {
        $factory = new StemmerFactory();
        $this->stemmer = $factory->createStemmer(true);
    }

    public function stem(string $words) : string
    {
        return $this->stemmer->stem($words);
    }

    public function getTerms(string $words) : array
    {
        return explode(' ', $this->stem($words));
    }
}