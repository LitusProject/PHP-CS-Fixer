<?php
/**
 * Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
 * Built with all the love in the world by @bgotink, licensed under the GPL v3.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 *
 * @license GPL v3 <https://gnu.org/licenses/gpl.html>
 */

namespace Litus\CodeStyle\Finder;

use Symfony\CS\Finder\DefaultFinder;

class Finder extends DefaultFinder
{
    private $_excludedFiles = array();

    protected function getFilesToExclude()
    {
        return array_merge(array(
            'init_autoloader.php',
        ), $this->_excludedFiles);
    }

    public function excludeFile($file)
    {
        $this->_excludedFiles[] = $file;

        return $this;
    }
}
