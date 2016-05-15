<?php

class Controller_Admin extends Controller_Root
{
    const VIEWPATH = "app/admin/";
    const URLPATH = "/admin/";

    public function before()
    {
        parent::before();
        array_unshift($this->template->title, "Administrácia");
    }

    public function action_index()
    {
        $this->template->content = \Fuel\Core\View::forge(self::VIEWPATH . 'index');
    }
}