<?php

use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Predmet
 *
 * @property int $id
 * @property int $ustav_id
 * @property string $nazov
 * @property string $skratka
 *
 * @property Model_Ustav $ustav
 * @property Model_Vyucba[] $vyucby
 *
 * @method static Model_Predmet|Model_Predmet[] find($id = null, array $options = array())
 */
class Model_Predmet extends Model
{
    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = 'predmety';

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        'id',
        'ustav_id',
        'nazov',
        'skratka',
    );

    protected static $_belongs_to = [
        'ustav' => [
            'key_from' => 'ustav_id',
            'model_to' => Model_Ustav::class,
            'key_to' => 'id'
        ]
    ];

    protected static $_has_many = [
        'vyucby' => [
            'key_from' => 'id',
            'model_to' => Model_Vyucba::class,
            'key_to' => Model_Vyucba::COL_PREDMET_ID
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field('ustav_id', 'Ústav', 'required|valid_string[numeric]');
        $val->add_field('nazov', 'Názov predmetu', 'required');
        $val->add_field('skratka', 'Skratka', 'required|valid_string[alpha]');

        return $val;
    }

    public function getOtvorenaVyuka()
    {
        foreach ($this->vyucby as $vyucba) {
            if($vyucba->otvorena == 1) {
                return $vyucba;
            }
        }
        return null;
    }

}
