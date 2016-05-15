<?php

use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Zadanie_Stav
 *
 * @property int $id
 * @property int $zadanie_id
 * @property int $vyucba_uzivatel_id
 * @property string $cas
 * @property string $informacie
 * @property string $hodnotenie
 * @property string $hodnotenie_text
 *
 * @property Model_Zadanie $zadanie
 * @property Model_Vyucba_Uzivatel $uzivatel
 *
 * @method static Model_Zadanie_Stav|Model_Zadanie_Stav[] find($id = null, array $options = array())
 */
class Model_Zadanie_Stav extends Model
{
    const COL_ZADANIE_ID = 'zadanie_id';
    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = 'zadania_stav';

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        'id',
        self::COL_ZADANIE_ID,
        'vyucba_uzivatel_id',
        'cas',
        'stav',
        'informacie',
        'hodnotenie',
        'hodnotenie_text',
    );

    protected static $_belongs_to = [
        'zadanie' => [
            'key_from' => self::COL_ZADANIE_ID,
            'model_to' => Model_Zadanie::class,
            'key_to' => 'id'
        ],
        'uzivatel' => [
            'key_from' => 'vyucba_uzivatel_id',
            'model_to' => Model_Vyucba_Uzivatel::class,
            'key_to' => 'id'
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field('zadanie_id', 'Zadanie', 'required|valid_string[numeric]');
        $val->add_field('vyucba_uzivatel_id', 'Užívateľ', 'required|valid_string[numeric]');

        return $val;
    }

}
