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
                        if(in_array($key,['user','AddUser', 'ViewUser', 'UpdateUser', 'ChangePwd', 'UserLock', 'BettingLock', 'Close_Ac']) && $rec==1){
                            $responceTemp['users'][]=$key;
                        }
                        if(in_array($key,['chip','ChipCr', 'ChipDr','ChipHistory', 'ChipSummary']) && $rec==1){
                            $responceTemp['chip'][]=$key;
                        }
                        if(in_array($key,['fancy','AddFancy', 'ViewFancy','EditFancy', 'ActiveFancy','LimitFancy', 'Result']) && $rec==1){
                            $responceTemp['fancy'][]=$key;
                        }
                        if(in_array($key,['seriesMatch','ViewSeriesMatch','ManageSeriesMatch']) && $rec==1){
                            $responceTemp['seriesMatch'][]=$key;
                        }
                        if(in_array($key,['settlementEntryList','ViewSettlementEntryList', 'ManageSettlementEntryList']) && $rec==1){
                            $responceTemp['settlementEntryList'][]=$key;
                        }
                        if(in_array($key,['settledMatches','ViewSettledMatches', 'ManageSettledMatches']) && $rec==1){
                            $responceTemp['settledMatches'][]=$key;
                        }
                        if(in_array($key,['trashBets','ViewTrashBets', 'ManageTrashBets']) && $rec==1){
                            $responceTemp['trashBets'][]=$key;
                        }
                        if(in_array($key,['marketSetting','ViewMarketSetting', 'ManageMarketSetting']) && $rec==1){
                            $responceTemp['marketSetting'][]=$key;
                        }
                        if(in_array($key,['matchSetting','ViewMatch', 'ActiveMatch', 'LimitMatch']) && $rec==1){
                            $responceTemp['matchSetting'][]=$key;
                        }
                        if(in_array($key,['setMatchResult','ViewSetMatchResult', 'ManageSetMatchResult']) && $rec==1){
                            $responceTemp['setMatchResult'][]=$key;
                        }
                        if(in_array($key,['settings','ViewSetting', 'ManageSetting']) && $rec==1){
                            $responceTemp['settings'][]=$key;
                        }
                        if(in_array($key,['closedUsersAccount','ViewClosedUsersAccount', 'ManageClosedUsersAccount']) && $rec==1){
                            $responceTemp['closedUsersAccount'][]=$key;
                        }
                        if(in_array($key,['RemoveOldGameAndUser']) && $rec==1){
                            $responceTemp['RemoveOldGameAndUser'][]=$key;
                        }
                        if(in_array($key,['RemoveOldBetData']) && $rec==1){
                            $responceTemp['RemoveOldBetData'][]=$key;
                        }
                        if(in_array($key,['MarketWatch']) && $rec==1){
                            $responceTemp['MarketWatch'][]=$key;
                        }
                        if(in_array($key,['subAdmin','ViewSubAdmin', 'UpdateSubAdmin']) && $rec==1){
                            $responceTemp['subAdmin'][]=$key;
                        }
                        if(in_array($key,['profitAndLoss','ViewProfitAndLoss', 'ManageProfitAndLoss']) && $rec==1){
                            $responceTemp['profitAndLoss'][]=$key;
                        }
                        if(in_array($key,['OnlineUsers']) && $rec==1){
                            $responceTemp['OnlineUsers'][]=$key;
                        }

                    }
                    $responce=[];
                    if($responceTemp['users']){
                        array_push($responce,[
                            'label'=>'users',
                            'fields'=>$responceTemp['users']
                        ]);
                    }
                    if($responceTemp['chip']){
                        array_push($responce,[
                            'label'=>'chip',
                            'fields'=>$responceTemp['chip']
                        ]);
                    }
                    if($responceTemp['fancy']){
                        array_push($responce,[
                            'label'=>'fancy',
                            'fields'=>$responceTemp['fancy']
                        ]);
                    }
                    if($responceTemp['seriesMatch']){
                        array_push($responce,[
                            'label'=>'Manage Series/Matches',
                            'fields'=>$responceTemp['seriesMatch']
                        ]);
                    }
                    if($responceTemp['settlementEntryList']){
                        array_push($responce,[
                            'label'=>'Settlement Entry List',
                            'fields'=>$responceTemp['settlementEntryList']
                        ]);
                    }
                    if($responceTemp['settledMatches']){
                        array_push($responce,[
                            'label'=>'Settled Matches',
                            'fields'=>$responceTemp['settledMatches']
                        ]);
                    }
                    if($responceTemp['trashBets']){
                        array_push($responce,[
                            'label'=>'Trash Bets',
                            'fields'=>$responceTemp['trashBets']
                        ]);
                    }
                    if($responceTemp['marketSetting']){
                        array_push($responce,[
                            'label'=>'Market Setting',
                            'fields'=>$responceTemp['marketSetting']
                        ]);
                    }
                    if($responceTemp['matchSetting']){
                        array_push($responce,[
                            'label'=>'Match Setting',
                            'fields'=>$responceTemp['matchSetting']
                        ]);
                    }
                    if($responceTemp['setMatchResult']){
                        array_push($responce,[
                            'label'=>'Set Match Result',
                            'fields'=>$responceTemp['setMatchResult']
                        ]);
                    }
                    if($responceTemp['settings']){
                        array_push($responce,[
                            'label'=>'Settings',
                            'fields'=>$responceTemp['settings']
                        ]);
                    }
                    if($responceTemp['closedUsersAccount']){
                        array_push($responce,[
                            'label'=>'Closed Users Account',
                            'fields'=>$responceTemp['closedUsersAccount']
                        ]);
                    }

                    $responce = ['ID'=>$data['ID'],'name'=>$data['name'],'roleList'=>$responce];
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
                        'fields'=>['user','AddUser', 'ViewUser', 'UpdateUser', 'ChangePwd', 'UserLock', 'BettingLock', 'Close_Ac']
                    ],
                    [
                        'label'=>'chip',
                        'fields'=>['chip','ChipCr', 'ChipDr','ChipHistory', 'ChipSummary']
                    ],
                    [
                        'label'=>'Manage Fancy',
                        'fields'=>['fancy','AddFancy', 'ViewFancy','EditFancy', 'ActiveFancy','LimitFancy', 'Result' ]
                    ],
                    [
                        'label'=>'Manage Series/Matches',
                        'fields'=>['seriesMatch','ViewSeriesMatch','ManageSeriesMatch']
                    ],
                    [
                        'label'=>'Settlement Entry List',
                        'fields'=>['settlementEntryList','ViewSettlementEntryList', 'ManageSettlementEntryList',]
                    ],
                    [
                        'label'=>'Settled Matches',
                        'fields'=>['settledMatches','ViewSettledMatches', 'ManageSettledMatches',]
                    ],
                    [
                        'label'=>'Trash Bets',
                        'fields'=>['trashBets','ViewTrashBets', 'ManageTrashBets',]
                    ],
                    [
                        'label'=>'Market Setting',
                        'fields'=>['marketSetting','ViewMarketSetting', 'ManageMarketSetting',]
                    ],
                    [
                        'label'=>'Match Setting',
                        'fields'=>['matchSetting','ViewMatch', 'ActiveMatch', 'LimitMatch',]
                    ],
                    [
                        'label'=>'Set Match Result',
                        'fields'=>['setMatchResult','ViewSetMatchResult', 'ManageSetMatchResult',]
                    ],
                    [
                        'label'=>'Settings',
                        'fields'=>['settings','ViewSetting', 'ManageSetting']
                    ],
                    [
                        'label'=>'Closed Users Account',
                        'fields'=>['closedUsersAccount','ViewClosedUsersAccount', 'ManageClosedUsersAccount']
                    ],
                    [
                        'label'=>'Remove Old Game + Users Data',
                        'fields'=>['RemoveOldGameAndUser']
                    ],
                    [
                        'label'=>'Remove Old Bet Data',
                        'fields'=>['RemoveOldBetData']
                    ],
                    [
                        'label'=>'Market Watch',
                        'fields'=>['MarketWatch']
                    ],
                    [
                        'label'=>' Manage Subadmin',
                        'fields'=>['subAdmin','ViewSubAdmin', 'UpdateSubAdmin']
                    ],
                    [
                        'label'=>'Profit & Loss',
                        'fields'=>['profitAndLoss','ViewProfitAndLoss', 'ManageProfitAndLoss']
                    ],
                    [
                        'label'=>'Online Users',
                        'fields'=>['OnlineUsers']
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
