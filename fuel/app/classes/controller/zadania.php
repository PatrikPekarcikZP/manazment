<?php

use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Zadania extends Controller_Root
{
    const VIEWPATH = 'app/zadania/';
    const URLPATH = '/zadania';

    public function before()
    {
        parent::before();
        array_unshift($this->template->title, "Zadania");
    }

    public function action_index()
    {
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'title' => 'Zadania',
            'list' => Model_Zadanie::find_otvorene()
        ]);
    }

    public function action_archive()
    {
        array_unshift($this->template->title, "Archív");
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'title' => 'Archív zadaní',
            'list' => Model_Zadanie::find_archive()
        ]);
    }

    public function action_view($id = null)
    {
        array_unshift($this->template->title, "Detail #" . $id);
        $this->template->content = View::forge(self::VIEWPATH . 'view', [
            'zadanie' => Model_Zadanie::find($id)
        ]);
    }

    public function action_create()
    {
        if (Input::method() == 'POST') {
            $val = Model_Zadanie::validate('create');

            // TODO: vytvorit mozeme len na otvorenej vyucbe!

            if ($val->run()) {
                $zadanie = Model_Zadanie::forge(array(
                    'vyucba_id' => Input::post('vyucba_id'),
                    'nazov' => Input::post('nazov'),
                    'data' => Input::post('data')
                ));

                if ($zadanie and $zadanie->save()) {
                    Session::set_flash('success', e('Vytvorené zadanie #' . $zadanie->id . '.'));

                    Response::redirect(self::URLPATH);
                } else {
                    Session::set_flash('error', e('Nie je možné uložiť nové zadanie.'));
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        } else {
            $zadanie = Model_Zadanie::forge([
                Model_Zadanie::COL_VYUCBA_ID => Input::get('vyucba_id', '')
            ]);
            View::set_global('zadanie', $zadanie);
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Nové zadanie'
        ]);
    }

    public function action_edit($id = null)
    {
        $zadanie = Model_Zadanie::find($id);
        $val = Model_Zadanie::validate('edit');

        if ($val->run()) {
            $zadanie->vyucba_id = Input::post('vyucba_id');
            $zadanie->nazov = Input::post('nazov');
            $zadanie->data = Input::post('data');

            if ($zadanie->save()) {
                Session::set_flash('success', e('Uložené zmeny zadania #' . $id));

                Response::redirect(self::URLPATH);
            } else {
                Session::set_flash('error', e('Nie je možné uložiť zmeny #' . $id));
            }
        } else {
            if (Input::method() == 'POST') {
                $zadanie->vyucba_id = $val->validated('vyucba_id');
                $zadanie->nazov = $val->validated('nazov');
                $zadanie->data = $val->validated('data');

                Session::set_flash('error', $val->error());
            }

            View::set_global('zadanie', $zadanie, false);
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Úprava zadania'
        ]);
    }

    public function action_delete($id = null)
    {
        if ($zadanie = Model_Zadanie::find($id)) {
            $zadanie->delete();
            Session::set_flash('success', e('Zmazané zadanie #' . $id));
        } else {
            Session::set_flash('error', e('Nie je možné zmazať zadanie #' . $id));
        }

        Response::redirect(self::URLPATH);

    }
}