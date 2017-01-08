<?php

use Yab\Cerebrum\Perception;

class PerceptionTest extends PHPUnit_Framework_TestCase
{
    use Perception;

    public function setUp()
    {
        $this->testClass = new TestClass();
    }

    public function testNormalize()
    {
        $data = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $test = $this->normalize($data);

        $this->assertEquals($test[3], 0.3);
    }

    public function testClassifierNaiveBayesPrediction()
    {
        $test = $this->supervised()->samples([
            'blue' => [5, 1, 1],
            'red' => [1, 5, 1],
            'orange' => [1, 2, 5],
            'purple' => [1, 2, 3],
        ])->predict([4, 5, 6]);

        $this->assertEquals($test, 'orange');
        $this->assertTrue(!is_array($test));
    }

    public function testClassifierNaiveBayesPredictionMultiple()
    {
        $test = $this->supervised()->samples([
            'monday' => [5, 1],
            'tuesday' => [3, 0.5],
            'wednesday' => [9, 3],
            'thursday' => [2, 0],
            'friday' => [8, 1],
        ])->predict([
            [8, 2],
            [2, 0],
        ]);

        $this->assertEquals($test, ['wednesday', 'wednesday']);
        $this->assertTrue(is_array($test));
    }

    public function testExpecting()
    {
        $test = $this->ai()->samples(['monday', 'tuesday', 'wednesday', 'tuesday', 'friday', 'monday', 'tuesday'])->expecting();
        $this->assertEquals($test['tuesday'], 0.42857142857143);
    }
}
