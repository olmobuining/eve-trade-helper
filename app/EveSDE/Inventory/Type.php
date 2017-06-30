<?php
/**
 * App\EveSDE\Inventory\Type
 *
 * @property int $typeID
 * @property int $groupID
 * @property string $typeName
 * @property string $description
 * @property float $mass
 * @property float $volume
 * @property float $capacity
 * @property int $portionSize
 * @property int $raceID
 * @property float $basePrice
 * @property bool $published
 * @property int $marketGroupID
 * @property int $iconID
 * @property int $soundID
 * @property int $graphicID
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereBasePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereCapacity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereGraphicID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereGroupID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereIconID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereMarketGroupID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereMass($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type wherePortionSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type wherePublished($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereRaceID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereSoundID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereTypeID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereTypeName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EveSDE\Inventory\Type whereVolume($value)
 */

namespace App\EveSDE\Inventory;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * The database table used by the model (mysql).
     *
     * @var string
     */
    protected $table = 'inv_types';
}
