<?php

use Yab\Cerebrum\Linguistics;

class LinguisticsTest extends PHPUnit_Framework_TestCase
{
    use Linguistics;

    public function setUp()
    {
        $this->testSentence = 'People act like doing woodworking and gardening are ‘simple’ tasks, or that farming is easy and not nearly as stressful as a white collar job in the heart of Toronto.';

        $this->testParagraph = 'People act like doing woodworking and gardening are ‘simple’ tasks, or that farming is easy and not nearly as stressful as a white collar job in the heart of Toronto. These are the most inaccurate ideas. Woodworking is like every other skill out there, you have to practise a considerable amount to get any good at it. Not only that its dangerous, far more than drawing or painting. Living out in the country and #accidentally cutting yourself while working on a project, means jumping in a car and driving to a hospital that it at least 35 or more minutes away. Gardening, and developing a great garden like those in movies, and the one people idealize along with the simple life, take hours of work to maintain. You’re standing in the sun, digging through dirt, and watering continuously. I #cannot even begin to explain the complexities of even the simplest farming. Tracking weather patterns to know when to protect your ‘small batch’ crops and what foods will grow best on the land that you have. Understanding how much you have to ‘naturally’ protect your crops from bugs, not to mention the complexity of cost vs yield, are just a few of the things a farmer must manage. Farms with animals are even more complicated, especially with frequent veterinarian visits. #superman';

        $this->testTweet = 'this has a #hashtag a  #badhash-tag and a #goodhash_tag';

        $this->testClass = new TestClass();
    }

    public function testGetKeywords()
    {
        $test = $this->getKeywords($this->testParagraph);
        $this->assertEquals($test, [
            'the' => 11,
            'to' => 9,
            'a' => 8,
            'and' => 8,
            'in' => 5,
            'of' => 5,
            'are' => 4,
            'that' => 4,
            'even' => 3,
            'you' => 3,
        ]);
    }

    public function testGetHashtags()
    {
        $test = $this->getHashtags($this->testTweet);
        $this->assertEquals($test, ['#hashtag', '#badhash', '#goodhash_tag']);
    }

    public function testGetSentences()
    {
        $test = $this->getSentences($this->testParagraph);
        $this->assertEquals($test[0], 'People act like doing woodworking and gardening are ‘simple’ tasks, or that farming is easy and not nearly as stressful as a white collar job in the heart of Toronto');
    }

    public function testGetWords()
    {
        $test = $this->getWords($this->testParagraph);
        $this->assertEquals($test[0], 'People');
    }

    public function testGetActionWords()
    {
        $test = $this->getActionWords($this->testParagraph);
        $this->assertEquals($test[0], 'act');
    }

    public function testGetStopWords()
    {
        $test = $this->getStopWords($this->testParagraph);
        $this->assertEquals($test[0], 'like');
    }

    public function testGetStem()
    {
        // $test = $this->getStem("paragraph");
        // $this->assertEquals($test, "paract");
    }

    public function testGetWordsByLength()
    {
        $test = $this->getWords($this->testParagraph, 9);
        $this->assertEquals($test[0], 'woodworking');
    }

    public function testGetUniqueWords()
    {
        $test = $this->getUniqueWords($this->testParagraph);
        $this->assertEquals($test[0], 'the');
    }

    public function testGetWordsByComplexity()
    {
        $test = $this->getWordsByComplexity($this->testParagraph);
        $this->assertEquals($test[1], 'Understanding');
    }

    public function testHasDenial()
    {
        $test = $this->hasDenial('Please stop sending me emails!');
        $this->assertEquals($test, true);
    }

    public function testHasConfirmation()
    {
        $test = $this->hasConfirmation('I would like to get more of these');
        $this->assertEquals($test, true);
    }

    public function testHasEmail()
    {
        $test = $this->hasEmail('I want to be like matt@yabhq.com!');
        $this->assertEquals($test, true);
    }

    public function testDoesntHaveEmail()
    {
        $test = $this->hasEmail('I want to be like matt');
        $this->assertEquals($test, false);
    }

    public function testHasUrl()
    {
        $test = $this->hasUrl("I'm so happy to be a part of http://yabhq.com!");
        $this->assertEquals($test, true);
    }

    public function testRemovePunctuation()
    {
        $test = $this->removePunctuation($this->testSentence);
        $this->assertEquals($test, 'People act like doing woodworking and gardening are simple tasks or that farming is easy and not nearly as stressful as a white collar job in the heart of Toronto');
    }

    public function testIsQuestion()
    {
        $test = $this->isQuestion('Can you believe that I am writing code at this hour?');
        $this->assertEquals($test, true);
    }

    public function testIsNotQuestion()
    {
        $test = $this->isQuestion('I am agreeing that this is not a question');
        $this->assertEquals($test, false);
    }
}
