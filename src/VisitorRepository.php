<?php

namespace Itb;

class VisitorRepository
{

    private $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function createTableAccounts()
    {

        $sql = 'CREATE TABLE visitors 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    firstName VARCHAR(50) NOT NULL,
                    secondName VARCHAR (50) NOT NULL,
                    country VARCHAR(50) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    date TIMESTAMP
                    ); ';

        $this->connection->exec($sql);
    }

    public function insertVisitor(Visitor $v)
    {
        $firstName = $v->getfirstName();
        $secondName = $v->getSecondName();
        $country = $v->getCountry();
        $email = $v->getEmail();

        $sql = 'INSERT INTO visitors (firstName, secondName, country, email)
			VALUES (:firstName, :secondName, :country, :email)';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':firstName', $firstName );
        $stmt->bindParam(':secondName', $secondName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

    }

    public function dropTableVisitorss()
    {
        $this->connection->exec('DROP TABLE visitors');
    }


    public function getAllVisitors()
    {
        $sql = 'SELECT * FROM visitors';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $visitor = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Itb\\Visitor');
        return $visitor;
    }

    public function getOneById($id){

        $sql = 'SELECT * FROM visitors WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Visitor');

        if ($visitor = $stmt->fetch()) {
            return $visitor;
        } else {
            return null;
        }
    }

    public function getOneByName($name){
        $sql = 'SELECT * FROM visitors WHERE firstName = :firstName';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('firstName', $name);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'ITB\\Visitor');

        if($visitor = $stmt->fetch()){
            return $visitor->getId();

            var_dump($visitor);
            die();
        }
        else{
            return null;
        }
    }

    public function deleteOneVisitor($id){

        $sql = 'DELETE FROM visitors WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

    public function updateVisitorTable($id, $firstName, $secondName, $country, $email){

        $sql = 'UPDATE visitors SET firstName = :firstName, secondName = :secondName, country = :country, email = :email  WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':firstName', $firstName );
        $stmt->bindParam(':secondName', $secondName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
    }
}