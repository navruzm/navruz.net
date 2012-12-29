<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset
{

    private $js_inline = array('before' => array(), 'ready' => array(), 'after' => array());
    private $js_files = array('file' => array(), 'ready' => array());
    private $js_minify = array();
    private $css_inline = array();
    private $css_files = array();
    private $css_minify = array();

    public function __construct()
    {
        
    }

    public function add_js_inline($js, $position='after')
    {
        $js = $this->minify_js($js);
        if ($js != NULL && !in_array($js, $this->js_inline[$position]))
        {
            $this->js_inline[$position][] = $js;
        }
    }

    public function add_js_link($js, $type='file')
    {
        if ($js != NULL && !in_array($js, $this->js_files[$type]))
        {
            $this->js_files[$type][] = $js;
        }
    }

    public function add_js_minify($js)
    {
        if ($js != NULL && !in_array($js, $this->js_minify))
        {
            $this->js_minify[] = $js;
        }
    }

    public function add_js_jquery($js, $file=NULL)
    {
        $this->add_js_inline($js, 'ready');
        $this->add_js_link($file, 'ready');
        $this->set_jquery();
    }

    public function set_jquery()
    {
        $js = 'assets/js/jquery.js';
        if (!in_array($js, $this->js_files['ready']))
        {
            array_unshift($this->js_files['ready'], $js);
        }
        return $this;
    }

    public function add_css_inline($css)
    {
        $css = $this->minify_js($css);
        if ($css != NULL && !in_array($css, $this->css_inline))
        {
            $this->css_inline[] = $css;
        }
    }

    public function add_css_link($css)
    {
        if ($css != NULL && !in_array($css, $this->css_files))
        {
            $this->css_files[] = $css;
        }
    }

    public function add_css_minify($css)
    {
        if ($css != NULL && !in_array($css, $this->css_minify))
        {
            $this->css_minify[] = $css;
        }
    }

    public function minify_css($css)
    {

        $css = preg_replace('#\s+#', ' ', $css);
        $css = preg_replace('#/\*.*?\*/#s', '', $css);

        $css = str_replace('; ', ';', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(' {', '{', $css);
        $css = str_replace('{ ', '{', $css);
        $css = str_replace(', ', ',', $css);
        $css = str_replace('} ', '}', $css);
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    public function minify_js($js)
    {

        $js = preg_replace('#\s+#', ' ', $js);
        $js = preg_replace('#/\*.*?\*/#s', '', $js);

        return trim($js);
    }

    /**
     * @access private
     * @param  $script
     * @return string
     */
    private function tag_js_inline($script)
    {
        $js = '<script type="text/javascript">';
        $js .= "\n/* <![CDATA[ */\n";
        $js .= $script;
        $js .= "\n/* ]]> */\n</script>";

        return $js;
    }

    private function tag_js_file($file)
    {
        return '<script type="text/javascript" src="' . $file . '"></script>';
    }

    private function tag_css_inline($style)
    {
        return '<style type="text/css">' . $style . '</style>';
    }

    private function tag_css_file($file)
    {
        return '<link type="text/css" rel="stylesheet" href="' . $file . '" />';
    }

    public function render_js()
    {

        $js = $this->tag_js_file('assets/js/head.js') . "\n";
        $js_array = array();

        if (count($this->js_inline['before']) > 0)
        {
            $js_array[] = implode("\n", $this->js_inline['before']);
        }

        if (count($this->js_files['ready']) > 0)
        {
            $files = array();
            foreach ($this->js_files['ready'] as $file)
            {
                $files[] = '"'.$file.'"';
            }
            $js_array[] = 'head.js(' . implode(",", $files) . ',function() {' . "\n " . implode("\n", $this->js_inline['ready']) . '});';
        }

        if (count($this->js_minify) > 0)
        {
            $js_array[] = 'head.js("compres.js?theme='.get_instance()->template->get_theme().'&js=' . implode(',', $this->js_minify) . '");';
        }

        if (count($this->js_files['file']) > 0)
        {
            foreach ($this->js_files['file'] as $file)
            {
                $js_array[] = 'head.js("' . $file . '");';
            }
        }

        if (count($this->js_inline['after']) > 0)
        {
            $js_array[] = implode("\n", $this->js_inline['after']);
        }
        $js.= count($js_array) ? $this->tag_js_inline(implode("\n", $js_array)) : '';
        return $js;
    }

    public function render_css()
    {
        $css = array();
        if (count($this->css_minify) > 0)
        {
            $css[] = $this->tag_css_file('compress.css?theme='.get_instance()->template->get_theme().'&css=' . implode(",", $this->css_minify));
        }

        if (count($this->css_files) > 0)
        {
            foreach ($this->css_files as $file)
            {
                $css[] = $this->tag_css_file($file) . "\n";
            }
        }

        if (count($this->css_inline) > 0)
        {
            $css[] = $this->tag_css_inline(implode("\n", $this->css_inline));
        }

        return implode("\n", $css);
    }

}