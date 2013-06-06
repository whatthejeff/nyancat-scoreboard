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

use NyanCat\Scoreboard;

/**
 * Scoreboard test cases.
 *
 * @covers NyanCat\Scoreboard
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class ScoreboardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidScoreboardProvider
     */
    public function testInvalidStates($cat, $rainbow, $teams, $width, $writer, $message)
    {
        $this->setExpectedException('InvalidArgumentException', $message);
        new Scoreboard($cat, $rainbow, $teams, $width, $writer);
    }

    /**
     * @dataProvider scoreboardProvider
     */
    public function testRunning($scoreboard)
    {
        $this->assertFalse($scoreboard->isRunning());

        $scoreboard->start();
        $this->assertTrue($scoreboard->isRunning());

        $scoreboard->stop();
        $this->assertFalse($scoreboard->isRunning());
    }

    public function scoreboardProvider()
    {
        return array(
            array(
                new Scoreboard(
                    $this->getCat(),
                    $this->getRainbow(),
                    array(
                        $this->getTeam()
                    ),
                    5,
                    function ($string) {
                    }
                )
            )
        );
    }

    public function invalidScoreboardProvider()
    {
        $cat = $this->getCat();
        $rainbow = $this->getRainbow();
        $team = $this->getTeam();
        $writer = function ($string) {
        };

        return array(
            array(
                $cat, $rainbow, array(), 5, $writer,
                'You must provide at least one team'
            ),
            array(
                $cat, $rainbow, array('my team'), 5, $writer,
                'All teams must be an instance of NyanCat\\Team'
            ),
            array(
                $cat, $rainbow, array($team), 5.2, $writer,
                'Width must be a positive interger'
            ),
            array(
                $cat, $rainbow, array($team), 0, $writer,
                'Width must be a positive interger'
            ),
            array(
                $cat, $rainbow, array($team), 5, 'callbcak',
                'Writer must be callable'
            ),
        );
    }

    protected function getCat()
    {
        $cat = $this->getMockBuilder('NyanCat\Cat')
            ->disableOriginalConstructor()
            ->getMock();

        $cat->expects($this->any())
            ->method('next')
            ->will($this->returnValue(array()));

        return $cat;
    }

    protected function getRainbow()
    {
        $rainbow = $this->getMockBuilder('NyanCat\Rainbow')
            ->disableOriginalConstructor()
            ->getMock();

        $rainbow->expects($this->any())
            ->method('next')
            ->will($this->returnValue(array()));

        return $rainbow;
    }

    protected function getTeam()
    {
        return $this->getMockBuilder('NyanCat\Team')
            ->disableOriginalConstructor()
            ->getMock();
    }
}