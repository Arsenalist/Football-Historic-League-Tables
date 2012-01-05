<?php


class Splash extends CI_Controller {


	public function index() {
	    $content = $this->load->view('splash/main', array(), true);
        $this->load->view('layout/main', array('content' => $content));
	}




}
?>
