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

use NyanCat\Rainbow;

/**
 * Rainbow test cases.
 *
 * @covers NyanCat\Rainbow
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class RainbowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidRainbowProvider
     */
    public function testInvalidStates($states, $maxWidth, $height, $message)
    {
        $this->setExpectedException('InvalidArgumentException', $message);

        new Rainbow(
            $this->getMock('Fab\SuperFab'),
            $states,
            $maxWidth,
            $height
        );
    }

    public function invalidRainbowProvider()
    {
        return array(
            array(
                array(0), 64, 4,
                'State must be a one character string'
            ),
            array(
                array('-_-'), 64, 4,
                'State must be a one character string'
            ),
            array(
                array('-','_'), 'something', 4,
                'Maximum width must be a positive integer'
            ),
            array(
                array('-','_'), 0, 4,
                'Maximum width must be a positive integer'
            ),
            array(
                array('-','_'), 64, 'something',
                'Height must be a positive integer'
            ),
            array(
                array('-','_'), 64, 0,
                'Height must be a positive integer'
            ),
        );
    }
}
