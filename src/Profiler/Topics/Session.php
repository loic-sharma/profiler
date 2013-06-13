<?php namespace Profiler\Topics;

//use TopicInterface;

class Session extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
	return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.
                '<span class="anbu-count">'.count($this->getItems()).'</span></a></li>';
    }
    
    public function query()
    {
	if(empty($this->items))
	{
	    $this->items=\Session::all();
	}
    }
    

} 
