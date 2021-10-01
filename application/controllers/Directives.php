<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Directives extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function sidebar()
    {
        $this->load->view('directives/sidebar');
    }
    public function userSidebar()
    {
        $this->load->view('directives/usersidebar');
    }
  public function userRightbar()
    {
        $this->load->view('directives/userrightbar');
    }
    public function adminSidebar()
    {
        $this->load->view('directives/adminSidebar');
    }
    public function masterSidebar()
    {
        $this->load->view('directives/masterSidebar');
    }

     public function dealerSidebar()
    {
        $this->load->view('directives/dealerSidebar');
    }
   
    public function userheader()
    {
        $this->load->view('directives/userheader');
    }
    public function dealerheader()
    {
        $this->load->view('directives/dealerheader');
    }
    public function masterheader()
    {
        $this->load->view('directives/masterheader');
    }
    public function adminHeader()
    {
        $this->load->view('directives/adminHeader');
    }
    public function header()
    {
        $this->load->view('directives/header');
    }
    //   public function masterHeader()
    // {
    //     $this->load->view('directives/masterHeader');
    // }
    //   public function dealerHeader()
    // {
    //     $this->load->view('directives/dealerHeader');
    // }
    
    public function stats()
    {
        $this->load->view('directives/stats');
    }
    public function userlist(){
        $this->load->view('directives/userListsessFncy');
    }
    
   
}
