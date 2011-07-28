<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Template
{

    private $ci;
    private $layout = 'layout';
    private $view;
    private $js_inline = array();
    private $js_files = array();
    private $js_minify = array();
    private $css_inline = array();
    private $css_files = array();
    private $css_minify = array();
    private $head_more = array();
    private $jquery = array();
    private $keywords = array();
    private $title;
    private $description;
    private $meta = array();
    private $language = 'tr';
    private $breadcrumbs = array();
    private $last_breadcrumbs;
    public $debug = FALSE;

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->ci = & get_instance();
        //$this->ci->output->cache(100);
        if ((get_option('debug') == 1) && $this->ci->auth->is_admin())
        {
            $this->ci->output->enable_profiler(TRUE);
            $this->debug = TRUE;
            $this->add_css('profiler');
            $this->add_js('tools');
            $this->add_js('profiler');
            $this->jquery();
        }
    }

    /**
     * Layout dosyasının orta bölümünü oluşturur.
     * @param string $view
     * @param array $data
     * @param boolean $true
     * @return void
     */
    public function view($view, $data = array(), $true = TRUE)
    {
        $this->view = $this->ci->load->view($view, $data, $true);
        $messages = $this->ci->session->flashdata('messages');
        if (is_array($messages) && count($messages))
        {
            $_messages = array();
            foreach ($messages as $message)
            {
                $_messages[] = '<div class="' . $message['type'] . ' closeable">' . $message['message'] . '</div>';
            }
            $this->view = implode("\n", $_messages) . $this->view;
        }
        return $this;
    }

    /**
     * Layout dosyasını ekrana yazdırır.
     * @param string $layout
     * @return void
     */
    public function load($layout = '')
    {
        $this->layout = ($layout != '') ? $layout : $this->layout;
        $this->ci->load->view('layout_core', $this->output());
    }

    /**
     * Sayfada kullanılan değişkenleri oluşturur.
     * @TODO Düzenle
     * @access private
     * @return array
     */
    private function output()
    {
        $this->ci->load->helper('url');
        if ($this->layout == 'no_layout')
        {
            $data['body'] = $this->view;
        }
        else
        {
            $data['body'] = $this->ci->load->view($this->layout, array('content' => $this->view), TRUE);
        }

        $data['css'] = '';

        if (count($this->css_minify) > 0)
            $data['css'] .= $this->tag_css_file('css/' . implode("-", $this->css_minify) . '.css');

        if (count($this->css_files) > 0)
            foreach ($this->css_files as $file)
                $data['css'] .= $this->tag_css_file($file) . "\n";

        if (count($this->css_inline) > 0)
            $data['css'] .= $this->tag_css_inline(implode("\n", $this->css_inline));

        //JS
        $data['js'] = '';

        if (count($this->jquery) > 0)
            $this->set_jquery();

        if (count($this->js_minify) > 0)
            $data['js'] .= $this->tag_js_file('js/' . implode("-", $this->js_minify) . '.js') . "\n";

        if (count($this->js_files) > 0)
            foreach ($this->js_files as $file)
                $data['js'] .= $this->tag_js_file($file) . "\n";

        if (count($this->js_inline) > 0)
            $data['js'] .= $this->tag_js_inline(implode("\n", $this->js_inline));

        $data['more'] = '';
        if (count($this->head_more) > 0)
            $data['more'] = implode("\n", $this->head_more);

        $data['title'] = $this->get_title();

        $data['meta'] = $this->get_meta();

        return $data;
    }

    /**
     * Javascript dosyası veya kodu ekler.
     * @param  $js
     * @param string $type
     * @return void
     */
    public function add_js($js, $type = 'compress')
    {
        switch ($type)
        {
            case 'compress':
                if ($js != NULL && !in_array($js, $this->js_minify))
                    $this->js_minify[] = $js;
                break;
            case 'import':
            case 'link':
                if ($js != NULL && !in_array($js, $this->js_files))
                    $this->js_files[] = $js;
                break;
            case 'embed':
                $js = $this->minify_js($js);
                if ($js != NULL && !in_array($js, $this->js_inline))
                    $this->js_inline[] = $js;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Css dosyası veya kodu ekler.
     * @param  $css
     * @param string $type
     * @return void
     */
    public function add_css($css, $type = 'compress')
    {
        switch ($type)
        {
            case 'compress':
                if ($css != NULL && !in_array($css, $this->css_minify))
                    $this->css_minify[] = $css;
                break;
            case 'link':
                if ($css != NULL && !in_array($css, $this->css_files))
                    $this->css_files[] = $css;
                break;
            case 'embed':
                $css = $this->minify_css($css);
                if ($css != NULL && !in_array($css, $this->css_inline))
                    $this->css_inline[] = $css;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Jquery kodu ekler.
     * @param  $jquery
     * @return void
     */
    public function add_jquery($jquery)
    {
        if ($jquery != NULL && !in_array($jquery, $this->jquery))
            $this->jquery[] = $jquery;
        return $this;
    }

    /**
     * Jquery kütüphanesini ekler.
     * @return void
     */
    public function jquery()
    {
        $js = 'js';
        if (!in_array($js, $this->js_minify))
            array_unshift($this->js_minify, $js);
        return $this;
    }

    /**
     *
     * Ekstra head ekler.
     * @param  $more
     * @return void
     */
    public function add_more($more)
    {
        if ($more != NULL && !in_array($more, $this->head_more))
            $this->head_more[] = $more;
        return $this;
    }

    /**
     * Anahtar kelime ekler.
     * @param array|string $keywords
     * @return void
     */
    public function add_keyword($keywords)
    {
        if ($keywords != NULL && is_array($keywords))
        {
            foreach ($keywords as $keyword)
            {
                $keyword = trim($keyword);
                if (!in_array($keyword, $this->keywords) && $keyword != '')
                    $this->keywords[] = $keyword;
            }
        }
        elseif ($keywords != NULL)
        {
            $keywords = trim($keywords);
            if (!in_array($keywords, $this->keywords) && $keywords != '')
                $this->keywords[] = $keywords;
        }
        return $this;
    }

    /**
     * Meta kodu ekler.
     * @param  $meta
     * @return void
     */
    public function add_meta($meta)
    {
        if ($meta != NULL)
            $this->meta[] = $meta;
        return $this;
    }

    /**
     * Sayfa başlığı ekler.
     * @TODO Eski haline getir
     * @param  $title
     * @return void
     */
    public function set_title($title, $site_name = FALSE)
    {
        if ($title != '')
        {
            $this->title = $title;
        }
        if ($site_name)
        {
            $this->title = $this->title . ' - ' . get_option('site_name');
        }
        return $this;
    }

    /**
     * Sayfa açıklamasını ekler.
     * @param  $description
     * @return void
     */
    public function set_description($description, $site_name = FALSE)
    {
        if ($description != '')
        {
            $this->description = $description;
        }
        if ($site_name)
        {
            $this->description = $this->description . ' - ' . get_option('site_name');
        }
        return $this;
    }

    /**
     * Eklenen jquery kodlarını add_js metodunu kullanarak javascript kodu olarak ekler.
     * @access private
     * @return void
     */
    private function set_jquery()
    {
        if (count($this->jquery) == 0)
            return;
        $jquery = "$(document).ready(function(){";
        $jquery .= implode("\n", $this->jquery);
        $jquery .= "});";
        $this->add_js($jquery, 'embed');
        $this->jquery();
        return $this;
    }

    /**
     * Meta kodlarını oluşturmak için kullanılacak diziyi oluşturur.
     * @access private
     * @return array
     */
    private function get_meta()
    {
        $meta = array();

        $this->add_meta(array(
            'name' => 'Content-type',
            'content' => 'text/html; charset=utf-8',
            'type' => 'equiv')
        );

        $this->add_meta(array(
            'name' => 'Content-Language',
            'content' => $this->language,
            'type' => 'equiv')
        );

        $this->add_meta(array(
            'name' => 'description',
            'content' => self::get_description())
        );

        if (sizeof($this->keywords) > 0)
            $this->add_meta(array(
                'name' => 'keywords',
                'content' => $this->clean_meta(implode(", ", $this->keywords)))
            );

        if (get_option('google_verify') != '')
            $this->add_meta(array(
                'name' => 'google-site-verification',
                'content' => get_option('google_verify'))
            );

        if (get_option('yahoo_verify') != '')
            $this->add_meta(array(
                'name' => 'y_key',
                'content' => get_option('yahoo_verify'))
            );

        if (get_option('bing_verify') != '')
            $this->add_meta(array(
                'name' => 'msvalidate.01',
                'content' => get_option('bing_verify'))
            );

        return $this->meta;
    }

    /**
     * Yönlendirme ara sayfası oluşturur.
     *
     * @param string $message
     * @param string $redirect
     * @param int $wait
     * @return void
     */
    public function redir($message, $redirect = '', $wait = 3)
    {
        if (!preg_match('#^https?://#i', $redirect))
        {
            $redirect = site_url($redirect);
        }
        $data = array(
            'message' => $message,
            'redirect' => $redirect,
            'wait' => $wait
        );
        $this->view('system/redirect', $data);
        $this->load('no_layout');
        $this->ci->output->_display();
        die();
    }

    /**
     * DEPRECATED
     *
     * Sayfada kullanılacak sabit dosyaları ekleyen dosyayı çalıştırır.
     * @access private
     * @return void
     */
    public function set_layout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Css kodlarındaki fazlalıkları temizler.
     * @access private
     * @param  $css
     * @return string
     */
    public function minify_css($css)
    {
        if ($this->debug === TRUE)
            return $css;

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

    /**
     * Javascript kodlarındaki fazlalıkları temizler.
     * @access private
     * @param  $js
     * @return string
     */
    public function minify_js($js)
    {
        if ($this->debug === TRUE)
            return $js;

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

    /**
     *
     * @access private
     * @param  $file
     * @return string
     */
    private function tag_js_file($file)
    {
        $js = '<script type="text/javascript" src="' . $file . '"></script>';

        return $js;
    }

    /**
     * @access private
     * @param  $style
     * @return string
     */
    private function tag_css_inline($style)
    {
        $css = '<style type="text/css">';
        $css .= $style;
        $css .= '</style>';

        return $css;
    }

    /**
     * @access private
     * @param  $file
     * @param string $media
     * @return string
     */
    private function tag_css_file($file)
    {
        $css = '<link type="text/css" rel="stylesheet" href="' . $file . '" />';
        return $css;
    }

    public function add_breadcrumb($title, $url = NULL)
    {
        if ($url !== NULL)
        {
            $this->breadcrumbs[$url] = $title;
        }
        else
        {
            $this->last_breadcrumbs = $title;
        }
        return $this;
    }

    public function get_breadcrumb()
    {
        $array[] = anchor('', 'Anasayfa', 'class="homes"');
        foreach ($this->breadcrumbs as $url => $title)
        {
            $array[] = anchor($url, $title);
        }
        $array[] = '<strong>' . $this->last_breadcrumbs . '</strong>';
        return ul($array);
    }

    public function page_num()
    {
        if (isset($this->ci->pagination))
        {

            $page_num = $this->ci->pagination->cur_page;
            if ($page_num > 1)
            {
                return ' - Sayfa ' . $page_num;
            }
        }
        return;
    }

    private function get_description()
    {
        if ($this->description == '')
        {
            $module = $this->ci->router->fetch_module();
            $m_config = $this->ci->module_config->get_module_config();
            $return = $m_config[$module]['name'] . ' - ' . get_option('site_name') . self::page_num();
        }
        else
        {
            $return = $this->description . self::page_num();
        }
        return $this->clean_meta($return);
    }

    private function get_title()
    {
        if ($this->title == '')
        {
            $module = $this->ci->router->fetch_module();
            $m_config = $this->ci->module_config->get_module_config();
            $return = $m_config[$module]['name'] . ' - ' . get_option('site_name') . self::page_num();
        }
        else
        {
            $return = $this->title . self::page_num();
        }
        return $this->clean_meta($return);
    }

    private function clean_meta($str)
    {
        return preg_replace("#[^a-zğĞüÜşŞıİöÖçÇ0-9\-'\s\.,\?\(\)!]#ei", '', str_replace(array("\n\r", "\n", "\r", "\t", " "), ' ', $str));
    }

}

/* End of file Template.php */