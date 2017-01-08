<?php

namespace Yab\Cerebrum\Utilities;

use Yab\Cerebrum\Supervised\Regression\NeuralNetwork;

class ArtificialIntelligence
{
    public $samples;

    public function samples($data)
    {
        $this->samples = $data;

        return $this;
    }

    /**
     * Makes a best guess on the number of occurances.
     *
     * @return mixed
     */
    public function expecting()
    {
        $possibilities = count($this->samples);
        $orderedByOccurance = array_count_values($this->samples);
        array_multisort($orderedByOccurance, SORT_DESC);

        $probabilities = [];

        foreach ($orderedByOccurance as $item => $value) {
            $probabilities[$item] = $value / $possibilities;
        }

        return $probabilities;
    }

    public function neuralNetwork()
    {
        return new NeuralNetwork($this->samples);
    }
}
