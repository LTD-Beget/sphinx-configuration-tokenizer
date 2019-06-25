<?php
/**
 * @author: Viskov Sergey
 * @date: 20.03.16
 * @time: 3:13
 */

use LTDBeget\sphinx\Tokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidConfigurationTest
 */
class ValidConfigurationTest extends TestCase
{
    public function testValid()
    {
        $config = $this->getConfigContents('valid');
        $result = md5(serialize(Tokenizer::tokenize($config)));

        $this->assertEquals('c357008aa8dec1d7bcd4f42f62f3537b', $result);
    }

    public function testExample()
    {
        $config = $this->getConfigContents('sphinx');
        $result = md5(serialize(Tokenizer::tokenize($config)));

        $this->assertEquals('ddddfff0aba2a84ac0bdccd88df7e41c', $result);
    }

    public function testUnicode()
    {
        $config = $this->getConfigContents('unicode');
        $result = md5(serialize(Tokenizer::tokenize($config)));

        $this->assertEquals('2a589652bd1c299d62420f089b1207f9', $result);
    }

    private function getConfigContents(string $name): string
    {
        $path = sprintf('%s/../sphinx/%s.conf', __DIR__, $name);
        return file_get_contents($path);
    }
}
