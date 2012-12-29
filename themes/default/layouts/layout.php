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
    <div class="container">
        <div class="wrapper">
            <div class="page-header">
                <header>
                    <?php echo heading(anchor('', get_option('site_name')), ($this->module == 'home' ? 1 : 2)); ?>
                    <span><?php echo get_option('site_description'); ?></span>
                </header>
                <nav class="row">
                    <?php echo get_navigation('HEAD_MENU', 'clearfix'); ?>
                    <div id="search">
                        <form action="search" method="GET">
                            <input type="text" id="keyword" name="q" placeholder="Arama terimini girin" class="keyword" value="<?php echo $this->input->get('q'); ?>">
                            <input type="submit" id="search-btn" value="">
                        </form>
                    </div>
                </nav>
            </div>
            <div class="row">
                <div class="span12">
                    <?php echo $body; ?>
                </div>
                <div class="span4">
                    <h3>Kategoriler</h3>
                    <?php echo get_category_ul(array('class' => 'categories')); ?>
                </div>
            </div>
            <footer>
                <p>&copy; Mustafa Navruz 2012 {elapsed_time} sn.</p>
            </footer>
        </div>
        <div id="go-top">^ Başa Dön</div>
    </div> <!-- /container -->
</body>
</html>