## Requirements

The Nyan Cat Scoreboard works with PHP 5.3.3 or later.

## Installation

The recommended way to install the Nyan Cat Scoreboard is
[through composer](http://getcomposer.org). Just create a `composer.json` file
and run the `php composer.phar install --dev` command to install it:

    {
        "require": {
            "whatthejeff/nyancat-scoreboard": "1.0.*@dev"
        }
    }

## Example

~~~php
require_once 'vendor/autoload.php';

use NyanCat\Cat;
use NyanCat\Rainbow;
use NyanCat\Team;
use NyanCat\Scoreboard;

use Fab\SuperFab;

$scoreboard = new Scoreboard(
    function ($string) {
        echo $string;
    },
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
$scoreboard->end();
~~~

## Tests

To run the test suite, you need [composer](http://getcomposer.org).

    $ php composer.phar install --dev
    $ vendor/bin/phpunit

## License

The Nyan Cat Scoreboard is licensed under the [MIT license](LICENSE).