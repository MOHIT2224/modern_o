<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class CronShedule extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $this->load->model('Betentrymodel');
		}
		
		function BetHistory($userId=null){
			
			$data['BetHistory']=$this->Betentrymodel->BetHistory($userId);
			$fp = fopen('BetHistorydb-backup-'.time().date(DATE_RFC822).'.json', 'w')or die('fopen failed');
			fwrite($fp, json_encode($data)) or die('fwrite failed'); 
			fclose($fp); 
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
			
		}
		function wholeDataBaseBkp(){
			// Load the DB utility class
			$this->load->dbutil();

			// Backup your entire database and assign it to a variable
			$backup = $this->dbutil->backup();

			// Load the file helper and write the file to your server
			$this->load->helper('file');
			write_file(base_url().'MY_Bkp/', $backup);
			echo base_url().'MY_Bkp/';

			// Load the download helper and send the file to your desktop
			$this->load->helper('download');
			force_download('CrickExch_BKP'.time().date(DATE_RFC822).'.gz', $backup);
		}

		function updateMstRunners(){
			$this->updateMatchRunners();
		}
		

	}