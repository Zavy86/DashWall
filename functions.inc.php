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
 if(!is_array($_SESSION['dashwall'])){
  $_SESSION['dashwall']=array();
  $_SESSION['dashwall']['authenticated']=false;
 }
 // check for application session alerts array
 if(!is_array($_SESSION['dashwall']['alerts'])){$_SESSION['dashwall']['alerts']=array();}
}

/**
 * Check Authorizations
 */
function checkAuthorizations(){
  if(!strpos($_SERVER['REQUEST_URI'],"/admin.php")){header("location: ../admin.php");}
  if(!$_SESSION['dashwall']['authenticated']){
   // alert and redirect
   api_alert("Authentication expired","warning");
   api_redirect($GLOBALS['APP']->path."admin.php?mod=authentication");
  }
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
 * Redirect
 *
 * @param string $location Location URL
 */
function api_redirect($location){
 if(DEBUG){
  echo "<div class='redirect'>".api_tag("strong","REDIRECT")."<br>".api_link($location,$location)."</div>";
  echo "<link href=\"".$GLOBALS['APP']->path."styles/admin.css\" rel=\"stylesheet\">\n";
  die();
 }
 exit(header("location: ".$location));
}

/**
 * Return Script
 *
 * @param string $default Default script
 * @return string|boolean Return script defined or false
 */
function api_return_script($default){
 if(!$default){return false;}
 // get return script
 $return=$_REQUEST['return_scr'];
 // if not found return default
 if(!$return){$return=$default;}
 // return
 return $return;
}

/**
 * Alert
 *
 * @param string $message Alert message
 * @param string $class Alert class [info|warning|error]
 * @return boolean
 */
function api_alert($message,$class="info"){
 // checks
 if(!$message){return false;}
 // build alert object
 $alert=new stdClass();
 $alert->timestamp=time();
 $alert->message=$message;
 $alert->class=$class;
 $_SESSION['dashwall']['alerts'][]=$alert;
 // return
 return true;
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
 * Link
 * @param string $url URL
 * @param string $label Label
 * @param string $title Title
 * @param string $class CSS class
 * @param booelan $popup Show popup title
 * @param string $confirm Show confirm alert box
 * @param string $style Style tags
 * @param string $tags Custom HTML tags
 * @param string $target Target window
 * @param string $id Link ID or random created
 * @return string link
 */
function api_link($url,$label,$title=null,$class=null,$popup=false,$confirm=null,$style=null,$tags=null,$target="_self",$id=null){
 if(!$url){return false;}
 if(!$label){return false;}
 if(!$id){$id=rand(1,99999);}
 if(substr($url,0,1)=="?"){$url="index.php".$url;}
 $return="<a id=\"link_".$id."\" href=\"".$url."\"";
 if($class){$return.=" class=\"".$class."\"";}
 if($style){$return.=" style=\"".$style."\"";}
 if($title){
  if($popup){$return.=" data-toggle=\"popover\" data-placement=\"top\" data-content=\"".$title."\"";}
  else{$return.=" title=\"".$title."\"";}
 }
 if($confirm){$return.=" onClick=\"return confirm('".addslashes($confirm)."')\"";}
 if($tags){$return.=" ".$tags;}
 $return.=" target=\"".$target."\">".$label."</a>";
 return $return;
}

/**
 * Parse URL to standard class
 *
 * @param string $url URL to parse
 * @return object Parsed
 */
function api_parse_url($url=null){
 // check url
 if(!$url){$url=(isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];}
 // build object
 $return=new stdClass();
 // parse url string into object
 foreach(parse_url($url) as $key=>$value){$return->$key=$value;}
 // parse query to array
 $return->query_array=array();
 parse_str($return->query,$return->query_array);
 // return
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
