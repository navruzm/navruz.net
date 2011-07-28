<?php foreach ($status as $message => $status): ?>
<?php if($status): ?>
<div class="success">
    <?php echo $message; ?> Önbelleği Temizlendi
</div>
<?php else: ?>
<div class="error">
    <?php echo $message; ?> Önbelleği Temizlenemedi
</div>
<?php endif; ?>
<?php endforeach; ?>