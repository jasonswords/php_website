<?php

namespace Itb;

class ProductController{

    private $twig;
    private $logger;

    public function __construct($twig, $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function processProductAction(){
        $fileUpload = new FileUpload();

        if($fileUpload->fileWasUploaded()) {
            $imageName = $fileUpload->uploadImage();
        }
        else{
            $imageName = null;
        }

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

        $id = $productRepository->getIdByName($name);

        if ($id == null) {
            header("Location: index.php?action=productError");
            exit();
        } else {
            header("Location: index.php?action=displaySingleProduct&id=<? $id >");
            exit();
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

    public function displayProductAction($p, $heading, $link){

        if(empty($p)){
            $heading = 'No Product Data Available';
        }

        $template = 'products.html.twig';
        $argsArray = [
            'pageTitle' => 'Products',
            'heading' => $heading,
            'link' => $link,
            'products'  => $p
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }






    public function displaySearchProductAction($searchString)
    {
        $productRepository = new ProductRepository();
        $products = $productRepository->searchProduct($searchString);

        if($products == null){
            $this->displayProductAction(null, 'No Results Found', true);
        }else {
            $this->displayProductAction($products, 'Search Results', true);
        }
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

    public function processProductUpdateAction(){

        $fileUpload = new FileUpload();

        $id = filter_input(INPUT_POST, 'id');
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $price = filter_input(INPUT_POST, 'price');
        $imageName = filter_input(INPUT_POST, 'imageName');

        if($fileUpload->fileWasUploaded()) {
            $fileName = $fileUpload->uploadImage();

            if ($fileName != ".") {
                $imageName = $fileName;
            }
        }

        $productRepository = new ProductRepository();
        $productRepository->updateProductTable($id, $name, $description, $imageName, $price);
        header("Location: index.php?action=displaySingleProduct&id=<? $id >");
        exit();
    }

}