<?php

/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 3:42
 */


use LTDBeget\sphinx\SyntaxErrorException;
use LTDBeget\sphinx\Tokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidConfigurationTest
 */
class InvalidConfigurationTest extends TestCase
{
    public function testNameConf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected 'w' on line 1.");

        $config = $this->getConfigContents('name');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testTypeConf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '_' on line 1.");

        $config = $this->getConfigContents('type');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testInheritanceConf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected 'w' on line 1.");

        $config = $this->getConfigContents('inheritance');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testOptionNameConf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected 'w' on line 3.");

        $config = $this->getConfigContents('option_name');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }
    public function testWrongMultiLineConf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '' on line 15.");

        $config = $this->getConfigContents('wrong_multiline');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testWrongMultiLine2Conf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '.' on line 7.");

        $config = $this->getConfigContents('wrong_multi_line_2');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testWrongMultiLine3Conf()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '.' on line 14.");

        $config = $this->getConfigContents('wrong_multi_line_3');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testEmptyIndexName()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '\n' on line 1.");

        $config = $this->getConfigContents('empty_index_name');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testEmptySourceName()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '\n' on line 1.");

        $config = $this->getConfigContents('empty_source_name');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testEmptyInheritanceName()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected '\n' on line 1.");

        $config = $this->getConfigContents('empty_inheritance_name');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    public function testEmptySourceInheritanceName()
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectExceptionMessage("Parse error: syntax error, unexpected ':' on line 1.");

        $config = $this->getConfigContents('empty_source_name_inheritance');
        /** @noinspection PhpUnhandledExceptionInspection */
        Tokenizer::tokenize($config);
    }

    private function getConfigContents(string $name): string
    {
        $path = sprintf('%s/../sphinx/invalid/%s.conf', __DIR__, $name);
        return file_get_contents($path);
    }
}
