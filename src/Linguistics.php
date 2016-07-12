<?php

namespace Yab\Cerebrum;

use TextAnalysis\Tokenizers\SentenceTokenizer;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use TextAnalysis\Tokenizers\PennTreeBankTokenizer;
use TextAnalysis\Stemmers\LancasterStemmer;

trait Linguistics
{
    public function getKeywords($string)
    {
        $minWordLen = 3;
        $minWordOccurrences = 2;
        $maxWords = 8;
        $restrict = false;

        $str = str_replace(array("?","!",";","(",")",":","[","]"), " ", $string);
        $str = str_replace(array("\n","\r","  "), " ", $str);
        strtolower($str);

        $str = preg_replace('/[^\p{L}0-9 ]/', ' ', $str);
        $str = trim(preg_replace('/\s+/', ' ', $str));

        $words = explode(' ', $str);

        /*
        Only compare to common words if $restrict is set to false
        Tags are returned based on any word in text
        If we don't restrict tag usage, we'll remove common words from array
        */

        if ($restrict == false) {
            /* Full list of common words in the downloadable code */
            $commonWords = array('a','able','about','above','abroad','according');
            $words = array_udiff($words, $commonWords,'strcasecmp');
        }

        /* Restrict Keywords based on values in the $allowedWords array */
        /* Use if you want to limit available tags */
        if ($restrict == true) {
            $allowedWords =  array('engine','boeing','electrical','pneumatic','ice');
            $words = array_uintersect($words, $allowedWords,'strcasecmp');
        }

        $keywords = array();

        while(($c_word = array_shift($words)) !== null) {
        if(strlen($c_word) < $minWordLen) continue;

        $c_word = strtolower($c_word);
        if(array_key_exists($c_word, $keywords)) $keywords[$c_word][1]++;
        else $keywords[$c_word] = array($c_word, 1);
        }

        usort($keywords, [$this, 'keywordCountSort']);
        $final_keywords = array();

        foreach($keywords as $keyword_det) {
            if($keyword_det[1] < $minWordOccurrences) break;
            array_push($final_keywords, $keyword_det[0]);
        }

        $final_keywords = array_slice($final_keywords, 0, $maxWords);

        return $final_keywords;

// /* Usage */
// $str = "Many systems that traditionally had a reliance on the pneumatic system have been transitioned to the electrical architecture. They include engine start, API start, wing ice protection, hydraulic pumps and cabin pressurisation. The only remaining bleed system on the 787 is the anti-ice system for the engine inlets. In fact, Boeing claims that the move to electrical systems has reduced the load on engines (from pneumatic hungry systems) by up to 35 percent (not unlike today's electrically power flight simulators that use 20% of the electricity consumed by the older hydraulically actuated flight sims).";
// echo extract_keywords($str, $minWordLen = 3, $minWordOccurrences = 2, $asArray = false, $maxWords = 8, $restrict = false)
    }

    private function keywordCountSort($first, $sec)
    {
        return $sec[1] - $first[1];
    }

    public function getHashtags($string)
    {
        return $string;
    }

    public function getSentences($string)
    {

    }

    public function hasConfirmed($string)
    {

    }

    public function getWords($string)
    {

    }

    public function isQuestion($string)
    {

    }

    public function isCompleteSentence($string)
    {

    }

    public function getAttitude($string)
    {

    }

    public function getStem($string)
    {

    }

    public function isPositive($string)
    {

    }

    public function isNegative($string)
    {

    }

    public function containsProfanity($string)
    {

    }
}
