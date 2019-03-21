<?php
/**
 * Dataset List
 *
 * @package DashWall\Admin\Datasets
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Datasets");
 // set database
 $database=$GLOBALS['APP']->db;
 // datasets query
 $query=<<<EOS
SELECT
 `TABLE_NAME` AS `name`,
 SUM(`TABLE_ROWS`) AS `rows`,
 SUM(`DATA_LENGTH`+`INDEX_LENGTH`)/1024/1024 AS `size`
FROM `INFORMATION_SCHEMA`.`TABLES`
WHERE `TABLE_SCHEMA`='$database'
 AND `TABLE_NAME` LIKE 'datasets__%'
GROUP BY `TABLE_NAME`
ORDER BY `TABLE_NAME`
EOS;
 // get datasets
 $results=$GLOBALS['DB']->queryObjects($query);
 // build table
 $table=new strTable("There is no dataset to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Dataset",null,"100%");
 $table->addHeader("Rows","nowrap text-right");
 $table->addHeader("Size","nowrap text-right");
 // cycle all results
 foreach($results as $result_f){
  // add table datas
  $table->addRow();
  $table->addRowField(api_link("admin.php?mod=datasets&scr=dataset_view&dataset=".$result_f->name,api_icon("search"),"View dataset","hidden-link"),"nowrap");
  $table->addRowField($result_f->name,"nowrap");
  $table->addRowField("~".number_format($result_f->rows,0,",","."),"nowrap text-right");
  $table->addRowField("~".number_format($result_f->size,2,",",".")." MB","nowrap text-right");
 }
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
