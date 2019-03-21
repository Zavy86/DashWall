<?php
/**
 * DWP KPI - Functions
 *
 * @package DashWall\Plugin\DWP KPI
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function pfc(&$return){
 $output=new stdClass();
 $output->value=rand(100,999);
 $output->description="+10k ★";
 $return->output=$output;
 return;
}
?>