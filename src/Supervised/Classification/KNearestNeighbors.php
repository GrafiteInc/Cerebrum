<?php

namespace Yab\Cerebrum\Supervised\Classification;

class KNearestNeighbors
{
    public $samples = [];
    public $predictions = [];
    public $k = 3;

    public function samples($data)
    {
        $this->samples = $data;

        return $this;
    }

    public function expecting()
    {
        return $this->predictions;
    }


/**
     * @param array $sample
     *
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
        $distances = $this->kNeighborsDistances($sample);
        $predictions = array_combine(array_values($this->targets), array_fill(0, count($this->targets), 0));
        foreach ($distances as $index => $distance) {
            ++$predictions[$this->targets[$index]];
        }
        arsort($predictions);
        reset($predictions);
        return key($predictions);
    }
    /**
     * @param array $sample
     *
     * @return array
     *
     * @throws \Phpml\Exception\InvalidArgumentException
     */
    private function kNeighborsDistances(array $sample)
    {
        $distances = [];
        foreach ($this->samples as $index => $neighbor) {
            $distances[$index] = $this->distanceMetric->distance($sample, $neighbor);
        }
        asort($distances);
        return array_slice($distances, 0, $this->k, true);
    }









    public function predict($testable)
    {
        $predictions = collect();

        if (isset($testable[0]) && is_array($testable[0])) {
            foreach ($testable as $test) {
                $predictions->push($this->predictionFromSamples($test));
            }

            return $predictions->toArray();
        } else {
            return $this->predictionFromSamples($testable);
        }
    }

    public function predictionFromSamples($testable)
    {
        $targets = array_keys($this->samples);

        foreach ($targets as $label) {
            $this->predictions[$label] = 0;
            foreach ($testable as $token => $count) {
                if (array_key_exists($token, $this->samples[$label])) {
                    $this->predictions[$label] += $count * $this->samples[$label][$token];
                }
            }
        }

        arsort($this->predictions, SORT_NUMERIC);
        reset($this->predictions);

        return key($this->predictions);
    }

    /**
     * @param array $a
     * @param array $b
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public function distance(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            throw InvalidArgumentException::arraySizeNotMatch();
        }
        $distance = 0;
        $count = count($a);
        for ($i = 0; $i < $count; ++$i) {
            $distance += pow($a[$i] - $b[$i], 2);
        }
        return sqrt((float) $distance);
    }
}
