<?php
namespace DlcBase\Code\Reflection;

use \ReflectionFunction;

/**
 * Closure reflection class
 */
class Closure extends ReflectionFunction
{
    /**
     * Get source code of closure
     *
     * @return string
     */
    public function getSourceCode($initialIdent = 4)
    {
        $file = explode(PHP_EOL, file_get_contents($this->getFileName()));

        $funcOpenLine  = $file[$this->getStartLine()-1];
        $funcOpenLine  = rtrim(substr($funcOpenLine, strpos($funcOpenLine, 'function')));

        $funcCloseLine = $file[$this->getEndLine()-1];
        $funcCloseLine = ltrim(substr($funcCloseLine, 0, strpos($funcCloseLine, '}')+1));

        $source = $funcOpenLine
                . $this->getContents($initialIdent, false)
                . $funcCloseLine;

        return $source;
    }

    /**
     * Get contents of closure
     *
     * @return string
     */
    public function getContents($initialIdent = 4, $trimFirstAndLastLineBreak = true)
    {
        $file      = explode(PHP_EOL, file_get_contents($this->getFileName()));
        $startLine = $this->getStartLine();
        $endLine   = $this->getEndLine()-1;
        $contents  = PHP_EOL;
        $subStrStart = false;

        while ($startLine<$endLine) {
            $line = $file[$startLine];

            if (!$subStrStart) {
                $numberOfWhiteSpace = strspn($line, ' ');
                $subStrStart        = $numberOfWhiteSpace - $initialIdent;
            }

            $contents .= substr(rtrim($line), $subStrStart) . PHP_EOL;

            $startLine++;
        }

        if ($trimFirstAndLastLineBreak) {
            $contents = ltrim(rtrim($contents, PHP_EOL), PHP_EOL);
        }

        return $contents;
    }
}