<?php

namespace Itb;

class StaffRepository
{
    private $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function createTableStaff()
    {
        $sql = 'CREATE TABLE staff 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    userName VARCHAR(50) NOT NULL,
                    password VARCHAR (255) NOT NULL,
                    privilege  INT,
                    date TIMESTAMP
                    ); ';
        $this->connection->exec($sql);
    }

    public function insertUser(Staff $u)
    {
        $userName = $u->getUserName();
        $password = $u->getPassword();
        $privilege = $u->getPrivilege();

        $sql = 'INSERT INTO staff(userName, password, privilege)
			VALUES (:userName, :password, :privilege)';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':privilege', $privilege);

        $stmt->execute();
    }

    public function dropTableStaff()
    {
        $this->connection->exec('DROP TABLE staff');
    }


    public function getAllStaff()
    {
        $sql = 'SELECT * FROM staff';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $staff = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Itb\\Staff');

        return $staff;
    }

    public function getOneById($id){

        $sql = 'SELECT * FROM staff WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Staff');

        if ($staff = $stmt->fetch()) {
            return $staff;
        } else {
            return null;
        }
    }

    public function updateStaffTable($id, $userName, $password, $privilege){


        $sql = 'UPDATE staff SET userName = :userName, password = :password, privilege = :privilege WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':privilege', $privilege);

        $stmt->execute();
    }

    public function getOneByUserName($userName){

        $sql = 'SELECT * FROM staff WHERE userName = :userName';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Staff');

        if ($staff = $stmt->fetch()) {
            return $staff;
        } else {
            return null;
        }
    }

    public function deleteOneUser($id){

        $sql = 'DELETE FROM staff WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

    public function searchStaff($search){

        $search = '%' . $search . '%';
        $sql = "SELECT * from staff WHERE (userName LIKE :search) or (privilege LIKE :search)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':search', $search);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Product');
        $stmt->execute();
        $staff = $stmt->fetchAll();
        return $staff;
    }
}