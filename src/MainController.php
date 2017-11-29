<?php

namespace Itb;

class MainController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }


    public function indexAction(){

        $template = 'home.html.twig';
        $argsArray = [
            'pageTitle' => 'Home'
        ];

        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function galleryAction(){
        $dirName = 'images/';
        $images = glob($dirName."*.*");

        $template = 'gallery.html.twig';
        $argsArray = [
            'pageTitle' => 'Gallery',
            'images'  => $images
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
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
        $template = 'products.php.twig';
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

    public function editVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitor = $visitorRepository->getOneById($id);
        $template = 'editVisitor.html.twig';
        $args = [
            'pageTitle' => 'Edit Visitor',
            'visitor' => $visitor
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function visitorAction(){
        $visitorRepository = new VisitorRepository();
        $visitors = $visitorRepository->getAllVisitors();
        $template = 'visitor.html.twig';
        $args = [
            'pageTitle' => 'Visitor',
            'visitors' => $visitors
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function viewVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitor = $visitorRepository->getOneById($id);
        $template = 'viewSingleVisitor.html.twig';
        $args =[
            'pageTitle' => 'View Visitor',
            'visitor' => $visitor
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

    public function createVisitorAction(){
        $template = 'createVisitor.html.twig';
        $argsArray = [
            'pageTitle' => 'Create Account'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function deleteProductAction($id){
        $productRepository = new ProductRepository();
        $productRepository->deleteOneProduct($id);
        header("Location: index.php?action=displayProduct");
        exit();
    }

    public function deleteVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitorRepository->deleteOneVisitor($id);
        header("Location: index.php?action=visitor");
        exit();
    }

    public function processProductUpdateAction($id, $name, $description, $image, $price){
        $productRepository = new ProductRepository();
        $productRepository->updateProductTable($id, $name, $description, $image, $price);
        header("Location: index.php?action=displayProduct");
        exit();
    }

    public function processVisitorUpdateAction($id, $firstName, $secondName, $country, $email){
        $visitorRepository = new VisitorRepository();
        $visitorRepository->updateVisitorTable($id, $firstName, $secondName, $country, $email);
        header("Location: index.php?action=visitor");
        exit();
    }

    public function errorAction(){
        $template = 'errorPage.html.twig';
        $argsArray = [
            'pageTitle' => 'Error'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function productErrorAction(){
        $template = 'productError.html.twig';
        $argsArray = [
            'pageTitle' => 'Product Error'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function aboutAction(){
        $template = 'about.html.twig';
        $argsArray = [
            'pageTitle' => 'About'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function aboutConfirmAction(){
        $template = 'aboutConfirm.html.twig';
        $argsArray = [
            'pageTitle' => 'About'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function siteMapAction(){
        $template = 'siteMap.html.twig';
        $argsArray = [
            'pageTitle' => 'Site Map'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function errorPageAction(){
        $template = 'errorPage.html.twig';
        $argsArray = [
            'pageTitle' => 'Error Page'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }




}