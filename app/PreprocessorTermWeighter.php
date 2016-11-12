<?php

namespace App;

use App\Preprocessor\Preprocessor;
use App\TermWeighter\TermWeighter;

class PreprocessorTermWeighter
{
    private $preprocessor;

    public function __construct()
    {
        $this->preprocessor = new Preprocessor();
    }

    public function fromFiles(array $files) : array
    {
        $files = array_map(function($file) {
            return [
                'title' => pathinfo($file, PATHINFO_FILENAME),
                'content' => file_get_contents($file),
            ];
        }, $files);
        $preprocessed = $this->preprocess($files);
        $weighted = $this->weight($preprocessed);
        return $weighted;
    }

    public function preprocess(array $documents) : array
    {
        return array_map(function($document) {
            return [
                'title' => $document['title'],
                'terms' => $this->preprocessor->getTerms($document['content']),
            ];
        }, $documents);
    }

    public function weight(array $preprocessed) : array
    {
        $termWeighter = new TermWeighter($preprocessed);
        return $termWeighter->getTfIdf();
    }
}