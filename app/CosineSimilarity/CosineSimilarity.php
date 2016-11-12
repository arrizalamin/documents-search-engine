<?php

namespace App\CosineSimilarity;

use App\Document;
use App\TermWeighter\InverseDocumentFrequency;
use App\TermWeighter\TermFrequency;
use App\TermWeighter\TfIdf;

class CosineSimilarity
{
    private $documents;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
    }

    public function search(Document $query) : array
    {
        $tf = $this->getTf($this->documents, $query);
        $tfidf = $this->getTfIdf($tf);

        $queryTf = $this->getTf([$query]);
        $queryTfIdf = $this->getTfIdf($queryTf);

        $cosineSimilarity = array_map(function(array $document) use($queryTfIdf) {
            return $this->calculateCosineSimilarity($document, $queryTfIdf[0]);
        }, $tfidf);
        usort($cosineSimilarity, function($a, $b) {
            return $b['cosine-similarity'] <=> $a['cosine-similarity'];
        });
        return $cosineSimilarity;
    }

    private function getTf(array $documents, Document $query = null) : array
    {
        $termFrequency = new TermFrequency($documents);
        if (is_null($query)) {
            return $termFrequency->termFrequencies();
        }
        $queryTerms = $query->getTerms();
        return $termFrequency->termFrequenciesFilter($queryTerms);
    }

    private function getTfIdf(array $tf) : array
    {
        $idf = (new InverseDocumentFrequency($this->documents))->calculateIdf();

        return (new TfIdf())->calculate($tf, $idf);
    }

    private function calculateCosineSimilarity($document, $queryTfIdf) : array
    {
        $dotProduct = $this->dotProduct($document['terms'], $queryTfIdf['terms']);
        $distanceDocument = $this->euclidianDistance($document['terms']);
        $distanceQuery = $this->euclidianDistance($queryTfIdf['terms']);
        $distanceDocumentAndQuery = $distanceDocument * $distanceQuery;
        if ($distanceDocumentAndQuery == 0) {
            $document['cosine-similarity'] = 0;
        } else {
            $document['cosine-similarity'] = $dotProduct / ($distanceDocument * $distanceQuery);
        }
        return $document;
    }

    private function dotProduct($terms, $queryTerms) : float
    {
        $sum = 0;
        for ($i = 0; $i < count($terms); $i++) {
            $sum += $terms[$i]['tf-idf'] * $queryTerms[$i]['tf-idf'];
        }
        return $sum;
    }

    private function euclidianDistance(array $terms) : float
    {
        $squaredEachTerm = 0;
        foreach ($terms as $term) {
            $squaredEachTerm += pow($term['tf-idf'], 2);
        }
        return sqrt($squaredEachTerm);
    }
}