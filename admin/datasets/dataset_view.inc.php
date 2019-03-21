<?php
/**
 * Dataset View
 *
 * @package DashWall\Admin\Datasets
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // acquire variables
 $r_dataset=$_REQUEST['dataset'];
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Dataset ".substr($r_dataset,10));
 // set database
 $database=$GLOBALS['APP']->db;
 // fields query
 $fields_query=<<<EOS
SELECT
 `COLUMN_NAME` AS `field`
FROM `INFORMATION_SCHEMA`.`COLUMNS`
WHERE `TABLE_SCHEMA`='$database'
 AND `TABLE_NAME` LIKE '$r_dataset'
ORDER BY `ORDINAL_POSITION`
EOS;
 // get fields
 $fields=$GLOBALS['DB']->queryObjects($fields_query);
 // get datas
 $datas=$GLOBALS['DB']->queryObjects("SELECT * FROM `".$r_dataset."`");
 // build description list
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement("Dataset",api_tag("strong",$r_dataset));
 $dl->addElement("Rows",number_format(count($datas),0,",","."));
 // build table
 $table=new strTable("There is no dataset to show..");
 // add table headers
 foreach($fields as $field_f){$table->addHeader($field_f->field,"nowrap");}
 $table->addHeader("&nbsp;");
 // cycle all results
 foreach($datas as $data_f){
  // check selected
  if($data_f->id==$_REQUEST['id']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table->addRow($tr_class);
  foreach($fields as $field_f){$table->addRowField($data_f->{$field_f->field},"nowrap");}
  $table->addRowFieldAction("admin.php?mod=datasets&scr=dataset_edit&dataset=".$r_dataset."&id=".$data_f->id,api_icon("pencil"),"text-right");
 }
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($dl->render(6),"col-xs-12");
 // add grid row
 $grid->addRow();
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
