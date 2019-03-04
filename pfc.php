<?php
/**
 * PFC (Plugin Function Call)
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
include("functions.inc.php");
// build return object
$return=new stdClass();
$return->error=false;
$return->errors=array();
$return->input=$_REQUEST;
$return->output=null;
// check for plugin functions
if(!file_exists(DIR."plugins/".$_REQUEST['plugin']."/functions.php")){
 $return->error=true;
 $return->errors[]="plugin_functions_not_found";
}else{
 // include plugin functions
 require_once("plugins/".$_REQUEST['plugin']."/functions.php");
 // call plugin main function
 pfc($return);
}
// encode and return
header("Content-Type: application/json; charset=utf-8");
echo json_encode($return);
