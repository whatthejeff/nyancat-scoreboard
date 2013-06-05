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

use NyanCat\Cat;

/**
 * Cat test cases. (=^.^=)
 *
 * @covers NyanCat\Cat
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class CatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * An ASCII dinosaur for testing custom states.
     *
     * @var array
     */
    private $dinosaurs = array(
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
    );

    /**
     * @dataProvider invalidStatesProvider
     */
    public function testInvalidStates($states, $message)
    {
        $this->setExpectedException('InvalidArgumentException', $message);

        new Cat($states);
    }

    /**
     * @dataProvider validCatProvider
     */
    public function testHeight($states, $height, $width)
    {
        $cat = new Cat($states);
        $this->assertEquals($height, $cat->getHeight());
    }

    /**
     * @dataProvider validCatProvider
     */
    public function testWidth($states, $width, $width)
    {
        $cat = new Cat($states);
        $this->assertEquals($width, $cat->getWidth());
    }

    public function testNext()
    {
        $cat = new Cat($this->dinosaurs);

        $this->assertEquals($this->getDinosaurState(0), $cat->next());
        $this->assertEquals($this->getDinosaurState(1), $cat->next());
        $this->assertEquals($this->getDinosaurState(2), $cat->next());
        $this->assertEquals($this->getDinosaurState(0), $cat->next());
        $this->assertEquals($this->getDinosaurState(1), $cat->next());
        $this->assertEquals($this->getDinosaurState(2), $cat->next());
    }

    public function testNextWithEye()
    {
        $cat = new Cat($this->dinosaurs);

        $this->assertEquals($this->getDinosaurState(0, '^'), $cat->next('^'));
        $this->assertEquals($this->getDinosaurState(1, 'x'), $cat->next('x'));
        $this->assertEquals($this->getDinosaurState(2), $cat->next());
    }

    public function validCatProvider()
    {
        return array(
            array(null, 4, 11),
            array($this->dinosaurs, 6, 19)
        );
    }

    public function invalidStatesProvider()
    {
        return array(
            array(
                array(5),
                'States must be an array of strings'
            ),
            array(
                array(array(5)),
                'States must be an array of strings'
            ),
            array(
                array(array('-'), array('-','_')),
                'Height must be the same for all states'
            )
        );
    }

    private function getDinosaurState($index, $eye = '-')
    {
        return str_replace('%', $eye, $this->dinosaurs[$index]);
    }
}
