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

}