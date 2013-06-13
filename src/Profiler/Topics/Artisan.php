<?php namespace Profiler\Topics;


class Artisan extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
	return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.'</a></li>';
    }
    
    public function query()
    {
    }
} 
