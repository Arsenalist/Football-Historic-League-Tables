<?php
class LeagueSeason {


    function __construct($key) {        
        $this->league = substr($key, 0, 3);
        $this->season = substr($key, -4, 4);
        $this->tier  = substr($key, 3, 1);
    }

    function getSeasonRange() {
        return $this->season .  '-' .  ($this->season +1) ;    
    }    

    function getName() {
        return strtoupper($this->league);
    }
  
    
}
class League extends CI_Model {

    var $season;
    var $league;
    var $tier;

    function __construct() {
        parent::__construct();        
    }


    function getLeagues() {

        $this->load->database();
        $results = $this->db->query('select distinct Season from stats order by Season asc')->result();
        $leagues = array();
        foreach ($results as $r) {
            $l = new LeagueSeason($r->Season);
            $leagues[] = $l;
        }
        return $leagues;

    }



}
?>
