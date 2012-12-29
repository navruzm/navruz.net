<?php foreach ($status as $message => $status): ?>
<?php if($status): ?>
<div class="alert-message success">
    <?php echo $message; ?> Önbelleği Temizlendi
</div>
<?php else: ?>
<div class="alert-message error">
    <?php echo $message; ?> Önbelleği Temizlenemedi
</div>
<?php endif; ?>
<?php endforeach; ?>