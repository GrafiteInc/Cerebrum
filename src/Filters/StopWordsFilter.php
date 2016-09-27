<?php

namespace Yab\Cerebrum\Filters;

class StopWordFilter
{
    protected $language;

    protected $version;

    public $stopWords = [];

    /**
     * Populate the stopWords array
     *
     * @param string $language
     */
    public function __construct($language = 'english')
    {
        $this->language = $language;
        $this->version = 1;

        $fileName = 'stop-words_'.$this->language.'_'.$this->version.'.txt';

        $path = realpath(dirname(__DIR__).'/../../data/'.basename($fileName));

        if (file_exists($path)) {
            $this->stopWords =  array_map('trim', file($path));
        }
    }

    /**
     * Check if the stop word is in the list
     *
     * @param string $token
     */
    public function transform($token)
    {
        if (isset($this->stopWords[$token])) {
            return null;
        }

        return $token;
    }
}
