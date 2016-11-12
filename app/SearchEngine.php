<?php

namespace App;

use App\CosineSimilarity\CosineSimilarity;
use App\Preprocessor\Preprocessor;
use App\TermWeighter\DocumentTermFrequency;

class SearchEngine
{
    private $preprocessor;
    private $documents;

    public function __construct(array $files)
    {
        $this->preprocessor = new Preprocessor();
        $this->documents = $this->readDocuments($files);
    }

    private function readDocuments($files) : array
    {
        return array_map(function($file) {
            $content = file_get_contents($file);
            return new Document(
                pathinfo($file, PATHINFO_FILENAME),
                $this->preprocessor->getTerms($content),
                $content
            );
        }, $files);
    }

    public function search(string $query) : array
    {
        $cosineSimilarity = new CosineSimilarity($this->documents);

        $query = new Document('query', $this->preprocessor->getTerms($query));
        $result = $cosineSimilarity->search($query);
        return $result;
    }
}