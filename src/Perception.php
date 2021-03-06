<?php

namespace Yab\Cerebrum;

use Yab\Cerebrum\Supervised\Classification\NaiveBayes;
use Yab\Cerebrum\Utilities\Normalizer;
use Yab\Cerebrum\Utilities\ArtificialIntelligence;

trait Perception
{
    protected $classifier;

    protected $normalizer;

    private $classifiers = [
        'NaiveBayes',
    ];

    public function setNormalizer()
    {
        $this->normalizer = new Normalizer();
    }

    public function setClassifier($algorithm)
    {
        if (!in_array($algorithm, $this->classifiers)) {
            throw new Exception('Must be one of: '.implode($this->classifiers), 1);
        }

        $class = 'Yab\Cerebrum\Supervised\Classification\\'.$algorithm;

        $this->classifier = new $class();
    }

    public function supervised($algorithm = 'NaiveBayes')
    {
        $this->setClassifier($algorithm);

        return $this->classifier;
    }

    public function ai()
    {
        return new ArtificialIntelligence();
    }

    public function normalize($data)
    {
        $this->setNormalizer();

        $normalizedData = collect();

        list($min, $max) = $this->normalizer->getMinMax($data);

        foreach ($data as $item) {
            $normalizedData->push($this->normalizer->set($item)->process($min, $max));
        }

        return $normalizedData;
    }
}
