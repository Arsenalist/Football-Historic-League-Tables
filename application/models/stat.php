<?php
require_once('team.php');

function standingsSort($a, $b) {
	$aPoints = $a->getPoints();
	$bPoints = $b->getPoints();
	$aGd = $a->getGoalDifference();
	$bGd = $b->getGoalDifference();
    $aName = $a->name;
    $bName = $b->name;

	if ($aPoints < $bPoints) {
		return 1;
	} else if ($aPoints > $bPoints) {
		return -1;
	} else {
		if ($aGd < $bGd) {
			return 1;
		} else if ($aGd > $bGd) {
			return -1;
		} else {
    		if ($aName < $bName) {
	    		return -1;
		    } else if ($aName > $bName) {
			    return 1;
    		} else {
                return 0;
            }
		}
	}

}

class Stat extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getsome($league, $tier, $year, $date) {

    	// get all the games played up until this date
    	$this->load->database();
       $suffixSql = " AND Season = '$league$tier$year'";
		$teams = $this->getTeams($suffixSql);
		$teamInfos = array();
		foreach ($teams as $t) {
			$team = new Team();
			//$wins = $this->getInt($this->db->query("SELECT count(*) as value from stats where ((HomeTeam = ? AND FTHG > FTAG) OR (AwayTeam = ? AND FTAG > FTHG))  " . $suffixSql, array($t, $t))->result());
			$wins = $this->getInt($this->db->query("SELECT count(*) as value from stats where ((HomeTeam = ? AND FTHG > FTAG) OR (AwayTeam = ? AND FTAG > FTHG)) AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d') " . $suffixSql, array($t, $t, $date))->result());
			//$wins = $this->getInt($this->db->query("SELECT count(*) as value from stats where ((HomeTeam = ? AND FTHG > FTAG) OR (AwayTeam = ? AND FTAG > FTHG)) AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d') " . $suffixSql, array($t, $t, $date))->result());
			$losses = $this->getInt($this->db->query("SELECT count(*) as value from stats where ((HomeTeam = ? AND FTHG < FTAG) OR (AwayTeam = ? AND FTAG < FTHG)) AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d')" . $suffixSql, array($t, $t, $date))->result());
			$draws = $this->getInt($this->db->query("SELECT count(*) as value from stats where ((HomeTeam = ? AND FTHG = FTAG) OR (AwayTeam = ? AND FTAG = FTHG)) AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d')" . $suffixSql , array($t, $t, $date))->result());
			$goalsFor = $this->getInt($this->db->query("SELECT (SELECT SUM(FTHG) FROM stats WHERE HomeTeam = ? AND  STR_TO_DATE(DATE, '%d/%m/%y') <= STR_TO_DATE(?, '%Y-%m-%d') $suffixSql)  +  (SELECT SUM(FTAG) FROM stats  WHERE AwayTeam = ? AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d') $suffixSql) as value", array($t, $date, $t, $date))->result());
			$goalsAgainst = $this->getInt($this->db->query("SELECT (SELECT SUM(FTAG) FROM stats WHERE HomeTeam = ? AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d') $suffixSql)  +  (SELECT SUM(FTHG) FROM stats WHERE AwayTeam = ? AND  STR_TO_DATE(DATE, '%d/%m/%Y') <= STR_TO_DATE(?, '%Y-%m-%d') $suffixSql) as value", array($t, $date, $t, $date))->result());
			$points = $wins * 3 + $draws;
			$team->name = $t;
			$team->wins = $wins;
			$team->losses = $losses;
			$team->draws = $draws;
			$team->goalsFor = $goalsFor;
			$team->goalsAgainst = $goalsAgainst;
			$teamInfos[] = $team;
		}
		usort($teamInfos, 'standingsSort');
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

    function getInt($result) {
		return intval($result[0]->value);
    }



}
?>
