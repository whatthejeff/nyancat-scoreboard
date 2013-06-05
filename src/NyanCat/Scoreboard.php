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
 *
 *
 * @author Jeff Welch <whatthejeff@gmail.com>
 */
class Scoreboard
{
    const ESC = "\x1b[";
    const NND = "\x1b[0m";

    private $position = 0;
    private $width;
    private $height;

    private $cats;
    private $rainbow;

    public function __construct($writer, Cat $cat, Rainbow $rainbow, array $teams = array(), $width = 5)
    {
        $this->cat = $cat;
        $this->rainbow = $rainbow;

        $this->setTeams($teams);
        $this->setWriter($writer);
        $this->setWidth($width);
        $this->setHeight();
    }

    private function setHeight()
    {
        $this->height = max(
            count($this->teams),
            $this->rainbow->getHeight(),
            $this->cat->getHeight()
        );
    }

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

    private function setWriter($writer)
    {
        if (!is_callable($writer)) {
            throw new \InvalidArgumentException(
                'Writer must be callable'
            );
        }

        $this->writer = $writer;
    }

    private function setWidth($width)
    {
        if (!is_int($width) || $width < 1) {
            throw new \InvalidArgumentException(
                'Width must be a positive interger'
            );
        }

        $this->width = $width;
    }

    public function start()
    {
        $this->score('start');
    }

    public function score($team, $points = 1)
    {
        if (isset($this->teams[$team])) {
            $this->teams[$team]->updateScore($points);
        }

        $this->drawScoreboard();
        $this->drawRainbow();
        $this->drawCat($team);
    }

    public function end()
    {
        $this->writeLines($this->height);
    }

    public function getPreamble($size)
    {
        $height_diff = $this->height - $size;
        return $height_diff > 0 ? floor($height_diff / 2) : 0;
    }

    public function getPostamble($size)
    {
        $height_diff = $this->height - $size;
        return $height_diff > 0 ? $height_diff - floor($height_diff / 2) : 0;
    }

    public function preamble($size)
    {
        $preamble = $this->getPreamble($size);
        if ($preamble > 0) {
            $this->writeLines($preamble);
        }
    }

    public function postamble($size)
    {
        $postamble = $this->getPostamble($size);
        if ($postamble > 0) {
            $this->writeLines($postamble);
        }

        $this->resetCursor();
    }

    protected function cursorUp($lines)
    {
        $this->write(self::ESC . $lines . 'A');
    }

    protected function resetCursor()
    {
        $this->cursorUp($this->height);
    }

    protected function writeLines($size)
    {
        if ($size > 0) {
            $this->write(str_repeat(" \n", $size));
        }
    }

    protected function write($string)
    {
        return call_user_func($this->writer, $string);
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

    protected function drawCat($status)
    {
        $cat = $this->cat->next(
            isset($this->teams[$status]) ? $this->teams[$status]->getEye() : '-'
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
}
