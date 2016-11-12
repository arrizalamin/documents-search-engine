<?php

namespace App\TermWeighter;

use App\Document;

class TermFrequency
{
    private $documents;

    public function __construct(array $documents = [])
    {
        $this->documents = $documents;
    }

    public function termFrequencies() : array
    {
        return array_map(function(Document $document) {
            return [
                'title' => $document->getTitle(),
                'content' => $document->getContent(),
                'terms' => $this->normalizedTermFrequencies($document->getTerms()),
            ];
        }, $this->documents);
    }

    public function normalizedTermFrequencies(array $terms) : array
    {
        return array_map(function (string $term) use($terms) {
            $tf = count(array_keys($terms, $term));
            return [
                'term' => $term,
                'tf' => $tf / count($terms),
            ];
        }, array_unique($terms));
    }
    
    public function termFrequenciesFilter(array $query) : array
    {
        return array_reduce($this->termFrequencies(), function(array $carry, array $document) use($query) {
            $document['terms'] = array_map(function($q) use($document) {
                foreach ($document['terms'] as $term) {
                    if ($term['term'] == $q) {
                        return $term;
                    }
                }
                return [
                    'term' => $q,
                    'tf' => 0,
                ];
            }, $query);
            $carry[] = $document;
            return $carry;
        }, []);
    }
}