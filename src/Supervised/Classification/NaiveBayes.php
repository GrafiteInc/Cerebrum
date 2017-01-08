<?php

namespace Yab\Cerebrum\Supervised\Classification;

class NaiveBayes
{
    public $samples = [];
    public $predictions = [];

    public function samples($data)
    {
        $this->samples = $data;

        return $this;
    }

    public function expecting()
    {
        return $this->predictions;
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
}
