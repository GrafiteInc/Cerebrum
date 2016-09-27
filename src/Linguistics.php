<?php

namespace Yab\Cerebrum;

use Yab\Cerebrum\Analysis\FrequencyAnalysis;
use Yab\Cerebrum\Filters\StopWordsFilter;
use Yab\Cerebrum\Stemmers\LancasterStemmer;
use Yab\Cerebrum\Analysis\Tokenizers\General;
use Yab\Cerebrum\Analysis\Tokenizers\Sentence;
use Yab\Cerebrum\Analysis\Tokenizers\Whitespace;

trait Linguistics
{
    /**
     * Inquiry Words
     * @var array
     */
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

    /**
     * Confirmation words
     * @var array
     */
    protected $confirmationWords = [
        'confirm',
        'can',
        'agree',
        'yes',
        'continue',
        'like',
        'want',
    ];

    /**
     * Denial words
     * @var array
     */
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

    /**
     * Get words in a string.
     *
     * @param  string $string
     * @param  integer $minLength
     * @return array
     */
    public function getWords($string, $minLength = null)
    {
        $tokenizer = new Whitespace();
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

    /**
     * Get keywords
     *
     * @param  string  $string
     * @param  integer $amount
     * @return array
     */
    public function getKeywords($string, $amount = 10)
    {
        $words = $this->getWords($string);

        $analysis = new FrequencyAnalysis($words);

        $keywords = $analysis->getKeyValuesByFrequency();

        return array_slice($keywords, 0, $amount);
    }

    /**
     * get hashtags.
     *
     * @param  string $string
     * @return array
     */
    public function getHashtags($string)
    {
        preg_match_all("/(#\w+)/", $string, $matches);
        return $matches[0];
    }

    /**
     * Get sentences
     *
     * @param  string $string
     * @return array
     */
    public function getSentences($string)
    {
        $tokenizer = new Sentence();
        return $tokenizer->tokenize($string);
    }

    /**
     * Get unique words
     *
     * @param  string $string
     * @return array
     */
    public function getUniqueWords($string)
    {
        $words = $this->getWords($string);
        $analysis = new FrequencyAnalysis($words);
        $words = $analysis->getKeyValuesByFrequency();

        return array_unique(array_keys($words));
    }

    /**
     * Get words by complexity
     *
     * @param  string $string
     * @return array
     */
    public function getWordsByComplexity($string)
    {
        $words = $this->getWords($string);
        $analysis = new FrequencyAnalysis($words);
        $sortedWords = $analysis->getKeyValuesByFrequency();
        $wordsByFrequency = array_unique(array_keys($sortedWords));

        usort($wordsByFrequency, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        return $wordsByFrequency;
    }

    /**
     * Get stop words
     *
     * @param  string $string
     * @return array
     */
    public function getStopWords($string, $language = 'english')
    {
        $words = $this->getWords($string);
        $filter = new StopWordsFilter($language);
        $stopWords = [];

        foreach ($words as $word) {
            if ($filter->transform($word)) {
                $stopWords[] = $word;
            }
        }

        return $stopWords;
    }

    /**
     * Get a words stem
     *
     * @param  string $string
     * @return string
     */
    public function getStem($string)
    {
        // $stemmer = new LancasterStemmer();
        // return $stemmer->stem($string);
    }

    /**
     * Remove all punctuation
     *
     * @param  string $string
     * @return string
     */
    public function removePunctuation($string)
    {
        return trim(preg_replace("/[^0-9a-z]+/i", " ", $string));
    }

    /**
     * Has confirmation
     *
     * @param  string  $string
     * @return boolean
     */
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

    /**
     * Has denial content
     *
     * @param  string  $string
     * @return boolean
     */
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

    /**
     * Checks if string has a url
     *
     * @param  string  $string
     * @return boolean
     */
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

    /**
     * Check if string has email
     *
     * @param  string  $string
     * @return boolean
     */
    public function hasEmail($string)
    {
        $result = false;
        $tokenizer = new General();
        $words = $tokenizer->tokenize($string);

        foreach ($words as $word) {
            if (filter_var($word, FILTER_VALIDATE_EMAIL)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Check if string is question
     *
     * @param  string  $string
     * @return boolean
     */
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
