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

use Litus\CodeStyle\Fixer\LicenseFixer,
    Litus\CodeStyle\Fixer\SingleUseFixer,
    PhpCsFixer\Config as BaseConfig;

class Config extends BaseConfig
{
    public function __construct()
    {
        parent::__construct('litus', 'The configuration for Litus');

        $this->setRules(
            array(
                //'psr0' => true,

                // PSR-1
                'encoding' => true,
                'full_opening_tag' => true,

                // PSR-2
                'braces' => true,
                'elseif' => true,
                'single_blank_line_at_eof' => true,
                //'no_spaces_after_function_name' => true,
                'function_declaration' => true,
                'indentation_type' => true,
                'blank_line_after_namespace' => true,
                'line_ending' => true,
                'lowercase_constants' => true,
                'lowercase_keywords' => true,
                //'method_argument_space' => true,
                //'single_import_per_statement' => true,
                //'no_spaces_inside_parenthesis' => true,
                'no_closing_tag' => true,
                //'single_line_after_imports' => true,
                'no_trailing_whitespace' => true,
                'visibility_required' => true,

                // Symfony
                //'alias_functions' => true,
                //'blank_line_after_opening_tag' => true,
                'concat_space' => [
                    'spacing' => 'one',
                ],
                //'no_multiline_whitespace_around_double_arrow' => true,
                //'no_empty_statement' => true,
                //'simplified_null_return' => true,
                'no_extra_consecutive_blank_lines' => true,
                'include' => true,
                //'no_trailing_comma_in_list_call' => true,
                //'method_separation' => true,
                'trailing_comma_in_multiline_array' => true,
                //'no_leading_namespace_whitespace' => true,
                'new_with_braces' => true,
                //'no_blank_lines_after_class_opening' => true,
                //'no_blank_lines_after_phpdoc' => true,
                'object_operator_without_whitespace' => true,
                'binary_operator_spaces' => [
                    'operators' => [
                        '=>' => 'align_single_space_minimal',
                    ],
                ],
                'phpdoc_align' => true,
                'phpdoc_indent' => true,
                // 'phpdoc_inline_tag' => true,
                // 'phpdoc_no_access' => true,
                // 'phpdoc_no_empty_return' => true,
                // 'phpdoc_no_package' => true,
                // 'phpdoc_scalar' => true,
                // 'phpdoc_separation' => true,
                // 'phpdoc_summary' => true,
                // 'phpdoc_to_comment' => true,
                // 'phpdoc_trim' => true,
                // 'phpdoc_no_alias_tag' => true,
                // 'phpdoc_var_without_name' => true,
                // 'pre_increment' => true,
                // 'no_leading_import_slash' => true,
                // 'no_extra_consecutive_blank_lines' => true,
                'blank_line_before_return' => true,
                //'self_accessor' => true,
                'no_trailing_comma_in_singleline_array' => true,
                //'single_blank_line_before_namespace' => true,
                //'single_quote' => true,
                //'no_singleline_whitespace_before_semicolons' => true,
                'cast_spaces' => true,
                'standardize_not_equals' => true,
                'ternary_operator_spaces' => true,
                //'trim_array_spaces' => true,
                //'unary_operator_spaces' => true,
                //'no_unused_imports' => true,
                'no_whitespace_in_blank_line' => true,

                // Litus
                'Litus/license' => true,
                'Litus/single_use' => true,
            )
        );

        $this->registerCustomFixers(
            [ 
                new SingleUseFixer(),
            ]
        );
    }
    
    public function setLicense($file)
    {
        $this->registerCustomFixers(
            [
                new LicenseFixer($file),
            ]
        );

        return $this;
    }
}
