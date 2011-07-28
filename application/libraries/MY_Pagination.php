<?php

class MY_Pagination extends CI_Pagination {

// The page we are linking to
    public $base_url = '';
// The page we are linking to
    public $base_url_del = FALSE;
// Total number of items (database results)
    public $total_rows = '';
// Max number of items you want shown per page
    public $per_page = 0;
// Number of "digit" links to show before/after the currently viewed page
    public $num_links = 3;
// The current page being viewed
    public $cur_page = 1;
    public $first_link = '&lsaquo; İlk Sayfa';
    public $next_link = '&gt;';
    public $prev_link = '&lt;';
    public $last_link = 'Son Sayfa &rsaquo;';
    public $uri_segment = 3;
    public $full_tag_open = '<ul class="pagination clearfix">';
    public $full_tag_close = '</ul><br/>';
    public $first_tag_open = '<li>';
    public $first_tag_close = '</li>';
    public $last_tag_open = '<li>';
    public $last_tag_close = '</li>';
    public $cur_tag_open = '<li class="current"><span>';
    public $cur_tag_close = '</span></li>';
    public $next_tag_open = '<li>';
    public $next_tag_close = '</li>';
    public $prev_tag_open = '<li>';
    public $prev_tag_close = '</li>';
    public $num_tag_open = '<li>';
    public $num_tag_close = '</li>';
    public $page_query_string = FALSE;
    public $query_string_segment = 'per_page';
    public $offset = 0;
    public $page_tag_open = '<li class="pagestr">';
    public $page_string = 'Sayfa';
    public $page_tag_close = '</li>';
    public $delimiter ='-';

    public function __construct($params = array())
    {
        // Determine the current page number.
        $this->ci = & get_instance();
        if(count($params) > 0)
            parent::initialize($params);
        log_message('debug', "Pagination Class Initialized");
    }
    
    public function initialize($params = array())
    {
        if(count($params) > 0)
        {
            foreach($params as $key => $val)
            {
                if(isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
        if($this->per_page==0)
            $this->per_page = get_option('per_page');
    }
    
    public function init($url, $total, $segment = 3, $per_page = FALSE)
    {
        $this->base_url = $url;
        $this->uri_segment = $segment;
        $this->total_rows = $total;
        $this->initialize();
        if($per_page) {
            $this->per_page = $per_page;
        }
        return $this->create_links();
    }
    
    public function create_links()
    {
        // If our item count or per-page total is zero there is no need to continue.
        if($this->total_rows == 0 OR $this->per_page == 0)
            return '';
        // Calculate the total number of pages
        $num_pages = ceil($this->total_rows / $this->per_page);
        // Is there only one page? Hm... nothing more to do here then.
        if($num_pages == 1)
            return '';

        if($this->ci->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
        {
            if($this->ci->input->get($this->query_string_segment) != 0)
            {
                $this->cur_page = $this->ci->input->get($this->query_string_segment);
                $this->cur_page = (int) $this->cur_page;
            }
        }
        else
        {
            if($this->ci->uri->rsegment($this->uri_segment) != 0)
            {
                $this->cur_page = $this->ci->uri->rsegment($this->uri_segment);
                $this->cur_page = (int) $this->cur_page;
            }
        }

        $this->num_links = (int) $this->num_links;

        if($this->num_links < 1)
        {
            show_error('Your number of links must be a positive number.');
        }

        if(!is_numeric($this->cur_page) || $this->cur_page < 1)
        {
            $this->cur_page = 1;
        }
        // Is the page number beyond the result range?
        // If so we show the last page
        if($this->cur_page > $num_pages)
        {
            $this->cur_page = $num_pages;
        }
        $this->_offset();
        $uri_page_number = $this->cur_page;
        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
        // Is pagination being used over GET or POST?  If get, add a per_page query
        // string. If post, add a trailing slash to the base URL if needed
        $delimiter = '';

        if($this->ci->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
        {
            $this->base_url = rtrim($this->base_url) . '&amp;' . $this->query_string_segment . '=';
        }
        else
        {
            if($this->base_url_del === TRUE)
            {
                $this->base_url = rtrim($this->base_url, '/');
                $delimiter = $this->delimiter;
            }
            else
            {
                $this->base_url = rtrim($this->base_url, '/') . '/';
            }
        }
        // And here we go...
        $output = '';

        $output .= $this->page_tag_open . $this->page_string . ' ' . $this->cur_page . ' / ' . $num_pages . $this->page_tag_close;
        // Render the "First" link
        if($this->cur_page > ($this->num_links + 1))
        {
            $output .= $this->first_tag_open . anchor($this->base_url, $this->first_link) . $this->first_tag_close;
        }
        //// Render the "previous" link
        if($this->cur_page != 1)
        {
            $i = $uri_page_number - 1;
            $i = ($i != 1) ? $delimiter . $i : '';
            $output .= $this->prev_tag_open . anchor($this->base_url . $i, $this->prev_link) . $this->prev_tag_close;
        }
        // Write the digit links
        for($loop = $start; $loop <= $end; $loop++)
        {
            if($this->cur_page == $loop)
            {
                $output .= $this->cur_tag_open . $loop . $this->cur_tag_close; // Current page
            }
            else
            {
                $n = ($loop == 1) ? '' : $delimiter . $loop;
                $output .= $this->num_tag_open . anchor($this->base_url . $n, $loop) . $this->num_tag_close;
            }
        }
        // Render the "next" link
        if($this->cur_page < $num_pages)
        {
            $output .= $this->next_tag_open . anchor($this->base_url . $delimiter . ($this->cur_page + 1 ), $this->next_link) . $this->next_tag_close;
        }
        // Render the "Last" link
        if(($this->cur_page + $this->num_links) < $num_pages)
        {
            $i = $delimiter . $num_pages;
            $output .= $this->last_tag_open . anchor($this->base_url . $i, $this->last_link) . $this->last_tag_close;
        }
        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\\1/", $output);
        // Add the wrapper HTML if exists
        $output = $this->full_tag_open . $output . $this->full_tag_close;

        return $output;
    }
    
    public function get_offset()
    {
        return $this->offset;
    }
    
    private function _offset()
    {
        $this->offset = ($this->cur_page * $this->per_page) - $this->per_page;
        return;
    }
}

//End of file MY_Pagination.php