<?php
/**
 * Description List
 *
 * @package DashWall\Structures
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Description List class
 */
class strDescriptionList{

	/** Properties */
	protected $id;
	protected $separator;
	protected $class;
	protected $elements_array;

	/**
	 * Description List
	 *
	 * @param string $separator Default elements separator ( null | hr | br )
	 * @param string $class CSS class
	 * @param string $id Table ID, if null randomly generated
	 * @return boolean
	 */
	public function __construct($separator='',$class='',$id=null){
		if(!in_array(strtolower($separator),array(null,"hr","br"))){return false;}
		$this->id="dl_".($id?$id:api_random());
		$this->class=$class;
		$this->separator=$separator;
		$this->elements_array=array();
		return true;
	}

	/**
	 * Add Element
	 *
	 * @param string $label Label
	 * @param string $content Content
	 * @param string $separator Element Separator ( default | null | hr | br )
	 * @param string $class CSS class
	 * @return boolean
	 */
	public function addElement($label,$content,$separator="default",$class=''){
		if(!in_array(strtolower($separator),array(null,"default","hr","br"))){return false;}
		if($separator=="default"){$separator=$this->separator;}
		if(!strlen($content)>0){$content="&nbsp;";}
		$element=new stdClass();
		$element->type="element";
		$element->label=$label;
		$element->content=$content;
		$element->separator=$separator;
		$element->class=$class;
		// add element to elements array
		$this->elements_array[]=$element;
		return true;
	}

	/**
	 * Add Separator
	 *
	 * @todo verificare a che cosa serve... :/
	 *
	 * @param string $separator Separator ( default | hr | br )
	 * @param string $class CSS class
	 * @return boolean
	 */
	public function addSeparator($separator="default",$class=''){
		if(!in_array(strtolower($separator),array("default","hr","br"))){return false;}
		if($separator=="default"){$separator=$this->separator;}
		$element=new stdClass();
		$element->type="separator";
		$element->separator=$separator;
		$element->class=$class;
		$this->elements_array[]=$element;
		return true;
	}

	/**
	 * Renderize Description List object
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
		// check for elements
		if(!count($this->elements_array)){return $return;}
		// renderize description list
		$return.=$ind."<!-- ".$this->id." -->\n";
		$return.=$ind."<dl class=\"".$this->class."\" id=\"".$this->id."\">\n";
		foreach($this->elements_array as $index=>$element){
			switch($element->type){
				case "element":
					$return.=$ind." <dt class='".$element->class."'>".$element->label."</dt><dd class='".$element->class."'>".$element->content."</dd>";
					if($element->separator<>null && isset($this->elements_array[$index+1]) && $this->elements_array[$index+1]->type=="element"){$return.=$ind."<".$element->separator.">\n";}else{$return.=$ind."\n";}
					break;
				case "separator":
					$return.=$ind." <".$element->separator.">\n";
					break;
			}
		}
		$return.=$ind."</dl><!-- /".$this->id." -->\n";
		// return html source code
		return $return;
	}

}
