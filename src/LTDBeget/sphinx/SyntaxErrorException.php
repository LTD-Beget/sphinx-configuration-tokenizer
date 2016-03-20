<?php
/**
 * @author: Viskov Sergey
 * @date: 3/10/16
 * @time: 6:27 PM
 */

namespace LTDBeget\sphinx;


use Exception;
use LTDBeget\stringstream\StringStream;

/**
 * Class SyntaxErrorException
 * @package LTDBeget\sphinx\configurator\exceptions
 */
class SyntaxErrorException extends \Exception
{
    /**
     * SyntaxErrorException constructor.
     * @param StringStream $stream
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(StringStream $stream, int $code = 0, Exception $previous = null)
    {
        if($stream->isEnd()) {
            $stream->end();
        }

        $this->unexpected_char = (string) $stream->current();
        $this->error_line      = $this->getParseErrorLineNumber($stream);
        $message               = sprintf($this->messageTemplate, $this->unexpected_char, $this->error_line);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getErrorLine()
    {
        return $this->error_line;
    }

    /**
     * @return string
     */
    public function getUnexpectedChar()
    {
        return $this->unexpected_char;
    }

    /**
     * @internal
     * @param StringStream $stream
     * @return int
     */
    private function getParseErrorLineNumber(StringStream $stream) : int
    {
        $parse_error_char_position = $stream->position();
        $plain_data                = $stream->getString();
        $exploded_by_lines         = explode("\n", $plain_data);
        foreach ($exploded_by_lines as $key => $line) {
            $line_length = strlen($line) + 1;
            $parse_error_char_position -= $line_length;
            if ($parse_error_char_position < 0) {
                return $key + 1;
            }
        }

        return 1;
    }

    /**
     * @var int
     */
    private $error_line;
    /**
     * @var string
     */
    private $unexpected_char;

    /**
     * @var string
     */
    private $messageTemplate = "Parse error: syntax error, unexpected '%s' on line %d.";
}