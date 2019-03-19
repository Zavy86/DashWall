<?php
/**
 * Functions
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

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

// check for configuration file                                         @todo setup!
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

/**
 * Initialize session and setup default sessions variables
 */
function api_session_start(){
 // start php session
 session_start();
 // check for application session array
 if(!is_array($_SESSION['dashwall'])){$_SESSION['dashwall']=array();}
 // check for application session alerts array
 if(!is_array($_SESSION['dashwall']['alerts'])){$_SESSION['dashwall']['alerts']=array();}
}

/**
 * Dump a variable into a debug box (only if debug is enabled)
 *
 * @param string $variable Dump variable
 * @param string $label Dump label
 * @param string $class Dump class
 * @param boolean $force Force dump also if debug is disabled
 */
function api_dump($variable,$label=null,$class=null,$force=false){
 if(!DEBUG && !$force){return false;}
 echo "\n<!-- dump -->\n";
 echo "<pre class='debug ".$class."'>\n";
 if($label<>null){echo "<b>".$label."</b>\n";}
 if(is_string($variable)){$variable=str_replace(array("<",">"),array("&lt;","&gt;"),$variable);}
 print_r($variable);
 echo "</pre>\n<!-- /dump -->\n";
}

/**
 * Redirect (if debug is enabled show a redirect link)
 *
 * @param string $location Location URL
 */
function api_redirect($location){
 if(DEBUG){die("<a href=\"".$location."\">".$location."</a>");}
 exit(header("location: ".$location));
}

/*
 * Random generator
 *
 * @param integer $lenght Number of characters
 */
function api_random($lenght=9){
 // check parameters
 if(!is_int($lenght)){$lenght=9;}
 // definitions
 $return=null;
 $chars=array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h",
              "i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
              "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
              "S","T","U","V","W","X","Y","Z");
 // pick random character
 for($i=0;$i<$lenght;$i++){$return.=$chars[array_rand($chars)];}
 // return
 return $return;
}

/**
 * Tag
 *
 * @param string $tag
 * @param string $text
 * @param string $classes
 * @param string $style Style tags
 * @param string $tags Custom HTML tags
 * @return string|boolean
 */
function api_tag($tag,$text,$classes=null,$style=null,$tags=null){
 if(!strlen($text)){return false;}
 if(!$tag){return $text;}
 $html="<".$tag;
 if($classes){$html.=" class=\"".$classes."\"";}
 if($style){$html.=" style=\"".$style."\"";}
 if($tags){$html.=" ".$tags;}
 $html.=">".$text."</".$tag.">";
 return $html;
}

/**
 * Icon
 *
 * @param string $icon Glyphs
 * @param string $title Title popup
 * @param string $classes Additional classes
 * @return string|boolean Icon source code or false
 */
function api_icon($icon,$title=null,$classes=null){
 if($icon==null){return false;}
 $return="<i class=\"fa fa-".$icon." ".$classes."\"";
 if($title){$return.=" title=\"".$title."\"";}
 $return.="></i>";
 return $return;
}

/**
 * Timestamp Format
 *
 * @param integer $timestamp Unix timestamp
 * @param string $format Date Time format (see php.net/manual/en/function.date.php)        /** @todo integrare eurore/rome timezone
 * @return string|boolean Formatted timestamp or false
 */
function api_timestamp_format($timestamp,$format="Y-m-d H:i:s"){
 if(!is_numeric($timestamp) || $timestamp==0){return false;}
 // build date time object
 $datetime=new DateTime("@".$timestamp);
 // return date time formatted
 return $datetime->format($format);
}
