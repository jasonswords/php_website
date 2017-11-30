<?php


namespace Itb;


class SecondaryController
{
    private $twig;
    private $username;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function processLoginAction()
    {
        $userName = filter_input(INPUT_POST, 'userName');
        $password = filter_input(INPUT_POST, 'password');

        $staffRepository = new StaffRepository();
        $account = $staffRepository->getOneByUserName($userName);

        if (null == $account)
        {
            header("Location: index.php?action=loginError");
            exit();
        } else {
            if(password_verify($password, $account->getPassword()))
            {
                if(1 == $account->getPrivilege()){
                    $privilege = 'Administrator';
                }
                else{
                    $privilege = 'Standard Account';
                }
                $_SESSION['username'] = $userName;
                $_SESSION['privilege'] = $privilege;

                header("Location: index.php");
                exit();
            } else {
                header("Location: index.php?action=loginError");
                exit();
            }
        }
    }

    public function processStaffAction(){
        $staff = new StaffRepository();
        $staff->createTableStaff();
        $userName =  filter_input(INPUT_POST, 'userName');
        $hashedPassword = password_hash(filter_input(INPUT_POST, 'password1'), PASSWORD_DEFAULT);
        $password = filter_input(INPUT_POST, 'password2');
        $privilege = filter_input(INPUT_POST, 'radioButton');
        if(password_verify($password, $hashedPassword)){
            $s = new Staff();
            $s->setUserName($userName);
            $s->setPassword($hashedPassword);
            $s->setPrivilege($privilege);
            $staff->insertUser($s);

            header("Location: index.php?action=staff");
            exit();
        }
        else{
            header("Location: index.php?action=staffError");
            exit();
        }
    }

    public function leaguePage(){
        $leagueRepo = new LeagueRepository();
        $leagueMember = $leagueRepo->getAllLeague();
        $template = 'league.html.twig';
        $args = [
            'pageTitle' => 'League Results',
            'leagueMember' => $leagueMember
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function editLeagueMemberAction($id){
        $leagueRepository = new LeagueRepository();
        $leagueMember = $leagueRepository->getOneById($id);
        $template = 'editLeague.html.twig';
        $args = [
            'pageTitle' => 'Edit League',
            'league' => $leagueMember
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function addLeagueMemberPageAction(){

        $template = 'addLeagueMember.html.twig';
        $args = [
            'pageTitle' => 'Edit League',
        ];
        $html = $this->twig->render($template, $args);
        print $html;

    }

    public function addLeagueMemberAction($name, $country, $drone, $position){
        $leagueRepository = new LeagueRepository();
        $leagueRepository->createTableLeague();
        $l = new League();
        $l->setName($name);
        $l->setCountry($country);
        $l->setDrone($drone);
        $l->setPosition($position);
        $leagueRepository->insertLeagueMember($l);

        header("Location: index.php?action=leaguePage");
        exit();

    }

    public function processLeagueUpdateAction($id, $name, $country, $drone, $position){
        $leagueRepo = new LeagueRepository();
        $leagueRepo->createTableLeague();
        $leagueRepo->updateLeagueTable($id, $name, $country, $drone, $position);
        header("Location: index.php?action=leaguePage");
        exit();
    }

    public function deleteLeagueMemberAction($id){
        $leagueRepo = new LeagueRepository();
        $leagueRepo->deleteOneLeagueMember($id);
        header("Location: index.php?action=leaguePage");
        exit();
    }

    public function displaySingleMember($id){
        $leagueRepository = new LeagueRepository();
        $member = $leagueRepository->getOneById($id);
        $template = 'singleLeagueMember.html.twig';
        $args = [
          'pageTitle' => 'League Member',
          'member'   =>   $member
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function loginAction(){
        $template = 'login.html.twig';
        $argsArray = [
            'pageTitle' => 'Login'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function loginErrorAction(){

        $template = 'loginError.html.twig';
        $args = [
          'pageTitle' => 'Login Error'
        ];

        $html = $this->twig->render($template, $args);
        print $html;
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

}