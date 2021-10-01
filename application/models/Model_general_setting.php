<?php defined('BASEPATH') OR exit('No direct script access allowed');

		class Model_general_setting extends CI_Model
		{
			
			function __construct()
			{
		     $_POST = json_decode(file_get_contents('php://input'), true);
					 
			}
			function update($key,$value){

                $data=array('key_value'=>$value);
                $this->db->where('key_name', $key);

                $query=$this->db->update('general_settings', $data);
                if ($query) {
                    return true;
                }else{
                    return false;
                }


			}

			function get($key=null){
				$this->db->select("key_name,key_value");
				$this->db->from('general_settings');
				if(!empty($key)){
                    $this->db->where('key_name', $key);
                    $query = $this->db->get();
                    return $query->row();
                }else{
                    $query = $this->db->get();
                    return $query->result_array();
                }

			}


}