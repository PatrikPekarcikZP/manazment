<?php

use Fuel\Core\Validation;
use Orm\Model;

/**
 * Class Model_Ustav
 *
 * @property int $id
 * @property string $nazov
 * @property string $skratka
 *
 * @property Model_Predmet[] $predmety
 *
 * @method static Model_Ustav|Model_Ustav[] find($id = null, array $options = array())
 */
class Model_Ustav extends Model
{
    /**
     * @var  string  table name to overwrite assumption
     */
    protected static $_table_name = 'ustavy';

    /**
     * @var array    model properties
     */
    protected static $_properties = array(
        'id',
        'nazov',
        'skratka',
    );

    protected static $_has_many = [
        'predmety' => [
            'key_from' => 'id',
            'model_to' => Model_Predmet::class,
            'key_to' => 'ustav_id'
        ]
    ];

    public static function validate($factory)
    {
        $val = Validation::forge($factory);

        $val->add_field('nazov', 'Názov ústavu', 'required');
        $val->add_field('skratka', 'Skratka', 'required|valid_string[alpha]');

        return $val;
    }

}
