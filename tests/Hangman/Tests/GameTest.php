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

    //tester si le joueur n'a pas gagné au début
    public function testGameHasStart()
    {
        $this->assertFalse($this->game->isWon());

    }

    //tester si le joueur n'a pas perdu au début
    public function testNotGameOverAtStart()
    {
        $this->assertFalse($this->game->isHanged());
    }

    //tester si les lettres trouvées correspondent à l'input
    /**
     * @dataProvider getDataGoodLetters
     */
    public function testIsLetterFound($letter)
    {

        $this->game->tryLetter($letter);

        $this->assertTrue($this->game->isLetterFound($letter));

    }
    //tester si les lettres trouvées ne correspondent pas à l'input
    /**
     * @dataProvider getDataBadLetters
     */
    public function testIsLetterNotFound($letter)
    {

        $this->game->tryLetter($letter);


        $this->assertFalse($this->game->isLetterFound($letter));
    }
    // tester si le jeu est gagné après avoir entré le bon mot
    public function testGameIsWonAfterAGoodWord()
    {

        $this->game->tryWord('php');

        $this->assertTrue($this->game->isWon());
    }
    //tester le nombre d'essais au début correspond au nombre d'essais maximum
    public function testGetRemainingAttemptsAreEqualsAtStart()
    {
        $this->assertEquals(Game::MAX_ATTEMPTS, $this->game->getRemainingAttempts());
    }
    //tester si le jeu est perdu après avoir utilisé tous les essais
    public function testGameOverWhenAttemptsIsMaxAttempts(){
        $this->game->setAttempts(11);
        $this->assertTrue($this->game->isHanged());
    }
    //tester que le jeu n'est pas perdu tant qu'il reste des essais
    public function testGameNotOverWhenAttemptsIsLessThanMaxAttempts(){
        $this->game->setAttempts(9);
        $this->assertFalse($this->game->isHanged());
    }
    //tester que le mot obtenu par getWord() correspond au mot à trouver
    public function testGetWord(){
        $this->assertEquals($this->game->getWord(), "php");
    }
    //vérifier que le nombre obtenu par getRemainingAttempts() correspond au nombre d'essais restants
    public function testGetRemainingAttempts(){
        $this->game->setAttempts(8);
        $this->assertEquals($this->game->getRemainingAttempts(), Game::MAX_ATTEMPTS - $this->game->getAttempts());
    }
    //vérifier que la méthode tryWord fonctionne
    public function testTryAGoodWord(){
        $this->assertTrue($this->game->tryWord("php"));
    }
    //vérifier qu'il y ai une erreur lorsque la lettre entrée n'est pas la bonne
    /**
     * @dataProvider getDataBadLetters
     */
    public function testTryBadLetters($letter){
        $this->assertFalse($this->game->tryLetter($letter));
    }
    //Vérifier que le nombre d'essais diminue après une saisie
    public function testGetAttemptsAfterTryOneLetter()
    {
        $this->game->setAttempts(8);
        $this->game->tryLetter("m");
        $this->assertEquals($this->game->getAttempts(),9);
    }

    //Tester un insert dans la bdd
    public function testInsert(){
        $this->assertTrue( $this->game->insert("Yayaya",2));
    }
    //Tester un update dans la bdd
    public function testUpdate(){
        $this->game->updateScoreByName("Yayaya",4);
        $this->assertEquals($this->game->getScoreByName("Yayaya"), 4);
    }
    //Tester un Get User by Name dans la bdd
    public function testUserByName(){
        $this->assertNotNull($this->game->getUserByName("Yayaya"));
    }
    //Tester un delete dans la bdd
    public function testDelete(){
        $this->assertTrue($this->game->deleteByName("Yayaya"));
    }
    //Tester le score après un bon mot
    public function testAddScoreAfterGoodWord(){
        $this->game->setScore(10);
        $this->game->tryWord('php');
        $this->assertEquals($this->game->getScore(), 20);
    }
    //Tester le score après une bonne lettre
    public function testAddScoreAfterAGoodLetter(){
        $this->game->setScore(10);
        $this->game->tryLetter('p');
        $this->assertEquals($this->game->getScore(), 11);
    }
    //Tester le score après une mauvaise lettre
    public function testAddScoreAfterABadLetter(){
        $this->game->setScore(10);
        $this->game->tryLetter('k');
        $this->assertEquals($this->game->getScore(), 9);
    }
}