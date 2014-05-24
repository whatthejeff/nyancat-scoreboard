[![Nyan Cat Scoreboard](https://github.com/whatthejeff/nyancat-scoreboard/raw/master/examples.png)](https://github.com/whatthejeff/nyancat-scoreboard/raw/master/examples.png)

## Requirements

The Nyan Cat scoreboard requires:

 * PHP 5.3.3 or later.
 * A terminal emulator with support for ANSI escape sequences, including color
   and cursor control.

**NOTE:** By default, the Windows console does not support ANSI escape
sequences. If you'd like to use the Nyan Cat scoreboard on Windows, you
may want to try one of the following solutions:

 * [ANSICON](https://github.com/adoxa/ansicon)
 * [ConEmu](https://github.com/Maximus5/ConEmu)

## Installation

The recommended way to install the Nyan Cat Scoreboard is
[through composer](http://getcomposer.org). Just create a `composer.json` file
and run the `php composer.phar install` command to install it:

~~~json
{
    "require": {
        "whatthejeff/nyancat-scoreboard": "~1.1"
    }
}
~~~

## Example

~~~php
require_once 'vendor/autoload.php';

use NyanCat\Cat;
use NyanCat\Rainbow;
use NyanCat\Team;
use NyanCat\Scoreboard;

use Fab\SuperFab;

$scoreboard = new Scoreboard(
    new Cat(),
    new Rainbow(
        new SuperFab()
    ),
    array(
        new Team('pass', 'green', '^'),
        new Team('fail', 'red', 'o'),
        new Team('pending', 'cyan', '-'),
    )
);

$scoreboard->start();
for ($i = 0; $i <= 200; $i++) {
    usleep(90000);
    $scoreboard->score('pass');
}
$scoreboard->stop();
~~~

## Tests

[![Build Status](https://travis-ci.org/whatthejeff/nyancat-scoreboard.png?branch=master)](https://travis-ci.org/whatthejeff/nyancat-scoreboard)

To run the test suite, you need [composer](http://getcomposer.org).

    $ php composer.phar install --dev
    $ vendor/bin/phpunit

## License

The Nyan Cat Scoreboard is licensed under the [MIT license](LICENSE).
