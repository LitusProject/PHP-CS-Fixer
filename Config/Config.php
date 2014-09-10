<?php
/**
 * Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
 * Built with all the love in the world by @bgotink, licensed under the GPL v3+.
 *
 * @author Bram Gotink <bram.gotink@litus.cc>
 *
 * @license GPL v3+ <https://gnu.org/licenses/gpl.html>
 */

namespace Litus\CodeStyle\Config;

use Symfony\CS\Config\Config as BaseConfig,
    Symfony\CS\FixerInterface as Fixer,
    Litus\CodeStyle\Fixer\License as LicenseFixer,
    Litus\CodeStyle\Fixer\SingleUseFixer,
    Litus\CodeStyle\Finder\Finder;

class Config extends BaseConfig
{
    public function __construct()
    {
        parent::__construct('litus', 'The configuration for Litus');

        $this->finder(new Finder());

        $this->level(Fixer::SYMFONY_LEVEL);
        $this->fixers(array(
            '-multiple_use',
            '-concat_without_spaces',
            '-unused_use',
        ));

        $this->addCustomFixer(new SingleUserFixer());
    }
    /**
     * Adds a fixer to check for the existence of a license.
     *
     * @param  string                         $file a file containing the unformatted license
     * @return \Litus\CodeStyle\Fixer\License
     */
    public function setLicense($file)
    {
        $this->addCustomFixer(new LicenseFixer($file));

        return $this;
    }

    public function excludeFile($file)
    {
        $this->finder->excludeFile($file);

        return $this;
    }
}
