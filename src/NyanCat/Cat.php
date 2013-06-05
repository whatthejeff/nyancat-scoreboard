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
 * ASCII cat representation class.
 *
 * NOTE: Technically this doesn't even need to be a cat, but shhh don't tell.
 * We don't want people knowing they can use this Cat class to represent a
 * dinosaur or something crazy like that. After all, if we wanted to represent
 * a dinosaur, we would need to specify the proper class hierarchy:
 *
 *     Organism > Eukaryote > Animal > Reptile > Dinosaur
 *
 * At least I think that's how this object-oriented stuff works. The wikipedia
 * article is a bit short on examples.
 *
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class Cat
{
    /**
     * An array of ASCII cat states.
     *
     * Each state should be the array you'd get after running the following on
     * an ASCII cat:
     *
     *     implode("\n", $ascii_state);
     *
     * Use percents (%) for eyes so they can be replaced at certain states to
     * indicate cat emotions.
     *
     * @var array
     */
    private $states = array(
        array(
            '_,------,',
            '_|  /\\_/\\',
            '~|_( % .%)',
            ' ""  ""'
        ),
        array(
            '_,------,',
            '_|   /\\_/\\',
            '^|__( % .%)',
            '  ""  ""'
        )
    );

    /**
     * The number of characters in the widest of the cat states.
     *
     * NOTE: This is calculate automatically.
     *
     * @var integer
     */
    private $width = 11;
    /**
     * The number of newlines in the ASCII cat.
     *
     * NOTE: This is calculate automatically.
     *
     * @var integer
     */
    private $height = 4;

    /**
     * Rolling index for iterating through the cat states.
     *
     * @var integer
     */
    private $index = -1;

    /**
     * Initializes the cat.
     *
     * If you wish to specify a custom ASCII cat, each state should be the
     * array you'd get after running the following on an ASCII cat:
     *
     *     implode("\n", $ascii_state);
     *
     * Use percents (%) for eyes so they can be replaced at certain states to
     * indicate cat emotions.
     *
     * @param array $states An optional array of custom ASCII cat states.
     */
    public function __construct(array $states = null)
    {
        if (empty($states)) {
            return;
        }

        $this->setStates($states);
    }

    /**
     * Gets the number of characters in the widest of the cat states.
     *
     * @return integer The width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Gets the number of newlines in the ASCII cat.
     *
     * @return integer The height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Gets the next cat state in the rolling index.
     *
     * @param string $eye an optional eye for the next cat state.
     *
     * @return array The next cat state.
     */
    public function next($eye = '-')
    {
        if (!isset($this->states[++$this->index])) {
            $this->index = 0;
        }

        $state = $this->states[$this->index];

        foreach ($state as &$line) {
            $line = str_pad(
                str_replace('%', $eye, $line),
                $this->getWidth()
            );
        }

        return $state;
    }

    /**
     * Sets custom cat states.
     *
     * @param array $states The array of custom ASCII cat states.
     *
     * @throws \InvalidArgumentException When states array is malformed.
     */
    private function setStates(array $states)
    {
        if (empty($states)) {
            throw new \InvalidArgumentException(
                'You must provide cat states'
            );
        }

        $height = count($states[0]);
        $width = 0;

        foreach ($states as $state) {
            if (!is_array($state)) {
                throw new \InvalidArgumentException(
                    'States must be an array of strings'
                );
            }

            if (count($state) !== $height) {
                throw new \InvalidArgumentException(
                    'Height must be the same for all states'
                );
            }

            foreach ($state as $line) {
                if (!is_string($line)) {
                    throw new \InvalidArgumentException(
                        'States must be an array of strings'
                    );
                }

                $width = max($width, strlen($line));
            }
        }

        $this->states = $states;
        $this->width = $width;
        $this->height = $height;
    }
}