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

use RuntimeException,
    SplFileInfo,
    Symfony\CS\AbstractFixer,
    Symfony\CS\FixerInterface as Fixer,
    Symfony\CS\Tokenizer\Tokens;

class License extends AbstractFixer
{
    //const REGEXP_NO_LICENSE          = '/^<\?php\s*\r?\n\s*\r?\n/s';
    const REGEXP_NO_LICENSE          = '/^\r?\n\s*\r?\n/s';
    const REGEXP_PHP_HEADER          = '/^<\?php\s*\r?\n\s*/s';
    //const REGEXP_LICENSE             = '/^<\?php\s*\r?\n(\/\*\*?\s*\r?\n.*?\r?\n\s*\*\/)/s';
    const REGEXP_LICENSE             = '/^(\/\*\*?\s*\r?\n.*?\r?\n\s*\*\/)/s';
    const REGEXP_TRAILING_WHITESPACE = '/\s*$/';
    const REGEXP_NEWLINE             = '/\r?\n$/m';

    /**
     * @var string
     */
    private $_license = null;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new RuntimeException('File ' . $file . ' doesn\'t exist');
        }

        $lines = array();

        $lines[] = '/**';
        foreach (file($file) as $line) {
            $lines[] = preg_replace(self::REGEXP_TRAILING_WHITESPACE, '', ' * ' . $line);
        }
        $lines[] = ' */';

        $this->_license = implode("\n", $lines);
    }

    private function _getLicense()
    {
        if (null !== $this->_license) {
            return $this->_license;
        }
    }

    public function isCandidate(Tokens $tokens)
    {
        // TODO: This should probably be changed at some point.
        return true;
    }

    public function fix(SplFileInfo $file, Tokens $tokens)
    {
        $path = strtr($file->getRealPath(), '\\', '/');

        $phpHeaderToken = $tokens[0];
        $phpHeader = $phpHeaderToken->getContent();
        $licenseToken = $tokens[1];
        $license = $licenseToken->getContent();
        $licenseFooterToken = $tokens[2];
        $licenseFooter = $licenseFooterToken->getContent();

        if (!preg_match(self::REGEXP_PHP_HEADER, $phpHeader, $matches)) {
            echo 'File ' . $path . ' has a weird header, should be a PHP header:' . PHP_EOL;
            echo implode(PHP_EOL, array_slice(preg_split(self::REGEXP_NEWLINE, $phpHeader), 0, 5)) . PHP_EOL;

            return;
        }

        // Is there some kind of license?
        if (preg_match(self::REGEXP_LICENSE, $license, $matches)) {
            // Check if the license is correct
            $matchedLicense = $matches[1];
            if (strcmp($matchedLicense, $this->_license) !== 0) {
                echo 'File ' . $path . ' has a different license header, replacing...' . PHP_EOL;

                $licenseToken->setContent(preg_replace(self::REGEXP_LICENSE, $this->_license, $license));
            }
        } else {
            // Add a license
            echo 'Adding license to ' . $path . PHP_EOL;

            $licenseToken->setContent($this->_license . "\n\n" . ltrim($license));
        }

        // After the license should be an empty line
        if (trim($licenseFooter) != '') {
            $licenseFooterToken->setContent("\n\n" . ltrim($licenseFooter));
        }
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
        return 'license';
    }

    public function getDescription()
    {
        return 'PHP code must contain the Litus license header right after <?php';
    }
}
