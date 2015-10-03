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

use Litus\CodeStyle\Finder\Finder,
    Litus\CodeStyle\Fixer\License as LicenseFixer,
    Litus\CodeStyle\Fixer\SingleUseFixer,
    Symfony\CS\Config\Config as BaseConfig;

class Config extends BaseConfig
{
    public function __construct()
    {
        parent::__construct('litus', 'The configuration for Litus');

        $this->finder(new Finder());

        $this->setRules(
            array(
                //'psr0' => true,

                // PSR-1
                'encoding' => true,
                'short_tag' => true,

                // PSR-2
                'braces' => true,
                'elseif' => true,
                'eof_ending' => true,
                //'function_call_space' => true,
                'function_declaration' => true,
                'indentation' => true,
                'line_after_namespace' => true,
                'linefeed' => true,
                'lowercase_constants' => true,
                'lowercase_keywords' => true,
                //'method_argument_space' => true,
                //'multiple_use' => true,
                //'parenthesis' => true,
                'php_closing_tag' => true,
                //'single_line_after_imports' => true,
                'trailing_spaces' => true,
                'visibility' => true,

                // Symfony
                //'alias_functions' => true,
                //'blankline_after_open_tag' => true,
                'concat_with_spaces' => true,
                //'concat_without_spaces' => true,
                //'double_arrow_multiline_whitespaces' => true,
                //'duplicate_semicolon' => true,
                //'empty_return' => true,
                'extra_empty_lines' => true,
                'include' => true,
                //'list_commas' => true,
                //'method_separation' => true,
                'multiline_array_trailing_comma' => true,
                //'namespace_no_leading_whitespace' => true,
                'new_with_braces' => true,
                //'no_blank_lines_after_class_opening' => true,
                //'no_empty_lines_after_phpdocs' => true,
                'object_operator' => true,
                'operators_spaces' => true,
                'phpdoc_align' => true,
                'phpdoc_indent' => true,
                // 'phpdoc_inline_tag' => true,
                // 'phpdoc_no_access' => true,
                // 'phpdoc_no_empty_return' => true,
                // 'phpdoc_no_package' => true,
                // 'phpdoc_scalar' => true,
                // 'phpdoc_separation' => true,
                // 'phpdoc_short_description' => true,
                // 'phpdoc_to_comment' => true,
                // 'phpdoc_trim' => true,
                // 'phpdoc_type_to_var' => true,
                // 'phpdoc_var_without_name' => true,
                // 'pre_increment' => true,
                // 'remove_leading_slash_use' => true,
                // 'remove_lines_between_uses' => true,
                'return' => true,
                //'self_accessor' => true,
                'single_array_no_trailing_comma' => true,
                //'single_blank_line_before_namespace' => true,
                //'single_quote' => true,
                //'spaces_before_semicolon' => true,
                'spaces_cast' => true,
                'standardize_not_equal' => true,
                'ternary_spaces' => true,
                //'trim_array_spaces' => true,
                //'unalign_double_arrow' => true,
                //'unalign_equals' => true,
                //'unary_operators_spaces' => true,
                //'unused_use' => true,
                'whitespacy_lines' => true,

                // Litus
                'license' => true,
                'single_use' => true,
            )
        );

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
