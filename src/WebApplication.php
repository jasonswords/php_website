<?php

namespace Itb;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class WebApplication
{
    private $mainController;
    private $loginController;
    private $staffController;
    private $leagueController;
    private $productController;
    private $visitorController;

    const PATH_TO_TEMPLATES = __DIR__ . '/../views';

    public function __construct()
    {
        $logger = new Logger('PHP Website');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logger/log.txt', Logger::DEBUG));
        $twig = new \Twig\Environment(new \Twig_Loader_Filesystem(self::PATH_TO_TEMPLATES));
        $this->mainController = new MainController($twig,$logger);
        $this->loginController = new LoginController($twig,$logger);
        $this->staffController = new StaffController($twig,$logger);
        $this->leagueController = new LeagueController($twig,$logger);
        $this->productController = new ProductController($twig,$logger);
        $this->visitorController = new VisitorController($twig,$logger);
        $twig->addGlobal('session', $_SESSION);
    }

    public function run()
    {
        $action = filter_input(INPUT_GET, 'action');
        if(empty($action)) {
            $action = filter_input(INPUT_POST, 'action');
        }
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

            case 'staffPrivilegeError':
                $id = filter_input(INPUT_GET, 'id');
                $this->staffController->staffPrivilegeErrorAction($id);
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
                $staff = new StaffRepository();
                $staffMember = $staff->getAllStaff();
                $this->staffController->displayStaffAction($staffMember, 'Staff Table', null);
                break;

            case 'searchStaff':
                $searchString = filter_input(INPUT_POST,'search');
                $this->staffController->displaySearchStaffAction($searchString);
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

            case 'editProduct':
                if(isset($_SESSION['privilege'])== 'Administrator' || 'Standard Account') {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
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
                $products = new ProductRepository();
                $p = $products->getAllProducts();
                $this->productController->displayProductAction($p,'Products Available', null );
                break;

            case 'displaySingleProduct':
                $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_NUMBER_INT);
                $this->productController->displaySingleProductAction($id);
                break;

            case 'searchProducts':
                $searchString = filter_input(INPUT_POST,'search');
                $this->productController->displaySearchProductAction($searchString);
                break;

            case 'processProductUpdate':
                if(isset($_SESSION['privilege']) == 'Administrator' || 'Standard Account') {
                $this->productController->processProductUpdateAction();
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
                $id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_NUMBER_INT);
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
                $this->visitorController->processVisitorUpdateAction();
                }else{$this->mainController->indexAction();}
                break;
//                ---------------------------  League Page  ----------------------------------------------------------

            case 'leaguePage':
                $leagueRepo = new LeagueRepository();
                $leagueMember = $leagueRepo->getAllLeague();
                $this->leagueController->leaguePage($leagueMember, 'Drone Leader Board', null);
                break;

            case 'searchLeague':
                $searchString = filter_input(INPUT_POST,'search');
                $this->leagueController->displaySearchLeagueAction($searchString);
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
                $this->leagueController->processLeagueUpdateAction();
                }else{$this->mainController->indexAction();}
                break;

            case 'addLeagueMember':
                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->leagueController->addLeagueMemberAction();
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
//                if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
                $this->mainController->setupDatabaseAction();
//                }else{$this->mainController->indexAction();}
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
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $this->mainController->aboutConfirmAction($id);
                break;

            case 'error':
                $this->mainController->errorAction();
                break;

            case 'index':
            default:
                $this->mainController->indexAction();
                break;
        }
    }
}
