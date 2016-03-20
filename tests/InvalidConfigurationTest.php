<?php
/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 3:42
 */


use LTDBeget\sphinx\Tokenizer;

/**
 * Class InvalidConfigurationTest
 */
class InvalidConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'w' on line 1.
     */
    public function testNameConf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/name.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 's' on line 1.
     */
    public function testTypeConf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/type.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'w' on line 1.
     */
    public function testInheritanceConf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/inheritance.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'o' on line 3.
     */
    public function testOptionNameConf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/option_name.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected '
' on line 16.
     */
    public function testWrongMultiLineConf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/wrong_multiline.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'n' on line 7.
     */
    public function testWrongMultiLine2Conf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/wrong_multi_line_2.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }

    /**
     * @expectedException        \LTDBeget\sphinx\SyntaxErrorException
     * @expectedExceptionMessage Parse error: syntax error, unexpected 'n' on line 14.
     */
    public function testWrongMultiLine3Conf()
    {
        $config_path = realpath(__DIR__."/../sphinx/invalid/wrong_multi_line_3.conf");
        $plain_config = file_get_contents($config_path);
        Tokenizer::tokenize($plain_config);
    }
}
