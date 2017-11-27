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
                    name VARCHAR(100) NOT NULL,
                    description VARCHAR(255),
                    image VARCHAR(255) NOT NULL,
                    price FLOAT  NOT NULL,
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


        $sql = 'INSERT INTO products (name, description, image, price)
			VALUES (:name, :description, :image, :price)';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':name', $name );
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);

        $stmt->execute();
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
    }

    public function getOneByName($name){
        $sql = 'SELECT * FROM products WHERE name = :name';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('name', $name);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'ITB\\Product');

        if($product = $stmt->fetch()){
            return $product->getId();
        }
        else{
            return null;
        }


}

    public function updateProductTable($id, $name, $description, $image, $price){

        $sql = 'UPDATE products SET name = :name, description = :description, image = :image, price = :price  WHERE id = :id';

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