<?php
/**
 * Table
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Table class
 */
class strTable{

	/** Properties */
	protected $id;
	protected $emptyrow;
	protected $class;
	protected $caption;
	protected $rows_array;
	protected $current_row;

	/**
	 * Table class
	 *
	 * @param string $emptyrow Text to show if no results
	 * @param string $class CSS class
	 * @param string $caption Table caption
	 * @param string $id Table ID, if null randomly generated
	 * @return boolean
	 */
	public function __construct($emptyrow='',$class='',$caption='',$id=null){
		$this->id="table_".($id?$id:api_random());
		$this->emptyrow=$emptyrow;
		$this->class=$class;
		$this->caption=$caption;
		$this->current_row=0;
		$this->rows_array=array();
		// initialize headers row array
		$this->rows_array["headers"]=array();
		return true;
	}

	/**
	 * Add Table Header
	 *
	 * @param string $label Label
	 * @param string $class CSS class
	 * @param string $width Width
	 * @param string $style Custom CSS
	 * @param string $tags Custom HTML tags
	 * @param string $order Query field for order
	 * @return boolean
	 */
	public function addHeader($label,$class='',$width='',$style='',$tags=''){
		if(!$label){return false;}
		// build header object
		$th=new stdClass();
		$th->label=$label;
		$th->class=$class;
		$th->width=$width;
		$th->style=$style;
		$th->tags=$tags;
		// add header to headers
		$this->rows_array["headers"][]=$th;
		return true;
	}

	/**
	 * Add Table Row
	 *
	 * @param string $class CSS class
	 * @param string $style Custom CSS
	 * @param string $tags Custom HTML tags
	 * @param string $id Row ID, if null randomly generated
	 * @return boolean
	 */
	public function addRow($class='',$style='',$tags='',$id=null){
		// build row object
		$tr=new stdClass();
		$tr->id="tr_".($id?$id:api_random());
		$tr->class=$class;
		$tr->style=$style;
		$tr->tags=$tags;
		$tr->fields_array=array();
		// add row to table
		$this->current_row++;
		$this->rows_array[$this->current_row]=$tr;
		return true;
	}

	/**
	 * Add Table Row Field
	 *
	 * @param string $content Content data
	 * @param string $class CSS class
	 * @param string $style Custom CSS
	 * @param string $tags Custom HTML tags
	 * @param string $id Field ID, if null randomly generated
	 * @return boolean
	 */
	function addRowField($content,$class='',$style='',$tags='',$id=null){
		if(!$this->current_row){echo "ERROR - Table->addRowField - No row defined";return false;}
		if(!$content){$content="&nbsp;";}
		// build field object
		$td=new stdClass();
		$td->id="td_".($id?$id:api_random());
		$td->content=$content;
		$td->class=$class;
		$td->style=$style;
		$td->tags=$tags;
		// checks
		if(is_int(strpos($td->class,"truncate-ellipsis"))){$td->content="<span>".$td->content."</span>";}
		// add field to row
		$this->rows_array[$this->current_row]->fields_array[]=$td;
		return true;
	}

	/**
	 * Add Table Row Field Action
	 *
	 * @param string $url Action URL
	 * @param string $label Button label
	 * @param string $class CSS class
	 * @param string $style Custom CSS
	 * @param string $tags Custom HTML tags
	 * @return boolean
	 */
	function addRowFieldAction($url,$label,$class='',$style='',$tags='',$id=null){
		if(!$this->current_row){echo "ERROR - Table->addRowFieldAction - No row defined";return false;}
		if(!$url){echo "ERROR - Table->addRowFieldAction - URL is required";return false;}
		if(!$label){$label="&nbsp;";}
		// build field object
		$td=new stdClass();
		$td->id="td_".($id?$id:api_random());
		$td->content=api_link($url,$label,null,"btn btn-default btn-xs");
		$td->class=$class;
		$td->style=$style;
		$td->tags=$tags;
		// add field to row
		$this->rows_array[$this->current_row]->fields_array[]=$td;
		return true;
	}

	/**
	 * Renderize table object
	 *
	 * @param integer $indentations Numbers of indentations spaces
	 * @return string HTML source code
	 */
	public function render($indentations=0){
		// check parameters
		if(!is_integer($indentations)){return false;}
		// definitions
		$return=null;
		// make ident spaces
		$ind=str_repeat(" ",$indentations);
		// open table
		$return.=$ind."<!-- table -->\n";
		$return.=$ind."<div class=\"table-responsive\">\n";
		$return.=$ind." <table id=\"".$this->id."\" class=\"table table-striped table-hover table-condensed ".$this->class."\">\n";
		// table caption
		if($this->caption){$return.=$ind."  <caption>".$this->caption."</caption>\n";}
		// open head
		if(array_key_exists("headers",$this->rows_array)){
			$return.=$ind."  <thead>\n";
			$return.=$ind."   <tr>\n";
			// cycle all headers
			foreach($this->rows_array["headers"] as $th){
				$return.=$ind."    <th";
				if($th->class){$return.=" class=\"".$th->class."\"";}
				if($th->width){$return.=" width=\"".$th->width."\"";}
				if($th->style){$return.=" style=\"".$th->style."\"";}
				if($th->tags){$return.=" ".$th->tags;}
				$return.=">".$th->label."</th>\n";
			}
			$return.=$ind."   </tr>\n";
			$return.=$ind."  </thead>\n";
		}
		// open body
		$return.=$ind."  <tbody>\n";
		foreach($this->rows_array as $row_id=>$tr){
			if($row_id=="headers"){continue;}
			// show rows
			$return.=$ind."   <tr id=\"".$tr->id."\"";
			if($tr->class){$return.=" class=\"".$tr->class."\"";}
			if($tr->style){$return.=" style=\"".$tr->style."\"";}
			if($tr->tags){$return.=" ".$tr->tags."";}
			$return.=">\n";
			// cycle all row fields
			foreach($tr->fields_array as $td){
				// show field
				$return.=$ind."    <td id=\"".$td->id."\"";
				if($td->class){$return.=" class=\"".$td->class."\"";}
				if($td->style){$return.=" style=\"".$td->style."\"";}
				if($td->tags){$return.=" ".$td->tags."";}
				$return.=">".$td->content."</td>\n";
			}
			$return.=$ind."   </tr>\n";
		}
		// show empty row text
		if(count($this->rows_array)==1 && $this->emptyrow){
			$return.=$ind."   <tr><td colspan=".count($this->rows_array["headers"]).">".$this->emptyrow."</td></tr>\n";
		}
		// closures
		$return.=$ind."  </tbody>\n";
		$return.=$ind." </table>\n";
		$return.=$ind."</div><!-- /table-responsive -->\n";
		// return HTML code
		return $return;
	}

}
