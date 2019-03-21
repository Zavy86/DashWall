<?php
/**
 * Update
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 // load application
 require_once("loader.inc.php");
 // debug
 api_dump($APP);
 // check for git
 if(!is_dir($APP->dir.".git")){die("Git directory not found!");}
 // check for localhost
 if(in_array($_SERVER['HTTP_HOST'],array("localhost","127.0.0.1"))){die("Git pull denied on localhost!");}
 // make command
 $command="cd ".$APP->dir." ; pwd ; git stash 2>&1 ; git stash clear ; git pull 2>&1 ; chmod 755 -R ./";
 // exec shell commands
 $shell_output=exec('whoami')."@".exec('hostname').":".shell_exec($command);
 // debug
 echo "<b><code>".$command."</code></b><pre>".$shell_output."</pre>";
