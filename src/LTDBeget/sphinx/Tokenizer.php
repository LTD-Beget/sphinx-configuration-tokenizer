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
     * @throws \Hoa\Ustring\Exception
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function tokenize(string $plainData) : array
    {
        $tokens = [];

        if(! empty($plainData)) {
            $tokens = (new self($plainData))->tokenizeInternal()->tokens;
        }

        return $tokens;
    }

    /**
     * SphinxConfigurationParser constructor.
     * @internal
     * @param string $string
     * @throws BadMethodCallException
     * @throws \Hoa\Ustring\Exception
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
     * @throws \LogicException
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractSection()
    {
        $this->stream->ignoreWhitespace();

        $this->extractSectionType();

        switch ($this->currentSection['type']) {
            case 'source':
            case 'index':
                $this->stream->ignoreHorizontalSpace();
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

        $this->stream->ignoreWhitespace();
        
        $this->extractOptions();
        
        $this->stream->ignoreWhitespace();
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractSectionType()
    {
        start:
        $char = $this->stream->currentAscii();
        if ($char->isLetter()) {
            $this->currentSection['type'] .= $this->stream->current();
            $this->stream->next();
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
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractSectionName()
    {
        start:
        $char = $this->stream->currentAscii();
        
        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE)) {
            $this->currentSection['name'] .= $this->stream->current();
            $this->stream->next();
            goto start;
        } elseif ($char->isWhiteSpace() || $char->is(AsciiChar::COLON)) {
            if (empty($this->currentSection['name'])) {
                throw new SyntaxErrorException($this->stream);
            }

            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     * @throws \InvalidArgumentException
     * @throws \LogicException
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
            $this->stream->ignoreHorizontalSpace();
            $this->extractInheritanceName();
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractInheritanceName()
    {
        start:
        $char = $this->stream->currentAscii();
        
        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE)) {
            $this->currentSection['inheritance'] .= $this->stream->current();
            $this->stream->next();
            goto start;
        } elseif ($char->isWhiteSpace()) {
            if (empty($this->currentSection['inheritance'])) {
                throw new SyntaxErrorException($this->stream);
            }

            return;
        } else {
            throw new SyntaxErrorException($this->stream);
        }
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function extractOptions()
    {
        if ($this->stream->currentAscii()->is(AsciiChar::OPENING_BRACE)) {
            $this->stream->next();

            start:
            $this->stream->ignoreWhitespace();

            if($this->stream->isEnd()) {
                throw new SyntaxErrorException($this->stream);
            }

            if ($this->stream->currentAscii()->is(AsciiChar::CLOSING_BRACE)) {
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
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractOption()
    {
        $this->extractOptionName();
        $this->stream->ignoreHorizontalSpace();
        
        if (!$this->stream->currentAscii()->is(AsciiChar::EQUALS)) {
            throw new SyntaxErrorException($this->stream);
        }
        
        $this->stream->next();
        $this->stream->ignoreHorizontalSpace();
        
        $this->extractOptionValue();
        $this->saveCurrentOption();
    }

    /**
     * @internal
     * @throws SyntaxErrorException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function extractOptionName()
    {
        start:
        $char = $this->stream->currentAscii();

        if ($char->isLetter() || $char->isDigit() || $char->is(AsciiChar::UNDERSCORE)) {
            $this->currentOption['name'] .= $this->stream->current();
            $this->stream->next();
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
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    private function extractOptionValue()
    {
        start:
        $char = $this->stream->currentAscii();
        
        if($this->stream->isEnd()) {
            throw new SyntaxErrorException($this->stream);
        }

        if ($char->isPrintableChar() || $char->isHorizontalSpace()) {

            if ($char->is(AsciiChar::BACKSLASH)) { // if possibility of multi-line
                $this->stream->next();

                if ($this->stream->currentAscii()->isVerticalSpace()) { // multi-line opened
                    $this->currentOption['value'] .= chr(AsciiChar::BACKSLASH);
                    $this->currentOption['value'] .= $this->stream->current();
                    $this->stream->next();
                    goto start;
                } else { // backslash as mean symbol
                    $this->currentOption['value'] .= chr(AsciiChar::BACKSLASH);
                    goto start;
                }
            } else {
                $this->currentOption['value'] .= $this->stream->current();
                $this->stream->next();
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
