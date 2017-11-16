<?php

namespace Itb;

class WebApplication
{

    private $mainController;
    private $secondaryController;
    private $processingController;

    const PATH_TO_TEMPLATES = __DIR__ . '/../views';

    public function __construct()
    {
        $userName = $this->getUserNameFromSession();
        $twig = new \Twig\Environment(new \Twig_Loader_Filesystem(self::PATH_TO_TEMPLATES));
        $this->mainController = new MainController($twig, $userName);
        $this->secondaryController = new SecondaryController($twig, $userName);
        $this->processingController = new processingController($twig, $userName);
    }

    public function getUserNameFromSession()
    {
        if(isset($_SESSION['username'])){
            return $_SESSION['username'];
        } else {
            return null;
        }
    }

    public function run()
    {

        $action = filter_input(INPUT_GET, 'action');

        switch($action) {

//               Login

            case 'login':
                $this->secondaryController->loginAction();
                break;

            case 'processLogin':
                $this->secondaryController->processLoginAction();
                break;

//                Staff

            case 'processStaff':
                $this->secondaryController->processStaffAction();
                break;

            case 'createStaff':
                $this->processingController->createStaffAction();
                break;

            case 'editStaff':
                $id = filter_input(INPUT_GET, 'id');
                $this->processingController->editStaffAction($id);
                break;

            case 'deleteStaff':
                $id = filter_input(INPUT_GET, 'id');
                $this->processingController->deleteStaffAction($id);
                break;

            case 'staff':
                $this->processingController->displayStaffAction();
                break;

            case 'processStaffUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $userName = filter_input(INPUT_POST, 'userName');
                $password = filter_input(INPUT_POST, 'password1');
                $hash = password_hash(filter_input(INPUT_POST, 'password2'), PASSWORD_DEFAULT);
                $privilege = filter_input(INPUT_POST, 'privilege');

                    if(password_verify($password, $hash)){
                        $this->processingController->processStaffUpdateAction($id, $userName, $hash, $privilege);
                    }
                    else{
                     //do something
                    }
                break;

//                 Product

            case 'processProduct':
                $this->processingController->processProductAction();
                break;

            case 'addProduct':
                $this->mainController->addProductAction();
                break;

            case 'editProduct':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->editProductAction($id);
                break;

            case 'deleteProduct':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->deleteProductAction($id);
                break;

            case 'displayProduct':
                $this->mainController->displayProductAction();
                break;

            case 'processProductUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $name = filter_input(INPUT_POST, 'name');
                $price = filter_input(INPUT_POST, 'price');
                $description = filter_input(INPUT_POST, 'description');
                $image = filter_input(INPUT_POST, 'image');

                $this->mainController->processProductUpdateAction($id, $name, $price, $image, $description);
                break;

//                Account

            case 'processAccountForm':
                $this->processingController->processAccountFormAction();
                break;

            case 'deleteAccount':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->deleteAccountAction($id);
                break;

            case 'accounts':
                $this->mainController->accountsAction();
                break;

            case 'createAccount':
                $this->mainController->createAccountAction();
                break;

            case 'editAccount':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->editAccountAction($id);
                break;

            case 'processAccountUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $firstName = filter_input(INPUT_POST, 'firstName');
                $secondName = filter_input(INPUT_POST, 'secondName');
                $country = filter_input(INPUT_POST, 'country');
                $userName = filter_input(INPUT_POST, 'userName');
                $password = filter_input(INPUT_POST, 'password');

                $this->mainController->processAccountUpdateAction($id, $firstName, $secondName, $country, $userName, $password);
                break;

//                Database

            case 'setupDatabase':
                $this->processingController->setupDatabaseAction();
                break;

            case 'deleteDatabase':
                $this->processingController->deleteDatabaseAction();
                break;

            case 'gallery':
                $this->mainController->galleryAction();
                break;

            case 'index':
            default:
                $this->mainController->indexAction();
                break;
        }
    }
}
