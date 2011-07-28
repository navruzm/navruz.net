<?php
/*
 * $Date:  $
 * $Id:  $
 * $Rev:  $
*/

define('MINIPATH',realpath(dirname(__FILE__)).'/');
define('SITEPATH',MINIPATH.'../../');
$params = array();

/**$params = array(
    'debug'=>TRUE,
    'charset'=>'utf-8'
);
*/
$compress = new Compress($params);

if(isset($_GET['js']))
{
    $compress->serve_js($_GET['js']);
}
else if(isset($_GET['css']))
{
    $compress->serve_css($_GET['css']);
}

class Compress {

    private $debug = TRUE;

    private $charset = 'UTF-8';

    private $js_path;

    private $css_path;

    private $cache_path;

    private $default = array(
        'css'=>array(
            'style'=> array(
                'yaml.base.css',
                'yaml.navigation.css',
                'yaml.forms.css',
                'basemod.css',
                'basemod_site.css',
                'content.css',
                'styles.css',
                'yaml.print.css',
                'button.css',
                'nivo.css',
            ),
            'mobile'=> array(
                'yaml.base.css',
                'yaml.navigation.css',
                'yaml.forms.css',
                'basemod.css',
                'basemod_mobile.css',
                'content_mobile.css',
                'styles.css',
                'button.css',
            ),
            'admin'=> array(
                'yaml.base.css',
                'yaml.navigation.css',
                'yaml.forms.css',
                'basemod.css',
                'basemod_admin.css',
                'content.css',
                'styles.css',
                'content_admin.css',
                'yaml.print.css',
                'button.css'
            ),
            'ie' => array(
                'yaml.iehacks.css',
                'yaml.forms.iehacks.css',
                'patch_site.css'
            ),
            'ie-admin' => array(
                'yaml.iehacks.css',
                'yaml.forms.iehacks.css',
                'patch_admin.css'

            ),

        ),
        'js' => array(
            'js' => array(
                'jquery.js',
                //'cufon.js',
                //'font.js',
                'custom.js',
                'yaml-focusfix.js',
                'scrollto.js',
                'nivo.js',
            ),
            'highlight' => array(
                'highlight.pack.js',
                'highlight.js'
            )
        )

    );

    public function __construct($params)
    {
        $this->initialize($params);
    }

    public function serve_js($files)
    {
        $opt =  array(
            'files' => $this->get_js_files($files),
            'contentTypeCharset'=>$this->charset
        );

        Minify::setCache($this->cache_path);
        Minify::serve('Files', $opt);
    }

    public function serve_css($files)
    {
        $opt =  array(
            'files' => $this->get_css_files($files),
            'contentTypeCharset'=>$this->charset
        );

        Minify::setCache($this->cache_path);
        Minify::serve('Files', $opt);
    }

    private function get_js_files($files)
    {
        $file_array = explode('-',$files);
        unset($files);
        $files = array();
        foreach ($file_array as $file)
        {
            if(array_key_exists($file, $this->default['js']))
            {
                foreach ($this->default['js'][$file] as $group_file)
                {
                    if(file_exists($this->js_path.$group_file))
                    {
                        $files[] = $this->js_path.$group_file;
                    }
                }
            }
            elseif(file_exists($this->js_path.$file.'.js'))
            {
                $files[] = $this->js_path.$file.'.js';
            }
        }

        return array_unique($files);
    }

    private function get_css_files($files)
    {
        $file_array = explode('-',$files);
        unset($files);
        $files = array();
        foreach ($file_array as $file)
        {
            if(array_key_exists($file, $this->default['css']))
            {
                foreach ($this->default['css'][$file] as $group_file)
                {
                    if(file_exists($this->css_path.$group_file))
                    {
                        $files[] = $this->css_path.$group_file;
                    }
                }
            }
            elseif(file_exists($this->css_path.$file.'.css'))
            {
                $files[] = $this->css_path.$file.'.css';
            }
        }
        return array_unique($files);
    }

    private function set_cache_path()
    {
        $this->cache_path = SITEPATH.'cache/min/';
    }

    private function set_js_file_path()
    {
        $this->js_path = SITEPATH.'assets/js/';
    }

    private function set_css_file_path()
    {
        $this->css_path = SITEPATH.'assets/css/';
    }

    private function initialize($params = array())
    {
        set_include_path(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'minify' . PATH_SEPARATOR . get_include_path());
        require_once 'Minify/Build.php';
        require_once 'Minify.php';

        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }

        if($this->debug===TRUE)
        {
            $this->init_debug();
            Minify_Logger::setLogger(FirePHP::getInstance(true));
        }
        $this->set_css_file_path();
        $this->set_js_file_path();
        $this->set_cache_path();
    }

    private function init_debug()
    {
        require_once MINIPATH.'minify/FirePHP.php';
        require_once MINIPATH.'minify/Minify/Logger.php';
    }

}