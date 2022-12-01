<?php

namespace Hangman\Tests;

use Hangman\Game;

class GameTest extends \PHPUnit\Framework\TestCase
{
    private $game;

    public static $dataGoodLetters = array(
        array('p'),
        array('h')
    );

    public static $dataBadLetters = array(
        array('x'),
        array('z')
    );

    public function getDataGoodLetters(): array
    {
        return self::$dataGoodLetters;
    }

    public function getDataBadLetters(): array
    {
        return self::$dataBadLetters;
    }

    public function setUp(): void
    {
        parent::setUp();
        $stub =
            $this->getMockBuilder('Hangman\Word')
                ->setConstructorArgs(array('php'))
                ->setMethods(null)
                ->getMock();

        $this->game = new Game($stub);
    }

    public function testGameHasStart()
    {
        $this->assertFalse($this->game->isWon());

    }
    public function testNotGameOverAtStart()
    {
        $this->assertFalse($this->game->isHanged());
    }
    /**
     * @dataProvider getDataGoodLetters
     */
    public function testIsLetterFound($letter)
    {

        $this->game->tryLetter($letter);

        $this->assertTrue($this->game->isLetterFound($letter));

    }

    /**
     * @dataProvider getDataBadLetters
     */
    public function testIsLetterNotFound($letter)
    {

        $this->game->tryLetter($letter);


        $this->assertFalse($this->game->isLetterFound($letter));
    }

    public function testGameIsWonAfterAGoodWord()
    {

        $this->game->tryWord('php');

        $this->assertTrue($this->game->isWon());
    }

    public function testGetRemainingAttemptsAreEqualsAtStart()
    {
        $this->assertEquals(Game::MAX_ATTEMPTS, $this->game->getRemainingAttempts());
    }

    public function testGameOverWhenAttemptsIsMaxAttempts(){
        $this->game->setAttempts(11);
        $this->assertTrue($this->game->isHanged());
    }

    public function testGameNotOverWhenAttemptsIsLessThanMaxAttempts(){
        $this->game->setAttempts(9);
        $this->assertFalse($this->game->isHanged());
    }

    public function testGetWord(){
        $this->assertEquals($this->game->getWord(), "php");
    }

    public function testGetRemainingAttempts(){
        $this->game->setAttempts(8);
        $this->assertEquals($this->game->getRemainingAttempts(), 3);
    }

    public function testTryAGoodWord(){
        $this->assertTrue($this->game->tryWord("php"));
    }

    /**
     * @dataProvider getDataBadLetters
     */
    public function testTryBadLetters($letter){
        $this->assertFalse($this->game->tryLetter($letter));
    }

    public function testGetAttemptsAfterTryOneLetter()
    {
        $this->game->setAttempts(8);
        $this->game->tryLetter("m");
        $this->assertEquals($this->game->getAttempts(),9);
    }


}