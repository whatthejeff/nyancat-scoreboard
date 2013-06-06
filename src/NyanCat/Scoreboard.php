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
 * The scoreboard where everything is assembled.
 *
 * Example:
 *
 *     use NyanCat\Cat;
 *     use NyanCat\Rainbow;
 *     use NyanCat\Team;
 *     use NyanCat\Scoreboard;
 *
 *     use Fab\SuperFab;
 *
 *     $scoreboard = new Scoreboard(
 *         new Cat(),
 *         new Rainbow(
 *             new SuperFab()
 *         ),
 *         array(
 *             new Team('pass', 'green', '^'),
 *             new Team('fail', 'red', 'o'),
 *             new Team('pending', 'cyan', '-'),
 *         )
 *     );
 *
 *     $scoreboard->start();
 *     for($i = 0; $i <= 200; $i++) {
 *         usleep(90000);
 *         $scoreboard->score('pass');
 *     }
 *     $scoreboard->stop();
 *
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class Scoreboard
{
    /**
     * Ansi escape
     */
    const ESC = "\x1b[";
    /**
     * Ansi Normal
     */
    const NND = "\x1b[0m";

    /**
     * If a scoreboard is currently running.
     *
     * @var boolean
     */
    private $running = false;

    /**
     * Width in characters of the scores section on the scoreboard.
     *
     * @var integer
     */
    private $width;
    /**
     * Height in newlines of the tallest element on the scoreboard.
     *
     * NOTE: This is set automatically.
     *
     * @var integer
     */
    private $height;

    /**
     * A callable that takes a string and writes it to the appropriate output
     * source. For example:
     *
     *     function ($string) {
     *         print $string;
     *     }
     *
     * @var callable
     */
    private $writer;
    /**
     * The animated ASCII cat.
     *
     * @var NyanCat\Cat
     */
    private $cat;
    /**
     * The animated ASCII rainbow.
     *
     * @var NyanCat\Cat
     */
    private $rainbow;
    /**
     * A collection of teams to track on the scoreboard.
     *
     * @var array
     */
    private $teams;

    /**
     * Initializes the scoreboard.
     *
     * @param Fab\Cat $cat         The animated ASCII cat.
     * @param Fab\Rainbow $rainbow The animated ASCII rainbow.
     * @param array $teams         A collection of teams to track on the scoreboard.
     * @param integer $width       Width in characters of the scores section on the scoreboard.
     * @param callable $writer     A callable that takes a string and writes it to the appropriate output source.
     */
    public function __construct(Cat $cat, Rainbow $rainbow, array $teams, $width = 5, $writer = null)
    {
        $this->cat = $cat;
        $this->rainbow = $rainbow;

        $this->setTeams($teams);
        $this->setWidth($width);
        $this->setHeight();

        $this->setWriter($writer);
    }

    /**
     * Writes out the initial scoreboard.
     */
    public function start()
    {
        $this->running = true;
        $this->score('start');
    }

    /**
     * Updates the score for a team and makes another iteration in the
     * scoreboard animation.
     *
     * @param string $team    The name of the team
     * @param integer $points The number of points to add to the team's score.
     */
    public function score($team, $points = 1)
    {
        if (isset($this->teams[$team])) {
            $this->teams[$team]->updateScore($points);
        }

        $this->drawScoreboard();
        $this->drawRainbow();
        $this->drawCat($team);
    }

    /**
     * Moves the cursor below the scoreboard to end the scoreboard animation.
     */
    public function stop()
    {
        $this->running = false;
        $this->writeLines($this->height);
    }

    /**
     * Checks if a scoreboard is currently running.
     *
     * @return boolean if a scoreboard is running.
     */
    public function isRunning()
    {
        return $this->running;
    }

    /**
     * Gets the number of newlines from the top that it will take to place the
     * current element in the middle of the scoreboard.
     *
     * @param integer $size The size of the current element.
     *
     * @return integer the number of newlines from the top.
     */
    protected function getPreamble($size)
    {
        $height_diff = $this->height - $size;
        return $height_diff > 0 ? floor($height_diff / 2) : 0;
    }

    /**
     * Gets the number of newlines from the end that it will take to place the
     * current element in the middle of the scoreboard.
     *
     * @param integer $size The size of the current element.
     *
     * @return integer the number of newlines from the bottom.
     */
    protected function getPostamble($size)
    {
        $height_diff = $this->height - $size;
        return $height_diff > 0 ? $height_diff - floor($height_diff / 2) : 0;
    }

    /**
     * Prints newlines from the top to place the current element in the middle
     * of the scoreboard.
     *
     * @param integer $size The size of the current element.
     */
    protected function preamble($size)
    {
        $preamble = $this->getPreamble($size);
        if ($preamble > 0) {
            $this->writeLines($preamble);
        }
    }

    /**
     * Prints newlines from the bottom to place the current element in the
     * middle of the scoreboard.
     *
     * @param integer $size The size of the current element.
     */
    protected function postamble($size)
    {
        $postamble = $this->getPostamble($size);
        if ($postamble > 0) {
            $this->writeLines($postamble);
        }

        $this->resetCursor();
    }

    /**
     * Moves the cursor up a number of lines.
     *
     * @param integer $lines The number of lines to move the cursor up.
     */
    protected function cursorUp($lines)
    {
        $this->write(self::ESC . $lines . 'A');
    }

    /**
     * Moves the cursor up to the top of the scoreboard.
     */
    protected function resetCursor()
    {
        $this->cursorUp($this->height);
    }

    /**
     * Moves the cursor down a number of lines.
     *
     * @param integer $lines The number of lines to move the cursor down.
     */
    protected function writeLines($lines)
    {
        if ($lines > 0) {
            $this->write(str_repeat(" \n", $lines));
        }
    }

    /**
     * Writes out a string using the current writer.
     *
     * @param string $string The string to write out.
     */
    protected function write($string)
    {
        call_user_func($this->writer, $string);
    }

    /**
     * Draws the scoreboard for the current iteration.
     */
    protected function drawScoreboard()
    {
        $size = count($this->teams);
        $this->preamble($size);

        foreach ($this->teams as $key => $team) {
            $this->write(sprintf(
                " %s%sm%s%s\n",

                self::ESC,
                $team->getColor(),
                $team->getScore(),
                self::NND
            ));
        }

        $this->postamble($size);
    }

    /**
     * Draws the rainbow for the current iteration.
     */
    protected function drawRainbow()
    {
        $rainbow = $this->rainbow->next();
        $height = $this->rainbow->getHeight();
        $width = $this->rainbow->getWidth();

        $preamble = $this->getPreamble($height);
        $postamble = $this->getPostamble($height);

        while ($preamble-- > 0) {
            $this->write(self::ESC . $this->width . 'C');
            $this->write(str_repeat(' ', $width));
            $this->write("\n");
        }

        foreach ($rainbow as $line) {
            $this->write(self::ESC . $this->width . 'C');
            $this->write(implode($line));
            $this->write("\n");
        }

        while ($postamble-- > 0) {
            $this->write(self::ESC . $this->width . 'C');
            $this->write(str_repeat(' ', $width));
            $this->write("\n");
        }

        $this->resetCursor();

    }

    /**
     * Draws the cat for the current iteration.
     *
     * @param string $team The name of the team that has scored for this iteration.
     */
    protected function drawCat($team)
    {
        $cat = $this->cat->next(
            isset($this->teams[$team]) ? $this->teams[$team]->getEye() : '-'
        );

        $width = $this->width + $this->rainbow->getWidth();
        $size = $this->cat->getHeight();

        $this->preamble($size);

        foreach ($cat as $line) {
            $this->write(self::ESC . $width . 'C');
            $this->write($line);
            $this->write("\n");
        }

        $this->postamble($size);
    }

    /**
     * Sets the teams to track on the scoreboard.
     *
     * @param array $teams the teams to track on the scoreboard.
     *
     * @throws \InvalidArgumentException When invalid or no teams are provided.
     */
    private function setTeams(array $teams)
    {
        if (empty($teams)) {
            throw new \InvalidArgumentException(
                'You must provide at least one team'
            );
        }

        foreach ($teams as $team) {
            if (!$team instanceof Team) {
                throw new \InvalidArgumentException(
                    'All teams must be an instance of NyanCat\\Team'
                );
            }

            $this->teams[$team->getName()] = $team;
        }
    }

    /**
     * Sets the callable that writes out the scoreboard. For example:
     *
     *     function ($string) {
     *         print $string;
     *     }
     *
     * @param callable $writer the callable that writes out the scoreboard.
     *
     * @throws \InvalidArgumentException When $writer is not callable.
     */
    private function setWriter($writer = null)
    {
        if ($writer === null) {
            $writer = function ($string) {
                print $string;
            };
        }

        if (!is_callable($writer)) {
            throw new \InvalidArgumentException(
                'Writer must be callable'
            );
        }

        $this->writer = $writer;
    }

    /**
     * Sets the width in characters of the scores section on the scoreboard.
     *
     * @param integer $width the width in characters.
     *
     * @throws \InvalidArgumentException When $width is not a positive integer.
     */
    private function setWidth($width)
    {
        if (!is_int($width) || $width < 1) {
            throw new \InvalidArgumentException(
                'Width must be a positive interger'
            );
        }

        $this->width = $width;
    }

    /**
     * Sets the height in newlines of the tallest element on the scoreboard.
     */
    private function setHeight()
    {
        $this->height = max(
            count($this->teams),
            $this->rainbow->getHeight(),
            $this->cat->getHeight()
        );
    }
}
