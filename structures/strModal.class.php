<?php
/**
 * Modal
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Modal class
 */
class strModal{

 /** Properties */
 protected $id;
 protected $title;
 protected $class;
 protected $header;
 protected $body;
 protected $footer;
 protected $size;

 /**
  * Modal window class
  *
  * @param string $title Title
  * @param string $class CSS class
  * @param string $id Modal window ID
  * @return boolean
  */
 public function __construct($title=null,$class=null,$id=null){
  if(!$id){$id=rand(1,99999);}
  $this->id="modal_".$id;
  $this->title=$title;
  $this->class=$class;
  $this->size="normal";
  return true;
 }

 /**
  * Get
  *
  * @param string $property Property name
  * @return string Property value
  */
 public function __get($property){return $this->$property;}

 /**
  * Set Title
  *
  * @param string $title Modal window title
  * @return boolean
  */
 public function setTitle($title){
  if(!$title){return false;}
  $this->title=$title;
  return true;
 }

 /**
  * Set Header
  *
  * @param string $content Content of the header
  * @return boolean
  */
 public function setHeader($content){
  if(!$content){return false;}
  $this->header=$content;
  return true;
 }

 /**
  * Set Body
  *
  * @param string $content Content of the body
  * @return boolean
  */
 public function SetBody($content){
  if(!$content){return false;}
  $this->body=$content;
  return true;
 }

 /**
  * Set Footer
  *
  * @param string $content Content of the footer
  * @return boolean
  */
 public function SetFooter($content){
  if(!$content){return false;}
  $this->footer=$content;
  return true;
 }

 /**
  * Set Size
  *
  * @param string $size Modal size [normal,small,large]
  * @return boolean
  */
 public function setSize($size){
  if(!in_array($size,array("normal","small","large"))){return false;}
  $this->size=strtolower($size);
  return true;
 }

 /**
  * Link
  * @param string $label Label
  * @param string $title Title
  * @param string $class CSS class
  * @param string $confirm Show confirm alert box
  * @param string $style Style tags
  * @param string $tags Custom HTML tags
  * @return string Link HTML source code
  */
 public function link($label,$title=null,$class=null,$confirm=null,$style=null,$tags=null){
  return api_link("#".$this->id,$label,$title,$class,false,$confirm,$style,"data-toggle='modal' ".$tags,"_self",$this->id);
 }

 /**
  * Renderize Modal object
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
  // make size
  switch($this->size){
   case "small":$size_class=" modal-sm";break;
   case "large":$size_class=" modal-lg";break;
   default:$size_class=null;
  }
  // build html source coide
  $return.=$ind."<!-- ".$this->id." -->\n";
  $return.=$ind."<div class=\"modal fade ".$this->class."\" id=\"".$this->id."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$this->id."-label\">\n";
  $return.=$ind." <div class=\"modal-dialog".$size_class."\" role=\"document\">\n";
  $return.=$ind."  <div class=\"modal-content\">\n";
  // renderize modal window header
  if($this->header || $this->title){
   $return.=$ind."   <div class=\"modal-header\">\n";
   $return.=$ind."    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
   // show title
   if($this->title){$return.=$ind."     <h4 class=\"modal-title\" id=\"".$this->id."-label\">".$this->title."</h4>\n";}
   $return.=$ind.$this->header."   </div>\n";
  }
  // renderize modal window body
  $return.=$ind."   <div class=\"modal-body\">\n".$this->body.$ind."   </div>\n";
  // renderize modal window footer
  if($this->footer){$return.=$ind."   <div class=\"modal-footer\">\n".$this->footer."   </div>\n";}
  $return.=$ind."  </div>\n";
  $return.=$ind." </div>\n";
  $return.=$ind."</div><!-- /".$this->id." -->\n";
  // return html source code
  return $return;
 }

}
?>