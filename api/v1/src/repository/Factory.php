<?php

namespace Rest\Repository;

use Rest\Utils\Database\Connection;

class Factory
{
    /**
     * @param $entityName string`
     * @return RepositoryInterface
     * @throws \Exception
     */
    public function getRepository($entityName)
    {
        $repoClassName = 'Rest\\Repository\\' . $entityName . 'Repository';

        if (!class_exists($repoClassName)) {
            throw new \Exception('Repository for ' . $entityName . ' resource does not exist! Please, define this class');
        }

        $dbConnection = Connection::getInstance();
        $repository = new $repoClassName($dbConnection->getConnection());

        return $repository;
    }
}