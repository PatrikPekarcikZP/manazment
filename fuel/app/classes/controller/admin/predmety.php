<?php

use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Admin_Predmety extends Controller_Admin
{
    const VIEWPATH = 'app/admin/predmety/';
    const URLPATH = '/admin/predmety';

    public function before()
    {
        parent::before();
        array_unshift($this->template->title, "Predmety");
    }

    public function action_index()
    {
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'list' => Model_Predmet::find('all')
        ]);
    }

    public function action_view($id = null)
    {
        $this->template->content = View::forge(self::VIEWPATH . 'view', [
            'predmet' => Model_Predmet::find($id)
        ]);
    }

    public function action_create()
    {
        if (Input::method() == 'POST') {
            $val = Model_Predmet::validate('create');

            if ($val->run()) {
                $predmet = Model_Predmet::forge(array(
                    'ustav_id' => Input::post('ustav_id'),
                    'nazov' => Input::post('nazov'),
                    'skratka' => Input::post('skratka')
                ));

                if ($predmet and $predmet->save()) {
                    Session::set_flash('success', e('Vytvorený predmet #' . $predmet->id . '.'));

                    Response::redirect(self::URLPATH);
                } else {
                    Session::set_flash('error', e('Nie je možné uložiť nový predmet.'));
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Nový predmet'
        ]);
    }

    public function action_edit($id = null)
    {
        $predmet = Model_Predmet::find($id);
        $val = Model_Predmet::validate('edit');

        if ($val->run()) {
            $predmet->ustav_id = Input::post('ustav_id');
            $predmet->nazov = Input::post('nazov');
            $predmet->skratka = Input::post('skratka');

            if ($predmet->save()) {
                Session::set_flash('success', e('Uložené zmeny predmetu #' . $id));

                Response::redirect(self::URLPATH);
            } else {
                Session::set_flash('error', e('Nie je možné uložiť zmeny #' . $id));
            }
        } else {
            if (Input::method() == 'POST') {
                $predmet->nazov = $val->validated('nazov');
                $predmet->skratka = $val->validated('skratka');

                Session::set_flash('error', $val->error());
            }

            View::set_global('predmet', $predmet, false);
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Úprava predmetu'
        ]);
    }

    public function action_delete($id = null)
    {
        if ($predmet = Model_Predmet::find($id)) {
            $predmet->delete();
            Session::set_flash('success', e('Zmazaný predmet #' . $id));
        } else {
            Session::set_flash('error', e('Nie je možné zmazať predmet #' . $id));
        }

        Response::redirect(self::URLPATH);

    }
}