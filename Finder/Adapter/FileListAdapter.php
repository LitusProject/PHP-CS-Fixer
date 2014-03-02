<?php
/**
 * Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
 * Built with all the love in the world by @bgotink, licensed under the GPL v3.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 *
 * @license GPL v3 <https://gnu.org/licenses/gpl.html>
 */

 /*
  * This file contains part of the Symfony\CS\Adapter\{AbstractFinder,Php}Adapter classes
  * This file is part of the Symfony package.
  *
  * (c) Fabien Potencier <fabien@symfony.com>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  */

namespace Litus\CodeStyle\Finder\Adapter;

use Symfony\Component\Finder\Adapter\AbstractAdapter,
    Symfony\Component\Finder\Iterator,
    Litus\CodeStyle\Iterator\FileIterator;

/**
 * An Adapter that "finds" files within a given list of files.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 */
class FileListAdapter extends AbstractAdapter
{
    /**
     * @var array
     */
    protected $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param string $dir
     *
     * @return \Iterator Result iterator
     */
    public function searchInDirectory($dir)
    {
        $iterator = new FileIterator(array_unique($this->files), $dir);

        if ($this->minDepth > 0 || $this->maxDepth < PHP_INT_MAX) {
            $iterator = new Iterator\DepthRangeFilterIterator($iterator, $this->minDepth, $this->maxDepth);
        }

        if ($this->mode) {
            $iterator = new Iterator\FileTypeFilterIterator($iterator, $this->mode);
        }

        if ($this->exclude) {
            $iterator = new Iterator\ExcludeDirectoryFilterIterator($iterator, $this->exclude);
        }

        if ($this->names || $this->notNames) {
            $iterator = new Iterator\FilenameFilterIterator($iterator, $this->names, $this->notNames);
        }

        if ($this->contains || $this->notContains) {
            $iterator = new Iterator\FilecontentFilterIterator($iterator, $this->contains, $this->notContains);
        }

        if ($this->sizes) {
            $iterator = new Iterator\SizeRangeFilterIterator($iterator, $this->sizes);
        }

        if ($this->dates) {
            $iterator = new Iterator\DateRangeFilterIterator($iterator, $this->dates);
        }

        if ($this->filters) {
            $iterator = new Iterator\CustomFilterIterator($iterator, $this->filters);
        }

        if ($this->sort) {
            $iteratorAggregate = new Iterator\SortableIterator($iterator, $this->sort);
            $iterator = $iteratorAggregate->getIterator();
        }

        if ($this->paths || $this->notPaths) {
            $iterator = new Iterator\PathFilterIterator($iterator, $this->paths, $this->notPaths);
        }

        return $iterator;
    }

    /**
     * Tests adapter support for current platform.
     *
     * @return Boolean
     */
    public function isSupported()
    {
        return true;
    }

    /**
     * Returns adapter name.
     *
     * @return string
     */
    public function getName()
    {
        return 'litus';
    }

    protected function canBeUsed()
    {
        return true;
    }
}
