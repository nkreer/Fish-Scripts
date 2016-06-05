<?php

$channel = $argv[1]; //The channel where the command has been sent in
$user = $argv[2]; //The nick of the user who executed the command
for($i = 0; $i <= 2; $i++) unset($argv[$i]);
//The rest of the $argv array contains the arguments that were passed by the user

echo "Hello ".$channel."! I am a script that was executed by ".$user."!";