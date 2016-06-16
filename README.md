# Scripts

This is a plugin for the [Fish IRC-Bot](https://github.com/nkreer/Fish) which allows you to create simple, dynamic commands.

## Installation

To install this, simply drop the _Scripts.phar_ into the _plugins_ directory. Then, either restart the bot or load the plugin manually.
You might want to change the configuration found in config.json if the language interpreters are not in your PATH.

## Custom Scripts

Custom scripts are script files located in "Scripts/scripts/".
Scripts can be written in any language your system can interpret.
This plugin passes some variables as shell-args when the script is executed. All output of a script will be sent to the channel where the command has been executed.
Below is a small example (you can find more in the scripts folder):

```php
<?php

$channel = $argv[1]; //The channel where the command has been sent in
$user = $argv[2]; //The nick of the user who executed the command
for($i = 0; $i <= 2; $i++) unset($argv[$i]);
//The rest of the $argv array contains the arguments that were passed by the user

echo "Hello ".$channel."! I am a script that was executed by ".$user."!";
```

which on the IRC can be used like this:

```
<User> !example_php
<Fish> Hello #channel! I am a script that was executed by <User>!
```

If a script fails for some reason, the bot will tell you its exit status.

## License

This code is released to the public domain