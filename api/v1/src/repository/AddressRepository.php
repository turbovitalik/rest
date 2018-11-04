<?php

namespace Rest\Repository;

use Rest\models\Address;

class AddressRepository implements RepositoryInterface
{
	private $connection;

	private $table = 'address';

	public function __construct(\PDO $connection)
	{
        $this->connection = $connection;
	}

	public function find($id)
	{
	    $id = (int) $id;

		$stmt = $this->connection->prepare('
            select * 
            from ' . $this->table . ' 
            where id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\Rest\models\Address');
        
        return $stmt->fetch(); 
	}

	public function findAll()
    {
        $stmt = $this->connection->prepare('
            select * 
            from ' . $this->table . '
        ');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        
        return $stmt->fetchAll();
    }

    public function add(Address $address)
    {
        $stmt = $this->connection->prepare('
            insert into ' . $this->table . ' 
                (label, street, house_number, postal_code, city, country) 
            values 
                (:label, :street, :houseNumber, :postalCode, :city, :country)
        ');
        $stmt->bindParam(':label', $address->label);
        $stmt->bindParam(':street', $address->street);
        $stmt->bindParam(':houseNumber', $address->houseNumber);
        $stmt->bindParam(':postalCode', $address->postalCode);
        $stmt->bindParam(':city', $address->city);
        $stmt->bindParam(':country', $address->country);

        return $stmt->execute();
    }

    public function update(Address $address)
    {
        $stmt = $this->connection->prepare('
            update ' . $this->table . '
            set label = :label,
                street = :street,
                house_number = :houseNumber,
                postal_code = :postalCode,
                city = :city,
                country = :country
            where id = :id
        ');
        $stmt->bindParam(':label', $address->label);
        $stmt->bindParam(':street', $address->street);
        $stmt->bindParam(':houseNumber', $address->houseNumber);
        $stmt->bindParam(':postalCode', $address->postalCode);
        $stmt->bindParam(':city', $address->city);
        $stmt->bindParam(':country', $address->country);
        $stmt->bindParam(':id', $address->id);
        return $stmt->execute();
    } 
}