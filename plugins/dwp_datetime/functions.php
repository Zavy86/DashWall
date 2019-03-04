<?php
/**
 * DWP DateTime - Functions
 *
 * @package DashWall\Plugin\DWP DateTime
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function pfc(&$return){
 $return->output=date($return->input['format']);
 return;
}
?>