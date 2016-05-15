<?php

use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Vyucba
 *
 * @property int $id
 * @property int $predmet_id
 * @property int $rok
 * @property boolean $otvorena
 *
 * @property Model_Predmet $predmet
 * @property Model_Vyucba_Uzivatel[] $uzivatelia
 * @property Model_Zadanie[] $zadania
 *
 * @method static Model_Vyucba|Model_Vyucba[] find($id = null, array $options = array())
 */
class Model_Vyucba extends Model
{
    const COL_ID = 'id';
    const COL_PREDMET_ID = 'predmet_id';
    const COL_ROK = 'rok';
    const COL_OTVORENA = 'otvorena';
    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = 'vyucby';

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        self::COL_ID,
        self::COL_PREDMET_ID,
        self::COL_ROK,
        self::COL_OTVORENA,
    );

    protected static $_belongs_to = [
        'predmet' => [
            'key_from' => self::COL_PREDMET_ID,
            'model_to' => Model_Predmet::class,
            'key_to' => 'id'
        ]
    ];

    protected static $_has_many = [
        'uzivatelia' => [
            'key_from' => self::COL_ID,
            'model_to' => Model_Vyucba_Uzivatel::class,
            'key_to' => 'vyucba_id'
        ],
        'zadania' => [
            'key_from' => self::COL_ID,
            'model_to' => Model_Zadanie::class,
            'key_to' => 'vyucba_id'
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field(self::COL_PREDMET_ID, 'Predmet', 'required|valid_string[numeric]');
        $val->add_field(self::COL_ROK, 'Rok výučby', 'required|valid_string[numeric]|min_length=4|max_length=4');

        return $val;
    }

    /**
     * @return Model_Vyucba[]
     */
    public static function find_otvorena()
    {
        return self::find('all', [
            'where' => [
                [self::COL_OTVORENA, '=', '1']
            ]
        ]);
    }

    /**
     * @return Model_Vyucba[]
     */
    public static function find_archive()
    {
        return self::find('all', [
            'where' => [
                [self::COL_OTVORENA, '=', '0']
            ]
        ]);
    }

    public function getPocetStudentov()
    {
        return 0;
    }

    public function getPocetZadani()
    {
        return 0;
    }

    /**
     * @return Model_Vyucba_Uzivatel[]
     */
    public function getStudenti()
    {
        $a = [];
        foreach ($this->uzivatelia as $uzivatel) {
            if ($uzivatel->vyucujuci == 0) {
                $a[] = $uzivatel;
            }
        }
        return $a;
    }

    /**
     * @return Model_Vyucba_Uzivatel[]
     */
    public function getVyucujuci()
    {
        $a = [];
        foreach ($this->uzivatelia as $uzivatel) {
            if ($uzivatel->vyucujuci == 1) {
                $a[] = $uzivatel;
            }
        }
        return $a;
    }

}
