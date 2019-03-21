<?php
/**
 * Loader
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

// include functions
require_once("functions.inc.php");

// initialize session
api_session_start();

// check debug from session
if($_SESSION['dashwall']['debug']){$debug=true;}

// check debug from requests
if(isset($_GET['debug'])){
 if($_GET['debug']==1){$debug=true;$_SESSION['dashwall']['debug']=true;}
 else{$debug=false;$_SESSION['dashwall']['debug']=false;}
}

// errors settings
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors",$debug);

// check for configuration file                                        /** @todo setup!
if(!file_exists(realpath(dirname(__FILE__))."/config.inc.php")){die("Dash|Wall is not configured..<br><br>Launch <a href='setup.php'>Setup</a> script!");}

// include configuration file
$configuration=new stdClass();
require_once("config.inc.php");

// definitions
define('DEBUG',$debug);
define('VERSION',file_get_contents("VERSION.txt"));
define("PATH",$configuration->path);
define('HOST',(isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST']);
define('ROOT',rtrim(str_replace("\\","/",realpath(dirname(__FILE__))."/"),PATH));
define('URL',HOST.PATH);
define('DIR',ROOT.PATH);

// include classes
require_once(DIR."classes/Application.class.php");
require_once(DIR."classes/Database.class.php");
require_once(DIR."classes/Datasource.class.php");
require_once(DIR."classes/Schedule.class.php");
require_once(DIR."classes/Dashboard.class.php");
require_once(DIR."classes/Tile.class.php");

// build database
global $DB;
$DB=new Database($configuration);

// build application
global $APP;
$APP=new Application($configuration);

// destroy configuration class
unset($configuration);
