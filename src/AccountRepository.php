<?php

namespace Itb;

class AccountRepository
{

    private $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function createTableAccounts()
    {

        $sql = "CREATE TABLE accounts 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    firstName VARCHAR(20) NOT NULL,
                    secondName VARCHAR (20) NOT NULL,
                    country VARCHAR(20) NOT NULL,
                    userName VARCHAR(20) NOT NULL,
                    password VARCHAR (255) NOT NULL,
                    date TIMESTAMP
                    ); ";

        $this->connection->exec($sql);

    }

    public function insertAccount(Account $m)
    {
        $firstName = $m->getfirstName();
        $secondName = $m->getSecondName();
        $country = $m->getCountry();
        $password = $m->getPassword();
        $userName = $m->getUser();

        $sql = 'INSERT INTO accounts (firstName, secondName, country, userName, password)
			VALUES (:firstName, :secondName, :country, :userName, :password)';
        $stmt = $this->connection->prepare($sql);

        // Bind parameters to statement variables
        $stmt->bindParam(':firstName', $firstName );
        $stmt->bindParam(':secondName', $secondName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);

        // Execute statement
        $stmt->execute();

    }

    public function dropTableAccounts()
    {
        // Drop table messages from file db
        $this->connection->exec('DROP TABLE accounts');
    }


    public function getAllAccounts()
    {
        $sql = 'SELECT * FROM accounts';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $accounts = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Itb\\Account');
        return $accounts;
    }

    public function getOneById($id){

        $sql = 'SELECT * FROM accounts WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Account');

        if ($account = $stmt->fetch()) {
            return $account;
        } else {
            return null;
        }

        return $account;
    }

    public function getOneByUserName($userName){

        $sql = 'SELECT * FROM accounts WHERE userName = :userName';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Account');

        if ($account = $stmt->fetch()) {
            return $account;
        } else {
            return null;
        }

        return $account;
    }

    public function deleteOneAccount($id){

        $sql = 'DELETE FROM accounts WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

    public function updateAccountTable($id, $firstName, $secondName, $country, $userName, $password){

        $sql = 'UPDATE accounts SET firstName = :firstName, secondName = :secondName, country = :country, userName = :userName, password = :password  WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':firstName', $firstName );
        $stmt->bindParam(':secondName', $secondName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
    }

}