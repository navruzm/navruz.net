<?php

$directory = urldecode($this->input->post('dir'));
$root = $this->input->post('root');
if (file_exists($root . $directory))
{
    $files = scandir($root . $directory);
    natcasesort($files);
    if (count($files) > 2)
    {
        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        foreach ($files as $file)
        {
            if (file_exists($root . $directory . $file) && $file != '.' && $file != '..' && is_dir($root . $directory . $file))
            {
                echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($directory . $file) . "/\">" . htmlentities($file) . "</a></li>";
            }
        }
        foreach ($files as $file)
        {
            if (file_exists($root . $directory . $file) && $file != '.' && $file != '..' && !is_dir($root . $directory . $file))
            {
                $ext = preg_replace('/^.*\./', '', $file);
                echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($directory . $file) . "\">" . htmlentities($file) . "</a></li>";
            }
        }
        echo "</ul>";
    }
}