<?php

class Paging
{
    public $enabled  = true;
    
    private $per_page       = 10;
    private $start_position = 0;
    private $total          = 0;
    private $type           = 'db';
    private $current_page   = 1;
    
    private $offset         = 0;
    
    
    
    function __construct()
    {
    	global $Page;
    	
    	if(isset($Page->args['paging']) && $Page->args['paging']!='' && is_numeric($Page->args['paging'])) {
    		$this->current_page = $Page->args['paging'];
    	}
    	else {
    		$this->current_page = 1;
    	}
    }
    
    

    public function enable()
    {
        $this->enabled  = true;
    }
    
    public function disable()
    {
        $this->enabled = false;
    }
    
    
    public function enabled()
    {
        return $this->enabled;
    }
    
    public function set_per_page($per_page=10)
    {
        $this->per_page = $per_page;
    }
    
    public function per_page()
    {
        return $this->per_page;
    }
    
    public function set_start_position($start_position=0)
    {
        $this->start_position = $start_position;
    }
    
    public function start_position()
    {
        return $this->start_position;
    }
    
    public function offset()
    {
        return $this->offset;
    }
    
    public function set_offset($offset=0)
    {
        $this->offset = $offset;
    }
    
    public function set_total($total)
    {
        $this->total    = $total;
    }
    
    public function total()
    {
        return $this->total;
    }
    
    public function set_type($type='db')
    {
        $this->type = $type;
    }
    
    public function type()
    {
        return $this->type;
    }
    
    public function lower_bound()
    {
        return (($this->per_page * $this->current_page) - $this->per_page) + $this->offset;
    }
    
    public function upper_bound()
    {
        $ub = $this->lower_bound() + $this->per_page - 1;
        
        if ($this->total != 0 && $ub > $this->total) {
            return $this->total;
        }
        
        return $ub;
    }
    
    public function number_of_pages()
    {
        return ceil((0-$this->offset + $this->total) / $this->per_page);
    }
    
    public function is_first_page()
    {
        if ($this->current_page == 1) {
            return true;
        }
        
        return false;
    }
    
    public function is_last_page()
    {
        if ($this->current_page == $this->number_of_pages()) {
            return true;
        }
        
        return false;
    }
    
    public function set_current_page($page) {
    	$this->current_page = $page;
    }
    
    public function current_page()
    {
        return $this->current_page;
    }
    
    public function to_array()
    {
        $out    = array();
        $out['total']   = $this->total();
        $out['number_of_pages'] = $this->number_of_pages();
        $out['per_page']        = $this->per_page();
        $out['current_page']    = $this->current_page();

        return $out;
    }
}

?>