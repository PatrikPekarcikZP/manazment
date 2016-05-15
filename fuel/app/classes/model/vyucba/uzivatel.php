<?php

use Fuel\Core\Str;
use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Vyucba_Uzivatel
 *
 * @property int $id
 * @property int $vyucba_id
 * @property string $mail
 * @property boolean $vyucujuci
 *
 * @property Model_Vyucba $vyucba
 * @property Model_Zadanie_Stav[] $stavy
 *
 * @method static Model_Vyucba_Uzivatel|Model_Vyucba_Uzivatel[] find($id = null, array $options = array())
 */
class Model_Vyucba_Uzivatel extends Model
{
    const COL_ID = 'id';
    const COL_VYUCBA_ID = 'vyucba_id';
    const COL_MAIL = 'mail';
    const COL_VYUCUJUCI = 'vyucujuci';

    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = 'vyucby_uzivatelia';

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        self::COL_ID,
        self::COL_VYUCBA_ID,
        self::COL_MAIL,
        self::COL_VYUCUJUCI
    );

    protected static $_belongs_to = [
        'vyucba' => [
            'key_from' => self::COL_VYUCBA_ID,
            'model_to' => Model_Vyucba::class,
            'key_to' => Model_Vyucba::COL_ID
        ]
    ];

    protected static $_has_many = [
        'stavy' => [
            'key_from' => self::COL_ID,
            'model_to' => Model_Zadanie_Stav::class,
            'key_to' => 'vyucba_uzivatel_id'
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field('vyucba_id', 'Výučba', 'required|valid_string[numeric]');
        $val->add_field('mail', 'Mail', 'required|valid_string[email]');

        return $val;
    }

    public function getText()
    {
        $mailParts = explode("@", $this->mail);
        $nameParts = explode(".", $mailParts[0]);
        foreach ($nameParts as &$part) {
            $part = \Fuel\Core\Str::ucfirst($part);
        }
        if (count($nameParts) >= 2) {
            $a = $nameParts[0];
            $b = $nameParts[1];
            $nameParts[0] = $b;
            $nameParts[1] = $a;
        }
        return implode(" ", $nameParts);
    }

    public function getName()
    {
        return Str::lower(str_replace(" ", "_", $this->getText()));
    }

}
