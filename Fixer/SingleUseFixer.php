<?php

namespace Litus\Fixer;

use SplFileInfo,
    Symfony\CS\FixerInterface as Fixer,
    Symfony\CS\Tokens,
    RuntimeException;

class SingleUseFixer implements Fixer
{
    public function fix(SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $uses = array_reverse($tokens->getNamespaceUseIndexes());

        $newUses = array();
        $firstIndex = null;
        $first = true;

        foreach ($uses as $index) {
            if (null === $firstIndex) {
                $firstIndex = $index;
            }

            $endIndex = null;
            $tokens->getNextTokenOfKind($index, array(';'), $endIndex);

            $declarationContent = $tokens->generatePartialCode($index + 1, $endIndex - 1);

            $declarationParts = explode(',', $declarationContent);

            foreach ($declarationParts as $declarationPart) {
                if ($first) {
                    $newUses[] = $this->detectIndent($tokens, $index) . 'use ' . trim($declarationPart);
                } else {
                    $newUses[] = ",\n    " . $this->detectIndent($tokens, $index) . trim($declarationPart);
                }
            }

            for ($i = $index; $i <= $endIndex; ++$i) {
                $tokens[$i]->clear();
            }
        }

        $declarationContent = implode('', $newUses) . ';';

        $declarationTokens = Tokens::fromCode('<?php '.$declarationContent);
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
     * Run at standard priority.
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
        return 'There MUST be one use keyword per class.';
    }
}
