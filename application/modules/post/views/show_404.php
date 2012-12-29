<div class="alert-message block-message error">
    <h3>Aradığınız Sayfa Bulunamadı</h3>

    <p>
        Üzgünüz, aradağınız sayfaya erişemiyoruz.
    </p>

    <ul>
        <li>Aradığınız sayfa yayından kaldırılmış olabilir.</li>
        <li>Aradığınız sayfanın adresi değişmiş olabilir.</li>
        <li>Teknik bir hata oluşmuş olabilir.</li>
    </ul>
    <div class="alert-actions">
        <a href="<?php echo site_url();?>" class="btn small">Beni Anasayfaya Götür</a>
    </div>
</div>
<?php if (count($posts)): ?>
<div class="alert-message block-message info">
    <h5>İşinize Yarayabilecek Yazılar</h5>
    <ul>
        <?php foreach ($posts as $post): ?>
        <li><?php echo anchor($post['slug'], $post['title']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>