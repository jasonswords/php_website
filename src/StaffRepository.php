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

        $sql = "CREATE TABLE staff 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    userName VARCHAR(50) NOT NULL,
                    password VARCHAR (255) NOT NULL,
                    privilege  INT,
                    date TIMESTAMP
                    ); ";

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

        // Bind parameters to statement variables
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':privilege', $privilege);

        // Execute statement
        $stmt->execute();

    }

    public function dropTableStaff()
    {
        // Drop table messages from file db
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

        return $staff;
    }

    public function updateStaffTable($id, $userName, $password, $privilege){

        $sql = 'UPDATE staff SET userName = :userName, password = :password, privilege = :privileges WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':privileges', $privileges);

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

        return $staff;
    }

    public function deleteOneUser($id){

        $sql = 'DELETE FROM staff WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

}