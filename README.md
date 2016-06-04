# Scripts

This is a plugin for the [Fish IRC-Bot](https://github.com/nkreer/Fish) which allows you to create simple, dynamic commands.

## Installation

To install this, simply drop the "Scripts/" folder into the "plugins/" directory. Then, either restart the bot or load the plugin manually.

## Custom Scripts

Custom scripts are PHP files located in "Scripts/scripts/". They contain PHP code.
This plugin passes some variables as shell-args when the script is executed. All output of a script will be sent to the channel where the command has been executed.
Below is a small example:

```php
<?php

$channel = $argv[1]; //The channel the command has been sent in
$user = $argv[2]; //The nick of the user that has executed the command
for($i = 0; $i <= 2; $i++) unset($argv[$i]);
//The rest of the $argv array contains the arguments that were passed by the user

echo "Hello ".$channel."! I am a script that was executed by ".$user."!";
```

which on the IRC can be used like this:

```
<User> !example
<Fish> Hello #channel! I am a script that was executed by <User>!
```

If a script fails for some reason, the bot will tell you its exit status.

## TODO

I'm planning to add support for other programming languages (like Python or Ruby) in the future.