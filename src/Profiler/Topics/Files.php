<?php namespace Profiler\Topics;


class Files extends Topic implements \Profiler\Topics\TopicInterface {
    
    public function renderBtn()
    {
	return '<li><a class="anbu-tab" data-anbu-tab="anbu-'.$this->name().'count">'.$this->btn.
                '<span class="anbu-count">'.count($this->getItems()).'</span></a></li>';
    }
    
    public function query()
    {
	if(empty($this->items))
	{
	    $files = get_included_files();
	    foreach($files as $filePath)
	    {
		$size = \Profiler\Profiler::readableSize(filesize($filePath));
		$this->items[] = compact('filePath', 'size');
	    }
	}
    }
    

} 
