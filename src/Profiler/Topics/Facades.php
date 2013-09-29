<?php namespace Profiler\Topics;

//use TopicInterface;

class Facades extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
	return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.
                '<span class="anbu-count">'.count($this->getItems()).'</span></a></li>';
    }
    
    public function query()
    {
	if(empty($this->items))
	{
	    $this->items=array();
	    $configs=\Config::getItems() ;
	    foreach($configs as $a=>$b){
		    $this->devConfig($b,$a);
	    }
	    sort($this->items /*,SORT_STRING */ );
	}
    }
    
    private function devConfig($a,$prefix)
    {
	$path=base_path();
	$find='Illuminate\\Support\\Facades\\';
	if (!is_array($a)) {
	    if ( strpos($a,$find)!==false ){
		$a= str_replace($find,'',$a);
		$this->items[]=$a;
	    }
	}
	else
	    foreach($a as $aa=>$b){
		$this->devConfig($b,$prefix.'.'.$aa);
	    }
    }
} 
