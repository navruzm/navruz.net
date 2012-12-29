<?php
$this->asset->add_css_minify('styles.css');
$this->asset->add_js_jquery('$("#topbar").dropdown();', 'js/bootstrap-dropdown.js');

$this->load->helper('admin/admin_menu');