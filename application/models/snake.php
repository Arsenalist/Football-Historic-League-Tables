<?php
require_once('team.php');

class Snake extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getRaces($code) {
    	// get all the games played up until this date
    	$this->load->database();
       $suffixSql = " AND Season = '$code'";
		$teams = $this->getTeams($suffixSql);
		$teamInfos = array();

        $data = array();

		foreach ($teams as $t) {
			$team = new Team();
			$results = $this->db->query("select CASE WHEN ((HomeTeam = ? AND FTHG > FTAG) || (AwayTeam = ? AND FTAG > FTHG)) THEN 'W' WHEN ((HomeTeam = ?  AND FTHG < FTAG) || (AwayTeam = ? AND FTAG < FTHG))  THEN 'L' ELSE 'D' END as form, CASE WHEN HomeTeam = ? THEN FTHG - FTAG  ELSE FTAG - FTHG END as gd  from stats where Season = ?  and (HomeTeam = ? OR AwayTeam = ?) ORDER BY STR_TO_DATE(Date, '%d/%m/%Y')  ASC", array($t, $t, $t, $t, $t, $code, $t, $t))->result();
			$team->results = $results;
            $team->calculateMetrics();
			$team->name = $t;
			$teamInfos[] = $team;

		}
		return $teamInfos;
    }




    function getTeams($suffixSql) {
		$homeTeams =  $this->db->distinct()->query("select HomeTeam as team from stats where HomeTeam <> ''  $suffixSql")->result();
		$roadTeams =  $this->db->distinct()->query("select AwayTeam as team from stats where AwayTeam <> ''  $suffixSql")->result();
		$allTeams = (array_merge($homeTeams, $roadTeams));
		$teams = array();
		foreach ($allTeams as $t) {
			$teams[] = $t->team;
		}
		return array_unique($teams);
    }

}
?>
