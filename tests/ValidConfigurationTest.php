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
        $config_path = realpath(__DIR__."/../sphinx/valid.conf");

        $plain_config = file_get_contents($config_path);
        $result = md5(serialize(Tokenizer::tokenize($plain_config)));

        $this->assertEquals("9e628e3723cb2b4f3d9ae8dcd473bf8b", $result);
    }

    public function testExample()
    {
        $config_path = realpath(__DIR__."/../sphinx/sphinx.conf");
        $plain_config = file_get_contents($config_path);
        $result = md5(serialize(Tokenizer::tokenize($plain_config)));

        $this->assertEquals("6b7c4a39dbc99d5139969beb925d593f", $result);
    }
}