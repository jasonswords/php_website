<?php

namespace Itb;

class LeagueController{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function leaguePage($l, $heading, $link){

        if(empty($l)){
            $heading = 'No League Data Available';
        }
        $template = 'league.html.twig';
        $args = [
            'pageTitle' => 'League Results',
            'heading' => $heading,
            'link' => $link,
            'leagueMember' => $l
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function displaySearchLeagueAction($searchString){
        $leagueRepository = new LeagueRepository();
        $leagueMembers = $leagueRepository->searchLeague($searchString);
        if($leagueMembers == null){
            $this->leaguePage($leagueMembers, 'No Results Found', true);
        }else {
            $this->leaguePage($leagueMembers, 'Search Results', true);
        }
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

        if($fileUpload->fileWasUploaded()) {
            $imageName = $fileUpload->uploadImage();
        }
        else{
            $imageName = null;
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