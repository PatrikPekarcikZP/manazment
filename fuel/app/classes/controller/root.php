<?php

use Auth\Auth_Opauth;
use Auth\OpauthException;

class Controller_Root extends \Fuel\Core\Controller_Hybrid
{
    public $template = "semantic/html-template";

    private $current_user;

    public function before()
    {
        parent::before();
        if (\Auth\Auth::instance()->check()) {
            $this->current_user = \Auth\Auth::instance()->get_user_id();
        } else {
            $this->current_user = null;
        }

        $request = \Fuel\Core\Request::active();
        if ($this->current_user == null
            && !($request->controller == Controller_Root::class && (substr($request->action, 0,
                        5) == "login" || substr($request->action, 0, 8) == "callback"))
        ) {
            \Fuel\Core\Response::redirect("login");
        }
        if (!$this->is_restful()) {
            $this->template->user = $this->current_user;
            $this->template->title = ["GitLab Uni"];
        }
    }

    // region Auth

    public function action_login($provider = null)
    {
        if ($provider == null) {
            return \Fuel\Core\View::forge("semantic/login");
        }
        try {
            Auth_Opauth::forge([
                'provider' => 'GitLab', // len tento bude podporovany
                'redirect_uri' => \Fuel\Core\Uri::create('login/callback')
            ]);
            return \Fuel\Core\View::forge("semantic/login");
        } catch (OpauthException $e) {
            return \Fuel\Core\View::forge("semantic/login", ['error' => $e->getMessage()]);
        }
    }

    public function action_logout()
    {
        \Auth\Auth::instance()->logout();
        return \Fuel\Core\View::forge("semantic/login", ['semantic' => true]);
    }

    public function action_callback()
    {
        $opauth = Auth_Opauth::forge([
            'provider' => 'GitLab'
        ], false);
        $opauth->login_or_register();
        \Fuel\Core\Response::redirect("/");
    }

    // endregion

    public function action_index()
    {

        $vyucba = Model_Vyucba::find('all', [
            'where' => [
                [Model_Vyucba::COL_OTVORENA, '=', 1]
            ]
        ]);
        $predmety = Model_Predmet::find('all');
        $ustavy = Model_Ustav::find('all');

        $data = [
            'vyucba' => $vyucba,
            'predmety' => $predmety,
            'ustavy' => $ustavy
        ];

        array_unshift($this->template->title, "Dashboard");
        $this->template->content = \Fuel\Core\View::forge('app/dashboard', $data);
    }

    public function action_error($code)
    {
        $this->template->title = ["Error " . $code];
        $this->template->content = \Fuel\Core\View::forge("error/" . $code);
    }


}