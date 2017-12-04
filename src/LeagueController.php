<?php

namespace Itb;

class LeagueController{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
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

    public function addLeagueMemberAction(){
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $imageName = basename($target_file);

        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            header("Location: index.php?action=errorPage");
            exit();
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                header("Location: index.php?action=errorPage");
                exit();
            }
        }
        $name = filter_input(INPUT_POST, 'name');
        $country = filter_input(INPUT_POST, 'country');
        $position = filter_input(INPUT_POST, 'position');


        $l = new League();
        $l->setName($name);
        $l->setCountry($country);
        $l->setDrone($imageName);
        $l->setPosition($position);
        $leagueRepository = new LeagueRepository();
        $leagueRepository->createTableLeague();
        $leagueRepository->insertLeagueMember($l);

        $id = $leagueRepository->getIdByName($name);

        if($id == null){
            header("Location: index.php?action=productError");
            exit();
        }
        else{
            header("Location: index.php?action=displaySingleMember&id=<? $id >");
            exit();
        }
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
}