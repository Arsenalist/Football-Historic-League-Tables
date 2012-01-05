<?php


class Table extends CI_Controller {


	public function index() {
		$this->load->model('Stat');
		$this->load->model('League');

        $league = $this->input->get('league') == NULL ? 'eng' : $this->input->get('league');
        $tier = $this->input->get('tier') == NULL ? '1' : $this->input->get('tier');
        $season = $this->input->get('season') == NULL ? '2011' : $this->input->get('season');
        

        if ($this->input->get('date') == NULL) {
            $date = date('Y-m-d');    
        } else {
            $date = $this->input->get('date');    
        }


	    $what = $this->Stat->getsome($league, $tier, $season, $date);
	    $data = array(
	    	'teams' => $what,
            'date' => $date,
            'leagues' => $this->League->getLeagues(),
            'league' => $league,
            'tier' => $tier,
            'season' => $season
	    );
	    $content = $this->load->view('table/main', $data, true);
        $this->load->view('layout/main', array('content' => $content));
	}




}
?>
