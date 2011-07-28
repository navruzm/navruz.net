<?php

$cont = $this->uri->segment(2);
$this->template->add_css('admin');
$this->template->add_js('admin');
$this->template->jquery();
$this->load->helper('admin/admin_menu');
no_index();
?>
<!-- begin: main content area #main -->
<div id="main">
    <div class="page_margins">
        <div class="page">
            <!-- begin: #col1 - first float column -->
            <div id="col1">
                <div id="col1_content" class="clearfix">

                    <a href="admin" class="text-center" id="admin-home">
                        Yönetim
                    </a>
                    <ul id="side-nav">
                        <li>
                            <a href="admin/dashboard" <?php if ($cont == 'dashboard')
                            echo 'class="current"'; ?>>
                                <img src="assets/img/admin/menu/home.png" alt="" style="vertical-align: middle;margin-right: 5px;"/> Anasayfa </a>
                        </li>
                        <?php foreach (get_admin_menu_array () as $menu): ?>
                            <li>
                                <a href="#" class="side-nav-item<?php if ($cont == $menu['module_name'])
                                echo ' current'; ?>">
                                <img src="assets/img/admin/menu/<?php echo $menu['menu_image']; ?>" alt=""  style="vertical-align: middle;margin-right: 5px;"/>
                                <?php echo $menu['menu_text']; ?>
                            </a>
                            <?php if (sizeof($menu['menu_items']) > 0): ?>
                                    <ul>
                                <?php foreach ($menu['menu_items'] as $link => $item): ?>
                                        <li><?php echo anchor('admin/' . $menu['module_name'] . '/' . $link, $item); ?></li>
                                <?php endforeach; ?>
                                    </ul>
                            <?php endif; ?>
                                    </li>
                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- end: #col1 -->
                            <!-- begin: #col3 static column -->
                            <div id="col3">
                                <div id="page-head"><span id="user">Merhaba <?php echo get_username(); ?> - <?php echo anchor('', 'Siteye Git') ?></span></div>
                                <div id="col3_content" class="clearfix">
                    <?php
                                        echo isset($message) ? $message : '';
                                        echo $content;
                    ?>
                </div>
                <div id="ie_clearing">&nbsp;</div>
                <!-- End: IE Column Clearing -->
            </div>
            <!-- end: #col3 -->
        </div>

    </div>
</div>