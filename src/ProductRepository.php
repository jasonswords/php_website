<?php

namespace Itb;

class ProductRepository
{

    private $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function createTableProducts()
    {

        $sql = "CREATE TABLE products 
                    (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    name VARCHAR(20) NOT NULL,
                    price FLOAT  NOT NULL,
                    image VARCHAR(50) NOT NULL,
                    description VARCHAR(150),
                    date TIMESTAMP
                    ); ";

        $this->connection->exec($sql);

    }

    public function insertProduct(Product $p)
    {

        $name = $p->getName();
        $price = $p->getPrice();
        $image = $p->getImage();
        $description = $p->getDescription();


        $sql = 'INSERT INTO products (name, price, image, description)
			VALUES (:name, :price, :image, :description)';
        $stmt = $this->connection->prepare($sql);

        // Bind parameters to statement variables
        $stmt->bindParam(':name', $name );
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);

        // Execute statement
        $stmt->execute();

        //print "<h2> added to table successfully</h2>";
    }

    public function dropTableProducts()
    {
        // Drop table messages from file db
        $this->connection->exec('DROP TABLE products');
    }


    public function getAllProducts()
    {
        $sql = 'SELECT * FROM products';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $products = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Itb\\Product');
        return $products;
    }

    public function getOneById($id){
        $sql = 'SELECT * FROM products WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Itb\\Product');

        if ($product = $stmt->fetch()) {
            return $product;
        } else {
            return null;
        }

        return $product;
    }

    public function updateProductTable($id, $name, $price, $image, $description){

        $sql = 'UPDATE products SET name = :name, price = :price, image = :image, description = :description WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name );
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);

        $stmt->execute();
    }

    public function deleteOneProduct($id){
        $sql = 'DELETE FROM products WHERE id = :id';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
    }

}