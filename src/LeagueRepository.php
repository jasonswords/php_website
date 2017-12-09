<?php
/**
 * Created by PhpStorm.
 * User: jasonswords
 * Date: 20/11/2017
 * Time: 10:24
 */

namespace Itb;


class LeagueRepository
{
    private $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function createTableLeague()
    {
        $sql = 'CREATE TABLE league 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    name VARCHAR(20) NOT NULL,
                    country VARCHAR (20),
                    drone VARCHAR(20),
                    position int,
                    date TIMESTAMP
                    ); ';

        $this->connection->exec($sql);
    }

    public function insertLeagueMember(League $l)
    {
        $name = $l->getName();
        $country = $l->getCountry();
        $drone = $l->getDrone();
        $position = $l->getPosition();

        $sql = 'INSERT INTO league (name, country, drone, position)
			VALUES (:name, :country, :drone, :position)';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam('name', $name );
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':drone', $drone);
        $stmt->bindParam(':position', $position);

        $stmt->execute();
    }

    public function dropTableLeague()
    {
        $this->connection->exec('DROP TABLE league');
    }


    public function getAllLeague()
    {
        $sql = 'SELECT * FROM league';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $leagueResults = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Itb\\League');
        return $leagueResults;
    }

    public function getOneById($id){

        $sql = 'SELECT * FROM league WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\League');

        if ($leagueMember = $stmt->fetch()) {
            return $leagueMember;
        } else {
            return null;
        }
    }

    public function getOneByName($name){

        $sql = 'SELECT * FROM league WHERE name = :name';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\League');

        if ($leagueMember = $stmt->fetch()) {
            return $leagueMember;
        } else {
            return null;
        }
    }

    public function getImageById($id){

        $sql = 'SELECT * FROM league WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\League');

        if ($leagueMember = $stmt->fetch()) {
            return $leagueMember->getDrone();
        } else {
            return null;
        }
    }

    public function getIdByName($name){

        $sql = 'SELECT * FROM league WHERE name = :name';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\League');

        if ($leagueMember = $stmt->fetch()) {
            return $leagueMember->getId();
        } else {
            return null;
        }
    }

    public function deleteOneLeagueMember($id){

        $sql = 'DELETE FROM league WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

    public function updateLeagueTable($id, $name, $country, $drone, $position){

        $sql = 'UPDATE league SET name = :name, country = :country, drone = :drone, position = :position  WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name );
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':drone', $drone);
        $stmt->bindParam(':position', $position);

        $stmt->execute();
    }

    public function searchLeague($search){

        $search = '%' . $search . '%';
        $sql = "SELECT * from league WHERE (name LIKE :search) or (country LIKE :search) or (drone LIKE :search) or (position LIKE :search)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':search', $search);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\League');
        $stmt->execute();
        $league = $stmt->fetchAll();
        return $league;
    }
}