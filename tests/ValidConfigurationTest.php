<?php
/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 3:13
 */

use LTDBeget\sphinx\Tokenizer;

/**
 * Class ValidConfigurationTest
 */
class ValidConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $config_path = __DIR__. '/../sphinx/valid.conf';

        $plain_config = file_get_contents($config_path);
        $result = md5(serialize(Tokenizer::tokenize($plain_config)));

        static::assertEquals('c357008aa8dec1d7bcd4f42f62f3537b', $result);
    }

    public function testExample()
    {
        $config_path = __DIR__. '/../sphinx/sphinx.conf';
        $plain_config = file_get_contents($config_path);
        $result = md5(serialize(Tokenizer::tokenize($plain_config)));

        static::assertEquals('ddddfff0aba2a84ac0bdccd88df7e41c', $result);
    }

    public function testUnicode()
    {
        $config_path = __DIR__. '/../sphinx/unicode.conf';
        $plain_config = file_get_contents($config_path);
        $result = md5(serialize(Tokenizer::tokenize($plain_config)));

        static::assertEquals('2a589652bd1c299d62420f089b1207f9', $result);
    }
}