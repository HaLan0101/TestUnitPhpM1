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
    //Vérifier que le nombre de lettres uniques du mot correspond au count du getLetters()
    public function testGetCountLetterDifferent(){
        $this->testMockExcepts();
        $this->assertEquals(count($this->word->getLetters()),2);
    }
    //Vérifier que le mot n'est pas trouvé avec les mauvaises lettres en input
    public function testWordIsNotGuessed(){
        $this->word->tryLetter('j');
        $this->word->tryLetter('m');
        $this->assertFalse($this->word->isGuessed());
    }



}