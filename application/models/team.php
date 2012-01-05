<?php


function standingsSort2($a, $b) {
	$aPoints = $a->points;
	$bPoints = $b->points;
	$aGd = $a->gd;
	$bGd = $b->gd;
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



class ChartCreator {

    var $teams;

    function __construct($teams){
        $this->teams = $teams;
        usort($this->teams, 'standingsSort2');
    }

    function getChartData() {

        $dataValues = '';
        $largestPointTotal = 0;
        $lowestPointTotal = NULL;
        $mostGamesPlayed = 0;
        $teamNames = '';

        for ($i=0; $i<count($this->teams); $i++) {
            $t = $this->teams[$i];
            $teamInfo = $t->getChartData();
            $dataValues .= $teamInfo['data_string'];
            $teamNames .= $t->name;
            if ($i != count($this->teams) - 1) {
                $dataValues .= '|';
                $teamNames .= '|';
            }
            if ($teamInfo['points'] > $largestPointTotal) $largestPointTotal = $teamInfo['points'];
            if ($lowestPointTotal == NULL || $teamInfo['points'] <  $lowestPointTotal) $lowestPointTotal = $teamInfo['points'];
            if ($teamInfo['games_played'] > $mostGamesPlayed) $mostGamesPlayed = $teamInfo['games_played'];


        }
        return array(
            'largest_point_total' => $largestPointTotal,
            'lowest_point_total' => $lowestPointTotal,
            'most_games_played'   => $mostGamesPlayed,
            'data' => $dataValues,
            'team_names' => $teamNames

        );
    }
}

class Team {
	public function getPoints() {
		return $this->wins *3  + $this->draws;
	}
	public function getGoalDifference() {
		return $this->goalsFor  - $this->goalsAgainst;
	}
    public function getPlayed() {
        return $this->wins + $this->losses + $this->draws;    
    }

    private function charToPoint($c) {
        switch ($c) {
            case 'W': return 3;
            case 'L': return 0;
            case 'D': return 1;
        }
    }



    public function calculateMetrics() {
        // output 1...# of games played and calculate point total as well        
        $this->points=  0;
        $this->gd = 0;
        for ($i=0; $i<count($this->results); $i++) {
            $r = $this->results[$i];
            $this->points += $this->charToPoint($r->form);
            $this->gd += $r->gd;
        }
    }
    
    public function getChartData() {
        // output 1...# of games played and calculate point total as well        
        $x = '';
        $y = '';
        $pointTotal = 0;
        $index = 1;
        $x = -1;
        for ($i=0; $i<count($this->results); $i++) {
            $f = $this->results[$i];
            $pointTotal += $this->charToPoint($f->form);
            $y .= $pointTotal;
            if ($i != count($this->results) - 1) {
                $y .= ',';

            }       
        }
        return array('data_string' =>"$x|$y", 'points' => $pointTotal, 'games_played' => count($this->results));
    }
    
}


?>
