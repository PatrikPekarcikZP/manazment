<?php

use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Zadanie
 *
 * @property int $id
 * @property int $vyucba_id
 * @property string $nazov
 * @property mixed $data
 *
 * @property Model_Vyucba $vyucba
 * @property Model_Zadanie_Stav[] $stavy
 *
 * @method static Model_Zadanie|Model_Zadanie[] find($id = null, array $options = array())
 */
class Model_Zadanie extends Model
{
    const TABLE_NAME = 'zadania';

    const COL_ID = 'id';
    const COL_VYUCBA_ID = 'vyucba_id';
    const COL_NAZOV = 'nazov';
    const COL_DATA = 'data';

    const REL_VYUCBA = 'vyucba';
    const REL_STAVY = 'stavy';

    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = self::TABLE_NAME;

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        self::COL_ID,
        self::COL_VYUCBA_ID,
        self::COL_NAZOV,
        self::COL_DATA,
    );

    protected static $_belongs_to = [
        self::REL_VYUCBA => [
            'key_from' => self::COL_VYUCBA_ID,
            'model_to' => Model_Vyucba::class,
            'key_to' => Model_Vyucba::COL_ID
        ]
    ];

    protected static $_has_many = [
        self::REL_STAVY => [
            'key_from' => self::COL_ID,
            'model_to' => Model_Zadanie_Stav::class,
            'key_to' => Model_Zadanie_Stav::COL_ZADANIE_ID
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field('vyucba_id', 'Výučba', 'required|valid_string[numeric]');
        $val->add_field('nazov', 'Názov', 'required');

        return $val;
    }

    /**
     * @return Model_Zadanie[]
     */
    public static function find_otvorene()
    {
        return self::find('all', [
            'related' => [self::REL_VYUCBA],
            'where' => [
                [self::REL_VYUCBA . '.' . Model_Vyucba::COL_OTVORENA, '=', '1']
            ]
        ]);
    }

    /**
     * @return Model_Zadanie[]
     */
    public static function find_archive()
    {
        return self::find('all', [
            'related' => [self::REL_VYUCBA],
            'where' => [
                [self::REL_VYUCBA . '.' . Model_Vyucba::COL_OTVORENA, '=', '0']
            ]
        ]);
    }

    public function getPocetVypracovanych()
    {
        // TODO: implement this
        return 0;
    }

    public function getPocetSHodnotenim()
    {
        // TODO: implement this
        return 0;
    }

}
