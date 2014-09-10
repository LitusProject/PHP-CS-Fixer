<?php
/**
 * Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
 * Built with all the love in the world by @bgotink, licensed under the GPL v3+.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 *
 * @license GPL v3+ <https://gnu.org/licenses/gpl.html>
 */

namespace Litus\CodeStyle\Fixer;

use Symfony\CS\FixerInterface as Fixer,
    SplFileInfo,
    RuntimeException;

class License implements Fixer
{
    const REGEXP_NO_LICENSE          = '/^<\?php\s*\r?\n\s*\r?\n/s';
    const REGEXP_LICENSE             = '/^<\?php\s*\r?\n(\/\*\*?\s*\r?\n.*?\r?\n\s*\*\/)/s';
    const REGEXP_TRAILING_WHITESPACE = '/\s*$/';
    const REGEXP_NEWLINE             = '/\r?\n$/m';

    /**
     * @var string
     */
    private $_license = null;

    public function __construct($file)
    {
        if (!file_exists($file))
            throw new RuntimeException('File ' . $file . ' doesn\'t exist');

        $lines = array();

        $lines[] = '/**';
        foreach (file($file) as $line)
            $lines[] = preg_replace(self::REGEXP_TRAILING_WHITESPACE, '', ' * ' . $line);
        $lines[] = ' */';

        $this->_license = implode("\n", $lines);
    }

    private function _getLicense()
    {
        if (null !== $this->_license)
            return $this->_license;
    }

    public function fix(SplFileInfo $file, $content)
    {
        $path = strtr($file->getRealPath(), '\\', '/');

        if (preg_match(self::REGEXP_NO_LICENSE, $content)) {
            echo 'Adding license to ' . $path . PHP_EOL;

            return preg_replace(self::REGEXP_NO_LICENSE, "<?php\n" . $this->_license . "\n\n", $content);
        }

        if (!preg_match(self::REGEXP_LICENSE, $content, $matches)) {
            echo 'File ' . $path . ' has a weird header:' . PHP_EOL;
            echo implode(PHP_EOL, array_slice(preg_split(self::REGEXP_NEWLINE, $content), 0, 5)) . PHP_EOL;

            return $content;
        }

        $matchedLicense = $matches[1];
        if (strcmp($matchedLicense, $this->_license) !== 0) {
            echo 'File ' . $path . ' has a different license header, replacing...' . PHP_EOL;

            return preg_replace(self::REGEXP_LICENSE, "<?php\n" . $this->_license, $content);
        }

        return $content;
    }

    public function getLevel()
    {
        return Fixer::CONTRIB_LEVEL;
    }

    /**
     * Run with lower priority -> trailing whitespace etc. already removed
     */
    public function getPriority()
    {
        return -10;
    }

    public function supports(SplFileInfo $file)
    {
        return 'php' == pathinfo($file->getFilename(), PATHINFO_EXTENSION);
    }

    public function getName()
    {
        return 'litus_license';
    }

    public function getDescription()
    {
        return 'PHP code must contain the Litus license header right after <?php';
    }
}
