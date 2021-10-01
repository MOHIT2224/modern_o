<?php
error_reporting("ERROR");
defined('BASEPATH') OR exit('No direct script access allowed');

	class Lstsavemstrcontroller extends CI_Controller {
		function __construct() {
		        parent::__construct();
		        $node1=$this->session->userdata('user_id');
		        $this->load->model('Modelcreatemaster');
	         $this->load->model('Modellstmaster');
        }
		function index(){
			
			$data = $this->Modellstmaster->getLstMaster();
        	$this->output->set_content_type('application/json')->set_output(json_encode($data));	
			
		}
		
		function lstSaveMaster($userid,$userType,$helperid,$helperType){
			
			$root=$userid;

			$tree = $this->Modellstmaster->GetUserTree($root,$userType,$helperid,$helperType);
            //print_r($tree);
			
			$result[] =  array(
		                'name' => $tree[0]['mstruserid'],
						'image' => $tree[0]['image'],
						'id' => $tree[0]['mstrid'],
						'collapsed'=> false,
						'lgnusrlckbtng' => $tree[0]['lgnusrlckbtng'],
						'lgnusrCloseAc' => $tree[0]['lgnusrCloseAc'],
						'usetype' => $tree[0]['usetype'],
		                'mstrlock' => $tree[0]['mstrlock'],
		                'Commission' => $tree[0]['Commission'],
		                'partner' => $tree[0]['partner'],
		                'mstrname' => $tree[0]['mstrname'],
		                'lgnUserMaxStake' => $tree[0]['lgnUserMaxStake'],
		                'lgnUserMaxProfit' => $tree[0]['lgnUserMaxProfit'],
		                'lgnUserMaxLoss' => $tree[0]['lgnUserMaxLoss'],
						'SessionComm' => $tree[0]['SessionComm'],
						'OtherComm' => $tree[0]['OtherComm'],
						'set_timeout' => $tree[0]['set_timeout'],
		                'children' => $this->parseTree($tree, $root)
		              );
					  //$this->parseTree($tree,$root);
			
			$data['tree'] = $result;
			/*$data['match'] = $this->Modellstmaster->getMatch();*/
			/*$data['getFancyHeader']=$this->Modelcreatemaster->getFancyHeader();	*/
			$data['getplayer']=$this->Modelcreatemaster->getPlayer();
        	$this->output->set_content_type('application/json')->set_output(json_encode($data));
        	
		}
		//Get Tree Value
		function printTree($tree) {
			    if(!is_null($tree) && count($tree) > 0) {
			        echo '<ul>';
			        foreach($tree as $node) {
			            echo '<li>'.$node['name'];
			            $this->printTree($node['children']);
			            echo '</li>';
			        }
			        echo '</ul>';
			    }
			}
		//$node1=$this->session->userdata('user_id');

		function parseTree($tree, $root) {
		    $return = array();
		    # Traverse the tree and search for direct children of the root
		    foreach($tree as $value) {
		    	$parent=$value['parentid'];
		    	$child=$value['mstrid'];
		        # A direct child is found
		        if($parent == $root) {
		            # Remove item from tree (we don't need to traverse this again)
		            //unset($tree[$child]);
		            # Append the child into result array and parse its children
		            $return[] = array(
		                'name' => $value['mstruserid'],
						'image' => $value['image'],
						'id' => $value['mstrid'],
						'collapsed'=> true,
						'lgnusrlckbtng' => $value['lgnusrlckbtng'],
						'lgnusrCloseAc' => $value['lgnusrCloseAc'],
						'usetype' => $value['usetype'],
		                'mstrlock' => $value['mstrlock'],
		                'Commission' => $value['Commission'],
		                'partner' => $value['partner'],
		                'mstrname' => $value['mstrname'],
		                'lgnUserMaxStake' => $value['lgnUserMaxStake'],
		                'lgnUserMaxProfit' => $value['lgnUserMaxProfit'],
		                'lgnUserMaxLoss' => $value['lgnUserMaxLoss'],
						'SessionComm' => $value['SessionComm'],
						'OtherComm' => $value['OtherComm'],
						'set_timeout' => $value['set_timeout'],
		                'children' => $this->parseTree($tree, $child)
		            );
		        }
		    }
		    return empty($return) ? null : $return;    
		}
		//End of get Tree Value
		function GetUserdata($userId){
			
			$data = $this->Modellstmaster->getUserDataById($userId);
        	$this->output->set_content_type('application/json')->set_output(json_encode($data));	
		}
		function updateMstr($id,$status){
			
			if ($data = $this->Modellstmaster->updateLstMaster($id,$status)) {
				$this->output->set_content_type('application/json')->set_output(json_encode($data));	
			}
			
		}
		function updateFancySatatus($id,$active){
			
			$condition=$this->Modelcreatemaster->updateMatchStatus($id,$active);
			if ($condition) {
				//$this->lstSaveMaster();
				echo json_encode(array('error' => 0 ,'message' => 'status Change Success...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	

		}
		function updateFancySatatusNew($id,$active){
			
			$condition=$this->Modelcreatemaster->updateFancyStatus($id,$active);
			if ($condition) {
				//$this->lstSaveMaster();
				echo json_encode(array('error' => 0 ,'message' => 'status Change Success...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	

		}
		function suspendFancy(){
			
			$condition=$this->Modelcreatemaster->suspendFancy();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'status Change Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	

		}
		function NormalFancy(){
			
			$condition=$this->Modelcreatemaster->suspendFancy();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'status Change Successfully...'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	

		}
		function getAcitvatedFancyMatches(){
			
			$data['activeMatch'] = $this->Modellstmaster->getActivatedMatch();
			$data['getFancy'] = $this->Modellstmaster->FancyItem();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));	
			
		}
		function getFancyData($matchId,$fancyId,$userId,$usertype,$TypeID){
			
			$data['fancyForm'] = $this->Modellstmaster->getFancyByMatchId($matchId,$fancyId);
			//if($usertype!='3')
				$data['UserBetData'] = $this->Modellstmaster->GetBetData($matchId,$fancyId,$userId,$usertype);
			//else
			//	$data['UserBetData'] =null;
			if ($TypeID==1)
			{
                $data['scorePosition'] = $this->Modellstmaster->scorePosition_evenOdd($userId,$fancyId,$TypeID,$matchId);
            }
			else if($TypeID==2){
                $data['scorePosition'] = $this->Modellstmaster->scorePosition($userId,$fancyId,$TypeID);
            }
			else if($TypeID==3){
                $data['scorePosition'] = $this->Modellstmaster->scorePosition_Khaddal($userId,$fancyId,$TypeID,$matchId);
            }
			else if($TypeID==4){
                $data['scorePosition'] = $this->Modellstmaster->scorePosition_LastDigit($userId,$fancyId,$TypeID,$matchId);
            }
			else if($TypeID==5){
                $data['scorePosition'] = $this->Modellstmaster->scorePosition_Up_Down($userId,$fancyId,$TypeID,$matchId);
            }

			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function getFancyUserSide($matchId,$fancyId,$userId,$usertype){
			
			$data['fancyForm'] = $this->Modellstmaster->getFancyByMatchId($matchId,$fancyId);
			$data['UserBetData'] = $this->Modellstmaster->GetBetData($matchId,$fancyId,$userId,$usertype);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function fancyList($matchId){

			$data = $this->Modellstmaster->listFancy($matchId);
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function GetFancyOnHeader(){
			$data['getFancy'] = $this->Modellstmaster->getFancylistHeader();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function saveUserFancy(){
			/*if ($this->input->post('TypeID')==2) {
				$chk=$this->Modellstmaster->ChkFancyOnBet($this->input->post('matchId'),$this->input->post('FancyID'));
				
			}else{

				$chk=$this->Modellstmaster->ChkFancyOnBet($this->input->post('matchId'),$this->input->post('FancyID'));
			}*/
			//print_r($_POST);die();
			
			$chk=$this->Modellstmaster->ChkFancyOnBet($this->input->post('matchId'),$this->input->post('FancyID'));


			if ($chk > 0) {


				    $condition = $this->Modellstmaster->saveUserFancyEvenOdd();
				   // print_r($condition);

                    if ($_POST['TypeID']==2) {
                        $data['scorePosition'] = $this->Modellstmaster->scorePosition($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                        $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                        $data['error']=$condition[0]['resultV'];
                        $data['message']='"'.$condition[0]['retMess'].'"';

                    }else if($_POST['TypeID']==1){
                        if ($condition[0]['resultV']==0){
                            $data['scorePosition'] = $this->Modellstmaster->scorePosition_evenOdd($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                            $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                            $data['error']=$condition[0]['resultV'];
                            $data['message']='"'.$condition[0]['retMess'].'"';
                        }else{
                            $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                            $data['scorePosition'] = $this->Modellstmaster->scorePosition($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                            $data['error']=$condition[0]['resultV'];
                            $data['message']='"'.$condition[0]['retMess'].'"';
                        }
                    }else if($_POST['TypeID']==4){
                        $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                        $data['scorePosition'] = $this->Modellstmaster->scorePosition_LastDigit($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                        $data['error']=$condition[0]['resultV'];
                        $data['message']='"'.$condition[0]['retMess'].'"';
                    }else if($_POST['TypeID']==3){
                        $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                        $data['scorePosition'] = $this->Modellstmaster->scorePosition_Khaddal($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                        $data['error']=$condition[0]['resultV'];
                        $data['message']='"'.$condition[0]['retMess'].'"';
                    }else if($_POST['TypeID']==5){
                        $data['UserBetData']=$this->Modellstmaster->GetBetData($this->input->post('matchId'),$this->input->post('FancyID'),$this->input->post('loginId'),$this->input->post('type'));
                        $data['scorePosition'] = $this->Modellstmaster->scorePosition_Up_Down($this->input->post('userId'),$this->input->post('FancyID'),$this->input->post('TypeID'),$this->input->post('matchId'));
                        $data['error']=$condition[0]['resultV'];
                        $data['message']='"'.$condition[0]['retMess'].'"';
                    }
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}else{
				//echo json_encode(array('error' => 1 ,'message' => 'Fancy Is Inactive'));
				$data['error']=1;
				$data['message']='Fancy Is Inactive';
				$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}
			
		}
		function test_fancy(){
			echo json_encode(array('error' => 0 ,'message' => 'status Change'));
		}
		function updateFancyHeaderSatatus(){
			
			$condition=$this->Modellstmaster->updateFancyHeaderSatatus();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'status Change'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	

		}
		function updateFancyResult(){


			$condition=$this->Modellstmaster->updateFancyResult();
			//$condition=$condition[0]['iReturn'];
			echo json_encode(array('error' => $condition ,'message' => $condition[0]['sMsg']));

		}
        function updateKhaddalResult(){


            $condition=$this->Modellstmaster->updateKhaddalResult();
            //print_r($condition);
            //$condition=$condition[0]['iReturn'];

                echo json_encode(array('error' => $condition[0]['iReturn'] ,'message' => $condition[0]['sMsg']));

        }
		function updateOddsLimit($limit,$matchId)//sourabh 11-nov-2016
		{
			$condition=$this->Modelcreatemaster->updateOddsLimit($limit,$matchId);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Limit Changes Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Limit Not Changes'));
			}	
		}
		function getMatchOddsLimit($matchId)//sourabh 11-nov-2016
		{
			$condition=$this->Modellstmaster->getMatchOddsLimit($matchId);
			$this->output->set_content_type('application/json')->set_output(json_encode($condition));
		}
		function updateStakeLimit($limit,$usecode)//sourabh 15-nov-2016
		{
			$condition=$this->Modelcreatemaster->updateStakeLimit($limit,$usecode);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Limit Changes Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Limit Not Changes'));
			}	
		}
		function updateCommission($Commission,$usecode)//sourabh 15-nov-2016
		{
			$condition=$this->Modelcreatemaster->updateCommission($Commission,$usecode);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Commission Changes Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Commission Not Changes'));
			}	
		}
		function UpdateMaxProfit($profit,$usecode){
			$condition=$this->Modelcreatemaster->UpdateMaxProfit($profit,$usecode);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Max Profit Updated Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Max Profit Not Changes'));
			}	
		}
		function UpdateMaxLoss($loss,$usecode){
			$condition=$this->Modelcreatemaster->UpdateMaxLoss($loss,$usecode);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Max Loss Updated Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Max Loss Not Changes'));
			}	
		}
		function UpdateMaxStake($stake,$usecode){
			$condition=$this->Modelcreatemaster->UpdateMaxStake($stake,$usecode);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Max Stake Updated Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Max Stake Not Changes'));
			}	
		}
		function updateVolumeLimit($limit,$matchId)//sourabh 30-nov-2016
		{
			$condition=$this->Modelcreatemaster->updateVolumeLimit($limit,$matchId);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Limit Changes Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Limit Not Changes'));
			}	
		}
		function closeUserList(){

			$data['closeUser'] = $this->Modellstmaster->closeUserList();			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));

		}
		function updateClsAc($userId,$status){
			$condition=$this->Modellstmaster->updateClsAc($userId,$status);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Status Changes Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Status Not Changes'));
			}	

		}
		function getFancyByEdit($id,$type){
			$data['FancyData'] = $this->Modellstmaster->getFancyByEdit($id,$type);			
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
		function setFancyMsg(){
			$condition=$this->Modellstmaster->setFancyMsg();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Message set Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Message Not set Successfully'));
			}	
		}
		function chnageRate(){
			$condition=$this->Modellstmaster->chnageRate();
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Rate set Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Rate Not set Successfully'));
			}	
		}
		function updateRateChangeMsg($FancyID,$TypeID){
			$condition=$this->Modellstmaster->updateRateChangeMsg($FancyID,$TypeID);
			if ($condition) {
				echo json_encode(array('error' => 0 ,'message' => 'Massage SuccessFully set Successfully'));
			}else{
				echo json_encode(array('error' => 1 ,'message' => 'Massage SuccessFully Not set Successfully'));
			}	
		}
		function updateSeriesSatatus($id,$active){
			$condition=$this->Modelcreatemaster->updateSeriesSatatus($id,$active);
			if ($condition)
			{
				echo json_encode(array('error' => 0 ,'message' => 'status Change Success...'));
			}
			else
			{
				echo json_encode(array('error' => 1 ,'message' => 'status Not Change'));
			}	
		}
		function getUserInfo($userId){
            $data['userInfo'] = $this->Modellstmaster->getUserInfo($userId);

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
       
        function setLiability(){
            $condition=$this->Modelcreatemaster->setLiability();
            if ($condition) {
                echo json_encode(array('error' => 0 ,'message' => 'Liability updated Successfully...'));
            }else{
                echo json_encode(array('error' => 1 ,'message' => 'Liability not updated'));
            }
        }
        function getPartnerShip($userId){
        	$data['userPrtnrShip'] = $this->Modellstmaster->getPartnerShip($userId);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        function getParentData($userId){
        	$data['parentData'] = $this->Modellstmaster->getParentData($userId);
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
		function getMTree(){
        	$data['mTree'] = $this->Modellstmaster->getMTree();
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
        function changeLgnPassword(){
        	$condition=$this->Modelcreatemaster->changeLgnPassword();
        	//print_r($condition);die();
            if ($condition) {
                echo json_encode(array('error' => 0 ,'message' => 'Password updated Successfully...'));
            }else{
                echo json_encode(array('error' => 1 ,'message' => 'Password not updated'));
            }
        }
		function changeLgnBitC(){//for android update first time password bit
        	$condition=$this->Modelcreatemaster->changeLgnBitC();
            if ($condition==1) {
                echo json_encode(array('error' => 0 ,'message' => 'Bit updated Successfully...'));
            }else{
                echo json_encode(array('error' => 1 ,'message' => 'Bit not updated'));
            }
        }
		function chaneMarketPPStatus($ppStatus){
			$condition=$this->Modelcreatemaster->chaneMarketPPStatus($ppStatus);
			if ($condition==1) {
                echo json_encode(array('error' => 0 ,'message' => 'Market Change...'));
            }
        }
	}