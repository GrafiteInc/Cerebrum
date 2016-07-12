<?php

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Yab\Cerebrum\Linguistics;

class LinguisticsTest extends PHPUnit_Framework_TestCase
{
    use Linguistics;

    public function setUp()
    {
        $this->testSentence = "People act like doing woodworking and gardening are ‘simple’ tasks, or that farming is easy and not nearly as stressful as a white collar job in the heart of Toronto. These are the most inaccurate ideas. Woodworking is like every other skill out there, you have to practise a considerable amount to get any good at it. Not only that its dangerous, far more than drawing or painting. Living out in the country and #accidentally cutting yourself while working on a project, means jumping in a car and driving to a hospital that it at least 35 or more minutes away. Gardening, and developing a great garden like those in movies, and the one people idealize along with the simple life, take hours of work to maintain. You’re standing in the sun, digging through dirt, and watering continuously. I #cannot even begin to explain the complexities of even the simplest farming. Tracking weather patterns to know when to protect your ‘small batch’ crops and what foods will grow best on the land that you have. Understanding how much you have to ‘naturally’ protect your crops from bugs, not to mention the complexity of cost vs yield, are just a few of the things a farmer must manage. Farms with animals are even more complicated, especially with frequent veterinarian visits. #superman";

        $this->testClass = new TestClass();
    }

    public function testMemory()
    {
        $test = $this->getKeywords($this->testSentence);

        dd($test);

        $this->assertEquals($test, ['test']);
    }
}
