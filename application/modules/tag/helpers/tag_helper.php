<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_tag_cloud_html($num_tags = 100, $min_font_size = 10, $max_font_size = 20, $span_class = 'cloud_tag', $tag_page_url = '/tag/', $tagger_id = NULL, $offset = 0)
{
    $ci = & get_instance();
    $tag_list = $ci->tags->get_tag_cloud_tags($num_tags, $tagger_id, $offset);
    if (count($tag_list))
    {
        $max_qty = max(array_values($tag_list));
        $min_qty = min(array_values($tag_list));
    }
    else
    {
        return '';
    }

    $spread = $max_qty - $min_qty;
    if (0 == $spread)
    {
        $spread = 1;
    }
    $step = ($max_font_size - $min_font_size) / ($spread);
    $cloud_html = '';
    $cloud_spans = array();
    foreach ($tag_list as $tag => $qty)
    {
        $size = round($min_font_size + ($qty - $min_qty) * $step);
        $cloud_span[] = '<li class="' . $span_class . $size . '"><a href="' . $tag_page_url . url_title($tag) . '" title="' . $tag . ' etiketli yazılar">' . $tag . '</a></li>';
    }
    $cloud_html = '<ul class="clearfix">' . implode("\n", $cloud_span) . '</ul>';
    return $cloud_html;
}

/*function Gradient($HexFrom, $HexTo, $ColorSteps)
{
    $FromRGB['r'] = hexdec(substr($HexFrom, 0, 2));
    $FromRGB['g'] = hexdec(substr($HexFrom, 2, 2));
    $FromRGB['b'] = hexdec(substr($HexFrom, 4, 2));

    $ToRGB['r'] = hexdec(substr($HexTo, 0, 2));
    $ToRGB['g'] = hexdec(substr($HexTo, 2, 2));
    $ToRGB['b'] = hexdec(substr($HexTo, 4, 2));

    $StepRGB['r'] = ($FromRGB['r'] - $ToRGB['r']) / ($ColorSteps - 1);
    $StepRGB['g'] = ($FromRGB['g'] - $ToRGB['g']) / ($ColorSteps - 1);
    $StepRGB['b'] = ($FromRGB['b'] - $ToRGB['b']) / ($ColorSteps - 1);
    $GradientColors = array();

    for ($i = 0; $i <= $ColorSteps; $i++)
    {
        $RGB['r'] = floor($FromRGB['r'] - ($StepRGB['r'] * $i));
        $RGB['g'] = floor($FromRGB['g'] - ($StepRGB['g'] * $i));
        $RGB['b'] = floor($FromRGB['b'] - ($StepRGB['b'] * $i));

        $HexRGB['r'] = sprintf('%02x', ($RGB['r']));
        $HexRGB['g'] = sprintf('%02x', ($RGB['g']));
        $HexRGB['b'] = sprintf('%02x', ($RGB['b']));
        $GradientColors[] = implode(NULL, $HexRGB);
    }
    return $GradientColors;
    /* $Gradients = Gradient("B5CFDF", "004F7F", 10);
      $i =1;
      foreach($Gradients as $Gradient)

      {

      //echo "<div style=\"background-color: #".$Gradient."; width: 100px; height: 25px;color:#fff\">aaaa</div>";
      echo '.tag'. $i++.' { background-color: #'.$Gradient.'; }';
      //$i++;
      } */
/*}*/

