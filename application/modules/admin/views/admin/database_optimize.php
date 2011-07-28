<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Tablo</th>
            <th>Durum</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result !== FALSE): ?>
        <?php foreach ($result as $table => $data): ?>
                <tr>
                    <td><?php echo $table; ?></td>
                    <td><?php echo $data['Msg_text']; ?></td>

                </tr>
        <?php endforeach; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="5">Bir hata meydana geldi.</td>
                    </tr>
        <?php endif; ?>
    </tbody>
</table>