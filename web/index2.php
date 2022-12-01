<?php
require __DIR__.'/../autoload.php';
use Hangman\Word;
use Hangman\WordList;
use Hangman\GameContext;
use Hangman\Loader\TextFileLoader;
use Hangman\Storage\SessionStorage;


$list = new WordList();
$list->load(new TextFileLoader(__DIR__.'/../data/words.txt'));

$context = new GameContext(new SessionStorage('hangman'));

// New Game?
if (isset($_GET['new'])) {
    $context->reset();
}

// Restore Game?
if (!$game = $context->loadGame()) {
    $game = $context->newGame(new Word($list->getRandomWord(2)));
}

$context->save($game);
?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BombParty Game</title>
</head>
<style>
    #button {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-left: 150px;
    }
    li {
        display: inline;
        padding-right: 100px;
        font-size: 30px;
    }
    h2{
        padding-top: 50px;
        padding-bottom: 50px;
        padding-left: 200px;
    }
    #form{
        padding-bottom: 50px;
        padding-left: 100px;
        font-size: 30px;
    }
</style>
<body>
<h1>BombParty Game</h1>
<ul>
    <li>Vie</li>
    <li>Nb de mots trouvés</li>
    <li>Timer</li>
</ul>
<h2>Syllabe : <?php echo $game->getWord() ?></h2>
<form action="index.php" method="post" id="form">
    <label for="word">Word:</label>
    <input type="text" name="word" id="x"/>
    <button type="button"">Envoyer</button>
</form>
<button id="button" type="button" onclick="setTimeout(activeButton, 0)">Next</button>
<button id="button" type="button" onclick="setTimeout(desactiveButton, 3000)">Start</button>

<script>
    function activeButton() {
        document.getElementById("x").disabled = false;
    }
    function desactiveButton() {
        document.getElementById("x").disabled = true;
    }
</script>
</body>
</html>