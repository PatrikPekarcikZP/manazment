<?php

use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Admin_Ustavy extends Controller_Admin
{
    const VIEWPATH = 'app/admin/ustavy/';
    const URLPATH = '/admin/ustavy';

    public function before()
    {
        parent::before();
        array_unshift($this->template->title, "Ústavy");
    }

    public function action_index()
    {
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'list' => Model_Ustav::find('all')
        ]);
    }

    public function action_view($id = null)
    {
        $this->template->content = View::forge(self::VIEWPATH . 'view', [
            'ustav' => Model_Ustav::find($id)
        ]);
    }

    public function action_create()
    {
        if (Input::method() == 'POST') {
            $val = Model_Ustav::validate('create');

            if ($val->run()) {
                $vyuka_ustavy = Model_Ustav::forge(array(
                    'nazov' => Input::post('nazov'),
                    'skratka' => Input::post('skratka')
                ));

                if ($vyuka_ustavy and $vyuka_ustavy->save()) {
                    Session::set_flash('success', e('Vytvorený ústav #' . $vyuka_ustavy->id . '.'));

                    Response::redirect(self::URLPATH);
                } else {
                    Session::set_flash('error', e('Nie je možné uložiť nový ústav.'));
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Nový ústav'
        ]);
    }

    public function action_edit($id = null)
    {
        $vyuka_ustavy = Model_Ustav::find($id);
        $val = Model_Ustav::validate('edit');

        if ($val->run()) {
            $vyuka_ustavy->nazov = Input::post('nazov');
            $vyuka_ustavy->skratka = Input::post('skratka');

            if ($vyuka_ustavy->save()) {
                Session::set_flash('success', e('Uložené zmeny ústavu #' . $id));

                Response::redirect(self::URLPATH);
            } else {
                Session::set_flash('error', e('Nie je možné uložiť zmeny #' . $id));
            }
        } else {
            if (Input::method() == 'POST') {
                $vyuka_ustavy->nazov = $val->validated('nazov');
                $vyuka_ustavy->skratka = $val->validated('skratka');

                Session::set_flash('error', $val->error());
            }

            View::set_global('ustav', $vyuka_ustavy, false);
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Úprava ústavu'
        ]);
    }

    public function action_delete($id = null)
    {
        if ($vyuka_ustavy = Model_Ustav::find($id)) {
            $vyuka_ustavy->delete();
            Session::set_flash('success', e('Zmazaný ústav #' . $id));
        } else {
            Session::set_flash('error', e('Nie je možné zmazať ústav #' . $id));
        }

        Response::redirect(self::URLPATH);

    }
}