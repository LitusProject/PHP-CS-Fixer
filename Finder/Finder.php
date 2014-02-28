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
use Litus\CodeStyle\Finder\Adapter\FileListAdapter;

class Finder extends DefaultFinder
{
    private $_files = null;

    protected function getFilesToExclude()
    {
        return array(
            'init_autoloader.php',
        );
    }

    public function in($dir)
    {
        if (!is_string($dir) || !file_exists($dir . '/.php_cs-files')) {
            return parent::in($dir);
        }

        $files = file($dir . '/.php_cs-files', FILE_IGNORE_NEW_LINES);

        // allow comments using #
        array_walk($files,
            function (&$file, $key) {
                if (false !== ($idx = strpos('#', $file))) {
                    $file = substr($file, 0, $idx);
                }
            }
        );

        // remove empty lines
        $files = array_filter($files);

        // store these files
        $this->_files = $files;

        return parent::in($dir);
    }

    public function getIterator()
    {
        if (null !== $this->_files) {
            $adapter = new FileListAdapter($this->_files);
            $this->addAdapter($adapter, 100)
                ->setAdapter('litus');
        }

        return parent::getIterator();
    }
}
