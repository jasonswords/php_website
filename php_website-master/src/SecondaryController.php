<?php


namespace Itb;


class SecondaryController
{
    private $twig;
    private $username;

    public function __construct($twig, $username)
    {
        $this->twig = $twig;
        $this->username = $username;
    }


    public function processLoginAction()
    {
        $userName = filter_input(INPUT_POST, 'userName');
        $password = filter_input(INPUT_POST, 'password');

        $staffRepository = new StaffRepository();
        $account = $staffRepository->getOneByUserName($userName);

        if (null == $account) {

            print 'User name does not exist in database';

        } else {

            if(password_verify($password, $account->getPassword())) {

                print 'Password and Username are valid';
                $_SESSION['username'] = $userName;

            } else {

                print 'The password or Username are not valid';

            }
            // --- based on outcome decide which VIEW to display
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
            if($privilege == 1){
                $s->setPrivilege(1);
            }
            else{
                $s->setPrivilege(0);
            }

            $staff->insertUser($s);
        }

        else{
            header("Location: index.php?action=createStaff");
            exit();
        }
    }



    public function loginAction(){

        $template = 'login.html.twig';
        $argsArray = [
            'pageTitle' => 'Login'
        ];

        $html = $this->twig->render($template, $argsArray);
        print $html;
    }
}