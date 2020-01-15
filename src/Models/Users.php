<?php

declare(strict_types=1);

namespace Canvas\Models;

use Canvas\Users as UserResource;

/**
 * Users Class.
 */
class Users
{
    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function find($params = null, $opts = null)
    {
        $searchBy = [];
        if (isset($params['conditions'])) {
            // Find for OR or AND statements and push them to an array
            // $conditions = str_replace('and',',', $params['conditions']);
            $conditions = preg_split("/\b(?:and|or)\b/", $params['conditions']);

            //If there is a bind among the params then we need to map the conditions wildcards to the elements on bind
            foreach ($conditions as $key => $value) {
                if (isset($params['bind']) && array_key_exists($key, $params['bind'])) {
                    $bindValue = $params['bind'][$key];
                    $conditions[$key] = !is_numeric($bindValue) ? str_replace(' ', '', str_replace('= ?' . $key, ':%' . $bindValue . '%', $value)) : str_replace(' ', '', str_replace('= ?' . $key, ':' . $bindValue, $value));
                } else {
                    $conditionArray = explode(' ', rtrim($value));
                    $conditions[$key] = !is_numeric(end($conditionArray)) ? str_replace(' ', '', str_replace('= '. end($conditionArray), ':%' . end($conditionArray) . '%', $value)) : str_replace(' ', '', str_replace('= '. end($conditionArray), ':' . end($conditionArray), $value));
                }
            }
            $searchBy['conditions'] = $conditions;
        }

        if (isset($params['order'])) {
            $params['order'] = strpos($params['order'], 'DESC') ? str_replace(' DESC', '|desc', $params['order']) : str_replace(' ASC', '|ASC', $params['order']);
            $searchBy['sort'] = $params['order'];
        }

        if (isset($params['limit'])) {
            $searchBy['limit'] = $params['limit'];
        }

        // return $searchBy;

        return UserResource::all([], $searchBy);
    }

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function findFirst($params = null, $opts = null)
    {
        return Users::retrieve('2', [], ['relationships' => ['roles']]);
    }
}
