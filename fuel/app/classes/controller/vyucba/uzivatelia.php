<?php

use Fuel\Core\Input;
use Fuel\Core\Session;

class Controller_Vyucba_Uzivatelia extends Controller_Root
{
    const URLPATH = Controller_Vyucba::URLPATH . "/uzivatelia";
    const VIEWPATH = Controller_Vyucba::VIEWPATH . "uzivatelia/";

    public function action_create()
    {
        if (Input::method() == 'POST') {
            $vyucba_id = Input::post('vyucba_id');
            $mail = Input::post('mail');
            $vyucujuci = Input::post('vyucujuci', false) == 'true';

            $vyucba = Model_Vyucba::find($vyucba_id);
            if ($vyucba == null) {
                return ['status' => 'error', 'message' => 'Výučba nebola nájdená!'];
            }

            $val = Model_Vyucba_Uzivatel::validate('create');

            $created = [];
            $errors = [];
            foreach (explode("\n", $mail) as $mailAddress) {

                $data = [
                    Model_Vyucba_Uzivatel::COL_VYUCBA_ID => $vyucba->id,
                    Model_Vyucba_Uzivatel::COL_MAIL => $mailAddress,
                    Model_Vyucba_Uzivatel::COL_VYUCUJUCI => $vyucujuci ? '1' : '0'
                ];

                if ($val->run($data)) {
                    $uzivatel = Model_Vyucba_Uzivatel::forge($data);

                    if ($uzivatel and $uzivatel->save()) {
                        $created[] = $mailAddress;
                    } else {
                        $errors[] = "Chyba pri uložení adresy " . $mailAddress;
                    }
                } else {
                    $errors[] = "Chyba validácie adresy " . $mailAddress;
                }
            }

            if (count($created) > 0) {
                $count = count($created);
                if ($count == 1) {
                    Session::set_flash('success', 'Uložená e-mailová adresa');
                } elseif ($count >= 2 && $count <= 5) {
                    Session::set_flash('success', 'Uložené ' . count($created) . ' emailové adresy');
                } else {
                    Session::set_flash('success', 'Uložených ' . count($created) . ' emailových adries');
                }

            }
            if (count($errors) > 0) {
                Session::set_flash('error', $errors);
            }
            
            return $this->response(['status' => 'ok', 'goto' => Controller_Vyucba::URLPATH . '/view/' . $vyucba->id]);
        }
        return $this->response(['status' => 'error', 'message' => 'requerst not available']);
    }

    public function action_delete($id) {
        $uzivatel = Model_Vyucba_Uzivatel::find($id);
        if($uzivatel != null) {
            $uzivatel->delete();
            return $this->response(['status' => 'ok']);
        }
        return $this->response(['status' => 'error', 'message' => 'error']);
    }

}