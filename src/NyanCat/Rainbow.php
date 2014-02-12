<?php

/*
 * This file is part of the Nyan Cat Scoreboard.
 *
 * (c) Jeff Welch <whatthejeff@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NyanCat;

use Fab\Fab;

/**
 * ASCII rainbow representation class.
 *
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class Rainbow
{
    /**
     * The Fab to paint the rainbow with.
     *
     * @var Fab\Fab
     */
    private $fab;

    /**
     * An array of ASCII rainbow states.
     *
     * @var array
     */
    private $states;
    /**
     * Holds the rainbow chars.
     *
     * @var array
     */
    private $trajectories;

    /**
     * Rolling index for iterating through the rainbow states.
     *
     * @var integer
     */
    private $index = -1;

    /**
     * The maximum number of characters in the rainbow.
     *
     * @var integer
     */
    private $maxWidth;
    /**
     * The number of rows in the rainbow.
     *
     * @var integer
     */
    private $height;

    /**
     * Initializes the rainbow.
     *
     * @param Fab\Fab $fab      The fab for painting the rainbow.
     * @param array $states     An array of ASCII rainbow states.
     * @param integer $maxWidth The maximum number of characters in the rainbow.
     * @param integer $height   The number of rows in the rainbow.
     */
    public function __construct(Fab $fab, array $states = array('-', '_'), $maxWidth = 64, $height = 4)
    {
        // Windows includes the cursor in the width calculation
        if (0 === strpos(strtolower(PHP_OS), 'win')) {
            $maxWidth--;
        }

        $this->fab = $fab;

        $this->setStates($states);
        $this->setMaxWidth($maxWidth);
        $this->setHeight($height);

        $this->trajectories = array_fill(0, $this->height, array());
    }

    /**
     * Gets the number of rows in the rainbow.
     *
     * @return integer The height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Gets the current number of characters in the rainbow.
     *
     * @return integer The width
     */
    public function getWidth()
    {
        return count($this->trajectories[0]);
    }

    /**
     * Gets the next rainbow state in the rolling index.
     *
     * @return array The next rainbow state.
     */
    public function next()
    {
        if (!isset($this->states[++$this->index])) {
            $this->index = 0;
        }

        $state = $this->fab->paint(
            $this->states[$this->index]
        );

        foreach ($this->trajectories as &$trajectory) {
            if (count($trajectory) >= $this->maxWidth) {
                array_shift($trajectory);
            }

            $trajectory[] = $state;
        }

        return $this->trajectories;
    }

    /**
     * Sets the max width.
     *
     * @param integer $maxWidth The max width.
     *
     * @throws \InvalidArgumentException When the width is not a positive integer.
     */
    private function setMaxWidth($maxWidth)
    {
        if (!is_int($maxWidth) || $maxWidth < 1) {
            throw new \InvalidArgumentException(
                'Maximum width must be a positive integer'
            );
        }

        $this->maxWidth = $maxWidth;
    }

    /**
     * Sets the height.
     *
     * @param string $height The height.
     *
     * @throws \InvalidArgumentException When the height is not a positive integer.
     */
    private function setHeight($height)
    {
        if (!is_int($height) || $height < 1) {
            throw new \InvalidArgumentException(
                'Height must be a positive integer'
            );
        }

        $this->height = $height;
    }

    /**
     * Sets the rainbow states.
     *
     * @param array $states The rainbow states.
     *
     * @throws \InvalidArgumentException When a rainbow segment is not a one char string.
     */
    private function setStates(array $states)
    {
        foreach ($states as $state) {
            if (!is_string($state) || strlen($state) !== 1) {
                throw new \InvalidArgumentException(
                    'State must be a one character string'
                );
            }
        }

        $this->states = $states;
    }
}