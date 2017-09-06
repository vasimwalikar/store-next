<?php
class AbstractDAO
{
    protected static function fetchQuery($query, $bindParams = array(), $fetchParams = PDO::FETCH_ASSOC, $classname = "")
    {
        try {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $connection->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
            $stmt = $connection->prepare($query);
            $stmt->execute($bindParams);
//            $connection->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
            if ($classname) {
                $results = $stmt->fetchAll($fetchParams, $classname);
            } else {
                $results = $stmt->fetchAll($fetchParams);
            }
        } catch (PDOException $pde) {
            throw $pde;
        }
        return $results;

    }

    public static function countAll($table) {
        $query = "SELECT count(*) as count from ".$table;
        $result = self::fetchQuery($query);
        return $result[0]['count'];
    }


    protected static function updateQuery($query, $bindParams = array())
    {
        try {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $stmt = $connection->prepare($query);
            $stmt->execute($bindParams);
            $q = $stmt->_debugQuery() ;
            return $stmt;


        } catch (PDOException $pde) {
            throw $pde;
        }

    }

    protected static function insertQuery($query, $bindParams = array())
    {
        try {


            $connection = ConnectionFactory::getFactory()->getConnection();
            $stmt = $connection->prepare($query);
            $stmt->execute($bindParams);
            $q = $stmt->_debugQuery() ;
            $lastInsertId = $connection->lastInsertId();


        } catch (PDOException $pde) {
            throw $pde;
        }
        return $lastInsertId;

    }

    protected static function deleteQuery($query, $bindParams = array())
    {
        try {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $stmt = $connection->prepare($query);
            $stmt->execute($bindParams);

        } catch (PDOException $pde) {
            throw $pde;
        }

    }
}