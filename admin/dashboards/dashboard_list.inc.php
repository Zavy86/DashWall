<?php
/**
 * Dashboard List
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Dashboards");
 // build table
 $table=new strTable("There is no dashboard to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Code","nowrap");
 $table->addHeader("Dashboard",null,"100%");
 // get dashboards
 $dashboards_array=array();
 $results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__dashboards` ORDER BY `code` ASC");
 foreach($results as $result){$dashboards_array[$result->id]=new Dashboard($result);}
 // cycle all dashboards
 foreach($dashboards_array as $dashboard_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement("admin.php?mod=dashboards&scr=dashboard_edit&idDashboard=".$dashboard_fobj->id."&return_scr=dashboard_list","fa-pencil","Edit this dashboard");
  $ob->addElement("index.php?dashboard=".$dashboard_fobj->id,"fa-th-large","Preview this dashboard",true,null,null,null,null,"_blank");
  // check selected
  if($dashboard_fobj->id==$_REQUEST['idDashboard']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table->addRow($tr_class);
  $table->addRowFieldAction("admin.php?mod=dashboards&scr=dashboard_view&idDashboard=".$dashboard_fobj->id,api_icon("search","View dashboard"));
  $table->addRowField(api_tag("samp",$dashboard_fobj->code),"nowrap");
  $table->addRowField($dashboard_fobj->title);
  $table->addRowField($ob->render(11),"nowrap text-right");
 }
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
