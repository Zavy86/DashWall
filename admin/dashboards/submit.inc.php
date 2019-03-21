<?php
/**
 * Submit
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // switch action
 switch(ACTION){
  // dashboards
  case "dashboard_save":dashboard_save();break;
  case "dashboard_remove":dashboard_remove();break;
  case "dashboard_tile_save":dashboard_tile_save();break;
  case "dashboard_tile_move":dashboard_tile_move();break;
  case "dashboard_tile_reorder":dashboard_tile_reorder();break;
  case "dashboard_tile_remove":dashboard_tile_remove();break;
  // default
  default:
   api_alert("Submit function for action <em>".ACTION."</em> was not found in module <em>".MODULE."</em>..","danger");
   api_redirect("admin.php?mod=".MODULE);
 }

 /**
  * Dashboard Save
  */
 function dashboard_save(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  // build query object
  $dashboard_qobj=new stdClass();
  $dashboard_qobj->id=$dashboard_obj->id;
  $dashboard_qobj->code=addslashes($_REQUEST['code']);
  $dashboard_qobj->title=addslashes($_REQUEST['title']);
  $dashboard_qobj->orientation=addslashes($_REQUEST['orientation']);
  $dashboard_qobj->theme=addslashes($_REQUEST['theme']);
  // check object
  if($dashboard_obj->id){
   // update
   //$dashboard_obj->updTimestamp=time();
   //$dashboard_obj->updFkUser=$GLOBALS['session']->user->id;
   // debug
   api_dump($dashboard_qobj,"dashboard query object");
   // execute query
   $GLOBALS['DB']->queryUpdate("dashwall__dashboards",$dashboard_qobj);
   // alert
   api_alert("Dashboard updated","success");
  }else{
   // insert
   //$dashboard_obj->addTimestamp=time();
   //$dashboard_obj->addFkUser=$GLOBALS['session']->user->id;
   // debug
   api_dump($dashboard_qobj,"dashboard query object");
   // execute query
   $dashboard_qobj->id=$GLOBALS['DB']->queryInsert("dashwall__dashboards",$dashboard_qobj);
   // alert
   api_alert("Dashboard created","success");
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=".api_return_script("dashboard_view")."&idDashboard=".$dashboard_qobj->id);
 }

 /**
  * Dashboard Remove
  */
 function dashboard_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  // check object
  if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
  // remove division
  $deleted=$GLOBALS['DB']->queryDelete("dashwall__dashboards",$dashboard_obj->id);
  // check query result
  if(!$deleted){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list&idDashboard=".$dashboard_obj->id);}
  // alert and redirect
  api_alert("Dashboard removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");
 }

 /**
  * Dashboard Tile Save
  */
 function dashboard_tile_save(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  $tile_obj=$dashboard_obj->getTile($_REQUEST['idTile']);
  api_dump($tile_obj,"tile object");
  // check object
  if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
  // acquire and encode parameters
  $parameters=json_decode($_REQUEST['parameters'],true);
  $parameters['refresh']=$_REQUEST['refresh'];
  // build query object
  $tile_qobj=new stdClass();
  $tile_qobj->id=$tile_obj->id;
  $tile_qobj->fkDashboard=$dashboard_obj->id;
  $tile_qobj->title=addslashes($_REQUEST['title']);
  $tile_qobj->width=$_REQUEST['width'];
  $tile_qobj->height=$_REQUEST['height'];
  $tile_qobj->plugin=addslashes($_REQUEST['plugin']);
  $tile_qobj->parameters=json_encode($parameters);
  // check object
  if($tile_obj->id){
   // update
   // debug
   api_dump($tile_qobj,"tile query object");
   // execute query
   $GLOBALS['DB']->queryUpdate("dashwall__tiles",$tile_qobj);
   // alert
   api_alert("Tile updated","success");
  }else{
   // insert
   $tile_qobj->order=count($dashboard_obj->getTiles())+1;
   // debug
   api_dump($tile_qobj,"tile query object");
   // execute query
   $tile_qobj->id=$GLOBALS['DB']->queryInsert("dashwall__tiles",$tile_qobj);
   // alert
   api_alert("Tile created","success");
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);
 }

 /**
  * Dashboard Tile Move
  */
 function dashboard_tile_move(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  $tile_obj=$dashboard_obj->getTile($_REQUEST['idTile']);
  api_dump($tile_obj,"tile object");
  // check object
  if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
  if(!$tile_obj->id){api_alert("Tile not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);}
  // build tile query object
  $tile_qobj=new stdClass();
  $tile_qobj->id=$tile_obj->id;
  //switch direction
  switch(strtolower($_REQUEST['to'])){
   // up -> order -1
   case "up":
    // set previous order
    $tile_qobj->order=$tile_obj->order-1;
    api_dump($tile_qobj,"tile query object");
    // check for order limits
    if($tile_qobj->order<1){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);}
    // update zone
    $GLOBALS['DB']->queryUpdate("dashwall__tiles",$tile_qobj);
    // rebase other zones
    api_dump($rebase_query="UPDATE `dashwall__tiles` SET `order`=`order`+'1' WHERE `order`<'".$tile_obj->order."' AND `order`>='".$tile_qobj->order."' AND `order`<>'0' AND `id`!='".$tile_obj->id."' AND `fkDashboard`='".$dashboard_obj->id."'","rebase_query");
    $GLOBALS['DB']->queryExecute($rebase_query);
    break;
   // down -> order +1
   case "down":
    // set following order
    $tile_qobj->order=$tile_obj->order+1;
    api_dump($tile_qobj,"tile query object");
    // update zone
    $GLOBALS['DB']->queryUpdate("dashwall__tiles",$tile_qobj);
    // rebase other zones
    api_dump($rebase_query="UPDATE `dashwall__tiles` SET `order`=`order`-'1' WHERE `order`>'".$tile_obj->order."' AND `order`<='".$tile_qobj->order."' AND `order`<>'0' AND `id`!='".$tile_obj->id."' AND `fkDashboard`='".$dashboard_obj->id."'","rebase_query");
    $GLOBALS['DB']->queryExecute($rebase_query);
    break;
   default:
    if($tile_qobj->order<1){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);}
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);
 }

  /**
  * Dashboard Tile Reorder
  */
 function dashboard_tile_reorder(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  // check object
  if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
  // definitions
  $order=1;
  // get dashboard tiles
  $tiles_results=$GLOBALS['DB']->queryObjects("SELECT `id` FROM `dashwall__tiles` WHERE `fkDashboard`='".$dashboard_obj->id."' ORDER BY `order` ASC");
  // cycle all tiles and update order
  foreach($tiles_results as $tile_f){$GLOBALS['DB']->queryExecute("UPDATE `dashwall__tiles` SET `order`='".$order."' WHERE `id`='".$tile_f->id."'");$order++;}
  // alert and redirect
  api_alert("Tiles reordered","success");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);
  api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);
 }

 /**
  * Dashboard Tile Remove
  */
 function dashboard_tile_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $dashboard_obj=new Dashboard($_REQUEST['idDashboard']);
  api_dump($dashboard_obj,"dashboard object");
  $tile_obj=$dashboard_obj->getTile($_REQUEST['idTile']);
  api_dump($tile_obj,"tile object");
  // check object
  if(!$dashboard_obj->id){api_alert("Dashboard not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_list");}
  if(!$tile_obj->id){api_alert("Tile not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);}
  // remove division
  $deleted=$GLOBALS['DB']->queryDelete("dashwall__tiles",$tile_obj->id);
  // check query result
  if(!$deleted){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);}
  // reorder tiles
  $GLOBALS['DB']->queryExecute("UPDATE `dashwall__tiles` SET `order`=`order`-'1' WHERE `fkDashboard`='".$dashboard_obj->id."' AND `order`>'".$tile_obj->order."' ORDER BY `order` ASC");
  // alert and redirect
  api_alert("Tile removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=dashboard_view&idDashboard=".$dashboard_obj->id);
 }
