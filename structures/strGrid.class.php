<?php
/**
 * Grid
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Grid class
 */
class strGrid{

 /** Properties */
 protected $class;
 protected $current_row;
 protected $rows_array;

 /**
  * Grid class
  *
  * @param string $class Grid css class
  * @return boolean
  */
 public function __construct($class=null){
  $this->class=$class;
  $this->current_row=0;
  $this->rows_array=array();
  return true;
 }

 /**
  * Add Row
  *
  * @param string $class Element css class
  * @return boolean
  */
 public function addRow($class=null){
  $row=new stdClass();
  $row->class=$class;
  $row->cols_array=array();
  $this->current_row++;
  $this->rows_array[$this->current_row]=$row;
  return true;
 }

 /**
  * Add Col
  *
  * @param string $content Col content
  * @param string $class Col css class (col-
  * @return boolean
  */
 public function addCol($content,$class=null){
  if(!$this->current_row){api_dump("ERROR - Grid->addCol - No rows defined");return false;}
  //if(!$content){api_dump("ERROR - Grid->addCol - Content is required");return false;}
  if(substr($class,0,4)!="col-"){api_dump("ERROR - Grid->addCol - Class \"col-..\" is required");return false;}
  $col=new stdClass();
  $col->content=$content;
  $col->class=$class;
  $this->rows_array[$this->current_row]->cols_array[]=$col;
  return true;
 }

 /**
  * Renderize Grid object
  *
  * @param boolean $container Renderize container
  * @param integer $indentations Numbers of indentations spaces
  * @return string HTML source code
  */
 public function render($container=true,$indentations=0){
  // check parameters
  if(!is_integer($indentations)){return false;}
  // definitions
  $return=null;
  // make ident spaces
  $ind=str_repeat(" ",$indentations);
  // renderize grid
  if($container){
   $return.=$ind."<!-- grid container -->\n";
   $return.=$ind."<div class='container ".$this->class."'>\n";
   $ind.=" ";
  }
  // cycle all grid rows
  foreach($this->rows_array as $row){
   // renderize grid rows
   $return.=$ind."<!-- grid-row -->\n";
   $return.=$ind."<div class='row ".$row->class."'>\n";
   // cycle all grid row cols
   foreach($row->cols_array as $col){
    // renderize grid row cols
    $return.=$ind." <!-- grid-row-col -->\n";
    $return.=$ind." <div class='".$col->class."'>\n";
    $return.=$col->content;
    $return.=$ind." </div><!-- /grid-row-col -->\n";
   }
   $return.=$ind."</div><!-- /grid-row -->\n";
  }
  if($container){$return.=substr($ind,0,-1)."</div><!-- /grid container -->\n";}
  // return
  return $return;
 }

}
?>