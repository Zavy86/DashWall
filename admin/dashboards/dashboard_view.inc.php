<?php
/**
 * Dashboard View
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // get object
 $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
 if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Dashboard ".$dashboard_obj->title);
 // definitions
 $order_check=1;
 $order_problems=false;
 // build description list
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement("Code",api_tag("samp",$dashboard_obj->code));
 $dl->addElement("Dashboard",api_tag("strong",$dashboard_obj->title));
 $dl->addElement("Orientation",ucfirst($dashboard_obj->orientation));
 $dl->addElement("Theme",ucfirst($dashboard_obj->theme));
 // build table
 $table=new strTable("There is no tile to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Tiles",null,"100%");
 $table->addHeader("&nbsp;");
 // cycle all tiles
 foreach($dashboard_obj->getTiles() as $tile_fobj){
  // check for order problems
  if($order_check!=$tile_fobj->order){$order_problems=true;}$order_check++;
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement("admin.php?mod=".MODULE."&scr=dashboard_view&act=tile_edit&idDashboard=".$dashboard_obj->id."&idTile=".$tile_fobj->id,"fa-pencil","Edit this tile");
  if($tile_fobj->order<count($dashboard_obj->getTiles())){$ob->addElement("admin.php?mod=".MODULE."&scr=submit&act=dashboard_tile_move&to=down&idDashboard=".$dashboard_obj->id."&idTile=".$tile_fobj->id,"fa-arrow-down","Move down");}
  if($tile_fobj->order>1){$ob->addElement("admin.php?mod=".MODULE."&scr=submit&act=dashboard_tile_move&to=up&idDashboard=".$dashboard_obj->id."&idTile=".$tile_fobj->id,"fa-arrow-up","Move up");}
  // check selected
  if($tile_fobj->id==$_REQUEST['idTile']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table->addRow($tr_class);
  $table->addRowField($tile_fobj->order);
  $table->addRowField($tile_fobj->title." (".$tile_fobj->width."x".$tile_fobj->height.")");
  $table->addRowField($ob->render(11),"nowrap text-right");
 }
 // check for problems
 if($order_problems){api_alert("Problems were found in the ordering of the tiles.<br>Please reorder the tiles with the specific operation!","warning");}
 // include modals
 require_once("dashboard_view-tile.inc.php");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($dl->render(6),"col-xs-5");
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-7");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
