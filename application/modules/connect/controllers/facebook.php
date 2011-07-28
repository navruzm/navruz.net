<?php

class Facebook extends MY_Controller
{

    private $access_token = FALSE;

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('session');
        keep_all_flashdata();
        $this->access_token = $this->session->userdata('access_token');
        $this->load->config('facebook');
        if (config_item('facebook_appid') == '' OR config_item('facebook_secret') == '')
        {
            show_error('You must set facebook application id and secret.');
        }
    }

    function index()
    {
        redirect(base_url());
    }

    function connect()
    {
        $code = $this->input->get('code');
        $login_url = 'https://graph.facebook.com/oauth/authorize?client_id=' . config_item('facebook_appid') . '&redirect_uri=' . base_url() . 'extra.php/connect/facebook/connect?x=y&scope=email,user_about_me,user_birthday,user_photos';
        if (!$this->access_token && !$code)
        {
            redirect($login_url);
        }
        elseif (!$this->access_token && $code)
        {
            $furl = $this->_make_request('https://graph.facebook.com/oauth/access_token?'
                            . 'client_id=' . config_item('facebook_appid') . '&'
                            . 'redirect_uri=' . base_url() . 'extra.php/connect/facebook/connect?x=y&'
                            . 'client_secret=' . config_item('facebook_secret') . '&'
                            . 'code=' . $code);
            parse_str($furl, $response);

            if (isset($response['access_token']))
            {
                $this->session->set_userdata('access_token', $response['access_token']);

                redirect(base_url() . 'extra.php/connect/facebook/connect');
            }
            else
            {
                echo 'nothing to do';
            }
        }
        elseif ($this->access_token)
        {
            $me = json_decode($this->_make_request('https://graph.facebook.com/me?fields=id,first_name,last_name,location,picture,email,birthday,gender&access_token=' . $this->access_token));

            if (isset($me->error))
            {
                $this->session->set_userdata('access_token', FALSE);
                redirect(base_url() . 'extra.php/connect/facebook/connect');
            }
            elseif (isset($me->email))
            {
                $array = array('user' => array(
                        'facebook_id' => $me->id,
                        'first_name' => $me->first_name,
                        'last_name' => $me->last_name,
                        'email' => $me->email,
                        'birthday' => @$me->birthday,
                        'gender' => @$me->gender,
                        'picture' => $me->picture,
                        'location' => @$me->location,
                    )
                );
                $this->session->set_userdata($array);
                redirect(base_url() . 'user/facebook/connected');
            }
        }
        echo 'nothing to do';
    }

    function logout()
    {
        $logout_url = 'http://www.facebook.com/logout.php?next=' . base_url() . 'user/logout.html&access_token=' . $this->access_token;
        if ($this->access_token)
        {
            $this->session->set_userdata('access_token', FALSE);
            redirect($logout_url);
        }
        else
        {
            redirect(base_url() . 'user/logout.html');
        }
    }

    function register()
    {
        redirect('facebook/connect');
    }

    function _make_request($url, $params=array())
    {
        $ch = curl_init();
        $opts = array(
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => 'facebook-php-2.0',
            CURLOPT_POST => FALSE,
        );

        if (count($params))
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');

        $opts[CURLOPT_URL] = $url;
// disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
// for 2 seconds if the server does not support this header.
        if (isset($opts[CURLOPT_HTTPHEADER]))
        {
            $existing_headers = $opts[CURLOPT_HTTPHEADER];
            $existing_headers[] = 'Expect:';
            $opts[CURLOPT_HTTPHEADER] = $existing_headers;
        }
        else
        {
            $opts[CURLOPT_HTTPHEADER] = array('Expect:');
        }

        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        if ($result === false)
        {
            die('error');
        }
        curl_close($ch);
        return $result;
    }

}

/* End of file facebook.php */