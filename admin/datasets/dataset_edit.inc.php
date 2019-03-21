<?php
/**
 * Dataset Edit
 *
 * @package DashWall\Admin\Datasets
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // acquire variables
 $r_dataset=$_REQUEST['dataset'];
 $r_id=$_REQUEST['id'];
 // include template
 require_once("template.inc.php");
 // set database
 $database=$GLOBALS['APP']->db;
 // structures query
 $fields_query=<<<EOS
SELECT
 `COLUMN_NAME` AS `field`,
 `DATA_TYPE` AS `typology`
FROM `INFORMATION_SCHEMA`.`COLUMNS`
WHERE `TABLE_SCHEMA`='$database'
 AND `TABLE_NAME` LIKE '$r_dataset'
ORDER BY `ORDINAL_POSITION`
EOS;
 // get fields
 $fields=$GLOBALS['DB']->queryObjects($fields_query);
 // get data
 $data_obj=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `".$r_dataset."` WHERE `id`='".$r_id."'");
 // set title
 if(!$data_obj->id){$bootstrap->setTitle("Add new data to dataset ".substr($r_dataset,10));}
 else{$bootstrap->setTitle("Edit #".$data_obj->id." from dataset ".substr($r_dataset,10));}
 // build description list
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement("Dataset",api_tag("strong",$r_dataset));
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=dataset_save&dataset=".$r_dataset."&id=".$data_obj->id,"POST",null,"dataset_edit");
 $form->addField("hidden","id",null,$data_obj->id);
 // cycle all fields
 foreach($fields as $field_f){
  if($field_f->field=="id"){continue;}
  if($field_f->typology=="text"){$typology="textarea";}else{$typology="text";}
  $form->addField($typology,$field_f->field,$field_f->field,$data_obj->{$field_f->field});
 }
 $form->addControl("submit","Submit");
 $form->addControl("button","Cancel","admin.php?mod=".MODULE."&scr=".api_return_script("dataset_view")."&dataset=".$r_dataset);
 $form->addControl("button","Remove","admin.php?mod=".MODULE."&scr=submit&act=dataset_remove&dataset=".$r_dataset."&id=".$data_obj->id,"btn-danger","Are you sure you want to remove definitively this dataset?");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($form->render(0,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
