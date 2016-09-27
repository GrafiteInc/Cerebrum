<?php

namespace Yab\Cerebrum\Analysis\Tokenizers;

use Yab\Cerebrum\Analysis\Tokenizers\General;

/**
 * Split strings by sentances
 *
 * @author yooper
 */
class Sentence extends General
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(".?!");
    }
}
