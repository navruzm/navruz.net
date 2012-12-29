<?php

define('MINIPATH', realpath(dirname(__FILE__)) . '/');
define('SITEPATH', MINIPATH . '../../');
$params = array('debug' => TRUE, 'theme' => isset($_GET['theme']) ? $_GET['theme'] : 'default');

$compress = new Compress($params);

if (isset($_GET['js']))
{
    $_GET['js'] = ($_GET['js'] == '') ? 'js' : $_GET['js'];
    $compress->serve_js($_GET['js']);
}
else if (isset($_GET['css']))
{
    $_GET['css'] = ($_GET['css'] == '') ? 'styles' : $_GET['css'];
    $compress->serve_css($_GET['css']);
}

class Compress
{

    private $debug = TRUE;
    private $charset = 'UTF-8';
    private $theme;
    private $js_path;
    private $css_path;
    private $cache_path;
    private $default_css = array(
        'styles.css' => array(
            'bootstrap.min.css',
            'default.css',
            'layout.css',
            'content.css',
        )
    );
    private $default_js = array();

    public function __construct($params)
    {
        $this->initialize($params);
    }

    public function serve_js($files)
    {
        $opt = array(
            'files' => $this->get_js_files($files),
            'contentTypeCharset' => $this->charset,
            'minifiers' => array(Minify::TYPE_JS => '')
        );

        /*
         * Bazı sunucularda fpassthru engelli olduğundan problem çıkarabiliyor.
         * Bu durumda fonksiyonun kullanıldığı filelocking seçeneğini FALSE gönderiyoruz.
         * Bu işlemi de function_exists(fpassthru) ile otomatik yapıyoruz.
         */
        Minify::setCache($this->cache_path, function_exists('fpassthru'));
        Minify::serve('Files', $opt);
    }

    public function serve_css($files)
    {
        $opt = array(
            'files' => $this->get_css_files($files),
            'contentTypeCharset' => $this->charset
        );
        /*
         * Bazı sunucularda fpassthru engelli olduğundan problem çıkarabiliyor.
         * Bu durumda fonksiyonun kullanıldığı filelocking seçeneğini FALSE gönderiyoruz.
         * Bu işlemi de function_exists(fpassthru) ile otomatik yapıyoruz.
         */
        Minify::setCache($this->cache_path, function_exists('fpassthru'));
        Minify::serve('Files', $opt);
    }

    private function get_js_files($files)
    {
        $file_array = explode(',', $files);
        unset($files);
        $files = array();
        foreach ($file_array as $file)
        {
            if (array_key_exists($file, $this->default_js))
            {
                foreach ($this->default_js[$file] as $group_file)
                {
                    foreach ($this->js_path as $path)
                    {
                        if (file_exists($path . $group_file))
                        {
                            $files[] = $path . $group_file;
                            break;
                        }
                    }
                }
            }
            else
            {
                foreach ($this->js_path as $path)
                {
                    if (file_exists($path . $file))
                    {
                        $files[] = $path . $file;
                        break;
                    }
                }
            }
        }
        return array_unique($files);
    }

    private function get_css_files($files)
    {
        $file_array = explode(',', $files);
        unset($files);
        $files = array();
        foreach (array_merge(array(), $file_array) as $file)
        {
            if (array_key_exists($file, $this->default_css))
            {
                foreach ($this->default_css[$file] as $group_file)
                {
                    foreach ($this->css_path as $path)
                    {
                        if (file_exists($path . $group_file))
                        {
                            $files[] = $path . $group_file;
                            break;
                        }
                    }
                }
            }
            else
            {
                foreach ($this->css_path as $path)
                {
                    if (file_exists($path . $file))
                    {
                        $files[] = $path . $file;
                        break;
                    }
                }
            }
        }
        return array_unique($files);
    }

    private function set_cache_path()
    {
        $this->cache_path = SITEPATH . 'application/cache/min/';
    }

    private function set_js_file_path()
    {
        $this->js_path = array(
            SITEPATH . 'themes/' . $this->theme . '/js/',
            SITEPATH . 'assets/js/',
            SITEPATH,
        );
    }

    private function set_css_file_path()
    {
        $this->css_path = array(
            SITEPATH . 'themes/' . $this->theme . '/css/',
            SITEPATH . 'assets/css/',
            SITEPATH,
        );
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
                $this->$key = $val;
            }
        }

        if ($this->debug === TRUE)
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
        require_once MINIPATH . 'minify/FirePHP.php';
        require_once MINIPATH . 'minify/Minify/Logger.php';
    }

}