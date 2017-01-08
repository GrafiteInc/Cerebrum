<?php

namespace Yab\Cerebrum\Supervised\Regression;

class NeuralNetwork
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function addLayer(Closure $method)
    {
        $this->data = $method($this->data);

        return $this;
    }

    public function output()
    {
        return $this->data;
    }
}
