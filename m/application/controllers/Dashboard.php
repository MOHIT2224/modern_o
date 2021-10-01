<?php

defined('BASEPATH') or exit('No direct script access allowed');
 // $_SESSION['loggedIn'] = true;  


class Dashboard extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('Modelcreatemaster');
        $this->load->model('Chip_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
    }
    public function main(){
        $this->load->view('dashboard/main');
    }
    public function usermain(){
        $this->load->view('dashboard/usermain');
    }
     public function dealermain(){
        $this->load->view('dashboard/dealermain');
    }
    public function mastermain(){
        $this->load->view('dashboard/mastermain');
    }
    public function home(){
        $data['sliderLst']=$this->Modelcreatemaster->get_DashBoardImage(); 
        $this->load->view('dashboard/home',$data); 
    }

    public function masterDashboard(){
        $data['sliderLst']=$this->Modelcreatemaster->get_DashBoardImage();
        $this->load->view('dashboard/masterDashboard',$data);
    } 
     public function dealerDashboard(){

        $data['sliderLst']=$this->Modelcreatemaster->get_DashBoardImage();
        $this->load->view('dashboard/dealerDashboard',$data);
    }
     public function userDashboard(){

        $data['sliderLst']=$this->Modelcreatemaster->get_DashBoardImage();
        $this->load->view('dashboard/userDashboard',$data);
    }       
    public function createMaster(){

        $this->load->view('dashboard/createMaster');
    }         
}
