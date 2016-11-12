<?php

namespace App\TermWeighter;

class TfIdf
{
    public function calculate(array $tf, $idf) : array
    {
        return array_map(function($document) use($idf) {
            $document['terms'] = array_map(function ($term) use($idf) {
                $term['idf'] = isset( $idf[$term['term']] ) ? $idf[$term['term']] : 0;
                $term['tf-idf'] = $term['tf'] * $term['idf'];
                return $term;
            }, $document['terms']);
            return $document;
        }, $tf);
    }
}