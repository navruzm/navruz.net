<div class="alert-message block-message info">
    <p>Veritabanı yedeğini almak için aşağıdaki butona basınız.</p>

    <div class="alert-actions">
        <?php echo anchor('admin/options/database_export', 'Yedekle', 'class="btn small"'); ?>
    </div>
</div>

<div class="alert-message block-message info">
    <p>Daha önce aldığınız yedeği buradan yükleyebilirsiniz.</p>
    <?php echo form_open_multipart('admin/options/database_import');?>
    <?php echo form_upload('file');?><br>
    <?php echo form_submit('submit', 'Gönder', 'class="btn"');?>
    <?php echo form_close();?>
</div>