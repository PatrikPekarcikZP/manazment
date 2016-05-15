<?php

class Controller_Setup extends Controller_Root
{
    public function before()
    {
        parent::before();
        $this->template->title = ["Administrácia"];
    }

    public function action_index()
    {
        $this->template->content = "admin";
    }
}