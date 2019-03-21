<?php
/**
 * Navbar
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Navbar structure class
 */
class strNavbar{

 /** Properties */
 protected $title;
 protected $class;
 protected $navs_array;
 protected $current_nav;
 protected $current_element;

 /**
  * Navbar class
  *
  * @param string $title Navbar title
  * @param string $class Navbar class
  * @return boolean
  */
 public function __construct($title=null,$class="navbar-default"){
  $this->title=$title;
  $this->class=$class;
  $this->current_nav=0;
  $this->current_element=0;
  $this->navs_array=array();
  return true;
 }

 /**
  * Set Title
  *
  * @param string $title Navbar title
  * @return boolean
  */
 public function setTitle($title=null){
  if(!$title){return false;}
  $this->title=$title;
  return true;
 }

 /**
  * Add Nav
  *
  * @param string $class Element css class
  * @return boolean
  */
 public function addNav($class=null){
  $nav=new stdClass();
  $nav->class=$class;
  $nav->elements_array=array();
  // add nav to navbar
  $this->current_nav++;
  $this->navs_array[$this->current_nav]=$nav;
  return true;
 }

 /**
  * Add Element
  *
  * @param string $label Element label
  * @param string $url Element url
  * @param string $class Element css class
  * @param boolean $enabled Enabled
  * @return boolean
  */
 public function addElement($label,$url="#",$enabled=true,$class=null,$style=null,$tags=null,$target="_self"){
  if(!$this->current_nav){echo "ERROR - Navbar->addElement - No nav defined";return false;}
  $element=new stdClass();
  $element->label=$label;
  $element->url=$url;
  $element->enabled=$enabled;
  $element->class=$class;
  $element->style=$style;
  $element->tags=$tags;
  $element->target=$target;
  $element->subElements_array=array();
  // check, parse and convert
  if(substr($element->url,0,1)=="?"){$element->url="index.php".$element->url;}
  $element->urlParsed=api_parse_url($url);
  // add element to nav
  $this->current_element++;
  $this->navs_array[$this->current_nav]->elements_array[$this->current_element]=$element;
  return true;
 }

 /**
  * Add Sub Element
  *
  * @param string $label Element label
  * @param string $url Element url
  * @param string $class Element css class
  * @param boolean $enabled Enabled
  * @return boolean
  */
 public function addSubElement($label,$url,$enabled=true,$class=null,$style=null,$tags=null,$target="_self"){
  if(!$this->current_element){echo "ERROR - Navbar->addSubElement - No element defined";return false;}
  $subElement=new stdClass();
  $subElement->typology="element";
  $subElement->label=$label;
  $subElement->url=$url;
  $subElement->urlParsed=api_parse_url($url);
  $subElement->enabled=$enabled;
  $subElement->class=$class;
  $subElement->style=$style;
  $subElement->tags=$tags;
  $subElement->target=$target;
  // add sub element to element
  $this->navs_array[$this->current_nav]->elements_array[$this->current_element]->subElements_array[]=$subElement;
  return true;
 }

 /**
  * Add Sub Separator
  *
  * @param string $class Separator css class
  * @return boolean
  */
 public function addSubSeparator($class=null){
  if(!$this->current_element){echo "ERROR - Navbar->addSubSeparator - No element defined";return false;}
  $subSeparator=new stdClass();
  $subSeparator->typology="separator";
  $subSeparator->enabled=true;
  $subSeparator->class=$class;
  // add sub element to element
  $this->navs_array[$this->current_nav]->elements_array[$this->current_element]->subElements_array[]=$subSeparator;
  return true;
 }

 /**
  * Add Sub Header
  *
  * @param string $label Element label
  * @param string $class Separator css class
  * @return boolean
  */
 public function addSubHeader($label,$class=null){
  if(!$this->current_element){echo "ERROR - Navbar->addSubHeader - No element defined";return false;}
  $subHeader=new stdClass();
  $subHeader->typology="header";
  $subHeader->label=$label;
  $subHeader->enabled=true;
  $subHeader->class=$class;
  // add sub element to element
  $this->navs_array[$this->current_nav]->elements_array[$this->current_element]->subElements_array[]=$subHeader;
  return true;
 }

 /**
  * Renderize Navbar object
  *
  * @param integer $indentations Numbers of indentations spaces
  * @return string HTML source code
  */
 public function render($indentations=0){
  // check parameters
  if(!is_integer($indentations)){return false;}
  // definitions
  $return=null;
  // make ident spaces
  $ind=str_repeat(" ",$indentations);
  // renderize navbar
  $return.=$ind."<!-- navbar -->\n";
  $return.=$ind."<nav class=\"navbar ".$this->class."\">\n";
  $return.=$ind." <!-- navbar-container -->\n";
  $return.=$ind." <div class=\"container\">\n";
  // renderize navbar-header
  $return.=$ind."  <!-- navbar-header -->\n";
  $return.=$ind."  <div class=\"navbar-header\">\n";
  $return.=$ind."   <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">\n";
  $return.=$ind."    <span class=\"sr-only\">Toggle navigation</span>\n";
  $return.=$ind."    <span class=\"icon-bar\"></span>\n";
  $return.=$ind."    <span class=\"icon-bar\"></span>\n";
  $return.=$ind."    <span class=\"icon-bar\"></span>\n";
  $return.=$ind."   </button>\n";
  $return.=$ind."   <a class=\"navbar-brand\" id=\"nav_brand_logo\" href=\"index.php\"><img alt=\"Dash|Wall Logo\" src=\"".$GLOBALS['APP']->path."styles/favicon.png"."\" height=\"20\"></a>\n";
  $return.=$ind."   <a class=\"navbar-brand\" id=\"nav_brand_title\" href=\"index.php\">".$this->title."</a>\n";
  $return.=$ind."  </div><!--/navbar-header -->\n";
  // renderize navbar collapse
  $return.=$ind."  <!-- navbar-collapse-->\n";
  $return.=$ind."  <div id=\"navbar\" class=\"navbar-collapse collapse\">\n";
  // cycle all navs
  foreach($this->navs_array as $nav){
   $return.=$ind."   <ul class=\"nav navbar-nav ".$nav->class."\">\n";
   // cycle all elements
   foreach($nav->elements_array as $element){
    // check for active
    $active=false;
    if($element->urlParsed->query_array['mod']==MODULE){$active=true;}
    //if($element->urlParsed->query_array['scr']&&$element->urlParsed->query_array['scr']!=SCRIPT){$active=false;}
    elseif(count($element->subElements_array)){
     foreach($element->subElements_array as $subElement){
      if($subElement->urlParsed->query_array['mod']==MODULE){$active=true;}
      //if($subElement->urlParsed->query_array['scr']&&$subElement->urlParsed->query_array['scr']!=SCRIPT){$sub_active=false;}
      if($active){break;}
     }
    }
    if(is_int(strpos($element->class,"inactive"))){$active=false;}
    // lock url if active or disabled
    if($active||!$element->enabled){$element->url="#";}
    // make element class
    $element_class=null;
    if($active){$element_class.="active ";}
    if(!$element->enabled){$element_class.="disabled ";}
    if($element->class){$element_class.=$element->class;}
    // make element tags
    $element_tags=null;
    if($element->style){$element_tags.=" style=\"".$element->style."\"";}
    if($element->tags){$element_tags.=" ".$element->tags;}
    // check for sub elements
    if(!count($element->subElements_array)){
     $return.=$ind."    <li class=\"".$element_class."\"".$element_tags."><a href=\"".$element->url."\" target=\"".$element->target."\">".$element->label."</a></li>\n";
    }else{
     $return.=$ind."    <li class=\"dropdown ".$element_class."\">\n";
     $return.=$ind."     <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">".$element->label." <span class=\"caret\"></span></a>\n";
     $return.=$ind."     <ul class=\"dropdown-menu ".$element->class."\">\n";
     // cycle all sub elements
     foreach($element->subElements_array as $subElement){
      // check for sub active
      if($subElement->urlParsed->query_array['mod']==MODULE){$sub_active=true;}else{$sub_active=false;}
      //if($subElement->urlParsed->query_array['scr']&&$subElement->urlParsed->query_array['scr']!=SCRIPT){$sub_active=false;}
      if(is_int(strpos($subElement->class,"inactive"))){$sub_active=false;}
      // lock url if active or disabled
      if($sub_active||!$subElement->enabled){$subElement->url="#";}
      // make sub element class
      $subElement_class=null;
      if($sub_active){$subElement_class.="active ";}
      if(!$subElement->enabled){$subElement_class.="disabled ";}
      if($subElement->class){$subElement_class.=$subElement->class;}
      // make sub element tags
      $subElement_tags=null;
      if($subElement->style){$subElement_tags.=" style=\"".$subElement->style."\"";}
      if($subElement->tags){$subElement_tags.=" ".$subElement->tags;}
      // switch typology
      switch($subElement->typology){
       case "element":$return.=$ind."      <li class=\"".$subElement_class."\"><a href=\"".$subElement->url."\" target=\"".$subElement->target."\">".$subElement->label."</a></li>\n";break;
       case "separator":$return.=$ind."      <li class=\"divider ".$subElement_class."\" role=\"separator\">&nbsp;</li>\n";break;
       case "header":$return.=$ind."      <li class=\"dropdown-header ".$subElement_class."\">".$subElement->label."</li>\n";break;
      }
     }
     $return.=$ind."     </ul><!-- dropdown -->\n";
     $return.=$ind."    </li>\n";
    }
   }
   $return.=$ind."   </ul>\n";
  }
  // renderize closures
  $return.=$ind."  </div><!-- /navbar-collapse -->\n";
  $return.=$ind." </div><!-- /navbar-container -->\n";
  $return.=$ind."</nav><!-- /navbar -->\n";
  // return
  return $return;
 }

}
?>