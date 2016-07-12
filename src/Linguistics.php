<?php

namespace Yab\Cerebrum;

use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Filters\EnglishStopWordsFilter;
use TextAnalysis\Stemmers\LancasterStemmer;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Tokenizers\SentenceTokenizer;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;

trait Linguistics
{
    protected $inquiryWords = [
        'who',
        'what',
        'when',
        'where',
        'why',
        'how',
        'can',
        'could',
        'would',
        'should',
        'is',
        'might',
    ];

    protected $confirmationWords = [
        'confirm',
        'can',
        'agree',
        'yes',
        'continue',
        'like',
        'want',
    ];

    protected $denialWords = [
        'stop',
        'no',
        'end',
        'can\'t',
        'can\'t want',
        'can\'t like',
        'can\'t continue',
        'can\'t confirm',
        'can\'t agree',
        'don\'t',
        'don\'t want',
        'don\'t like',
        'don\'t continue',
        'don\'t confirm',
        'don\'t agree',
        'won\'t',
        'won\'t want',
        'won\'t like',
        'won\'t continue',
        'won\'t confirm',
        'won\'t agree',
        'not',
    ];

    public function getWords($string, $minLength = null)
    {
        $tokenizer = new WhitespaceTokenizer();
        $words = $tokenizer->tokenize($string);

        if (! is_null($minLength)) {
            foreach ($words as $key => $word) {
                if (strlen($word) <= $minLength) {
                    unset($words[$key]);
                }
            }
        }

        return array_values($words);
    }

    public function getKeywords($string, $amount = 10)
    {
        $words = $this->getWords($string);
        $analysis = new FreqDist($words);

        $keywords = $analysis->getKeyValuesByFrequency();

        return array_slice($keywords, 0, $amount);
    }

    public function getHashtags($string)
    {
        preg_match_all("/(#\w+)/", $string, $matches);
        return $matches[0];
    }

    public function getSentences($string)
    {
        $tokenizer = new SentenceTokenizer();
        return $tokenizer->tokenize($string);
    }

    public function getUniqueWords($string)
    {
        $words = $this->getWords($string);
        $analysis = new FreqDist($words);
        $words = $analysis->getKeyValuesByFrequency();

        return array_unique(array_keys($words));
    }

    public function getWordsByComplexity($string)
    {
        $words = $this->getWords($string);
        $analysis = new FreqDist($words);
        $sortedWords = $analysis->getKeyValuesByFrequency();
        $wordsByFrequency = array_unique(array_keys($sortedWords));

        usort($wordsByFrequency, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        return $wordsByFrequency;
    }

    public function getStopWords($string)
    {
        $words = $this->getWords($string);
        $filter = new EnglishStopWordsFilter();
        $stopWords = [];

        foreach ($words as $word) {
            if ($filter->transform($word)) {
                $stopWords[] = $word;
            }
        }

        return $stopWords;
    }

    public function getStem($string)
    {
        $stemmer = new LancasterStemmer();
        return $stemmer->stem($string);
    }

    public function removePunctuation($string)
    {
        return trim(preg_replace("/[^0-9a-z]+/i", " ", $string));
    }

    public function hasConfirmation($string)
    {
        $result = false;
        $words = $this->getWords($string);

        foreach ($words as $word) {
            if (in_array($word, $this->confirmationWords)) {
                $result = true;
            }
        }

        return $result;
    }

    public function hasDenial($string)
    {
        $result = false;
        $words = $this->getWords($string);

        foreach ($words as $word) {
            if (in_array($word, $this->denialWords)) {
                $result = true;
            }
        }

        return $result;
    }

    public function hasUrl($string)
    {
        $result = false;
        $words = $this->getWords($string);

        foreach ($words as $word) {
            if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $word)) {
                $result = true;
            }
        }

        return $result;
    }

    public function hasEmail($string)
    {
        $result = false;
        $tokenizer = new GeneralTokenizer();
        $words = $tokenizer->tokenize($string);

        foreach ($words as $word) {
            if (filter_var($word, FILTER_VALIDATE_EMAIL)) {
                $result = true;
            }
        }

        return $result;
    }

    public function isQuestion($string)
    {
        $probability = 0;

        if (strpos($string, '?')) {
            $probability += 1;
        }

        $words = $this->getWords($string);

        foreach ($this->inquiryWords as $queryWord) {
            if (!strncmp(strtolower($string), $queryWord, strlen($queryWord))) {
                $probability += 1;
            } elseif (stristr(strtolower($string), $queryWord)) {
                $probability += 0.5;
            }
        }

        if ($probability >= 2) {
            return true;
        }

        return false;
    }
}
