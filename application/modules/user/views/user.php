<?php
add_css('tools');
add_js('js');
add_js('tools');
add_jquery('$("ul.tabs-link").tabs("div.tabs");');
?>
<h1><?php echo $user_profile['name']; ?></h1>
<ul class="tabs-link">
    <li><a href="#">Bilgiler</a></li>
    <li><a href="#">Açtığı Konular</a></li>
    <li><a href="#">Son Mesajları</a></li>
</ul>
<div id="tabs">
    <div class="tabs">
        <table class="full" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="2">Üye Bilgileri - <?php echo $user->username; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Üye Adı</td>
                    <td><?php echo $user->username; ?></td>
                </tr>
                <tr>
                    <td>Adı Soyadı</td>
                    <td><?php echo $user_profile['first_name']; ?> <?php echo $user_profile['last_name']; ?></td>
                </tr>
                <?php if (is_admin ()): ?>
                    <tr>
                        <td>E-Posta Adresi</td>
                        <td><?php echo $user->email; ?></td>
                    </tr>
                <?php endif; ?>
                    <tr>
                        <td>Son Giriş Tarihi</td>
                    <?php if ($user->last_online == '0000-00-00 00:00:00'): ?>
                        <td>Hiç giriş yapmamış</td>
                    <?php else: ?>
                            <td><?php echo timespan_basic(human_to_unix($user->last_online)); ?> Önce</td>
                    <?php endif; ?>
                        </tr>
                        <tr>
                            <td>Kayıt Tarihi</td>
                            <td><?php echo tr_date('d F Y', human_to_unix($user->created)); ?></td>
                        </tr>
                        <tr>
                            <td>Üye Grubu</td>
                            <td><?php echo $user_group['title']; ?></td>
                        </tr>
                        <tr>
                            <td>Cinsiyet</td>
                            <td><?php echo ($user_profile['gender'] == 'f') ? 'Bayan' : 'Bay'; ?></td>
                        </tr>
                        <tr>
                            <td>Forum Mesaj Sayısı</td>
                            <td><?php echo $user_profile['post_count']; ?> Mesaj</td>
                        </tr>
                    </tbody>
                </table>
        <div class="center"><?php echo anchor('pm/send_pm/'.$user->username,'Mesaj Gönder','class="awesome"'); ?></div>
            </div>
           
                                <div class="tabs">
                                    <table class="full" border="0" cellpadding="0" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Son açtığı konular</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                <?php foreach ($forum_topics as $topic): ?>
                                                <tr>
                                                    <td>
                        <?php echo anchor('topic/' . $topic['slug'], $topic['title']); ?>
                                            </td>
                                            <td>
                        <?php echo tr_date('d F Y H:i', $topic['created_on'], TRUE); ?>
                                            </td>
                                        </tr>
                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tabs">
                                        <table class="full" border="0" cellpadding="0" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Son yazdığı mesajlar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                <?php foreach ($forum_posts as $post): ?>
                                                    <tr>
                                                        <td>
                        <?php echo anchor('topic/' . $post['slug'], $post['title']); ?>
                                                </td>
                                                <td>
                        <?php echo tr_date('d F Y H:i', $post['post_created_on'], TRUE); ?>
                                                </td>
                                            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>