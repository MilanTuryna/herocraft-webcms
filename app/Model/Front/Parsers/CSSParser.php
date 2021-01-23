<?php

namespace App\Model\Front\Parsers;

use App\Front\Parsers\Exceptions\SyntaxError;
use Sabberworm\CSS\CSSList\Document;
use Sabberworm\CSS;
use Sabberworm\CSS\Parser;

/**
 * Code parser for better manipulation with user-written code in forms.
 * Class CSSParser
 * @package App\Model\Front\Parsers
 */
class CSSParser
{
    const CLASS_SELECTOR = '.';

    private string $rawCode;
    private string $class;
    private bool $strictParsing;

    private Document $document;

    /**
     * CSSParser constructor.
     * @param string $rawCode
     * @param string $class
     * @param bool $strictParsing
     * @throws SyntaxError
     */
    public function __construct(string $rawCode, string $class, bool $strictParsing = true)
    {
        $this->rawCode = $rawCode;
        $this->class = $class;
        $this->strictParsing = $strictParsing;
        $parser = new Parser($this->rawCode, $this->strictParsing ? CSS\Settings::create()->beStrict() : null);
        try {
            $this->document = $parser->parse();
        } catch (CSS\Parsing\SourceException $sourceException) {
            throw new SyntaxError($sourceException->getMessage(), $sourceException->getLine());
        }
    }

    /**
     * Removing all selectors except $this->class
     * @return array
     */
    public function removeDisabledSelectors(): array {
        $deletedSelectors = [];
        foreach ($this->document->getAllDeclarationBlocks() as $block) {
            /**
             * @var $block CSS\RuleSet\DeclarationBlock
             * @var $oSelector CSS\Property\Selector
             */
            foreach ($block->getSelectors() as $oSelector) if(!str_contains($oSelector, self::CLASS_SELECTOR . $this->class)) {
                array_push($deletedSelectors, $oSelector);
                $block->removeSelector($oSelector);
            }
        }
        return $deletedSelectors;
    }

    /**
     * @param bool $minify
     * @return string
     */
    public function getComputedCode(bool $minify = true): string {
        try {
            $format = $minify ? CSS\OutputFormat::createCompact() : null;
            return $this->document->render($format);
        } catch (CSS\Parsing\OutputException $outputException) {
            return '';
        }
    }

    /**
     * @param string $selector
     * @return string
     */
    public static function parseClass(string $selector): string {
        return str_starts_with($selector, self::CLASS_SELECTOR) ? substr($selector, 1) : $selector;
    }

    /**
     * @return string
     */
    public function getRawCode(): string
    {
        return $this->rawCode;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}