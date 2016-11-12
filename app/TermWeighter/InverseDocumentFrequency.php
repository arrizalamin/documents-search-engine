<?php

namespace App\TermWeighter;

use App\Document;

class InverseDocumentFrequency
{
    private $documents;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
    }

    public function calculateIdf() : array
    {
        return $this->withTerms($this->getUniqueTerms($this->documents));
    }

    public function withTerms(array $terms) : array
    {
        return array_reduce($terms, function($carry, $term) {
            $carry[$term] = $this->withTerm($term);
            return $carry;
        }, []);
    }

    public function withTerm(string $term) : float
    {
        $df = $this->countDocumentFrequency($term);
        return 1 + log( (count($this->documents) / $df) );
    }

    private function countDocumentFrequency(string $term) : int
    {
        return count(
            array_filter($this->documents, function (Document $document) use ($term) {
                return $document->hasTerm($term);
            })
        );
    }

    private function getUniqueTerms() : array
    {
        return array_unique(array_reduce($this->documents,
            function (array $terms, Document $document) {
                return array_merge($terms, $document->getTerms());
            }, []
        ));
    }

}