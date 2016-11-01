<?php

namespace Yab\Cerebrum;

use Carbon\Carbon;

trait Perception
{
    /**
     * A Regression based Aanylzer of data
     * @var Regression
     */
    protected $analyzer;

    protected $classifier;

    protected $normalizer;

    public function __construct()
    {
        $this->analyzer = new Regression();
        $this->classifier = new NaiveBayes();
        $this->normalizer = new Normalizer();
    }

    public function setClassifier($value='')
    {
        # code...
    }
}
