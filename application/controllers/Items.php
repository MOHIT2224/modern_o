<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$this->load->database();

		if(!empty($this->input->get("search"))){
			$this->db->like('title', $this->input->get("search"));
			$this->db->or_like('description', $this->input->get("search")); 
		}

		$this->db->limit(5, ($this->input->get("page",1) - 1) * 5);
		$query = $this->db->get("items");

		$data['data'] = $query->result();
		$data['total'] = $this->db->count_all("items");

		echo json_encode($data);
	}

	public function store()
    {
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);
    	$insert = $this->input->post();
		$this->db->insert('items', $insert);

		$id = $this->db->insert_id();
		$q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
    }

    public function edit($id)
    {
    	$this->load->database();

		$q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());



    }

    public function update($id)
    {
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);

    	$insert = $this->input->post();
    	$this->db->where('id', $id);
    	$this->db->update('items', $insert);

        $q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());

				//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
    }

    public function delete($id)
    {
    	$this->load->database();
        $this->db->where('id', $id);
		$this->db->delete('items');
		echo json_encode(['success'=>true]);

		//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 0,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table
    }
}
