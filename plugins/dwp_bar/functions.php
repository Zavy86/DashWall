<?php
/**
 * DWP Bar - Functions
 *
 * @package DashWall\Plugin\DWP Bar
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function pfc(&$return){
 $values=$return->input['values'];
 if(!$values){$values=10;}
 for($i=0;$i<$values;$i++){
  $row[]=($i+1);
  $value[]=rand(5,25);
 }
 $response=array("row"=>$row,"value"=>$value);
 $return->output=$response;
 return;
}
?>
