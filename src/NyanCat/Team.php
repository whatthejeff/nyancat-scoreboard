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

/**
 * A team whose score will be represented on the Nyan Cat Scoreboard.
 *
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class Team
{
    /**
     * ASCII colors.
     *
     * @var array
     */
    private static $colors = array(
        'black'   => 30,
        'red'     => 31,
        'green'   => 32,
        'yellow'  => 33,
        'blue'    => 34,
        'magenta' => 35,
        'cyan'    => 36,
        'white'   => 37
    );

    /**
     * Team name.
     *
     * @var string
     */
    private $name;
    /**
     * Team color.
     *
     * @var integer
     */
    private $color;
    /**
     * Team eye.
     *
     * @var string
     */
    private $eye;

    /**
     * The current team score.
     *
     * @var integer
     */
    private $score = 0;

    /**
     * Initializes the team.
     *
     * @param string $name  The team name.
     * @param string $color The team color.
     * @param string $eye   The team eye.
     */
    public function __construct($name, $color = 'green', $eye = '-')
    {
        $this->setName($name);
        $this->setColor($color);
        $this->setEye($eye);
    }

    /**
     * Gets the team name.
     *
     * @return string The team name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the team color.
     *
     * @return string The team color.
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Gets the team eye.
     *
     * @return string The team eye.
     */
    public function getEye()
    {
        return $this->eye;
    }

    /**
     * Gets the team score.
     *
     * @return integer The team score.
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Updates the team score.
     *
     * @param integer $points The points to add to the team score.
     */
    public function updateScore($points)
    {
        $this->score += $points;
    }

    /**
     * Sets the team color.
     *
     * @param string $color The team color.
     *
     * @throws \InvalidArgumentException When an invalid color is provided.
     */
    private function setColor($color)
    {
        if (!isset(self::$colors[$color])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid foreground color specified: "%s". Expected one of (%s)',
                $color,
                implode(', ', array_keys(self::$colors))
            ));
        }

        $this->color = self::$colors[$color];
    }

    /**
     * Sets the team name.
     *
     * @param string $name The team name.
     *
     * @throws \InvalidArgumentException When an invalid team name is provided.
     */
    private function setName($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new \InvalidArgumentException(
                'Name must be a non-empty string'
            );
        }

        $this->name = $name;
    }

    /**
     * Sets the team eye.
     *
     * @param string $eye The team eye.
     *
     * @throws \InvalidArgumentException When an invalid team eye is provided.
     */
    private function setEye($eye)
    {
        if (!is_string($eye) || strlen($eye) !== 1) {
            throw new \InvalidArgumentException(
                'Eye must be a one character string'
            );
        }

        $this->eye = $eye;
    }
}