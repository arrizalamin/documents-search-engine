<?php

namespace App;

class Document
{
    private $title;
    private $terms;
    private $content;

    public function __construct(string $title, array $terms, $content = '')
    {
        $this->title = $title;
        $this->terms = $terms;
        $this->content = $content;
    }

    public function hasTerm(string $term) : bool
    {
        return in_array($term, $this->terms);
    }

    public function getTerms() : array
    {
        return $this->terms;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getContent() : string
    {
        return $this->content;
    }

}