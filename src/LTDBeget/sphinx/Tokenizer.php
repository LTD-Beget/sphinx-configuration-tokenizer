<?php
/**
 * @author: Viskov Sergey
 * @date: 3/10/16
 * @time: 1:33 PM
 */

namespace LTDBeget\sphinx;


use BadMethodCallException;
use LTDBeget\ascii\AsciiChar;
use LTDBeget\stringstream\StringStream;

/**
 * Class SphinxConfigurationParser
 * @package LTDBeget\sphinx\configurator\parser
 */
final class Tokenizer
{
    /**
     * parse and tokenize input string
     * @param string $plainData
     * @throws SyntaxErrorException
     * @throws BadMethodCallException
     * @return array
     */
    public static function tokenize(string $plainData) : array
    {
        return (new self($plainData))->tokenizeInternal()->tokens;
    }

    /**
     * SphinxConfigurationParser constructor.
     * @internal
     * @param string $string
     * @throws BadMethodCallException
     */
    private function __construct(string $string)
    {
        $string       = $this->removeComments($string);
        $this->stream = new StringStream($string);
    }

    /**
     * @internal
     * @param string $string
     * @return string
     */
    private function removeComments(string $string) : string
    {
        return preg_replace("/(^#| #|	#).*\n/im", "\n", $string);
    }

    /**
     * @internal
     * @return Tokenizer
     * @throws SyntaxErrorException
     */
    private function tokenizeInternal() : Tokenizer
    {
        do {
            $this->extractSection();
            $this->saveCurrentSection();

        } while (!$this->stream->isEnd());

        return $this;
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractSection()
    {
        $this->extractSectionType();

        switch ($this->currentSection['type']) {
            case 'source':
            case 'index':
                $this->extractSectionName();

                $this->extractInheritance();
                break;
            case 'indexer':
            case 'searchd':
            case 'common':
                break;
            default:
                throw new SyntaxErrorException($this->stream);
        }

        $this->extractOptions();


        $this->stream->ignoreWhitespace();
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractSectionType()
    {
        $this->stream->ignoreWhitespace();
        start:
        $char = $this->stream->currentAscii();
        $this->stream->next();
        if ($char->isLetter()) {
            $this->currentSection['type'] .= (string) $char;
            goto start;
        } elseif ($char->isWhiteSpace()) {
            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractSectionName()
    {
        $this->stream->ignoreHorizontalSpace();

        start:
        $char = $this->stream->currentAscii();
        $this->stream->next();

        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE())) {
            $this->currentSection['name'] .= (string) $char;
            goto start;
        } elseif ($char->isWhiteSpace()) {
            return;
        } elseif ($char->is(AsciiChar::COLON())) {
            $this->stream->previous();

            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractInheritance()
    {
        $this->stream->ignoreHorizontalSpace();

        $char = $this->stream->currentAscii();

        if ($char->isVerticalSpace() || $char->is(AsciiChar::OPENING_BRACE())) {
            return;
        }

        if ($char->is(AsciiChar::COLON())) {
            $this->stream->next();
            $this->extractInheritanceName();
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractInheritanceName()
    {
        $this->stream->ignoreHorizontalSpace();
        start:
        $char = $this->stream->currentAscii();
        $this->stream->next();

        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE())) {
            $this->currentSection['inheritance'] .= (string) $char;
            goto start;
        } elseif ($char->isWhiteSpace()) {
            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractOptions()
    {
        $this->stream->ignoreWhitespace();

        if ($this->stream->currentAscii()->is(AsciiChar::OPENING_BRACE())) {
            $this->stream->next();

            start:
            $this->stream->ignoreWhitespace();

            if($this->stream->isEnd()) {
                throw new SyntaxErrorException($this->stream);
            }

            if ($this->stream->currentAscii()->is(AsciiChar::CLOSING_BRACE())) {
                $this->stream->next();

                return;
            }
            $this->extractOption();
            goto start;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractOption()
    {
        $this->extractOptionName();
        $this->extractOptionValue();
        $this->saveCurrentOption();
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractOptionName()
    {
        $this->stream->ignoreWhitespace();

        start:
        $char = $this->stream->currentAscii();
        $this->stream->next();

        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE())) {
            $this->currentOption['name'] .= (string) $char;
            goto start;
        } elseif ($char->isHorizontalSpace()) {
            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     */
    private function extractOptionValue()
    {
        $this->stream->ignoreHorizontalSpace();

        $char = $this->stream->currentAscii();
        $this->stream->next();

        if (!$char->is(AsciiChar::EQUALS())) {
            throw new SyntaxErrorException($this->stream);
        }

        $this->stream->ignoreHorizontalSpace();

        start:
        $char = $this->stream->currentAscii();
        $this->stream->next();

        if($this->stream->isEnd()) {
            throw new SyntaxErrorException($this->stream);
        }

        if ($char->isPrintableChar() || $char->isHorizontalSpace()) {

            if ($char->is(AsciiChar::BACKSLASH())) { // if possibility of multi-line
                $char = $this->stream->currentAscii();

                if ($char->isVerticalSpace()) { // multi-line opened
                    $this->currentOption['value'] .= (string) AsciiChar::BACKSLASH();
                    $this->currentOption['value'] .= (string) $char;
                    $this->stream->next();
                    goto start;
                } else { // backslash as mean symbol
                    $this->currentOption['value'] .= (string) AsciiChar::BACKSLASH();
                    goto start;
                }
            } else {
                $this->currentOption['value'] .= (string) $char;
                goto start;
            }
        } elseif ($char->isVerticalSpace()) {
            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     */
    private function saveCurrentSection()
    {
        $this->currentSection = array_filter($this->currentSection);
        $this->tokens[]       = $this->currentSection;
        $this->currentSection = $this->getEmptySectionData();
    }

    /**
     * @internal
     */
    private function saveCurrentOption()
    {
        $this->currentSection['options'][] = $this->currentOption;
        $this->currentOption               = $this->getEmptyOptionData();
    }

    /**
     * @internal
     * @return array
     */
    private function getEmptySectionData() : array
    {
        return [
            'type'        => '',
            'name'        => '',
            'inheritance' => '',
            'options'     => []
        ];
    }

    /**
     * @internal
     * @return array
     */
    private function getEmptyOptionData() : array
    {
        return [
            'name'  => '',
            'value' => ''
        ];
    }

    /**
     * @var StringStream
     */
    private $stream;

    /**
     * Result of tokenize input string
     * @var array
     */
    private $tokens = [];

    /**
     * temporary storage of tokens for one section
     * @var array
     */
    private $currentSection = [
        'type'        => '',
        'name'        => '',
        'inheritance' => '',
        'options'     => []
    ];
    /**
     * temporary storage of tokens for one option
     * @var array
     */
    private $currentOption = [
        'name'  => '',
        'value' => ''
    ];
}