<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chart extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/Welcome
     *	- or -
     * 		http://example.com/index.php/Welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/Welcome/<method_name>
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    function __construct() {
        parent::__construct();
        $this->load->model('Modelchkuser');
        ///$_POST = json_decode(file_get_contents('php://input'), true);
    }
    public function index()
    {
        $this->load->view('chart');
    }
   
   
}
