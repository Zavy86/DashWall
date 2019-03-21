<?php
/**
 * Datasource List
 *
 * @package DashWall\Admin\Datasources
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Datasources");
 // build table
 $table=new strTable("There is no datasource to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Code","nowrap");
 $table->addHeader("Datasource",null,"100%");
 // get datasources
 $datasources_array=array();
 $results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__datasources` ORDER BY `code` ASC");
 foreach($results as $result){$datasources_array[$result->id]=new Datasource($result);}
 // cycle all datasources
 foreach($datasources_array as $datasource_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement("admin.php?mod=datasources&scr=datasource_edit&idDatasource=".$datasource_fobj->id."&return_scr=datasource_list","fa-pencil","Edit this datasource");
  // add table datas
  $table->addRow();
  $table->addRowField(api_link("admin.php?mod=datasources&scr=datasource_view&idDatasource=".$datasource_fobj->id,api_icon("search"),"View datasource","hidden-link"),"nowrap");
  $table->addRowField(api_tag("samp",$datasource_fobj->code),"nowrap");
  $table->addRowField($datasource_fobj->description);
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
