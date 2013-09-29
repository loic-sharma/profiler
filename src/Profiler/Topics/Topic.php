<?php namespace Profiler\Topics;

class Topic {

    protected $topic='';
    protected $items=array();
    protected $btn='';
    
    public function __construct($topic,$btn)
    {
        $this->topic=$topic;
        $this->btn=$btn;
    }
    
    public function getItems()
    {
        return $this->items;
    }
    public function name()
    {
        return $this->topic;
    }    
    
    public function render($view)
    {
        
        /*ob_start();
        include __DIR__ .'/../../../views/topics/'.$this->topic.'.php';
        return ob_get_clean();*/
        
        return $view->make('profiler::topics.'.$this->topic)
            ->with('items',$this->getItems() )
            ->with('name',$this->name() );
        
    }
    

}