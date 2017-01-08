<?php

namespace Yab\Cerebrum\Utilities;

class Normalizer
{
    public $data;

    public function set($data)
    {
        $this->data = $data;

        return $this;
    }

    public function process($min, $max)
    {
        return ($this->data - $min) / ($max - $min);
    }

    public function denormalize($data, $min, $max)
    {
        return $data * ($max - $min) + $min;
    }

    public function getMinMax($data)
    {
        return [
            min($data),
            max($data),
        ];
    }
}
