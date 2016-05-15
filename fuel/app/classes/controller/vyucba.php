<?php

use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\View;

class Controller_Vyucba extends Controller_Root
{
    const VIEWPATH = 'app/vyucba/';
    const URLPATH = '/vyucba';

    public function before()
    {
        parent::before();
        array_unshift($this->template->title, "Výučba");
    }

    public function action_index()
    {
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'title' => 'Výučba',
            'list' => Model_Vyucba::find_otvorena()
        ]);
    }

    public function action_archive()
    {
        array_unshift($this->template->title, "Ukončená výučba");
        $this->template->content = View::forge(self::VIEWPATH . 'index', [
            'title' => 'Ukončená výučba',
            'list' => Model_Vyucba::find_archive()
        ]);
    }

    public function action_view($id = null)
    {
        $this->template->content = View::forge(self::VIEWPATH . 'view', [
            'vyucba' => Model_Vyucba::find($id)
        ]);
    }

    public function action_create()
    {
        if (Input::method() == 'POST') {
            $val = Model_Vyucba::validate('create');

            if ($val->run()) {
                $vyucba = Model_Vyucba::forge(array(
                    Model_Vyucba::COL_PREDMET_ID => Input::post('predmet_id'),
                    Model_Vyucba::COL_ROK => Input::post('rok'),
                    Model_Vyucba::COL_OTVORENA => 1
                ));

                // TODO: skontrolovat ze moze byt len jedna otvorena vyucba

                if ($vyucba and $vyucba->save()) {
                    Session::set_flash('success', e('Otvorená výučba #' . $vyucba->id . '.'));

                    Response::redirect(self::URLPATH . '/view/' . $vyucba->id);
                } else {
                    Session::set_flash('error', e('Nie je možné otvoriť výučbu.'));
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }

        $this->template->content = View::forge(self::VIEWPATH . '_form', [
            'formTitle' => 'Nová výučba'
        ]);
    }

    public function action_close($id = null)
    {
        if ($vyucba = Model_Vyucba::find($id)) {
            // TODO: neake kontroly??
            $vyucba->otvorena = 0;
            if ($vyucba->save()) {
                Session::set_flash('success', e('Uzavretá výučba #' . $id));
            } else {
                Session::set_flash('error', e('Nie je možné uzavrieť výučbu #' . $id . ' (chyba uloženia)'));
            }
        } else {
            Session::set_flash('error', e('Nie je možné uzavrieť výučbu #' . $id . ' (záznam nenájdený)'));
        }


        Response::redirect(self::URLPATH);
    }

    public function action_delete($id = null)
    {
        if ($vyucba = Model_Vyucba::find($id)) {
            $vyucba->delete();
            Session::set_flash('success', e('Zmazaný ústav #' . $id));
        } else {
            Session::set_flash('error', e('Nie je možné zmazať ústav #' . $id));
        }

        Response::redirect(self::URLPATH);

    }
}