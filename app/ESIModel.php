<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ESIModel extends Model
{

    /**
     * @param array $objects
     * @return called_class[]
     */
    public static function esiToObjects(array $objects)
    {
        $new_collection = [];
        foreach ($objects as $object) {
            $new_collection[] = self::esiInstanceToObject($object);
        }
        return $new_collection;
    }

    /**
     * converts a single object to a Model object (normally from ESI)
     * @param $esi
     * @return static
     */
    private static function esiInstanceToObject($esi)
    {
        $class = get_called_class();
        $new_object = new $class();
        foreach ($class::$mapping as $key => $map_to) {
            if (!empty($map_to) && property_exists($esi, $key)) {
                $new_object->$map_to = $esi->{$key};
            }
        }
        return $new_object;
    }
}
