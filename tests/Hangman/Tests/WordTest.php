<?php

namespace Hangman\Tests;


use Hangman\Word;
use InvalidArgumentException;

class WordTest extends \PHPUnit\Framework\TestCase
{
    private $word;

    public function setUp(): void
    {
        parent::setUp();
//        $this->word = new Word('php', array('p','h'),array('p','h'));
        $this->word =
            $this->getMockBuilder('Hangman\Word')
                ->setConstructorArgs(array('php'))
                ->setMethods(array('tryLetter'))
                ->getMock();

        $this->word
            ->expects($this->exactly(2))
            ->method('tryLetter');
    }

//    public function testException()
//    {
//        $this->markTestSkipped();
//        $this->expectException(InvalidArgumentException::class);
//        $this->word->tryLetter('p');
//    }

    public function testMockExcepts()
    {
        $this->word->tryLetter('p');
        $this->word->tryLetter('h');
    }



}