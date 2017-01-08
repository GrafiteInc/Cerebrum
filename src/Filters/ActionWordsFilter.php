<?php

namespace Yab\Cerebrum\Filters;

class ActionWordsFilter
{
    protected $language;

    protected $version;

    public $actionWords = [];

    /**
     * Populate the actionWords array.
     *
     * @param string $language
     */
    public function __construct($language = 'english')
    {
        $this->language = $language;
        $this->version = 1;

        $fileName = 'action-words_'.$this->language.'_'.$this->version.'.txt';

        $path = __DIR__.'/../../data/'.basename($fileName);

        if (file_exists($path)) {
            $this->actionWords = array_map('trim', file($path));
        }
    }

    /**
     * Check if the action word is in the list.
     *
     * @param string $word
     */
    public function filter($word)
    {
        if (in_array(strtolower($word), $this->actionWords)) {
            return $word;
        }

        return null;
    }
}
