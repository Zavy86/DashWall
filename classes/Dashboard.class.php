<?php
/**
 * Dashboard
 *
 * @package DashWall\Classes
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Dashboard class
 */
class Dashboard{

 /** Properties */
 protected $id;
 protected $code;
 protected $title;
 protected $orientation;
 protected $theme;
 protected $plugins_array;
 protected $tiles_id_array;

 /**
  * Constructor
  */
 public function __construct($dashboard){
  // load object
  if(is_numeric($dashboard)){$dashboard=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `dashwall__dashboards` WHERE `id`='".$dashboard."'");}
  if(is_string($dashboard)){$dashboard=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `dashwall__dashboards` WHERE `code`='".$dashboard."'");}
  if(!$dashboard->id){return false;}
  // initialize properties
  $this->id=(int)$dashboard->id;
  $this->code=stripslashes($dashboard->code);
  $this->title=stripslashes($dashboard->title);
  $this->orientation=stripslashes($dashboard->orientation);
  $this->theme=stripslashes($dashboard->theme);
  $this->plugins_array=array();
  $this->tiles_id_array=array();
  // get tiles
  $tiles_results=$GLOBALS['DB']->queryObjects("SELECT `id`,`plugin` FROM `dashwall__tiles` WHERE `fkDashboard`='".$this->id."' ORDER BY `order` ASC");
  foreach($tiles_results as $tile){
   // add tile id to array and generate random id
   $this->tiles_id_array[$tile->id]=api_random();
   // add plugin to array if not exist
   if(!array_key_exists($tile->plugin,$this->plugins_array)){$this->plugins_array[$tile->plugin]=$tile->plugin;}
  }
  // return
  return $this->id;
 }

 /**
  * Get Property
  *
  * @param string $property Property name
  * @return type Property value
  */
 public function __get($property){return $this->$property;}

 /**
  * Get Tiles
  */
 public function getTiles(){
  //definitions
  $tiles_array=array();
  // get tiles
  $tiles_results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__tiles` WHERE `fkDashboard`='".$this->id."' ORDER BY `order` ASC");
  foreach($tiles_results as $tile){
   // load tile
   $tiles_array[$this->tiles_id_array[$tile->id]]=new Tile($tile);
  }
  // return
  return $tiles_array;
 }

 /**
  * Get Tile
  */
 public function getTile($tile){
  // load tile
  $tile_obj=new Tile($tile);
  // check tile dashboard
  if($tile_obj->fkDashboard!=$this->id){return false;}
  // return
  return $tile_obj;
 }

 /**
  * Renderize Dashboard
  *
  * @return mixed HTML source code or boolean
  */
 public function render($echo=true){
  // definitions
  $return="<!DOCTYPE html>\n";
  $return.="<html>\n";
  $return.=" <head>\n";
  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Roboto\" media=\"screen,projection\"/>\n";
  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$GLOBALS['APP']->path."helpers/font-awesome-4.7.0/css/font-awesome.min.css\">\n";
  $return.="  <link type=\"text/css\" rel=\"stylesheet\" href=\"".$GLOBALS['APP']->path."styles/dashboard.css".(DEBUG?"?".api_random():null)."\" media=\"screen,projection\"/>\n";
  $return.="  <link type=\"image/png\" rel=\"icon\" href=\"".$GLOBALS['APP']->path."styles/favicon.png\" sizes=\"any\"/>\n";
  $return.="  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0\"/>\n";
  $return.="  <title>".($this->title?$this->title." - ":null).$GLOBALS['APP']->settings_array['title']."</title>\n";
  $return.=" </head>\n";
  $return.=" <body class=\"".$this->theme."\">\n\n";
  $return.="  <center>\n\n";
  $return.="   <!-- dashboard -->\n";
  $return.="   <div class=\"dashboard ".$this->orientation." ".$this->theme."\">\n\n";
  // cycle all sections
  foreach($this->getTiles() as $uid=>$tile_f){
   $return.="    <!-- tile_".$uid." -->\n";
   $return.="    <div class=\"tile ".$this->theme." w".$tile_f->width." h".$tile_f->height." ".$tile_f->classes."\" id=\"tile_".$uid."\">\n";
   $return.="     <div class=\"tile-title\">".$tile_f->title."</div>\n";
   $return.="     <div class=\"tile-content\"><canvas id=\"canvas_".$uid."\"></canvas></div>\n";
   $return.="    </div><!-- /tile_".$uid." -->\n\n";
  }
  $return.="   </div><!-- /dashboard -->\n\n";
  $return.="  </center>\n\n";
  // renderize scripts
  $return.="  <!-- external-scripts -->\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/jquery-3.3.1/js/jquery.min.js\"></script>\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/chartjs-2.7.3/js/chart.bundle.min.js\"></script>\n";
  $return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."helpers/jsurl-2.5.3/js/url.min.js\"></script>\n";
  $return.="  <!-- /external-scripts -->\n\n";
  $return.="  <!-- plugin-scripts -->\n";
  foreach($this->plugins_array as $plugin){$return.="  <script type=\"text/javascript\" src=\"".$GLOBALS['APP']->path."plugins/".$plugin."/script.js".(DEBUG?"?".api_random():null)."\"></script>\n";}
  $return.="  <!-- /plugin-scripts -->\n\n";
  $return.="  <!-- /tile-scripts -->\n";
  $return.="  <script type=\"text/javascript\">\n";
  $return.="  $(document).ready(function(){\n";
  foreach($this->getTiles() as $uid=>$tile_f){$return.="   var tile_".$uid."=new ".$tile_f->plugin."(".json_encode(array_merge(array("uid"=>$uid),$tile_f->parameters_array)).");\n";}
  $return.="  });\n";
  $return.="  </script><!-- /tile-scripts -->\n\n";
  // renderize closures
  $return.=" </body>\n";
  $return.="</html>\n";
  // return or echo
  if($echo){echo $return;}
  else{return $return;}
 }

}
