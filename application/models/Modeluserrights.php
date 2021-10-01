<?php
	error_reporting("ERROR");
	defined('BASEPATH') OR exit('No direct script access allowed');
		class ModelUserRights extends CI_Model
		{
			function __construct()
			{
				$_POST = json_decode(file_get_contents('php://input'), true);
			}
			function Save_userRights(){
				//print_r($_POST['view']);
				//print_r($_POST['userId']);
				foreach ($_POST['view'] as $name => $value) {
					//echo $value;
					foreach ($_POST['userId'] as $userName => $userId) {
						//echo $userId;
						$arrayValue = array('mencode'=> $value,'menuser'=> $userId["id"],'menview'=> 1);
						//print_r($arrayValue);
						$query=$this->db->insert('usermenust', $arrayValue);


					}
				}
			}

			function hasRole($roleName){
                if($this->session->userdata('subAdmin')){
                    $role = $this->getLoginUserRole();
                   // print_r($role);die;
                    if(!empty($role) && isset($role[$roleName])){
                        return array('status'=>false,'message'=>'Authorized');
                    }else{
                        return array('status'=>true,'message'=>'Not Authorized');
                    }
                }else{
                    return array('status'=>false,'message'=>'Authorized');
                }
            }

            function getLoginUserRole()
            {

                $this->model_load_model('Modelsubadmin');
                $subAdmin= $this->Modelsubadmin->getUserById($this->session->userdata('subAdminId'));
                $responceTemp = [];
                $this->db->select('*');
                $this->db->from('tblHelperRight');
                $this->db->where('ID', $subAdmin->HelperID);
                $query = $this->db->get();
                if(!empty($data = $query->row_array())){
                    foreach ($data as $key=>$rec){
                        if(in_array($key,$this->dbListHelperRightsColoum()) && $rec==1){
                            $responceTemp[$key]=$rec;
                        }

                    }
                    $responceTemp['ID']=$data['ID'];
                    $responceTemp['name']=$data['name'];
                    $responce = $responceTemp;
                }

                return $responce;

            }

            function checkHelperRights($HelperId)
            {
                $this->db->select('HelperID');
                $this->db->from('tblHelperRight');
                $this->db->where('HelperID', $HelperId);
                $query = $this->db->get();
                $num=$query->num_rows();
                return $num;
            }
            function getHelperRightsById($id){
			    $responceTemp = [];
                $this->db->select('*');
                $this->db->from('tblHelperRight');
                $this->db->where('ID', $id);
                $query = $this->db->get();
                if(!empty($data = $query->row_array())){
                    foreach ($data as $key=>$rec){
                        if(in_array($key,$this->dbListHelperRightsColoum()) && $rec==1){
                            $responceTemp[]=$key;
                        }

                    }
                    $responce = ['ID'=>$data['ID'],'name'=>$data['name'],'roleList'=>$responceTemp];
                }

                return $responce;
            }
            function  roleList(){
                $this->db->select('ID,name');
                $this->db->from('tblHelperRight');
                $query = $this->db->get();
                return $query->result_array();
            }
            function listHelperRights(){
			    $data = [
			        [
			            'label'=>'users',
                        'fields'=>[
                            ['key'=>'user','value'=>'User'],
                            ['key'=>'AddUser','value'=>'Add User'],
                            ['key'=>'ViewUser','value'=>'View User'],
                            ['key'=>'UpdateUser','value'=>'Update User'] ,
                            ['key'=>'ChangePwd','value'=>'Change Password'],
                            ['key'=>'UserLock','value'=>'User Lock'] ,
                            ['key'=>'BettingLock','value'=>'Betting Lock'],
                            ['key'=>'Close_Ac','value'=>'Close Account']
                        ]
                    ],
                    [
                        'label'=>'chip',
                        'fields'=>[['key'=>'chip','value'=>'Chip'],['key'=>'ChipCr','value'=>'Chip Cr'],['key'=>'ChipDr','value'=>'Chip Dr'] ,['key'=>'ChipHistory','value'=>'Balance History'],['key'=>'ChipSummary','value'=>' Balance Summary'] ]
                    ],
                    [
                        'label'=>'Manage Fancy',
                        'fields'=>[['key'=>'fancy','value'=>'Fancy'],['key'=>'AddFancy','value'=>'Add Fancy'],['key'=>'ViewFancy','value'=>'View Fancy'] ,['key'=>'EditFancy','value'=>'Edit Fancy'], ['key'=>'ActiveFancy','value'=>'Active Fancy'],['key'=>'LimitFancy','value'=>'Limit Fancy'],['key'=>'Result','value'=>'Result'] ]
                    ],
                    [
                        'label'=>'Manage Series/Matches',
                        'fields'=>[['key'=>'seriesMatch','value'=>'Series Match'],['key'=>'scheduleMatch','value'=>'Schedule Match'],['key'=>'ViewSeriesMatch','value'=>'View Series Match'],['key'=>'ManageSeriesMatch','value'=>'Manage Series Match']]
                    ],
                    [
                        'label'=>'Settlement Entry List',
                        'fields'=>[['key'=>'settlementEntryList','value'=>'Settlement Entry List'],['key'=>'ViewSettlementEntryList','value'=>'View Settlement Entry List'], ['key'=>'ManageSettlementEntryList','value'=>'Manage Settlement Entry List'],]
                    ],
                    [
                        'label'=>'Settled Matches',
                        'fields'=>[['key'=>'settledMatches','value'=>'Settled Matches'],['key'=>'ViewSettledMatches','value'=>'View Settled Matches'], ['key'=>'ManageSettledMatches','value'=>'Manage Settled Matches'],]
                    ],
                    [
                        'label'=>'Trash Bets',
                        'fields'=>[['key'=>'trashBets','value'=>'Trash Bets'],['key'=>'ViewTrashBets','value'=>'View Trash Bets'],['key'=>'ManageTrashBets','value'=>'Manage Trash Bets'],]
                    ],
                    [
                        'label'=>'Market Setting',
                        'fields'=>[['key'=>'marketSetting','value'=>'Market Setting'],['key'=>'ViewMarketSetting','value'=>'View Market Setting'],['key'=>'ManageMarketSetting','value'=>'Manage Market Setting'],]
                    ],
                    [
                        'label'=>'Match Setting',
                        'fields'=>[['key'=>'matchSetting','value'=>'Match Setting'],['key'=>'ViewMatch','value'=>'View Match'], ['key'=>'ActiveMatch','value'=>'Active Match'],['key'=>'LimitMatch','value'=>'Limit Match'],]
                    ],
                    [
                        'label'=>'Sport Setting',
                        'fields'=>[['key'=>'sportSetting','value'=>'Sport Setting'],['key'=>'ViewSport','value'=>'View Sport'], ['key'=>'ActiveSport','value'=>'Active Sport']]
                    ],
                    [
                        'label'=>'Set Match Result',
                        'fields'=>[['key'=>'setMatchResult','value'=>'Set Match Result'],['key'=>'ViewSetMatchResult','value'=>'View Set Match Result'],['key'=>'ManageSetMatchResult','value'=>'Manage Set Match Result'] ,]
                    ],
                    [
                        'label'=>'Settings',
                        'fields'=>[['key'=>'settings','value'=>'Settings'],['key'=>'ViewSetting','value'=>'View Setting'],['key'=>'ManageSetting','value'=>'Manage Setting'] ]
                    ],
                    [
                        'label'=>'Closed Users Account',
                        'fields'=>[['key'=>'closedUsersAccount','value'=>'Closed Users Account'],['key'=>'ViewClosedUsersAccount','value'=>'View Closed Users Account'],['key'=>'ManageClosedUsersAccount','value'=>'Manage Closed Users Account'] ]
                    ],
                    [
                        'label'=>'Remove Old Game + Users Data',
                        'fields'=>[['key'=>'RemoveOldGameAndUser','value'=>'Remove Old Game & User']]
                    ],
                    [
                        'label'=>'Remove Old Bet Data',
                        'fields'=>[['key'=>'RemoveOldBetData','value'=>'Remove Old Bet Data']]
                    ],
                    [
                        'label'=>'Market Watch',
                        'fields'=>[['key'=>'MarketWatch','value'=>'Market Watch']]
                    ],
                    [
                        'label'=>'One Page Report',
                        'fields'=>[['key'=>'onePageReportSetting','value'=>'View One Page Report'],['key'=>'BetHistory','value'=>'Bet History'],['key'=>'AccountStatements','value'=>'Account Statements'],['key'=>'ProfitLoss','value'=>'Profit & Loss '],['key'=>'LoginHistory','value'=>'Login History']]
                    ],
                    [
                        'label'=>' Manage Subadmin',
                        'fields'=>[['key'=>'subAdmin','value'=>'Sub Admin'],['key'=>'ViewSubAdmin','value'=>'View Sub Admin'],['key'=>'AddSubAdmin','value'=>'Add Sub Admin'],['key'=>'manageSubAdmin','value'=>'manage Sub Admin'] ]
                    ],
                    [
                        'label'=>'Profit & Loss',
                        'fields'=>[['key'=>'profitAndLoss','value'=>'Profit & Loss'],['key'=>'ViewProfitAndLoss','value'=>'View Profit & Loss'],['key'=>'ManageProfitAndLoss','value'=>'Manage Profit & Loss'] ]
                    ],
                    [
                        'label'=>'Online Users',
                        'fields'=>[['key'=>'OnlineUsers','value'=>'Online Users']]
                    ],
                    [
                        'label'=>'Manage Role',
                        'fields'=>[['key'=>'role','value'=>'Role'],['key'=>'AddRole','value'=>'Add Role'],['key'=>'ViewRole','value'=>'View Role'],['key'=>'ManageRole','value'=>'Manage Role']]
                    ],

                ];

                return $data;
            }

            function dbListHelperRightsColoum(){
                $data= $this->db->list_fields('tblhelperright');
                array_splice( $data,0, 3);
                return $data;
            }


			function saveHelperRights(){//170210


                $arrayValue['name'] =  $_POST['name'];

                if(!empty($_POST['UserRole'])){
                    foreach ($this->dbListHelperRightsColoum()  as $UserRole){
                        if(in_array($UserRole,$_POST['UserRole'])){
                            $arrayValue[$UserRole]=1;
                        }else{
                            $arrayValue[$UserRole]=0;
                        }

                    }
                }
                if($_POST['ID'] > 0){

                    $this->db->where('ID',$_POST['ID']);
                    if($this->db->update('tblHelperRight', $arrayValue)){

                        $responce =  array('error' => 0 ,'message' => 'Role Successfully Updated...');
                    }else{
                        $responce =  array('error' => 1 ,'message' => 'Role Not Updated...');
                    }

                }else{

                    if($this->db->insert('tblHelperRight', $arrayValue)){

                        $responce =  array('error' => 0 ,'message' => 'Role Successfully Created...');
                    }else{
                        $responce =  array('error' => 1 ,'message' => 'Role Not Created...');
                    }
                }

                return $responce;
			}

            function updateHelperRights($id){

                $arrayValue['name'] =  $_POST['name'];

                if(!empty($_POST['UserRole'])){
                    foreach ($_POST['UserRole'] as $UserRole){
                        $arrayValue[$UserRole]=1;
                    }
                }

                $this->db->where('ID',$id);
                if($this->db->update('tblHelperRight', $arrayValue)){
                    $responce =  array('error' => 0 ,'message' => 'Role Successfully Created...');
                }else{
                    $responce =  array('error' => 1 ,'message' => 'Role Not Created...');
                }
                return $responce;
            }

		}
