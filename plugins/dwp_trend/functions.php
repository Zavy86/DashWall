<?php
/**
 * DWP Trend - Functions
 *
 * @package DashWall\Plugin\DWP Trend
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function pfc(&$return){
 $values=$return->input['values'];
 if(!$values){$values=10;}
 for($i=0;$i<$values;$i++){
  $rand=rand(5,25);
  $row[]=($i+1);
  $min[]=$rand-rand(3,5);
  $avg[]=$rand;
  $max[]=$rand+rand(3,5);
 }
 $response=array("row"=>$row,"min"=>$min,"avg"=>$avg,"max"=>$max);
 $return->output=$response;
 return;
}
?>