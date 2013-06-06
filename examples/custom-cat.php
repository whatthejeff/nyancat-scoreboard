<?php

require_once __DIR__ . '/../vendor/autoload.php';

use NyanCat\Cat;
use NyanCat\Rainbow;
use NyanCat\Team;
use NyanCat\Scoreboard;

use Fab\Factory as FabFactory;

$scoreboard = new Scoreboard(
    new Cat(
        array(
            array(
                '              ___  ',
                '             / %_) ',
                '     _.----./ /    ',
                '    /        /     ',
                ' __/ (  | (  |     ',
                "/__.-'|_|--|_|     "
            ),
            array(
                '               ___ ',
                '              / %_)',
                '     _.----._/ /   ',
                '    /         /    ',
                ' __/ (  | (  |     ',
                "/__.-|_|--|_|      "
            ),
            array(
                '              ___  ',
                '             / %_) ',
                '    _.----._/ /    ',
                '   /         /     ',
                ' _/ (  / /  /      ',
                "/_/-|_/--|_/       "
            )
        )
    ),
    new Rainbow(
        FabFactory::getFab(
            empty($_SERVER['TERM']) ? 'unknown' : $_SERVER['TERM']
        )
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