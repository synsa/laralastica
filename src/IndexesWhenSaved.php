<?php namespace Michaeljennings\Laralastica;

use Illuminate\Database\Eloquent\Model;
use Michaeljennings\Laralastica\Events\IndexesWhenSaved as IndexesWhenSavedEvent;

trait IndexesWhenSaved {

    /**
     * Add the model event listener to index the model when it is saved.
     */
    protected static function bootIndexesWhenSaved()
    {
        static::saved(function($model)
        {
            static::$dispatcher->fire(new IndexesWhenSavedEvent($model));
        });
    }

    /**
     * Return an array of attributes to be indexed.
     *
     * @param Model $model
     * @return array
     */
    public function getIndexableAttributes(Model $model)
    {
        return $model->getAttributes();
    }

    /**
     * Return an array of columns to be indexed with the column as the key and
     * the desired data type as the value.
     *
     * @return array
     */
    public function getSearchDataTypes()
    {
        return [
            'id' => 'int',
        ];
    }

    /**
     * Loop through the attributes and type cast them if neccesary.
     *
     * @param array $attributes
     * @return array
     */
    public function transformAttributes(array $attributes)
    {
        $searchDataTypes = $this->getSearchDataTypes();

        if ( ! empty($searchDataTypes)) {
            foreach ($attributes as &$attribute) {
                if (array_key_exists($attribute, $searchDataTypes)) {
                    switch ($searchDataTypes[$attribute]) {
                        case "int":
                            $attribute = (int) $attribute;
                            break;
                        case "integer":
                            $attribute = (int) $attribute;
                            break;
                        case "string":
                            $attribute = (string) $attribute;
                            break;
                        case "float":
                            $attribute = (float) $attribute;
                            break;
                        case "bool":
                            $attribute = (bool) $attribute;
                            break;
                        case "boolean":
                            $attribute = (bool) $attribute;
                            break;
                    }
                }
            }
        }

        return $attributes;
    }

}