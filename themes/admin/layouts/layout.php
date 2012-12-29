<?php
$cont = $this->uri->segment(2);
?>
<!DOCTYPE html>
<html lang="tr">
    <head>
        <title><?php echo $title ?></title>
        <?php echo meta($meta); ?>
        <base href="<?php echo base_url() ?>">
        <meta charset="utf-8">
        <?php echo $css . "\n"; ?>
        <?php echo $js . "\n"; ?>
        <?php echo $more . "\n"; ?> 
    </head>
    <body>
        <div class="topbar" data-dropdown="dropdown">
            <div class="topbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="">Site Anasayfa</a>
                    <ul class="nav">
                        <?php $class = $cont == 'dashboard' ? 'active ' : ''; ?>
                        <li>
                            <a href="admin"<?php $class?>>Yönetim Anasayfa</a>
                        </li>
                        <?php foreach (get_admin_menu_array() as $menu): ?>
                            <?php
                            if (check_permission($menu['module_name'], $menu['controller_name']) !== TRUE)
                            {
                                continue;
                            }
                            $class = $cont == $menu['module_name'] ? 'active ' : '';
                            $class .= count($menu['menu_items']) > 1 ? 'dropdown' : '';
                            $class = $class == '' ? '' : ' class="' . $class . '"';
                            ?>
                            <li<?php echo $class; ?>>

                                <?php if (count($menu['menu_items']) > 1): ?>
                                    <a href="#" class="dropdown-toggle">
                                        <?php echo $menu['menu_text']; ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($menu['menu_items'] as $link => $item): ?>
                                            <li><?php echo anchor('admin/' . $menu['module_name'] . '/' . $link, $item); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <?php foreach ($menu['menu_items'] as $link => $item): ?>
                                        <a href="<?php echo 'admin/' . $menu['module_name'] . '/' . $link; ?>">
                                            <?php echo $item; ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="pull-right">Merhaba <?php echo $this->userdata['name']; ?></p>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="span16">
                    <?php echo $body; ?>
                </div>
            </div>
            <footer>
                <p>&copy; 2011 navruz.net</p>
            </footer>
        </div>
    </body>
</html>