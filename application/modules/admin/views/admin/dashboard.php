<div class="subcolumns">
    <ul class="dashboard-menu clearfix">
        <li><a href="admin/post/index">
                <img src="assets/img/admin/dashboard/post.png" width="48" height="48" alt=""/>
                <span>Yazılar</span>
            </a>
        </li>
        <li><a href="admin/post/add_post">
                <img src="assets/img/admin/dashboard/add-post.png" width="48" height="48" alt=""/>
                <span>Yazı Ekle</span>
            </a>
        </li>
        <li><a href="admin/user/index">
                <img src="assets/img/admin/dashboard/users.png" width="48" height="48" alt="" />
                <span>Üyeler</span>
            </a>
        </li>
        <li><a href="admin/user/add">
                <img src="assets/img/admin/dashboard/add-user.png" width="48" height="48" alt=""/>
                <span>Üye Ekle</span>
            </a>
        </li>
        <li><a href="admin/clear_cache">
                <img src="assets/img/admin/dashboard/clear.png" width="48" height="48" alt=""/>
                <span>Önbelleği Temizle</span>
            </a>
        </li>
        <li><a href="admin/database/backup">
                <img src="assets/img/admin/dashboard/database.png" width="48" height="48" alt=""/>
                <span>Veritabanı Yedekle</span>
            </a>
        </li>
        <li><a href="admin/permission/index/">
                <img src="assets/img/admin/dashboard/permission.png" width="48" height="48" alt=""/>
                <span>İzinler</span>
            </a>
        </li>
        <li><a href="admin/post/sitemap">
                <img src="assets/img/admin/dashboard/sitemap.png" width="48" height="48" alt=""/>
                <span>Site Haritasını Güncelle</span>
            </a>
        </li>
    </ul>
</div>
<h2>İstatistikler</h2>
<div class="subcolumns">
    <div class="c50l">
        <div class="subcl">
            <table class="full" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td>Yazı Sayısı</td>
                        <td><?php echo $stats['post']; ?></td>
                    </tr>
                    <tr>
                        <td>Kategori Sayısı</td>
                        <td><?php echo $stats['category']; ?></td>
                    </tr>
                    <tr>
                        <td>Etiket Sayısı</td>
                        <td><?php echo $stats['tags']; ?></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="c50r">
        <div class="subcl">
            <table>
                <tbody>
                    <tr>
                        <td nowrap="nowrap" style="vertical-align: top;">Php Sürümü</td>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" style="vertical-align: top;">Sunucu</td>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" style="vertical-align: top;">Mysql Versiyonu</td>
                        <td><?php echo mysql_get_server_info() ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h2>Son 5 Yazı</h2>
<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Başlık</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if ($post !== FALSE): ?>
        <?php foreach ($post as $post_item) : ?>
                <tr>
                    <td><?php echo anchor($post_item['slug'], cut_string($post_item['title'], 50, '...', ' '), 'target="_blank"'); ?></td>
                    <td>
                <?php echo ($post_item['created_on'] < time()) ? '<span class="yesil">Yayında</span>' : '<span class="kirmizi">Beklemede</span>'; ?>
            </td>
            <td><?php echo date('d-m-Y H:i', $post_item['created_on']); ?></td>
            <td class="action">
                <?php echo anchor('admin/post/edit_post/' . $post_item['id'], 'Düzenle', 'class="edit"'); ?>
                <?php echo anchor('admin/post/delete_post/' . $post_item['id'], 'Sil', 'class="delete" ' . js_confirm()); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="5">Henüz yazı bulunmuyor.</td>
                    </tr>
        <?php endif; ?>
    </tbody>
</table>