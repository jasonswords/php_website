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
            $fileUpload = new FileUpload();

            $fileName = $fileUpload->uploadImage();

            $name = filter_input(INPUT_POST, 'name');
            $country = filter_input(INPUT_POST, 'country');
            $position = filter_input(INPUT_POST, 'position');

            $l = new League();
            $l->setName($name);
            $l->setCountry($country);
            $l->setDrone($fileName);
            $l->setPosition($position);

            $leagueRepository = new LeagueRepository();
            $leagueRepository->createTableLeague();
            $leagueRepository->insertLeagueMember($l);

             $id = $leagueRepository->getIdByName($name);

            if ($id == null) {
                header("Location: index.php?action=error");
                exit();
            } else {
                header("Location: index.php?action=displaySingleMember&id=<? $id >");
                exit();
            }
    }

    public function processLeagueUpdateAction(){

        $fileUpload = new FileUpload();

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name');
        $country = filter_input(INPUT_POST, 'country');
        $position = filter_input(INPUT_POST, 'position');
        $imageName = filter_input(INPUT_POST, 'imageName');

        if($fileUpload->fileWasUploaded()) {
            $fileName = $fileUpload->uploadImage();

            if ($fileName != ".") {
                $imageName = $fileName;
            }
        }

        $leagueRepo = new LeagueRepository();
        $leagueRepo->updateLeagueTable($id, $name, $country, $imageName, $position);
        header("Location: index.php?action=displaySingleMember&id=<? $id >");
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