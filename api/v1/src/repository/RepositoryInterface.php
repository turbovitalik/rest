<?php

namespace Rest\Repository;

use Rest\models\Address;

interface RepositoryInterface
{
    public function find($id);
    public function findAll();
    public function add(Address $address);
    public function update(Address $address);
}