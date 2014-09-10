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
    Symfony\CS\FixerInterface as Fixer,
    Symfony\CS\Tokens;

class SingleUseFixer implements Fixer
{
    public function fix(SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $uses = array_reverse($tokens->getNamespaceUseIndexes());

        $newUses = array();
        $firstIndex = null;
        $indentation = '';
        $first = true;

        foreach ($uses as $index) {
            if (null === $firstIndex) {
                $firstIndex = $index;
                $indentation = $this->detectIndent($tokens, $index);
            }

            $endIndex = null;
            $tokens->getNextTokenOfKind($index, array(';'), $endIndex);

            $declarationContent = $tokens->generatePartialCode($index + 1, $endIndex - 1);

            $declarationParts = explode(',', $declarationContent);

            foreach ($declarationParts as $declarationPart) {
                $newUses[] = trim($declarationPart);
            }

            for ($i = $index; $i <= $endIndex; ++$i) {
                $tokens[$i]->clear();
            }
        }

        if (null === $firstIndex) {
            // no use statements
            return $content;
        }

        // sort
        asort($newUses);

        $declarationContent = $indentation . 'use '
                . implode(",\n    " . $indentation, $newUses) . ';';

        $declarationTokens = Tokens::fromCode('<?php ' . $declarationContent);
        $declarationTokens[0]->clear();

        $tokens->insertAt($firstIndex, $declarationTokens);

        return $tokens->generateCode();
    }

    private function detectIndent(Tokens $tokens, $index)
    {
        $prevIndex = $index - 1;
        $prevToken = $tokens[$prevIndex];

        // if can not detect indent:
        if (!$prevToken->isWhitespace()) {
            return '';
        }

        $explodedContent = explode("\n", $prevToken->content);

        return end($explodedContent);
    }

    public function getLevel()
    {
        return Fixer::CONTRIB_LEVEL;
    }

    /**
     * Run at normal priority.
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * Supports all .php files.
     */
    public function supports(SplFileInfo $file)
    {
        return 'php' == pathinfo($file->getFilename(), PATHINFO_EXTENSION);
    }

    public function getName()
    {
        return 'single_use';
    }

    public function getDescription()
    {
        return 'There MUST be one use keyword per class and MUST be ordered.';
    }
}
