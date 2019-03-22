<?php
/**
 * Bootstrap
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

// includes
require_once(realpath(dirname(__FILE__))."/strGrid.class.php");
require_once(realpath(dirname(__FILE__))."/strNav.class.php");
require_once(realpath(dirname(__FILE__))."/strNavbar.class.php");
require_once(realpath(dirname(__FILE__))."/strTable.class.php");
require_once(realpath(dirname(__FILE__))."/strModal.class.php");
require_once(realpath(dirname(__FILE__))."/strDescriptionList.class.php");
require_once(realpath(dirname(__FILE__))."/strOperationsButton.class.php");
require_once(realpath(dirname(__FILE__))."/strForm.class.php");

/**
 * Bootstrap class
 */
class strBootstrap{

 /** Properties */
 protected $title;
 protected $sections_array;
 protected $styles_array;
 protected $scripts_array;
 protected $modals_array;

 /**
  * Constructor
  *
  */
 public function __construct(){
  // initialize properties
  $this->title="Bootstrap";
  $this->sections_array=array();
  $this->styles_array=array();
  $this->scripts_array=array();
  $this->modals_array=array();
 }

 /**
  * Get Property
  *
  * @param string $property Property name
  * @return type Property value
  */
 public function __get($property){return $this->$property;}

 /**
  * Set HTML Title
  *
  * @param string $title
  * @return boolean
  */
 public function setTitle($title){
  // check parameters
  if(!$title){return false;}
  // set html title
  $this->title=$title;
  // return
  return true;
 }

 /**
  * Add Section
  *
  * @param string $content
  * @param boolean $container
  * @return boolean
  */
 public function addSection($content,$classes=null,$id=null){
  // check parameters
  if(!$content){return false;}
  // build section object
  $section=new stdClass();
  $section->id="section_".($id?$id:api_random());
  $section->classes=$classes;
  $section->content=$content;
  // add content to sections array
  $this->sections_array[$section->id]=$section;
  // return
  return true;
 }

 /**
  * Add Style Sheet
  *
  * @param string $url URL of style sheet
  * @return boolean
  */
 public function addStyleSheet($url){
  if(!$url){return false;}
  $this->styles_array[]=$url;
  return true;
 }

 /**
  * Add Script
  *
  * @param string $source Source code or URL
  * @param booelan $url true if source is an URL
  * @return boolean
  */
 public function addScript($source=null,$url=false){
  if(!$source && !$url){return false;}
  // build script class
  $script=new stdClass();
  $script->url=(bool)$url;
  $script->source=$source;
  // add script to scripts array
  $this->scripts_array[]=$script;
  return true;
 }

 /**
  * Add Modal
  *
  * @param string $modal Modal window object
  * @param booelan $url true if source is an URL
  * @return boolean
  */
 public function addModal($modal){
  if(!is_a($modal,strModal)){return false;}
  // add modal to modals array
  $this->modals_array[$modal->id]=$modal;
  return true;
 }

 /**
  * Renderize Grid object
  *
  * @param boolean echo Print if true or return
  * @return mixed Return or print HTML source code
  */
 public function render($echo=true){
  // definitions
  $return="<!DOCTYPE html>\n";

  $return.="<html>\n";
  $return.=" <head>\n";

  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Roboto\" media=\"screen,projection\"/>\n";
  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$GLOBALS['APP']->path."helpers/bootstrap-3.4.1/css/bootstrap.min.css\" media=\"screen,projection\"/>\n";
  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$GLOBALS['APP']->path."helpers/font-awesome-4.7.0/css/font-awesome.min.css\" media=\"screen,projection\"/>\n";

  foreach($this->styles_array as $url_f){$return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$url_f."\">\n";}

  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$GLOBALS['APP']->path."styles/admin.css\" media=\"screen,projection\"/>\n";
  $return.="  <link type=\"image/png\" rel=\"icon\" href=\"".$GLOBALS['APP']->path."styles/favicon.png\" sizes=\"any\"/>\n";
  $return.="  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0\"/>\n";

  $return.="  <title>".($this->title?$this->title." - ":null)."Dash|Wall</title>\n";
  $return.=" </head>\n";
  $return.=" <body>\n";

  // cycle all sections
  foreach($this->sections_array as $section_fe){
   $return.="  <!-- ".$section_fe->id." -->\n";
   $return.="  <section class='".$section_fe->classes."' id='".$section_fe->id."'>\n";
   $return.=$section_fe->content;
   $return.="  </section><!-- /".$section_fe->id." -->\n";
  }

  // renderize modals
  if(count($this->modals_array)){
   $return.="  <!-- modal-windows -->\n";
   foreach($this->modals_array as $modal){$return.=$modal->render(3);}
   $return.="  <!-- /modal-windows -->\n";
  }

  // renderize scripts
  $return.="  <!-- scripts -->\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/jquery-3.3.1/js/jquery.min.js\"></script>\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/bootstrap-3.4.1/js/bootstrap.min.js\"></script>\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/bootstrap-notify-3.1.3/js/bootstrap-notify.min.js\"></script>\n";

  // renderize internal scripts
  $return.="\n  <!-- external-scripts -->\n";
  foreach($this->scripts_array as $script){if($script->url){$return.="  <script type=\"text/javascript\" src=\"".$script->source."\"></script>\n";}}
  $return.="  <!-- /external-scripts -->\n\n";
  $return.="  <!-- internal-scripts -->\n";

  $return.="<script type=\"text/javascript\">\n\n";
  foreach($this->scripts_array as $script){if(!$script->url){$return.=$script->source."\n\n";}}
  $return.="</script><!-- /internal-scripts -->\n\n";

  $return.="  <!-- /scripts -->\n";

  // cycle all alerts
  if(is_array($_SESSION['dashwall']['alerts']) && count($_SESSION['dashwall']['alerts'])){
   $return.="  <!-- alerts -->\n";
   $return.="  <script type=\"text/javascript\">\n";
   foreach($_SESSION['dashwall']['alerts'] as $index=>$alert){
    // swicth class
    switch($alert->class){
     case "success":$title="Success";break;
     case "warning":$title="Warning";break;
     case "danger":$title="Error";break;
     case "info":$title="Information";break;
    }
    // show alert
    $return.="   $.notify({title:\"<strong>".$title.":</strong>\",message:\"".$alert->message."\"},{type:\"".$alert->class."\"});\n";
    // remove from session
    unset($_SESSION['dashwall']['alerts'][$index]);
   }
   $return.="  </script>\n";
   $return.="  <!-- /alerts -->\n";
  }

  //  renderize closures
  $return.=" </body>\n";
  $return.="</html>\n";
  // print or return
  if($echo){echo $return;}
  else{return $return;}
 }

}
