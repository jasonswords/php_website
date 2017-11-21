<?php

namespace Itb;

class MainController
{
    private $twig;
    private $username;

    public function __construct($twig, $username)
    {
        $this->twig = $twig;
        $this->username = $username;
    }


    public function indexAction(){

        $template = 'home.html.twig';
        $argsArray = [
            'pageTitle' => 'Home',
        ];

        if(!empty($username)){
            $argsArray['username'] = $username;
        }

        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function galleryAction(){
        $template = 'gallery.html.twig';
        $argsArray = [
            'pageTitle' => 'Gallery'
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

    public function editAccountAction($id){

        $accountRepository = new AccountRepository();

        $account = $accountRepository->getOneById($id);

        $template = 'editAccount.html.twig';
        $args = [
            'pageTitle' => 'Edit Account',
            'account' => $account
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function accountsAction(){

        $accountRepository = new AccountRepository();
        $accounts = $accountRepository->getAllAccounts();

        $template = 'accounts.html.twig';
        $args = [
            'pageTitle' => 'Accounts Available',
            'accounts' => $accounts
        ];

        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function createAccountAction(){
        $template = 'createAccount.html.twig';
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

    public function deleteAccountAction($id){

        $accountRepository = new AccountRepository();

        $accountRepository->deleteOneAccount($id);


        header("Location: index.php?action=accounts");
        exit();
    }

    public function processProductUpdateAction($id, $name, $price, $image, $description){

        $productRepository = new ProductRepository();

        $productRepository->updateProductTable($id, $name, $price, $image, $description);

        header("Location: index.php?action=displayProduct");
        exit();
    }

    public function processAccountUpdateAction($id, $firstName, $secondName, $country, $userName, $password){
        $accountRepository = new AccountRepository();

        $accountRepository->updateAccountTable($id, $firstName, $secondName, $country, $userName, $password);

        header("Location: index.php?action=products");
        exit();
    }

    public function errorAction(){

        $template = 'errorPage.html.twig';
        $argsArray = [
            'pageTitle' => 'Error',

        ];

        $html = $this->twig->render($template, $argsArray);
        print $html;
    }




}