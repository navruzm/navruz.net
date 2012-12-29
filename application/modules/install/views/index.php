<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <title>Kurulum</title>
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link type="text/css" rel="stylesheet" href="compress.css?theme=default&amp;css=styles.css" />
        <style type="text/css">
            /* Override some defaults */
            html, body {
                background-color: #eee;
            }
            body {
                padding-top: 10px; /* 10px to make the container go all the way to the bottom of the topbar */
            }
            .container > footer p {
                text-align: center; /* center align it with the container */
            }
            .container {
                width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
            }

            /* The white background content wrapper */
            .content {
                background-color: #fff;
                padding: 20px;
                margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
                -webkit-border-radius:6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
                -webkit-box-shadow: 1px 1px 2px rgba(0,0,0,.15);
                -moz-box-shadow: 1px 1px 2px rgba(0,0,0,.15);
                box-shadow: 1px 1px 2px rgba(0,0,0,.15);
            }

            /* Page header tweaks */
            .page-header {
                background-color: #f5f5f5;
                padding: 10px 20px 5px;
                margin: -10px -20px 20px;
            }
            .help-inline {display: block;}
            form div.clearfix.error {
                margin: -7px 0 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="page-header">
                    <h1>Kurulum</h1>
                </div>
                <div class="row">
                    <div class="span14">
                        <?php if ($this->input->get('ok') == 1): ?>
                            <div class="alert-message block-message success">
                                <p>Kurulum başarıyla tamamlandı.</p>
                                <div class="alert-actions">
                                    <?php echo anchor('admin','Giriş Yap','class="btn small"'); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php echo form_open($this->uri->uri_string()); ?>
                            <?php echo form_fieldset('Site Bilgileri'); ?>
                            <?php echo form_item('site_name', 'Site Adı'); ?>
                            <?php echo form_item('site_email', 'Site E-posta Adresi'); ?>
                            <?php echo form_fieldset_close(); ?>
                            <?php echo form_fieldset('Yönetici Bilgileri'); ?>
                            <?php echo form_item('name', 'İsim'); ?>
                            <?php echo form_item('email', 'E-Posta'); ?>
                            <?php echo form_item('password', 'Yeni Şifre', 'password'); ?>
                            <?php echo form_item('confirm_password', 'Yeni Şifre Tekrarı', 'password'); ?>
                            <?php echo form_hidden('permissions[:all:]', 1); ?>
                            <?php echo form_fieldset_close(); ?>
                            <div class="actions">
                                <button type="submit" class="btn primary">Gönder</button>
                            </div>
                            <?php echo form_close(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <footer>
                <p>&copy; 2011</p>
            </footer>
        </div> <!-- /container -->
    </body>
</html>