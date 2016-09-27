<?php

namespace Yab\Cerebrum\Analysis\Tokenizers;

/**
 * Split strings by whitespaces
 *
 * @author yooper
 */
class Whitespace
{
    public function tokenize($string)
    {
        return mb_split('\s+', $string);
    }
}
