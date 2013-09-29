<?php namespace Profiler\Topics;


class User extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
        if (\Auth::check())
		return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.
                '<span class="anbu-count">'.count($this->getItems()).'</span></a></li>';
    }
    
    public function query()
    {
        if (\Auth::check()){
            if(empty($this->items))
            {
                $this->items= \Auth::user()->toArray();
            }
	}
        else
        {
            $this->items=array();
        }
    }
} 
