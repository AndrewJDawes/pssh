<?php

require __DIR__ . '/vendor/autoload.php';


use phpseclib3\Net\SSH2;
use phpseclib3\System\SSH\Agent;


$config = [
    "user" => "pi",
    "hostname" => "192.168.0.61",
    "port" => "2223",
    "identityfile" => "~\/.ssh\/id_rsa"
];

$agent = new Agent;

$ssh = new SSH2($config['hostname'], $config['port']);
if (!$ssh->login($config['user'], $agent)) {
    throw new \Exception('Login failed');
}

echo $ssh->read('username@username:~$');

while (true) {
    $input = readline('');
    if ('pssh-quit' === $input) {
        break;
    }
    $command = $input . "\n";
    $ssh->write($command); // note the "\n"
    $output = $ssh->read('username@username:~$');
    $expression = '/^' . $input . '[\s]*/'; // Note no "\n"
    $output = preg_replace(
        $expression,
        '',
        $output,
        1
    );
    echo $output;
}

print('Exiting!');
