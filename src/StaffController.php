<?php


namespace Itb;


class StaffController
{
    private $twig;
    private $logger;

    public function __construct($twig, $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function displayStaffAction($s, $heading, $bool){

        if(empty($s)){
            $heading = 'No Staff Data Available';
        }

        $template = 'staff.html.twig';
        $argsArray = [
            'pageTitle' => 'Staff',
            'heading' => $heading,
            'link' => $bool,
            'staff'  => $s
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function displaySearchStaffAction($searchString){
        $staffRepository = new StaffRepository();
        $searchResults = $staffRepository->searchStaff($searchString);

            if($searchResults == null) {
                $this->displayStaffAction($searchResults, 'No Results Found', true);
            }else{
                $this->displayStaffAction($searchResults, 'Search Results', true);
            }
    }

    public function createStaffAction(){
        $template = 'createStaff.html.twig';
        $argsArray = [
            'pageTitle' => 'Create Staff'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function editStaffAction($id){
        $staff = new StaffRepository();
        $staffMember = $staff->getOneById($id);
        $template = 'editStaff.html.twig';
        $args = [
            'pageTitle' => 'Edit Staff',
            'staff' => $staffMember
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function deleteStaffAction($id){
        $staff = new StaffRepository();
        $this->logger->info($_SESSION['username'].' has deleted a staff member');
        $staff->deleteOneUser($id);
        header("Location: index.php?action=staff");
        exit();
    }

    public function processStaffUpdateAction(){
        $id = filter_input(INPUT_POST, 'id');
        $userName = filter_input(INPUT_POST, 'userName');
        $password = filter_input(INPUT_POST, 'password1');
        $hash = password_hash(filter_input(INPUT_POST, 'password2'), PASSWORD_DEFAULT);
        $privilege = filter_input(INPUT_POST, 'radioButton');
            if(null == $privilege){
                header("Location: index.php?action=staffPrivilegeError&id=$id");
                exit();
            }else{
                if(password_verify($password, $hash)){
                    $staffRepository = new StaffRepository();
                    $staffRepository->updateStaffTable($id, $userName, $hash, $privilege);
                    header("Location: index.php?action=staff");
                    exit();
                }
                else{
                    header("Location: index.php?action=staffError&id=$id");
                    exit();
                }
            }
    }

    public function staffErrorAction($id){
        $staffRepository = new StaffRepository();
        $staff = $staffRepository->getOneById($id);

        $template = 'editStaffError.html.twig';
        $argsArray = [
            'pageTitle' => 'Edit Staff',
            'staff' => $staff
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function staffPrivilegeErrorAction($id){
        $staffRepository = new StaffRepository();
        $staff = $staffRepository->getOneById($id);

        $template = 'editStaffPrivilegeError.html.twig';
        $argsArray = [
            'pageTitle' => 'Edit Staff',
            'staff' => $staff
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function processStaffAction(){
        $staff = new StaffRepository();
        $staff->createTableStaff();
        $userName =  filter_input(INPUT_POST, 'userName');
        $hashedPassword = password_hash(filter_input(INPUT_POST, 'password1'), PASSWORD_DEFAULT);
        $password = filter_input(INPUT_POST, 'password2');
        $privilege = filter_input(INPUT_POST, 'radioButton');

        if(null == $privilege) {
            header("Location: index.php?action=staffPrivilegeError");
            exit();
        }else {
            if (password_verify($password, $hashedPassword)) {
                $s = new Staff();
                $s->setUserName($userName);
                $s->setPassword($hashedPassword);
                $s->setPrivilege($privilege);
                $staff->insertUser($s);

                header("Location: index.php?action=staff");
                exit();
            } else {
                header("Location: index.php?action=staffError");
                exit();
            }
        }
    }
}