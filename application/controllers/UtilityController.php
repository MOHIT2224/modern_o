<?php

defined('BASEPATH') OR exit('No direct script access allowed');

	class UtilityController extends CI_Controller {

		function __construct() {

			parent::__construct();

			$this->load->model('Modelmatchmst');


		}
        function deleteUnMatchBetData(){
            $this->load->model("Betentrymodel");
            $data = $this->Betentrymodel->deleteUnMatchBetData();
        }

		function updateMatchmst(){
            $this->load->model('Modeleventlst');
            $series = $this->Modeleventlst->GetApiSeriesFrmDatabase();
            $matchIds = [];

            try {
                foreach ($series as $serie){
                   // echo BR_SUPER_AMDIN_URL."getMatches/".$serie['seriesId'];
                    $matches = json_decode($this->httpGet(BR_SUPER_AMDIN_URL.$serie['seriesId']), true);

                  // echo "<pre>"; print_r($matches);

                    foreach ($matches as $matche) {
                        $matchIds[]=$matche['eventId'];
                        $data = array('MstDate' => $matche['eventDate']);
                        $this->db->where('MstCode', $matche['eventId']);
                        $this->db->update('matchmst', $data);

                    }
                }
                /*$data = array('completed' => 0);
                $this->db->update('matchmst', $data);

                $data = array('completed' => 1);
                $this->db->where_not_in('MstCode', $matchIds);
                $this->db->update('matchmst', $data);*/
                //echo "successfully updated";
            } catch (Exception $e) {

                echo $e->getMessage();
            }

        }

        public function downloadApk()
        {
            if(APK_DOWNLOAD_URL=='N'){
                return false;
            }
            $this->db->select('apk_download_link');
            $this->db->from('apk_version');
            $this->db->order_by('id','DESC');
            $query = $this->db->get();
            $data = $query->row_array();
            $url =  site_url().'uploads/apk/'.$data['apk_download_link'];

            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($url) . "\"");
            readfile($url);
        }
	}