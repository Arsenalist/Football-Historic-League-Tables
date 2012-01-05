<?php


class Snakes extends CI_Controller {


	public function index() {
        $content = $this->load->view('snakes/main', array(), true);


       	$this->load->model('League');
	    $data = array(
            'leagues' => $this->League->getLeagues(),
        );
        $content = $this->load->view('snakes/main', $data, true);
        $this->load->view('layout/main', array('content' => $content));
	}


    public function image() {

       	$this->load->model('Snake');
	    $teams  = $this->Snake->getRaces($this->input->get('code'));
        $cc = new ChartCreator($teams);
        $data = $cc->getChartData();

         header('Content-Type: image/png');
          $url = 'https://chart.googleapis.com/chart?chid=' . md5(uniqid(rand(), true));
          $chd = 't:' . $data['data'];
          $chxr = '0,1,' . $data['most_games_played']. ',1|1,0,' . $data['largest_point_total'] . ',0';
          $chds = '1,' . $data['most_games_played']. ',0,' . $data['largest_point_total'];
          $chxp = '2,50|3,50';
          $chdl = $data['team_names'];
          $chxl = '2:|Round|3:|Points';

          $chco = $this->generateColorCodes(count($teams));

          // Add data, chart type, chart size, and scale to params.
          $chart = array(
            'cht' => 'lxy',
            'chs' => '600x400',
            'chxt' => 'x,y,x,y',
            'chxr' => $chxr,
            'chxp' => $chxp,
            'chxl' => $chxl,
            'chco' => $chco,
            'chds' => $chds,
            'chdl' => $chdl,
            'chd' => $chd
            );
          // Send the request, and print out the returned bytes.
          $context = stream_context_create(
            array('http' => array(
                                 'method' => 'POST',
                                 'content' => http_build_query($chart),
                                 'header' => "Content-Type: application/x-www-form-urlencoded\r\n" 
                            )
            )
          );
          $fp = fopen($url, 'r', false, $context);        
          fpassthru($fp);
          fclose($fp);
    }
    


    function generateColorCodes($numCodes) {
        $codes = '';
        for($i=0; $i<$numCodes; $i++) {
            $codes .= $this->randomColorCode();
            if ($i != $numCodes -1) $codes .= ',';
        }    
        return $codes;
    }


        function randomColorCode(){
            $r = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);
            $g = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);
            $b = str_pad(dechex(mt_rand(0,255)), 2, '0', STR_PAD_LEFT);
            $rgb = $r.$g.$b;
            return strtoupper($rgb);
        }



}
?>
