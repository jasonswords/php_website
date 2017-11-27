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
        $twig = new \Twig\Environment(new \Twig_Loader_Filesystem(self::PATH_TO_TEMPLATES));
        $this->mainController = new MainController($twig);
        $this->secondaryController = new SecondaryController($twig);
        $this->processingController = new processingController($twig);
        $twig->addGlobal('session', $_SESSION);
    }

    public function run()
    {
        $action = filter_input(INPUT_GET, 'action');

        switch($action) {

//              ----------------------  Login   ------------------------------------------------------------------------

            case 'login':
                $this->secondaryController->loginAction();
                break;

            case 'logOut':
                $this->processingController->deleteSession();
                break;

            case 'processLogin':
                $this->secondaryController->processLoginAction();
                break;

//              -----------------------  Staff   ----------------------------------------------------------------------

            case 'processStaff':
                $this->secondaryController->processStaffAction();
                break;

            case 'staffError':
                $this->secondaryController->staffErrorAction();
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
                        header("Location: index.php?action=error1");
                        exit();
                    }
                break;

//                ---------------------  Product   --------------------------------------------------------------------

            case 'processProduct':
                $this->processingController->processProductAction();
                break;

            case 'addProduct':
                $this->mainController->addProductAction();
                break;

            case 'productError':
                $this->mainController->productErrorAction();
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

            case 'displaySingleProduct':
                $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_NUMBER_INT);
                $this->mainController->displaySingleProductAction($id);
                break;

            case 'processProductUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $name = filter_input(INPUT_POST, 'name');
                $description = filter_input(INPUT_POST, 'description');
                $image = filter_input(INPUT_POST, 'image');
                $price = filter_input(INPUT_POST, 'price');

                $this->mainController->processProductUpdateAction($id, $name, $description, $image, $price);
                break;

//              --------------------------  Visitor  ------------------------------------------------------------------

            case 'processVisitorForm':
                $this->processingController->processVisitorFormAction();
                break;

            case 'deleteVisitor':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->deleteVisitorAction($id);
                break;

            case 'visitor':
                $this->mainController->visitorAction();
                break;

            case 'viewVisitor':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->viewVisitorAction($id);
                break;

            case 'createVisitor':
                $this->mainController->createVisitorAction();
                break;

            case 'editVisitor':
                $id = filter_input(INPUT_GET, 'id');
                $this->mainController->editVisitorAction($id);
                break;

            case 'processVisitorUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $firstName = filter_input(INPUT_POST, 'firstName');
                $secondName = filter_input(INPUT_POST, 'secondName');
                $country = filter_input(INPUT_POST, 'country');
                $email = filter_input(INPUT_POST, 'email');
                $this->mainController->processVisitorUpdateAction($id, $firstName, $secondName, $country, $email);
                break;
//                ---------------------------  League Page  ----------------------------------------------------------

            case 'leaguePage':
                $this->secondaryController->leaguePage();
                break;

            case 'editLeagueMember':
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->secondaryController->editLeagueMemberAction($id);
                break;

            case 'deleteLeagueMember':
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->secondaryController->deleteLeagueMemberAction($id);
                break;

            case 'processLeagueUpdate':
                $id = filter_input(INPUT_POST, 'id');
                $name = filter_input(INPUT_POST, 'name');
                $country = filter_input(INPUT_POST, 'country');
                $drone = filter_input(INPUT_POST, 'drone');
                $position = filter_input(INPUT_POST, 'position');
                $this->secondaryController->processLeagueUpdateAction($id, $name, $country, $drone, $position);
                break;

            case 'addLeagueMember':
                $name = filter_input(INPUT_POST, 'name');
                $country = filter_input(INPUT_POST, 'country');
                $drone = filter_input(INPUT_POST, 'drone');
                $position = filter_input(INPUT_POST, 'position');
                $this->secondaryController->addLeagueMemberAction( $name, $country, $drone, $position);
                break;

            case 'displaySingleMember':
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->secondaryController->displaySingleMember($id);
                break;

//                ---------------------------  Database   -------------------------------------------------------------

            case 'setup':
                $this->processingController->setupDatabaseAction();
                break;

            case 'delete':
                $this->processingController->deleteDatabaseAction();
                break;

            case 'gallery':
                $this->mainController->galleryAction();
                break;

            case 'about':
                $this->mainController->aboutAction();
                break;

            case 'siteMap':
                $this->mainController->siteMapAction();
                break;

            case 'index':
            default:
                $this->mainController->indexAction();
                break;
        }
    }
}
