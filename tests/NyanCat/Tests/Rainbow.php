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

        new Rainbow($this->getFab(), $states, $maxWidth, $height);
    }

    /**
     * @dataProvider heightProvider
     */
    public function testHeight($rainbow, $height)
    {
        $this->assertEquals($height, $rainbow->getHeight());
    }

    /**
     * @dataProvider widthProvider
     */
    public function testWidth($rainbow, $width)
    {
        $rainbow->next();
        $this->assertEquals(1, $rainbow->getWidth());

        $rainbow->next();
        $this->assertEquals(2, $rainbow->getWidth());

        for ($i = 0; $i <= $width; $i++) {
            $rainbow->next();
        }

        $rainbow->next();
        $this->assertEquals($width, $rainbow->getWidth());

        $rainbow->next();
        $this->assertEquals($width, $rainbow->getWidth());
    }

    public function testDefaultRainbow()
    {
        $rainbow = new Rainbow($this->getFab());

        $expected = array_fill(0, 4, array('-'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 4, array('-','_'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 4, array('-','_','-'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 4, array('-','_','-','_'));
        $this->assertEquals($expected, $rainbow->next());
    }

    public function testCustomRainbow()
    {
        $rainbow = new Rainbow($this->getFab(), array('*','-','+','/'), 64, 10);

        $expected = array_fill(0, 10, array('*'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 10, array('*','-'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 10, array('*','-','+'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 10, array('*','-','+','/'));
        $this->assertEquals($expected, $rainbow->next());

        $expected = array_fill(0, 10, array('*','-','+','/','*'));
        $this->assertEquals($expected, $rainbow->next());
    }

    public function heightProvider()
    {
        $fab = $this->getFab();

        return array(
            array(
                new Rainbow($fab),
                4
            ),
            array(
                new Rainbow($fab, array('-', '_'), 64, 10),
                10
            ),
        );
    }

    public function widthProvider()
    {
        $fab = $this->getFab();

        return array(
            array(
                new Rainbow($fab),
                64
            ),
            array(
                new Rainbow($fab, array('-', '_'), 100),
                100
            ),
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

    protected function getFab()
    {
        $fab = $this->getMockBuilder('Fab\SuperFab')
            ->disableOriginalConstructor()
            ->getMock();

        $fab->expects($this->any())
            ->method('paint')
            ->will(
                $this->returnCallback(function ($string) {
                    return $string;
                })
            );

        return $fab;
    }
}
