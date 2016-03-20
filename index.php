<?php
use LTDBeget\sphinx\Tokenizer;

require(__DIR__ . '/vendor/autoload.php');


$config_path = realpath(__DIR__."/sphinx/invalid/wrong_multi_line_3.conf");
$plain_config = file_get_contents($config_path);
Tokenizer::tokenize($plain_config);

//try {
//    $config_path = realpath(__DIR__."/sphinx/invalid/wrong_multiline.conf");
//    $plain_config = file_get_contents($config_path);
//    Tokenizer::tokenize($plain_config);
//} catch(Exception $e) {
//    echo $e->getMessage().PHP_EOL;
//}

//try {
//    $config_path = realpath(__DIR__."/sphinx/invalid/wrong_multi_line_2.conf");
//    $plain_config = file_get_contents($config_path);
//    Tokenizer::tokenize($plain_config);
//} catch(Exception $e) {
//    echo $e->getMessage().PHP_EOL;
//}
//

