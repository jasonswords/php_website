<?php

namespace Itb;

class WebApplication
{
    private $mainController;
    private $loginController;
    private $staffController;
    private $leagueController;
    private $productController;
    private $visitorController;
    private $errorController;
    

    const PATH_TO_TEMPLATES = __DIR__ . '/../views';

    public function __construct()
    {
        $twig = new \Twig\Environment(new \Twig_Loader_Filesystem(self::PATH_TO_TEMPLATES));
        $this->mainController = new MainController($twig);
        $this->loginController = new LoginController($twig);
        $this->staffController = new StafController($twig);
        $this->leagueController = new LeagueController($twig);
        $this->productController = new ProductController($twig);
        $this->visitorController = new VisitorController($twig);
        $this->errorController = new ErrorController($twig);
        $twig->addGlobal('session', $_SESSION);
    }

    public function run()
    {
        $action = filter_input(INPUT_GET, 'action');

        switch($action) {

//              ----------------------  Login   ------------------------------------------------------------------------

            case 'login':
                $this->loginController->loginAction();
                break;

            case 'loginError':
                $this->loginController->loginErrorAction();
                break;

            case 'logOut':
                $this->loginController->deleteSession();
                break;

            case 'processLogin':
                $this->loginController->processLoginAction();
                break;

//              -----------------------  Staff   ----------------------------------------------------------------------

            case 'processStaff':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                    $this->staffController->processStaffAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'staffError':
                $id = filter_input(INPUT_GET, 'id');
                $this->staffController->staffErrorAction($id);
                break;

            case 'createStaff':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->staffController->createStaffAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'editStaff':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id');
                $this->staffController->editStaffAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'deleteStaff':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id');
                $this->staffController->deleteStaffAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'staff':
                $this->staffController->displayStaffAction();
                break;

            case 'processStaffUpdate':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->staffController->processStaffUpdateAction();
                }else{$this->mainController->indexAction();}
                break;

//                ---------------------  Product   --------------------------------------------------------------------

            case 'processProduct':
                if(isset($_SESSION['privilege']) == 'Administrator' || 'Standard Account') {
                $this->productController->processProductAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'addProduct':
                if(isset($_SESSION['privilege']) == 'Administrator' || 'Standard Account') {
                $this->productController->addProductAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'productError':
                $this->errorController->productErrorAction();
                break;

            case 'editProduct':
                if(isset($_SESSION['privilege'])== 'Administrator' || 'Standard Account') {
                $id = filter_input(INPUT_GET, 'id');
                $this->productController->editProductAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'deleteProduct':
                if(isset($_SESSION['privilege']) == 'Administrator' || 'Standard Account') {
                $id = filter_input(INPUT_GET, 'id');
                $this->productController->deleteProductAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'displayProduct':
                $this->productController->displayProductAction();
                break;

            case 'displaySingleProduct':
                $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_NUMBER_INT);
                $this->productController->displaySingleProductAction($id);
                break;

            case 'processProductUpdate':
                if(isset($_SESSION['privilege']) == 'Administrator' || 'Standard Account') {
                $id = filter_input(INPUT_POST, 'id');
                $name = filter_input(INPUT_POST, 'name');
                $description = filter_input(INPUT_POST, 'description');
                $image = filter_input(INPUT_POST, 'image');
                $price = filter_input(INPUT_POST, 'price');

                $this->productController->processProductUpdateAction($id, $name, $description, $image, $price);
                }else{$this->mainController->indexAction();}
                break;

//              --------------------------  Visitor  ------------------------------------------------------------------

            case 'processVisitorForm':
                $this->visitorController->processVisitorFormAction();
                break;

            case 'deleteVisitor':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id');
                $this->visitorController->deleteVisitorAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'visitor':
                $this->visitorController->visitorAction();
                break;

            case 'viewVisitor':
                $id = filter_input(INPUT_GET, 'id');
                $this->visitorController->viewVisitorAction($id);
                break;

            case 'createVisitor':
                $this->visitorController->createVisitorAction();
                break;

            case 'editVisitor':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id');
                $this->visitorController->editVisitorAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'processVisitorUpdate':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_POST, 'id');
                $firstName = filter_input(INPUT_POST, 'firstName');
                $secondName = filter_input(INPUT_POST, 'secondName');
                $country = filter_input(INPUT_POST, 'country');
                $email = filter_input(INPUT_POST, 'email');
                $this->visitorController->processVisitorUpdateAction($id, $firstName, $secondName, $country, $email);
                }else{$this->mainController->indexAction();}
                break;
//                ---------------------------  League Page  ----------------------------------------------------------

            case 'leaguePage':
                $this->leagueController->leaguePage();
                break;

            case 'editLeagueMember':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->leagueController->editLeagueMemberAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'deleteLeagueMember':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->leagueController->deleteLeagueMemberAction($id);
                }else{$this->mainController->indexAction();}
                break;

            case 'processLeagueUpdate':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $id = filter_input(INPUT_POST, 'id');
                $name = filter_input(INPUT_POST, 'name');
                $country = filter_input(INPUT_POST, 'country');
                $drone = filter_input(INPUT_POST, 'drone');
                $position = filter_input(INPUT_POST, 'position');
                $this->leagueController->processLeagueUpdateAction($id, $name, $country, $drone, $position);
                }else{$this->mainController->indexAction();}
                break;

            case 'addLeagueMember':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $name = filter_input(INPUT_POST, 'name');
                $country = filter_input(INPUT_POST, 'country');
                $drone = filter_input(INPUT_POST, 'drone');
                $position = filter_input(INPUT_POST, 'position');
                $this->leagueController->addLeagueMemberAction( $name, $country, $drone, $position);
                }else{$this->mainController->indexAction();}
                break;

            case 'addLeagueMemberPage':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->leagueController->addLeagueMemberPageAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'displaySingleMember':
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->leagueController->displaySingleMember($id);
                break;

//                ---------------------------  Database   -------------------------------------------------------------

            case 'setup':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->mainController->setupDatabaseAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'delete':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->mainController->deleteDatabaseAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'gallery':
                $this->mainController->galleryAction();
                break;

            case 'about':
                $this->mainController->aboutAction();
                break;

            case 'aboutConfirm':
                $this->mainController->aboutConfirmAction();
                break;

            case 'errorPage':
                $this->errorController->errorPageAction();
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
