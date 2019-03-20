<?php
/**
 * Nav
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Nav class
 */
class strNav{

 /** Properties */
 protected $title;
 protected $class;
 protected $container;
 protected $items_array;
 protected $current_item;

 /**
  * Nav class
  *
  * @param string $class CSS class ( nav-tabs | nav-pills | nav-stacked )
  * @param boolean $container Renderize container
  * @return boolean
  */
 public function __construct($class="nav-tabs",$container=true){
  $this->class=$class;
  $this->container=$container;
  $this->current_item=0;
  $this->items_array=array();
  return true;
 }

 /**
  * Set Title
  *
  * @return boolean
  */
 public function setTitle($title){
  if(!$title){return false;}
  $this->title=$title;
  return true;
 }

 /**
  * Add Item
  *
  * @param string $label Label
  * @param string $url URL
  * @param string $class CSS class
  * @param boolean $enabled Enabled
  * @return boolean
  */
 public function addItem($label,$url="#",$enabled=true,$class=null,$style=null,$tags=null,$target="_self"){
  $item=new stdClass();
  $item->label=$label;
  $item->url=$url;
  $item->urlParsed=api_parse_url($url);
  $item->enabled=$enabled;
  $item->class=$class;
  $item->style=$style;
  $item->tags=$tags;
  $item->target=$target;
  $item->subItems_array=array();
  // add item to nav
  $this->current_item++;
  $this->items_array[$this->current_item]=$item;
  return true;
 }

 /**
  * Add Sub Item
  *
  * @param string $label Label
  * @param string $url URL
  * @param string $class CSS class
  * @param string $confirm Show confirm alert box
  * @param boolean $enabled Enabled
  * @return boolean
  */
 public function addSubItem($label,$url,$enabled=true,$confirm=null,$class=null,$style=null,$tags=null,$target="_self"){
  if(!$this->current_item){echo "ERROR - Nav->addSubItem - No item defined";return false;}
  $subItem=new stdClass();
  $subItem->typology="item";
  $subItem->label=$label;
  $subItem->url=$url;
  $subItem->urlParsed=api_parse_url($url);
  $subItem->enabled=$enabled;
  $subItem->confirm=$confirm;
  $subItem->class=$class;
  $subItem->style=$style;
  $subItem->tags=$tags;
  $subItem->target=$target;
  // add sub item to item
  $this->items_array[$this->current_item]->subItems_array[]=$subItem;
  return true;
 }

 /**
  * Add Sub Separator
  *
  * @param string $class CSS class
  * @return boolean
  */
 public function addSubSeparator($class=null){
  if(!$this->current_item){echo "ERROR - Nav->addSubSeparator - No item defined";return false;}
  $subSeparator=new stdClass();
  $subSeparator->typology="separator";
  $subSeparator->enabled=true;
  $subSeparator->class=$class;
  // add sub item to item
  $this->items_array[$this->current_item]->subItems_array[]=$subSeparator;
  return true;
 }

 /**
  * Add Sub Header
  *
  * @param string $label Label
  * @param string $class CSS class
  * @return boolean
  */
 public function addSubHeader($label,$class=null){
  if(!$this->current_item){echo "ERROR - Nav->addSubHeader - No item defined";return false;}
  $subHeader=new stdClass();
  $subHeader->typology="header";
  $subHeader->label=$label;
  $subHeader->enabled=true;
  $subHeader->class=$class;
  // add sub item to item
  $this->items_array[$this->current_item]->subItems_array[]=$subHeader;
  return true;
 }

 /**
  * Renderize Nav object
  *
  * @param boolean $echo Echo Nav source code or return
  * @param integer $indentations Numbers of indentations spaces
  * @return boolean|string Nav source code
  */
 public function render($echo=false,$indentations=0){
  // check parameters
  if(!is_integer($indentations)){return false;}
  // definitions
  $return=null;
  // make ident spaces
  $ind=str_repeat(" ",$indentations);
  // calculate responsive min-width
  $min_width=strlen($this->title)*16;
  foreach($this->items_array as $item){
   if(substr($item->label,0,2)=="<i"){$min_width+=45;}
   else{$min_width+=(strlen($item->label)*7)+32;}
  }
  // check for container
  if($this->container){
   $return.=$ind."<!-- nav container -->\n";
   $return.=$ind."<div class='container'>\n";
   $return.=$ind." <!-- nav-responsive -->\n";
   $return.=$ind." <div class=\"nav-responsive\">\n";
   $ident="  ";
  }
  $return.=$ind.$ident."<!-- nav -->\n";
  $return.=$ind.$ident."<ul class=\"nav ".$this->class."\" style=\"min-width:".$min_width."px;\">\n";
  // title
  if($this->title){$return.=$ind.$ident." <li class=\"title\">".$this->title."</li>\n";}
  // cycle all items
  foreach($this->items_array as $item){
   // check for active
   $active=false;
   if(/*$item->urlParsed->query_array['mod']==MODULE && */$item->urlParsed->query_array['scr']==$_REQUEST['scr']){$active=true;}
   if(is_int(strpos($this->class,"nav-pills")) && defined('TAB') && $item->urlParsed->query_array['tab']!=TAB){$active=false;}
   if(count($item->subItems_array)){
    foreach($item->subItems_array as $subItem){
     if(/*$subItem->urlParsed->query_array['mod']==MODULE && */$subItem->urlParsed->query_array['scr']==$_REQUEST['scr']){$active=true;}
     if(is_int(strpos($this->class,"nav-pills")) && defined('TAB') && $subItem->urlParsed->query_array['tab']!=TAB){$active=false;}
    }
   }
   // lock url if active or disabled
   if($active||!$item->enabled){$item->url="#";}
   // make item class
   $item_class=null;
   if($active){$item_class.="active ";}
   if(!$item->enabled){$item_class.="disabled ";}
   if($item->class){$item_class.=$item->class;}
   // make item tags
   $item_tags=null;
   if($item->style){$item_tags.=" style=\"".$item->style."\"";}
   if($item->tags){$item_tags.=" ".$item->tags;}
   // check for sub items
   if(!count($item->subItems_array)){
    $return.=$ind.$ident." <li class=\"".$item_class."\"".$item_tags."><a href=\"".$item->url."\" target=\"".$item->target."\">".$item->label."</a></li>\n";
   }else{
    $return.=$ind.$ident." <li class=\"dropdown ".$item_class."\">\n";
    $return.=$ind.$ident."  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">".$item->label." <span class=\"caret\"></span></a>\n";
    $return.=$ind.$ident."  <ul class=\"test dropdown-menu ".$item->class."\">\n";
    // cycle all sub items
    foreach($item->subItems_array as $subItem){
     // check for sub active
     /*$sub_active=false;
     if(/*$subItem->urlParsed->query_array['mod']==MODULE && *$subItem->urlParsed->query_array['scr']==$_REQUEST['scr']){$sub_active=true;}
     if(is_int(strpos($this->class,"nav-pills")) && defined('TAB') && $subItem->urlParsed->query_array['tab']!=TAB){$sub_active=false;}*/
     // lock url if disabled
     //if($sub_active||!$subItem->enabled){$subItem->url="#";}
     if(!$subItem->enabled){$subItem->url="#";}
     // make sub item class
     $subItem_class=null;
     //if($sub_active){$subItem_class.="active ";}
     if(!$subItem->enabled){$subItem_class.="disabled ";}
     if($subItem->class){$subItem_class.=$subItem->class;}
     // make sub item tags
     $subItem_tags=null;
     if($subItem->style){$subItem_tags.=" style=\"".$subItem->style."\"";}
     if($subItem->tags){$subItem_tags.=" ".$subItem->tags;}
     // switch sub item typology
     switch($subItem->typology){
      case "item":$return.=$ind.$ident."   <li class=\"".$subItem_class."\"><a href=\"".$subItem->url."\" target=\"".$subItem->target."\"".($subItem->confirm?" onClick=\"return confirm('".addslashes($subItem->confirm)."')\"":null).">".$subItem->label."</a></li>\n";break;
      case "separator":$return.=$ind.$ident."   <li class=\"divider ".$subItem_class."\" role=\"separator\">&nbsp;</li>\n";break;
      case "header":$return.=$ind.$ident."   <li class=\"dropdown-header".$subItem_class."\">".$subItem->label."</li>\n";break;
     }
    }
    $return.=$ind.$ident."  </ul><!-- dropdown -->\n";
    $return.=$ind.$ident." </li>\n";
   }
  }
  // renderize closures
  $return.=$ind.$ident."</ul><!-- /nav -->\n";
  // check for container
  if($this->container){
   $return.=$ind." </div><!-- /nav-responsive -->\n";
   if(is_int(strpos($this->class,"nav-tabs"))){$return.=$ind."<br><!-- line break -->\n";}
   if(is_int(strpos($this->class,"nav-pills"))){$return.=$ind."<!-- thematic break -->\n<div class=\"row\"><div class=\"col-xs-12\"><hr></div></div>\n";}
   $return.=$ind."</div><!-- /container -->\n";
  }
  // echo or return
  if($echo){echo $return;return true;}else{return $return;}
 }

}
?>