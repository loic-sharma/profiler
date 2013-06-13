<?php namespace Profiler\Topics;

//use TopicInterface;

class Config extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
	return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.
                '<span class="anbu-count">'.count($this->getItems()).'</span></a></li>';
    }
    
    public function query()
    {
	if(empty($this->items))
	{
	    $configs=\Config::getItems() ;
	    foreach($configs as $a=>$b){
		    $this->devConfig($b,$a);
	    }
	}
    }
    
    private function devConfig($a,$prefix)
    {
	if (!is_array($a)) {
	    $this->items[$prefix]=$a;
	}
	else
	    foreach($a as $aa=>$b){
		$this->devConfig($b,$prefix.'.'.$aa);
	    }
    }
} 
