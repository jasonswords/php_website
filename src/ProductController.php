<?php

namespace Itb;

class ProductController{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function processProductAction(){

        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $imageName = basename($target_file);

        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $uploadOk = 0;
            print 'wrong file extension';
            die();
        }
        if ($uploadOk == 0) {
            header("Location: index.php?action=errorPage");
            exit();
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                $name = filter_input(INPUT_POST, 'name');
                $price = filter_input(INPUT_POST, 'price');
                $description = filter_input(INPUT_POST, 'description');
                $p = new Product();
                $p->setName($name);
                $p->setPrice($price);
                $p->setImage($imageName);
                $p->setDescription($description);
                $productRepository = new ProductRepository();
                $productRepository->createTableProducts();
                $productRepository->insertProduct($p);

                $id = $productRepository->getOneByName($name);

                header("Location: index.php?action=displaySingleProduct&id=<? $id >");
                exit();

            } else {

                header("Location: index.php?action=productError");
                exit();
            }
        }
    }

    public function addProductAction(){
        $template = 'addProduct.html.twig';
        $argsArray = [
            'pageTitle' => 'Add Product'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function displayProductAction(){

        $products = new ProductRepository();
        $p = $products->getAllProducts();
        $template = 'products.html.twig';
        $argsArray = [
            'pageTitle' => 'Products',
            'products'  => $p
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function editProductAction($id){

        $productRepository = new ProductRepository();
        $product = $productRepository->getOneById($id);
        $template = 'editProduct.html.twig';
        $args = [
            'pageTitle' => 'Edit Product',
            'product' => $product
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function displaySingleProductAction($id){
        $productRepository = new ProductRepository();
        $product = $productRepository->getOneById($id);
        $template = 'displaySingleProduct.html.twig';
        $args = [
            'pageTitle' => 'View Product',
            'product' => $product
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function deleteProductAction($id){
        $productRepository = new ProductRepository();
        $productRepository->deleteOneProduct($id);
        header("Location: index.php?action=displayProduct");
        exit();
    }

    public function processProductUpdateAction($id, $name, $description, $image, $price){
        $productRepository = new ProductRepository();
        $productRepository->updateProductTable($id, $name, $description, $image, $price);
        header("Location: index.php?action=displayProduct");
        exit();
    }



}