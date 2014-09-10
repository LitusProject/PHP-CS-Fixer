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

use Symfony\CS\Config\Config as BaseConfig;
use Litus\CodeStyle\Fixer\License as LicenseFixer;
use Litus\CodeStyle\Fixer\SingleUseFixer;
use Litus\CodeStyle\Finder\Finder;

class Config extends BaseConfig
{
    public function __construct()
    {
        parent::__construct('litus', 'The configuration for Litus');

        $this->finder(new Finder());

        $this->fixers(array(
            // PSR-0
            // 'psr0',

            // PSR-1
            'encoding',
            'short_tag',

            // PSR-2
            'braces',
            'elseif',
            'eof_ending',
            'function_declaration',
            'indentation',
            'line_after_namespace',
            'linefeed',
            'lowercase_constants',
            'lowercase_keywords',
            // 'multiple_use',
            'php_closing_tag',
            'trailing_spaces',
            'visibility',

            // Symfony
            'concat_without_spaces', // negated by 'concat_with_spaces' in Contrib
            'extra_empty_lines',
            'include',
            'multiline_array_trailing_comma',
            'new_with_braces',
            'object_operator',
            'operators_spaces',
            'phpdoc_params',
            'return',
            'single_array_no_trailing_comma',
            'spaces_cast',
            'standardize_not_equal',
            'ternary_spaces',
            // 'unused_use',
            'whitespacy_lines',

            // Contrib
            'concat_with_spaces',
            // 'ordered_use_fixer',
            // 'short_array_syntax',
            // 'strict',
        ));

        $this->addCustomFixer(new SingleUseFixer());
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
