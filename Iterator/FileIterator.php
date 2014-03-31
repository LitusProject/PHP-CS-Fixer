<?php
/**
 * Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
 * Built with all the love in the world by @bgotink, licensed under the GPL v3+.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 *
 * @license GPL v3+ <https://gnu.org/licenses/gpl.html>
 */

/*
 * This file contains part of Symfony\Component\Finder\Iterator\FilePathsIterator
 * of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed at <https://github.com/symfony/Finder/blob/master/LICENSE>.
 */

namespace Litus\CodeStyle\Iterator;

use ArrayIterator,
    Symfony\Component\Finder\SplFileInfo;

class FileIterator extends ArrayIterator
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var string
     */
    private $subPath;

    /**
     * @var string
     */
    private $subPathname;

    /**
     * @var SplFileInfo
     */
    private $current;

    public function __construct(array $paths, $baseDir)
    {
        $this->baseDir = substr($baseDir, -1) === '/' ? $baseDir : ($baseDir . '/');

        parent::__construct($paths);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(array($this->current(), $name), $arguments);
    }

    /**
     * Return an instance of SplFileInfo with support for relative paths.
     *
     * @return SplFileInfo File information
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->current->getPathname();
    }

    public function next()
    {
        parent::next();
        $this->buildProperties();
    }

    public function rewind()
    {
        parent::rewind();
        $this->buildProperties();
    }

    /**
     * @return string
     */
    public function getSubPath()
    {
        return $this->subPath;
    }

    /**
     * @return string
     */
    public function getSubPathname()
    {
        return $this->subPathname;
    }

    private function buildProperties()
    {
        $this->subPathname = parent::current();
        $dir = dirname($this->subPathname);
        $this->subPath = '.' === $dir ? '' : $dir;
        $absolutePath = $this->baseDir . $this->subPathname;

        $this->current = new SplFileInfo($absolutePath, $this->subPath, $this->subPathname);
    }
}
