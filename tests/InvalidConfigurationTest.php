<?php
/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 3:42
 */

namespace LTDBeget\sphinx;

use LTDBeget\sphinx\Tokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidConfigurationTest
 */
class InvalidConfigurationTest extends TestCase
{
    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'w' on line 1.
     */
    public function testNameConf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/name.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected '_' on line 1.
     */
    public function testTypeConf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/type.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'w' on line 1.
     */
    public function testInheritanceConf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/inheritance.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'w' on line 3.
     */
    public function testOptionNameConf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/option_name.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected '
' on line 16.
     */
    public function testWrongMultiLineConf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/wrong_multiline.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected '.' on line 7.
     */
    public function testWrongMultiLine2Conf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/wrong_multi_line_2.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected '.' on line 14.
     */
    public function testWrongMultiLine3Conf()
    {
        $config_path = (__DIR__. '/../sphinx/invalid/wrong_multi_line_3.conf');
        $plain_config = file_get_contents($config_path);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        Tokenizer::tokenize($plain_config);
    }
}
