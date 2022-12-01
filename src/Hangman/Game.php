<?php

namespace Hangman;

class Game 
{
    const MAX_ATTEMPTS = 11;

    private $word;
    private $attempts;
    private $score;
    private $name;

    public function __construct(Word $word, $attempts = 0,$score =0, $name='')
    {
        $this->word = $word;
        $this->attempts = (int) $attempts;
        $this->score = $score;
        $this->name= $name;
    }

    public function getContext()
    {
        return array(
            'word'          => (string) $this->word,
            'attempts'      => $this->attempts,
            'found_letters' => $this->word->getFoundLetters(),
            'tried_letters' => $this->word->getTriedLetters()
        );
    }

    public function getRemainingAttempts()
    {
        return static::MAX_ATTEMPTS - $this->attempts;
    }

    public function isLetterFound($letter)
    {
        return in_array($letter, $this->word->getFoundLetters());
    }

    public function isHanged()
    {
        return static::MAX_ATTEMPTS === $this->attempts;
    }

    public function isWon()
    {
        return $this->word->isGuessed();
    }

    public function getWord()
    {
        return $this->word;
    }

    public function getScore(){
        return $this->score;
    }

    public function getAttempts()
    {
        return $this->attempts;
    }

    public function setAttempts($value)
    {
        return $this->attempts = $value;
    }

    public function tryWord($word)
    {
        if ($word === $this->word->getWord()) {
            $this->word->guessed();
            $this->score+=10;
            return true;
        }

        $this->attempts = self::MAX_ATTEMPTS;

        return false;
    }

    public function tryLetter($letter)
    {
        $result = false;

        try {
            $result = $this->word->tryLetter($letter);
        } catch (\InvalidArgumentException $e) {
            
        }

        if(true === $result){
            $this->score++;
        }

        if (false === $result) {
            $this->attempts++;
            $this->score--;
        }

        return $result;
    }
    public function insert($name,$score){
        $link = mysqli_connect("localhost", "root", "","hangman");
        $sql = "INSERT INTO user ( name, score) VALUES ( '$name', '$score')";
        if ($link->query($sql) === TRUE) {
           return true;
        } else {
            return false;
        }
    }

    public function updateScoreByName($name,$score){
        $link = mysqli_connect("localhost", "root", "","hangman");
        $sql = "UPDATE user SET score='$score' WHERE name='$name'";
        if ($link->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteByName($name){
        $link = mysqli_connect("localhost", "root", "","hangman");
        $sql = "DELETE FROM user WHERE  name='$name'";
        if ($link->query($sql) === TRUE) {
           return true;
        } else {
          return false;
        }
    }

    public function getScoreByName($name){
        $link = mysqli_connect("localhost", "root", "","hangman");
        $sql = "SELECT score from user where name='$name'";
        if (mysqli_query($link, $sql)) {
            $result = mysqli_query($link, $sql);
            $row = $result->fetch_assoc();
            return $row['score'];
        } else {
            return False;
        }
    }

    public function getUserByName($name){
        $conn = mysqli_connect("localhost", "root", "","hangman");
        $sql = "SELECT * from user where name='$name'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }
}