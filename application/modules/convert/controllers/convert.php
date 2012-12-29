<?php
set_time_limit(0);

class Convert extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->mongo_db->category->drop();
        $_categories = array();
        foreach ($this->db->get('categories')->result_array() as $category)
        {
            $sql_data = array(
                '_id'=> new MongoId(),
                'slug' => $category['category_slug'],
                'title' => $category['category_title'],
                'description' => $category['category_description'],
                'meta_title' => $category['meta_title'],
                'meta_description' => $category['meta_description'],
                'meta_keyword' => $category['meta_keywords'],
                'order' => $category['weight'],
            );

            $this->mongo_db->category->save($sql_data);
            $_categories[$category['category_id']] =  $sql_data['_id'];
            echo $category['category_title'] . ' kategorisi aktarıldı.<br>';
        }
        $this->mongo_db->post->drop();
        $this->mongo_db->gridfs->drop();
        require_once APPPATH . 'libraries/disqusapi/disqusapi.php';
        $disqus = new DisqusAPI('3V7jkQ6JyFpCr5m41XC0oSLCneh9NcHfI8expHgCVId3uNeX9cTXgx0CJXmocUwZ');
        $commented = array();
        foreach ($disqus->forums->listThreads(array('forum' => 'navruz', 'order' => 'asc', 'limit' => '100')) as $_ident)
        {
            $commented[] = isset($_ident->identifiers[1]) ? $_ident->identifiers[1] : $_ident->identifiers[0];
        }
        foreach ($this->db->get('posts')->result_array() as $post)
        {
            $comments = array();
            if (in_array('post_' . $post['id'], $commented))
            {
                $response = $disqus->threads->listPosts(array('thread' => 'ident:post_' . $post['id'], 'forum' => 'navruz', 'order' => 'asc'));
                foreach ($response as $comment)
                {
                    $comments[] = array(
                        'id' => $comment->id,
                        'author_name' => $comment->author->name,
                        'author_url' => isset($comment->author->url)? : $comment->author->url,
                        'message' => $comment->message,
                        'created_at' => new MongoDate(strtotime($comment->createdAt)),
                    );
                }
            }
            $categories = array();
            foreach ($this->db->where('post_id', $post['id'])->get('post_relationship')->result_array() as $category)
            {
                $categories[] = $_categories[$category['category_id']];
            }

            $tags = array();
            foreach ($this->db->where('object_id', $post['id'])->get('tags_object')->result_array() as $tag)
            {
                $_tag = $this->db->where('id', $tag['tag_id'])->get('tags')->row_array();
                $tags[] = array('slug' => $_tag['tag'], 'tag' => $_tag['raw_tag']);
            }

            $sql_data = array(
                'slug' => $post['slug'],
                'author' => $post['author'],
                'title' => $post['title'],
                'content' => $post['summary'] . $post['content'],
                'image' => $post['image'],
                'counter' => $post['counter'],
                'comments_enabled' => $post['comments_enabled'],
                'status' => 'publish',
                'categories' => $categories,
                'tags' => $tags,
                'meta_title' => $post['meta_title'],
                'meta_description' => $post['meta_description'],
                'meta_keyword' => $post['meta_keywords'],
                'created_at' => new MongoDate($post['created_on']),
                'updated_at' => new MongoDate($post['updated_on']? : time()),
                'comments' => $comments,
                'disqus_identifier' => 'post_'. $post['id'],
            );
            $image = 'media/posts/' . date('Y', $post['created_on']) . '/' . date('m', $post['created_on']) . '/' . $post['image'];
            if (is_file($image))
            {
                $file_ext = end(explode('.', $post['image']));
                if ($file_ext == 'jpg' OR $file_ext == 'jpeg')
                {
                    $type = 'image/jpeg';
                }
                elseif ($file_ext == 'png')
                {
                    $type = 'image/png';
                }
                $this->mongo_db->gridfs->storeFile($image, array('filename' => $post['image'], 'type' => $type));
            }
            $this->mongo_db->post->ensureIndex('slug');
            $this->mongo_db->post->save($sql_data);

            echo $post['title'] . ' yazısı aktarıldı.<br>';
        }

        $this->mongo_db->page->drop();
        foreach ($this->db->get('pages')->result_array() as $page)
        {
            $sql_data = array(
                'slug' => $page['slug'],
                'author' => $page['author'],
                'title' => $page['title'],
                'content' => $page['content'],
                'counter' => $page['counter'],
                'comments_enabled' => $page['comments_enabled'],
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'meta_keyword' => $page['meta_keywords'],
                'created_at' => new MongoDate($page['created_on']),
                'updated_at' => new MongoDate($page['updated_on']? : time()),
                'id' => $page['id'],
            );

            $this->mongo_db->page->ensureIndex('slug');
            $this->mongo_db->page->save($sql_data);

            echo 'Sayfalar aktarıldı.<br>';
        }
        $this->mongo_db->user->drop();
        foreach ($this->db->get('users')->result_array() as $user)
        {
            $sql_data = array(
                'name' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'id' => $user['id'],
                'activated' => $user['activated'],
                'last_ip' => $user['last_ip'],
                'last_login' => new MongoDate(strtotime($user['last_login'])),
                'created_at' => new MongoDate(strtotime($user['created'])),
                'updated_at' => new MongoDate(strtotime($user['modified'])),
                'permissions' => array(':all:' => 1),
            );

            $this->mongo_db->user->save($sql_data);
            echo 'Üyeler aktarıldı.<br>';

            $this->mongo_db->navigation->drop();
            $navigation = array(
                'slug' => 'HEAD_MENU',
                'title' => 'Üst Menü',
                'items' => array(
                    array(
                        'title' => 'Anasayfa',
                        'url' => '/',
                        'access_level' => '0',
                        'target' => '',
                    ),
                    array(
                        'title' => 'Arşiv',
                        'url' => '/post/archive',
                        'access_level' => '0',
                        'target' => '',
                    ),
                    array(
                        'title' => 'İletişim',
                        'url' => '/contact',
                        'access_level' => '0',
                        'target' => '',
                    )
                ),
            );
            $this->mongo_db->navigation->insert($navigation);
        }
    }

    public function comments($id)
    {
        require_once 'disqusapi/disqusapi.php';
        $disqus = new DisqusAPI('3V7jkQ6JyFpCr5m41XC0oSLCneh9NcHfI8expHgCVId3uNeX9cTXgx0CJXmocUwZ');
        //$response = $disqus->threads->listPosts(array('thread' => 'ident:post_60', 'forum' => 'navruz', 'order' => 'asc'));
        $response = $disqus->threads->listPosts(array('thread' => 'link:http://www.navruz.net/codeigniter-mongodb-kutuphanesi', 'forum' => 'navruz', 'order' => 'asc'));
        //$response = $disqus->forums->listPosts(array( 'forum' => 'navruz', 'since'=>strtotime('2 months ago'),'order' => 'asc'));
        //$response = $disqus->forums->listThreads(array('forum' => 'navruz', 'order' => 'asc'));
    }

}