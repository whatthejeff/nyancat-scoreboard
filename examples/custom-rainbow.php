<?php

require_once 'vendor/autoload.php';

use NyanCat\Cat;
use NyanCat\Rainbow;
use NyanCat\Team;
use NyanCat\Scoreboard;

use Fab\Factory as FabFactory;

$scoreboard = new Scoreboard(
    new Cat(),
    new Rainbow(
        FabFactory::getFab(
            empty($_SERVER['TERM']) ? 'unknown' : $_SERVER['TERM']
        ),
        array('+','-'), 64, 10
    ),
    array(
        new Team('pass', 'green', '^'),
        new Team('fail', 'red', 'o'),
        new Team('pending', 'cyan', '-'),
    )
);

$scoreboard->start();
for($i = 0; $i < 100; $i++) {
    usleep(90000);
    $scoreboard->score('pass');
}
$scoreboard->stop();