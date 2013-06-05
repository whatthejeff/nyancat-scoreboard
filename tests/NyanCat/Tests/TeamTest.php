<?php

/*
 * This file is part of Nyan Cat Scoreboard.
 *
 * (c) Jeff Welch <whatthejeff@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NyanCat\Tests;

use NyanCat\Team;

/**
 * Team test cases.
 *
 * @covers NyanCat\Team
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class TeamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidTeamProvider
     */
    public function testInvalidStates($name, $color, $eye, $message)
    {
        $this->setExpectedException('InvalidArgumentException', $message);
        new Team($name, $color, $eye);
    }

    public function testDefaults()
    {
        $team = new Team('test');

        $this->assertEquals(32, $team->getColor());
        $this->assertEquals('-', $team->getEye());
    }

    public function testCustom()
    {
        $team = new Team('test', 'red', '*');

        $this->assertEquals('test', $team->getName());
        $this->assertEquals(31, $team->getColor());
        $this->assertEquals('*', $team->getEye());
    }

    public function testScore()
    {
        $team = new Team('test');
        $this->assertEquals(0, $team->getScore());

        $team->updateScore(10);
        $this->assertEquals(10, $team->getScore());

        $team->updateScore(5);
        $this->assertEquals(15, $team->getScore());

        $team->updateScore(-5);
        $this->assertEquals(10, $team->getScore());
    }

    public function invalidTeamProvider()
    {
        return array(
            array(
                '', 'green', '-',
                'Name must be a non-empty string'
            ),
            array(
                5, 'green', '-',
                'Name must be a non-empty string'
            ),
            array(
                'test', 'Salmon', '-',
                'Invalid foreground color specified: "Salmon"'
            ),
            array(
                'test', 'Salmon', '-',
                'Invalid foreground color specified: "Salmon"'
            ),
            array(
                'test', 'green', '',
                'Eye must be a one character string'
            ),
            array(
                'test', 'green', 'XX',
                'Eye must be a one character string'
            ),
        );
    }
}
