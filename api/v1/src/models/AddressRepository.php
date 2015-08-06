<?php

namespace Rest\models;

use \Rest\models\Address;

class AddressRepository
{
	private $connection;

	public function __construct(\PDO $connection = null)
	{
        $this->connection = $connection;
        if ($this->connection === null) {
	    	$this->connection = new \PDO(
    			'mysql:host=localhost;dbname=api',
    			'root',
    			'1'
    		);
    		$this->connection->setAttribute(
    			\PDO::ATTR_ERRMODE,
    			\PDO::ERRMODE_EXCEPTION
    		);
        }
	}

	public function find($id)
	{
		$stmt = $this->connection->prepare('
            select * 
            from ADDRESS 
            where ADDRESSID = :id
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
            from ADDRESS
        ');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        
        return $stmt->fetchAll();
    }

    public function add(Address $address)
    {
        $stmt = $this->connection->prepare('
            insert into ADDRESS 
                (LABEL, STREET, HOUSENUMBER, POSTALCODE, CITY, COUNTRY) 
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
            update ADDRESS
            set LABEL = :label,
                STREET = :street,
                HOUSENUMBER = :houseNumber,
                POSTALCODE = :postalCode,
                CITY = :city,
                COUNTRY = :country
            where ADDRESSID = :id
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