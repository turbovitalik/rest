<?php

namespace Rest\models;

use Rest\Helpers\CamelCaseHelper;
use Rest\Utils\Database\Connection;

class AddressMapper
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $table = 'address';

    /**
     * AddressMapper constructor.
     * @param Connection $connection
     * @param CamelCaseHelper $camelCase
     */
    public function __construct(Connection $connection, CamelCaseHelper $camelCase)
    {
        $this->connection = $connection;
        $this->camelCase = $camelCase;
    }

    /**
     * @param array $criteria
     * @return array | Address
     */
    public function select($criteria = [])
    {
        $bindParams = [];
        $sql = "select * from {$this->table}";

        if ($criteria) {
            $whereSql = "";

            array_walk($criteria, function ($value, $key) use (&$whereSql, &$bindParams) {
                $wherePart = "$key=:$key";
                $whereSql .= strlen($whereSql) ? " and " : "";
                $whereSql .= $wherePart;
                $bindParams[":$key"] = $value;
            });

            $sql .= " where $whereSql";
        }

        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute($bindParams);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Rest\models\Address');

        return $stmt->fetchAll();
    }

    /**
     * @param Address $address
     * @return bool
     */
    public function insert(Address $address)
    {
        $query = "insert into {$this->table} (label, street, house_number, postal_code, city, country, comments)
            values (:label, :street, :houseNumber, :postalCode, :city, :country, :comments)";

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':label', $address->label);
        $stmt->bindParam(':street', $address->street);
        $stmt->bindParam(':houseNumber', $address->houseNumber);
        $stmt->bindParam(':postalCode', $address->postalCode);
        $stmt->bindParam(':city', $address->city);
        $stmt->bindParam(':country', $address->country);
        $stmt->bindParam(':comments', $address->comments);

        return $stmt->execute();
    }

    public function update($id, Address $address)
    {
        $id = (int) $id;

        $fields = $this->mapObjectToArray($address);
        $fieldsToSet = $this->excludeUnchanged($fields, $address->getUpdatedKeys());

        if (!$fieldsToSet) {
            return false;
        }

        $query = "update {$this->table} ";
        $query .= "set " . $this->getQuerySetString($fieldsToSet) . " ";
        $query .= "where id = :id";

        $stmt = $this->connection->getConnection()->prepare($query);

        $stmt->bindParam(':id', $id);

        foreach ($fieldsToSet as $key => $value) {
            $stmt->bindParam(":$key", $value);
        }

        return $stmt->execute();
    }

    private function getQuerySetString($attributes)
    {
        $queryStr = '';
        array_walk($attributes, function ($item, $key) use (&$queryStr) {
            $bind = ':' . $key;
            $queryStr .= strlen($queryStr) ? ',' : '';
            $queryStr .= $key . '=' . $bind;
        });

        return $queryStr;
    }

    private function mapObjectToArray(Address $address)
    {
        return [
            'label' => $address->getLabel(),
            'street' => $address->getStreet(),
            'house_number' => $address->getHouseNumber(),
            'postal_code' => $address->getPostalCode(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'comments' => $address->getComments()
        ];
    }

    private function excludeUnchanged($fields, $keysOfChanged)
    {
        $camelCase = new CamelCaseHelper();

        return array_filter($fields, function ($key) use ($keysOfChanged, $camelCase) {
            return in_array($camelCase($key), $keysOfChanged);
        }, ARRAY_FILTER_USE_KEY);
    }

    // TODO: duplicated in Address domain object
    public function nameCamelCase($name)
    {
        $parts = explode('_', $name);

        $i = 0;
        $camelCased = array_reduce($parts, function ($carry, $item) use (&$i) {
            $item = $i > 0 ? ucfirst($item) : $item;
            $i++;
            return $carry . $item;
        }, '');

        return $camelCased;
    }
}
