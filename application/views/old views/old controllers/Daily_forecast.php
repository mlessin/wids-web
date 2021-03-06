<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Daily_forecast extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
		$this->config->set_item('theme',$this->config->item('country'));
        $this->load->model('Daily_forecast_model');
		$this->load->model('Region_model');
        $this->load->library('form_validation');
        $this->load->model('Impacts_model');
         $this->load->library('session');
		$this->load->model('Division_model');//Season_names_model
		$this->load->model('Season_names_model');
    }

//index function 

    public function index()
    {
        $daily_forecast = $this->Daily_forecast_model->get_all();
		//$country = $this->config->item('country');
		//$data['division_type']= $this->Division_model->getdivisionname($country);  
        $data = array(
            'daily_forecast_data' => $daily_forecast,
            'change' => 3
        );
        $this->load->view('template', $data);
    }

      public function addnew()
    {
        $data['daily_forecast_data']= $this->Daily_forecast_model->get_all_replaced();
		$data['division_data']= $this->Division_model->get_all();//season_data season_data
		$data['season_names']= $this->Season_names_model->get_all();
		$country = $this->config->item('country');
		$data['division_type']= $this->Division_model->getdivisionname($country);
		$data['region'] = $this->Region_model->get_all();  
		
		$data['change'] = 36;
        $this->load->view('template', $data);
    }
    

public function create2(){
	$daily_forecast = $this->Daily_forecast_model->get_all_region();
	$data['change'] = 3;	
	$this->load->view('template', $data);
}

public function create()
    {
        $data = array(

        'button' => 'Create',
            'action' => site_url('index.php/daily_forecast/create_action'),
            'id' => set_value('id'),
            'mean_temp1' => set_value('mean_temp1'),
            'mean_temp2' => set_value('mean_temp2'),
            'mean_temp3' => set_value('mean_temp3'),
            'mean_temp4' => set_value('mean_temp4'),

            'wind_direction1' => set_value('wind_direction1'),
            'wind_direction2' => set_value('wind_direction2'),
            'wind_direction3' => set_value('wind_direction3'),
            'wind_direction4' => set_value('wind_direction4'),

            'wind_strength1' => set_value('wind_strength1'),
            'wind_strength2' => set_value('wind_strength2'),
            'wind_strength3' => set_value('wind_strength3'),
            'wind_strength4' => set_value('wind_strength4'),

            'weather1' => set_value('weather1'),
            'weather2' => set_value('weather2'),
            'weather3' => set_value('weather3'),
            'weather4' => set_value('weather4'),

            'date1' => set_value('date1'),
            'time'     => set_value('time'),
            'season_id' => set_value('season_id'),
            'cat_id' => set_value('cat_id'),
            'change'  => 1,
	);
        $this->load->view('template', $data);
}
 

  public function create_single(){    
	
	$data = array(
        'mean_temp' => $this->input->post('mean_temp',TRUE),		
        'wind_direction' => $this->input->post('wind_direction',TRUE),
        'wind_strength' => $this->input->post('wind_strength',TRUE),
         'lang'=>$this->input->post('lang',TRUE),
		'weather' => $this->input->post('weather',TRUE),
		'advisory' => $this->input->post('advisory',TRUE),
		'date' => $this->input->post('date',TRUE),
		'time' => $this->input->post('time',TRUE),		
        //'region' => $this->input->post('region',TRUE),
		'season_id' => $this->input->post('season_id',TRUE),
		'cat_id' => $this->input->post('cat_id',TRUE),
	    );
    $data = array(
            'button' => 'Create single',
            'action' => site_url('index.php/daily_forecast/create_single_action'),
        'id' => set_value('id'),       
        'change'  => 36
    );
    $this->load->view('template', $data);
  }

 public function create_action()
    {
		$this->_rules();

        if ($this->form_validation->run() == FALSE) {
          
            $this->create();
        } else {
            $data = array(
        'mean_temp' => $this->input->post('mean_temp',TRUE),
		
        'wind_direction' => $this->input->post('wind_direction',TRUE),
        'wind_strength' => $this->input->post('wind_strength',TRUE),
         'lang'=>$this->input->post('lang',TRUE),
		'weather' => $this->input->post('weather',TRUE),
		'advisory' => $this->input->post('advisory',TRUE),
		'date' => $this->input->post('date',TRUE),
		'time' => $this->input->post('time',TRUE),
		'region' => $this->input->post('region_id',TRUE),
        //'region' => $this->input->post('region',TRUE),
		'season_id' => $this->input->post('season_id',TRUE),
		'cat_id' => $this->input->post('cat_id',TRUE),
	    );

            $this->Daily_forecast_model->insert($data);
			$data = array(
			   'change' => 3,
			);
            $this->session->set_flashdata('message', '<font color="green" size="5">Create Record Success</font>');
             $this->load->view('template',$data);
        }
    }
	
//show area forecast 
function daily_forecast_data(){
  $id = $this->uri->segment(3);
  $data['daily_forecast_data'] = $this->Daily_forecast_model->get_daily_forecast_data($id);
  $country = $this->config->item('country');
  $data['division_type']= $this->Division_model->getdivisionname($country);  
  $data['forecast_id'] = $id;
  $data['change'] = 78; 
  $this->load->view('template', $data);	
	
}
//show area forecast impacts
function daily_impacts_data(){
    $id = $this->uri->segment(3);
    $data['daily_impacts_data'] = $this->Daily_forecast_model->get_daily_impacts_data($id);
   // $country = $this->config->item('country');
    //$data['division_type']= $this->Division_model->getdivisionname($country);  
    $data['forecast_id'] = $id;
    $data['change'] = 80; 
    $this->load->view('template', $data);	
      
  }
//add new forecast_impact
  function addforecastimpactdata(){

    $data['forecast_id'] = $this->uri->segment(3);	
    $data['change'] = 81; 
    $data['impacts_data'] = $this->Impacts_model->get_all();
   // print_r($data); exit();
    $this->load->view('template', $data);
      
  }

function addnewforecastdata(){

  $data['forecast_id'] = $this->uri->segment(3);	
  $data['change'] = 79; 
  $country = $this->config->item('country');
   $data['division_type']= $this->Division_model->getdivisionname($country); 
   $data['division_type']= $this->Division_model->getdivisionname($country);
  $data['region'] = $this->Region_model->get_all(); 
  $data['division_data']= $this->Division_model->get_all();
  $this->load->view('template', $data);
	
}
// save forecast-impact data
function saveforecastimpactdata(){
	$id = $this->input->post('forecast_id',TRUE);
	$data = array(
				'type' => $this->input->post('type',TRUE),
				'impact_id' => $this->input->post('impact_id',TRUE),
				'forecast_id' => $this->input->post('forecast_id',TRUE)
				);	
		    $this->Daily_forecast_model->insertforecastimpactdata($data);
            $data['daily_impacts_data'] = $this->Daily_forecast_model->get_daily_impacts_data($id);
            // $country = $this->config->item('country');
             //$data['division_type']= $this->Division_model->getdivisionname($country);  
             $data['forecast_id'] = $id;
             $data['change'] = 80; 
             $this->load->view('template', $data);   
	
}
function saveforecastdata(){
	$id = $this->input->post('forecast_id',TRUE);
	$data = array(
				'mean_temp' => $this->input->post('mean_temp',TRUE),
				'max_temp' => $this->input->post('max_temp',TRUE),
				'min_temp' => $this->input->post('min_temp',TRUE),
				'wind' => $this->input->post('wind',TRUE),				
				'wind_direction' => $this->input->post('wind_direction',TRUE),
				'wind_strength' => $this->input->post('wind_strength',TRUE),
				'region_id' => $this->input->post('region',TRUE),
				'division_id' => $this->input->post('division',TRUE),
				'forecast_id' => $this->input->post('forecast_id'),
				'weather_cat_id' => $this->input->post('cat_id',TRUE)
				);	
		    $this->Daily_forecast_model->insertforecastdata($data);
		     $data['daily_forecast_data'] = $this->Daily_forecast_model->get_daily_forecast_data($id);
			$data['change'] = 78;
           	$country = $this->config->item('country');
		    $data['division_type']= $this->Division_model->getdivisionname($country);  
            $this->load->view('template',$data);     
	
}


//save daily 
  public function save(){
      //--------date-----------------------------------
      $date = strtotime($this->input->post('date_forecasted',TRUE));
    $date_time = date('Y',$date).'-'.date('m',$date).'-'.date('d',$date);
    $issuedate = strtotime($this->input->post('issuedate',TRUE));
    $date_issue = date('Y',$issuedate).'-'.date('m',$issuedate).'-'.date('d',$issuedate);
      //-------end of date format----------------
         $data = array(
				'weather' => $this->input->post('weather',TRUE),
				'date' => $date_time,
				'time' => $this->input->post('time',TRUE),
				'dutyforecaster' => $this->input->post('dutyforecaster',TRUE),
				'validitytime' => $this->input->post('validitytime',TRUE),
				'issuedate' => $date_issue				
				);
		
            $this->Daily_forecast_model->insert($data);
		
			$data['change'] = 3;
            $data['daily_forecast_data'] = $this->Daily_forecast_model->get_all();
			//$country = $this->config->item('country');
		  //  $data['division_type']= $this->Division_model->getdivisionname($country);  
            $this->load->view('template',$data);
       
    }
	
	
//single form create action
    public function save_multiple(){
        $this->_rules_single();

        if ($this->form_validation->run() == FALSE) {          
            $this->addnew(); 
        } else {
			
		for($i=0;$i<4;$i++){
			
			//print_r($_POST); exit();
         $data = array(
				'mean_temp' => $this->input->post('mean_temp'.($i+1),TRUE),
				'max_temp' => $this->input->post('max_temp'.($i+1),TRUE),
				'min_temp' => $this->input->post('min_temp'.($i+1),TRUE),				
				'wind_direction' => $this->input->post('wind_direction'.($i+1),TRUE),
				'wind_strength' => $this->input->post('wind_strength'.($i+1),TRUE),
				'weather' => $this->input->post('cat_id'.($i+1),TRUE),
				'date' => $this->input->post('date'.($i+1),TRUE),
				'time' => $this->input->post('time'.($i+1),TRUE),
				'division_id' => $this->input->post('division',TRUE),
				'cat_id' => $this->input->post('cat_id'.($i+1),TRUE)
				);
		
            $this->Daily_forecast_model->insert($data);
		}
			$data = array(
			   'change' => 3
			);
            $data['daily_forecast'] = $this->Daily_forecast_model->get_all_region();
            $this->load->view('template',$data);
        }

    }

    public function update($id)
    {    $id = $this->uri->segment(3);
        $row = $this->Daily_forecast_model->get_by_id($id);
        
        if ($row) {
            
            $english= $row->weather;
            if($english!=NULL){
            $data = array(
                'button' => 'Update',
                'action' => site_url('index.php/daily_forecast/update_action'),
                'id' => set_value('id', $row->id),
                'mean_temp' => set_value('mean_temp', $row->mean_temp),
                'wind_direction' => set_value('wind_direction', $row->wind_direction),
                'wind_strength' => set_value('wind_strength', $row->wind_strength),
                'weather' => set_value('weather', $row->weather),
                'advisory' => set_value('advisory', $row->advisory),
                'date' => set_value('date', $row->date),
                'time' => set_value('time', $row->time),
                'season_id' => set_value('season_id', $row->season_id),
                'region_id' => set_value('region_id', $row->region),
                //'sub_region' => set_value('subregionid', $row->subregionid),
                'cat_id' => set_value('cat_id', $row->cat_id),
                'change'  => 1
	    );

            $this->load->view('template', $data);
        }
        } 
    }
    public function update_action()
    {
        $this->_rules_single();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
        'mean_temp' => $this->input->post('mean_temp',TRUE),
        'wind_direction' => $this->input->post('wind_direction',TRUE),
        'wind_strength' => $this->input->post('wind_strength',TRUE),
        'lang' => $this->input->post('lang',TRUE),
		'weather' => $this->input->post('weather',TRUE),
		'advisory' => $this->input->post('advisory',TRUE),
		'date' => $this->input->post('date',TRUE),
		'time' => $this->input->post('time',TRUE),
		'region' => $this->input->post('region_id',TRUE),
        //'regionid' => $this->input->post('region',TRUE),
		'season_id' => $this->input->post('season_id',TRUE),
		'cat_id' => $this->input->post('cat_id',TRUE),
	    );

            $this->Daily_forecast_model->update($this->input->post('id', TRUE), $data);
			$data = array(
			  'change' => 3,
			);
            $this->session->set_flashdata('message', '<font color="green" size="5">Update Record Success</font>');
            $this->load->view('template', $data);
        }
    }

    public function delete()
    {   $id = $this->uri->segment(3);
        $data['change'] =3;
            $this->Daily_forecast_model->delete($id);
            $this->session->set_flashdata('message', '<font color="green" size="5">Deleted Record Success</font>');
            $this->load->view('template', $data);

    }

    public function _rules()
    {
        $this->form_validation->set_rules('mean_temp1', 'mean temp(Late Evening)', 'trim|numeric|required');
        $this->form_validation->set_rules('mean_temp2', 'mean temp(Early Morning)', 'trim|numeric|required');
        $this->form_validation->set_rules('mean_temp3', 'mean temp(Late Morning)', 'trim|numeric|required');
        $this->form_validation->set_rules('mean_temp4', 'mean temp(Aternoon)', 'trim|numeric|required');

        $this->form_validation->set_rules('wind_direction1', 'wind strength(Late Evening)', 'trim|required');
        $this->form_validation->set_rules('wind_direction2', 'wind strength(Early Morning)', 'trim|required');
        $this->form_validation->set_rules('wind_direction3', 'wind strength(Late Morning)', 'trim|required');
        $this->form_validation->set_rules('wind_direction4', 'wind strength(Afternoon)', 'trim|required');

        $this->form_validation->set_rules('wind_strength1', 'wind strength(Late Evening)', 'trim|required');
        $this->form_validation->set_rules('wind_strength2', 'wind strength(Early Morning)', 'trim|required');
        $this->form_validation->set_rules('wind_strength3', 'wind strength(Late Morning)', 'trim|required');
        $this->form_validation->set_rules('wind_strength4', 'wind strength(Afternoon)', 'trim|required');

        $this->form_validation->set_rules('weather', 'weather(Late Evening)', 'trim|required');
        $this->form_validation->set_rules('date1', 'Date(Late Evening)', 'trim|required');
        $this->form_validation->set_rules('date2', 'Date(Other)', 'trim|required');
      	$this->form_validation->set_rules('season_id', 'season id', 'trim|required');
      	$this->form_validation->set_rules('id', 'id', 'trim');
      	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function _rules_single()
    {
        $this->form_validation->set_rules('mean_temp1', 'mean temp', 'trim|numeric|required');
		$this->form_validation->set_rules('mean_temp2', 'mean temp', 'trim|numeric|required');
		$this->form_validation->set_rules('mean_temp3', 'mean temp', 'trim|numeric|required');
		$this->form_validation->set_rules('mean_temp4', 'mean temp', 'trim|numeric|required');
        $this->form_validation->set_rules('wind_direction1', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_direction2', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_direction3', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_direction4', 'wind strength', 'trim|required');
        $this->form_validation->set_rules('wind_strength1', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_strength2', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_strength3', 'wind strength', 'trim|required');
		$this->form_validation->set_rules('wind_strength4', 'wind strength', 'trim|required');
        $this->form_validation->set_rules('weather', 'weather', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "daily_forecast.xls";
        $judul = "daily_forecast";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
		xlsWriteLabel($tablehead, $kolomhead++, "Max Temp");
		xlsWriteLabel($tablehead, $kolomhead++, "Min Temp");
		xlsWriteLabel($tablehead, $kolomhead++, "Sunrise");
		xlsWriteLabel($tablehead, $kolomhead++, "Sunset");
		xlsWriteLabel($tablehead, $kolomhead++, "Wind");
		xlsWriteLabel($tablehead, $kolomhead++, "Weather");
		xlsWriteLabel($tablehead, $kolomhead++, "Advisory");
		xlsWriteLabel($tablehead, $kolomhead++, "date");

	foreach ($this->Daily_forecast_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteNumber($tablebody, $kolombody++, $data->max_temp);
	    xlsWriteNumber($tablebody, $kolombody++, $data->min_temp);
	    xlsWriteLabel($tablebody, $kolombody++, $data->sunrise);
	    xlsWriteLabel($tablebody, $kolombody++, $data->sunset);
	    xlsWriteNumber($tablebody, $kolombody++, $data->wind);
	    xlsWriteLabel($tablebody, $kolombody++, $data->weather);
	    xlsWriteLabel($tablebody, $kolombody++, $data->advisory);
	    xlsWriteLabel($tablebody, $kolombody++, $data->date);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=daily_forecast.doc");

        $data = array(
            'daily_forecast_data' => $this->Daily_forecast_model->get_all(),
            'start' => 0
        );

        $this->load->view('daily_forecast_doc',$data);
    }

    public function pdf()
    {
        $data = array(
            'daily_forecast_data' => $this->Daily_forecast_model->get_all(),
            'start' => 0
        );
        
        $this->pdf_download($data);
    }


    public function pdf_archive()
    {
        $data = array(
            'daily_forecast_data' => $this->Daily_forecast_model->get_archive(),
            'start' => 0
        );
        $this->pdf_download($data);
    }

    public function pdf_latest()
    {
        $data = array(
            'daily_forecast_data' => $this->Daily_forecast_model->get_all_last_10days(),
            'start' => 0
        );
        $this->pdf_download($data);        
    }
	
	
	//download pdf
    public function pdf_download($data){
        
        ini_set('memory_limit', '10G');
        $html = $this->load->view('daily_forecast_pdf', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output('daily_forecast.pdf', 'D');

}

}

/* End of file Daily_forecast.php */
