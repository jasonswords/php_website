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

            $fileName = $this->uploadImage();

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

        $fileName = $this->uploadImage();

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name');
        $country = filter_input(INPUT_POST, 'country');
        $position = filter_input(INPUT_POST, 'position');

        $leagueRepo = new LeagueRepository();
        $leagueRepo->createTableLeague();
        $leagueRepo->updateLeagueTable($id, $name, $country, $fileName, $position);
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

    public function uploadImage(){
        $storage = new \Upload\Storage\FileSystem(__DIR__ .'/../web/images');
        $file = new \Upload\File('upload', $storage);

        $file->addValidations(array(
            new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg')),
            new \Upload\Validation\Size('5M')
        ));
        try {
            $file->upload();
        } catch (\Exception $e) {

        }

        return $file->getNameWithExtension();
    }
}