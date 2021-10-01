<?php defined('BASEPATH') OR exit('No direct script access allowed');

		class Modeleventlst extends CI_Model
		{
			
			function __construct()
			{
		        $_POST = json_decode(file_get_contents('php://input'), true);
					 
			}
			function saveSport(){
				$chk=$this->chkSports($_POST['id']);
				if ($chk==0) {
					$insertData = array('id'=> $_POST['id'],'name'=> $_POST['name'],'marketCount'=> $_POST['marketCount']);
					$query=$this->db->insert('sportmst', $insertData);


					return true;
				}else{
					return false;
				}
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
      
			function saveMatchMarket($data){
				//echo $_POST['MatchId'];
				$chk=$this->chkMatchMarket($data['marketId']);
				if ($chk==0) {
					$this->db->trans_start();
					if($data['SportsId'] == '7'){
						$insertData = array('Id'=> $data['marketId'],'Name'=> $data['marketName'],'matchId'=> $data['MatchId'],'sportsId'=> $data['SportsId'],'totalmatched'=> $data['totalMatched'],
						'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> '210','HelperID'=> $data['HelperID'],'max_bet_liability'=>$data['max_bet_liability'],'max_market_liability'=>$data['max_market_liability'],'max_market_profit'=>$data['max_market_profit'],'market_runner_json'=>$data['market_runner_json']);
					}
					else {
					$insertData = array('Id'=> $data['marketId'],'Name'=> $data['marketName'],'matchId'=> $data['MatchId'],'sportsId'=> $data['SportsId'],'totalmatched'=> 0,
						'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> $data['seriesId'],'HelperID'=> $data['HelperID'],'max_bet_liability'=>$data['max_bet_liability'],'max_market_liability'=>$data['max_market_liability'],'max_market_profit'=>$data['max_market_profit'],'isManualMatchOdds'=>$data['isManualMatchOdds'],'market_runner_json'=>$data['market_runner_json']);
					}
					//print_r($insertData);
					$MatchName=$this->getMatchNameById($data['MatchId']);

					//print_r($MatchName);
					$Name=$MatchName[0]->matchName;
					//die();
					$query=$this->db->insert('market', $insertData);

					
					$GetMarket= $data['marketName']."__".$Name;
					$menuoption1 = array('menCode'=> $data['marketId'],'menName'=> $GetMarket,'menDesc'=> $GetMarket,'mstType'=> '8');
					$query1=$this->db->insert('menuoption', $menuoption1);

					$this->db->trans_complete();


					return true;
				}else{
								//sourabh 170105
					$GetpId=$this->Get_MarketActive( $_POST['marketId']);
					$Active=$GetpId[0]->active;
					if($Active==0)
						$dataArray = array('active' => 1,'HelperID'=> $_POST['HelperID']);
					else
						$dataArray = array('active' => 0,'HelperID'=> $_POST['HelperID']);

    				$this->db->where('Id', $_POST['marketId']);
					$this->db->update('market', $dataArray);
					/*echo $this->db->queries[0];die();	*/
				//sourabh 170105

				//Add Userworking sourabh 170117
		//$creFancyId=$this->db->insert_id();
		////start user working table save the data By Manish 170117
		//$wortype="OddEven fancy";
		//$remarks="Fancy Type>>".$_POST['fancyType'].">>Fancy Name >>".$_POST['HeadName'].">> Match ID >>".$_POST['mid'];
		//$userWrkingArray = array(
		//	'woruser'=> $_POST['HeadName'],
		//	'wormode'=> 1,
		//	'wordate'=> $_POST['date'],
		//	'wortype'=> $wortype,
		//	'worcode'=> $creFancyId,
		//	'worsysn'=> $_SERVER['REMOTE_ADDR'],
		//	'worrema'=> $remarks,
		//	'worcudt'=> date('Y-m-d H:i:s',now()),
		//);
		//$condition=$this->db->insert('userworkin', $userWrkingArray);
		////End of useworking table

					return false;
				}
				
			}

			function saveMatchMarketSelectAll(){

				foreach($_POST as $post){
					$chk=$this->chkMatchMarket($post['marketId']);
					if ($chk==0) {
						$this->db->trans_start();
						if($post['SportsId'] == '7'){
							$insertData = array('Id'=> $post['marketId'],'Name'=> $post['marketName'],'matchId'=> $post['MatchId'],'sportsId'=> $post['SportsId'],'totalmatched'=> $post['totalMatched'],
								'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> '210','HelperID'=> $post['HelperID'],'active' => 1);
						}
						else {
							$insertData = array('Id'=> $post['marketId'],'Name'=> $post['marketName'],'matchId'=> $post['MatchId'],'sportsId'=> $post['SportsId'],'totalmatched'=> $post['totalMatched'],
								'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> $post['seriesId'],'HelperID'=> $post['HelperID'],'active' => 1);
						}

						$MatchName=$this->getMatchNameById($post['MatchId']);
						$Name=$MatchName[0]->matchName;
						$query=$this->db->insert('market', $insertData);
						$GetMarket= $post['marketName']."__".$Name;
						$menuoption1 = array('menCode'=> $post['marketId'],'menName'=> $GetMarket,'menDesc'=> $GetMarket,'mstType'=> '8');
						$query1=$this->db->insert('menuoption', $menuoption1);
						$this->db->trans_complete();
					}else{
						$dataArray = array('active' => 1,'HelperID'=> $post['HelperID']);
						$this->db->where('Id', $post['marketId']);
						$this->db->update('market', $dataArray);
					}
				}
			}

			function saveMatchMarketDeSelectAll(){

				foreach($_POST as $post){
					$chk=$this->chkMatchMarket($post['marketId']);
					if ($chk==0) {
						$this->db->trans_start();
						if($post['SportsId'] == '7'){
							$insertData = array('Id'=> $post['marketId'],'Name'=> $post['marketName'],'matchId'=> $post['MatchId'],'sportsId'=> $post['SportsId'],'totalmatched'=> $post['totalMatched'],
								'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> '210','HelperID'=> $post['HelperID'],'active' => 0);
						}
						else {
							$insertData = array('Id'=> $post['marketId'],'Name'=> $post['marketName'],'matchId'=> $post['MatchId'],'sportsId'=> $post['SportsId'],'totalmatched'=> $post['totalMatched'],
								'createdOn'=>date('Y-m-d H:i:s',now()),'seriesId'=> $post['seriesId'],'HelperID'=> $post['HelperID'],'active' => 0);
						}

						$MatchName=$this->getMatchNameById($post['MatchId']);
						$Name=$MatchName[0]->matchName;
						$query=$this->db->insert('market', $insertData);
						$GetMarket= $post['marketName']."__".$Name;
						$menuoption1 = array('menCode'=> $post['marketId'],'menName'=> $GetMarket,'menDesc'=> $GetMarket,'mstType'=> '8');
						$query1=$this->db->insert('menuoption', $menuoption1);
						$this->db->trans_complete();
					}else{
						$dataArray = array('active' => 0,'HelperID'=> $post['HelperID']);
						$this->db->where('Id', $post['marketId']);
						$this->db->update('market', $dataArray);
					}
				}
			}

			function getMatchNameById($matchId){
				$this->db->select("matchName");
				$this->db->from('matchmst');
				$this->db->where('MstCode',$matchId);
				$query = $this->db->get();
				return $query->result();

			}
      		function saveMatchMarketType(){
				//echo $_POST['MatchId'];
				$chk=$this->chkMatchMarketType($_POST['marketType'],$_POST['MatchId']);
				if ($chk==0) {
					$insertData = array('Name'=> $_POST['marketType'],'marketCount'=> $_POST['marketCount'],'matchId'=> $_POST['MatchId'],'sportsId'=> $_POST['SportsId'],'createdOn'=>date('Y-m-d H:i:s',now()));
					$query=$this->db->insert('markettype', $insertData);
					return true;
				}else{
					return false;
				}
				

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
			function chkMatchMarketType($Name,$matchId){

					$this->db->select('Id');
					$this->db->from('markettype');
					$this->db->where('Name', $Name);
	          		$this->db->where('matchId', $matchId);
						$query = $this->db->get();
						$num=$query->num_rows();
						
						if($num==1){

							return $num;
						}else{

							return $num;
						}
			}
      		function chkMatchMarket($id){

					$this->db->select('Id');
					$this->db->from('market');
					$this->db->where('Id', $id);
					$query = $this->db->get();
					$num=$query->num_rows();
					
					if($num==1){

						return $num;
					}else{

						return $num;
					}
			}
			function chkSports($id){

					$this->db->select('id');
					$this->db->from('sportmst');
					$this->db->where('id', $id);
					$query = $this->db->get();
					$num=$query->num_rows();
					
					if($num==1){
						return $num;
						//return false;
					}
					else{
						return $num;
						//return true;
					}
			}
			function saveSportMatch($data){

				$chk=$this->chkSportsMatch($data['matchId']);

				if ($chk==0) {

					$Modeltblconfig = $this->model_load_model('Modeltblconfig');
					$configData = $Modeltblconfig->find();
					
					if(empty($data['scoreboard_id'])){
                        $data['scoreboard_id'] = 0;
					}


					if(!empty($configData)){
						$max_bet_liability = $configData[0]['max_bet_liability'];
						$max_market_liability = $configData[0]['max_market_liability'];
						$max_market_profit = $configData[0]['max_market_profit'];
					}else{
						$max_bet_liability = MAX_BET_LIABLITY;
						$max_market_liability = MAX_MARKET_LIABLITY;
						$max_market_profit = MAX_MARKET_PROFIT;
					}
                    //echo "<pre>";print_r($data);die;
					if(empty($data['marketName'])){
						return array('error' => 2 ,'message' => 'Something went wrong...');	
					}

					$this->db->trans_start();


					if($_POST['sportId'] == 7){ 
						$insertData = array('seriesId'=> '210','MstCode'=> $data['matchId'],'matchName'=> $data['matchName'],'MstDate'=> $data['openDate'],'startDate'=> $data['startDate'],'SportID'=> $data['sportId'],'countryCode'=> $data['countryCode'],'createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $data['HelperID'],'score_board_json'=>$data['score_board_json'],'scoreboard_id'=>$data['scoreboard_id']);
					}
					else{

						$insertData = array('seriesId'=>  $data['seriesId'],'MstCode'=> $data['matchId'],'matchName'=> $data['matchName'],'MstDate'=> $data['openDate'],'startDate'=> $data['startDate'],'SportID'=> $data['sportId'],'countryCode'=> '','marketCount'=> '','createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $data['HelperID'],'is_manual'=>$data['is_manual'],'score_board_json'=>json_encode($data['score_board_json']),'scoreboard_id'=>$data['scoreboard_id']);
					}

					
					$query=$this->db->insert('matchmst', $insertData);

					//echo $this->db->last_query();die;
					$menuoption1 = array('menCode'=> $data['matchId'],'menName'=> $data['matchName'],'menDesc'=> $data['matchName'],'mstType'=> '7');
					$query1=$this->db->insert('menuoption', $menuoption1); 


					$marketData = array('marketId'=>$data['marketId'],'marketName'=>$data['marketName'],'MatchId'=>$data['matchId'],'SportsId'=>$data['sportId'],'HelperID'=>$data['HelperID'],'seriesId'=>$data['seriesId'],'max_bet_liability'=>$max_bet_liability,'max_market_liability'=>$max_market_liability,'max_market_profit'=>$max_market_profit,'isManualMatchOdds'=>$data['isManualMatchOdds'],'market_runner_json'=>$data['market_runner_json']);

					$this->saveMatchMarket($marketData);

					$selectionData = array();
					if($data['is_manual']){
                        if(!empty($data['runnerName1'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId1'],'runnerName'=>$data['runnerName1']);
                        }
                    }else{
                        if(!empty($data['selectionId1'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId1'],'runnerName'=>$data['runnerName1']);
                        }
                    }

                    if($data['is_manual']){
                        if(!empty($data['runnerName2'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId2'],'runnerName'=>$data['runnerName2']);
                        }
                    }else{
                        if(!empty($data['selectionId2'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId2'],'runnerName'=>$data['runnerName2']);
                        }
                    }

                    if($data['is_manual']){
                        if(!empty($data['runnerName3'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId3'],'runnerName'=>$data['runnerName3']);
                        }
                    }else{
                        if(!empty($data['selectionId3'])){
                            $selectionData[] = array('selectionId'=>$data['selectionId3'],'runnerName'=>$data['runnerName3']);
                        }
                    }
	
					$selectionJson = json_encode($selectionData);

					$this->SaveSelection($selectionJson,$data['matchId'],$data['sportId'],$data['marketId']);

					$this->db->trans_complete();
					 
					return array('error' => 0 ,'message' => 'Record Added Successfully...');
					//return true;
				}
				else{

				//sourabh 170105
					$GetpId=$this->Get_MatchActive( $data['matchId']);
					$Active=$GetpId[0]->active;
					if($Active==0)
						$dataArray = array('active' => 1,'HelperID'=> $data['HelperID'],'MstDate'=> $data['openDate'],'startDate'=> $data['startDate']);
					else
						$dataArray = array('active' => 0,'HelperID'=> $data['HelperID'],'MstDate'=> $data['openDate'],'startDate'=> $data['startDate']);

    				$this->db->where('MstCode', $data['matchId']);
					$this->db->update('matchmst', $dataArray);
					
					 
					if($Active==0)
					{
						return array('error' => 0 ,'message' => 'Record Added Successfully...');
					//	return $Active = 0;
					}
					else
					{
						return array('error' => 1 ,'message' => 'Record Deactive Successfully...');
					//	return $Active = 1;
					}
				}	
			}

			function saveSportMatchSelectAll(){

				foreach($_POST as $post){
					$chk=$this->chkSportsMatch($post['matchId']); 
					if ($chk==0) {
						$this->db->trans_start(); 
						if($post['sportId'] == 7){ 
							$insertData = array('seriesId'=> '210','MstCode'=> $post['matchId'],'matchName'=> $post['matchName'],'MstDate'=> $post['openDate'],'SportID'=> $post['sportId'],'countryCode'=> $post['countryCode'],'createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $post['HelperID']);
						}else{
							$insertData = array('seriesId'=>  $post['seriesId'],'MstCode'=> $post['matchId'],'matchName'=> $post['matchName'],'MstDate'=> $post['openDate'],'SportID'=> $post['sportId'],'countryCode'=> $post['countryCode'],'marketCount'=> $post['marketCount'],'createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $post['HelperID']);
						}

						$query=$this->db->insert('matchmst', $insertData);
						$menuoption1 = array('menCode'=> $post['matchId'],'menName'=> $post['matchName'],'menDesc'=> $post['matchName'],'mstType'=> '7');
						$query1=$this->db->insert('menuoption', $menuoption1); 
						$this->db->trans_complete();

					}else{
						$dataArray = array('active' => 1,'HelperID'=> $post['HelperID'],'MstDate'=> $post['openDate']);
						$this->db->where('MstCode', $post['matchId']);
						$this->db->update('matchmst', $dataArray);
					}	
				}
			}

			function saveSportMatchDeSelectAll(){

				foreach($_POST as $post){
					$chk=$this->chkSportsMatch($post['matchId']); 
					if ($chk==0) {
						$this->db->trans_start(); 
						if($post['sportId'] == 7){ 
							$insertData = array('active' => 0,'seriesId'=> '210','MstCode'=> $post['matchId'],'matchName'=> $post['matchName'],'MstDate'=> $post['openDate'],'SportID'=> $post['sportId'],'countryCode'=> $post['countryCode'],'createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $post['HelperID']);
						}else{
							$insertData = array('seriesId'=>  $post['seriesId'],'MstCode'=> $post['matchId'],'matchName'=> $post['matchName'],'MstDate'=> $post['openDate'],'SportID'=> $post['sportId'],'countryCode'=> $post['countryCode'],'marketCount'=> $post['marketCount'],'createdOn'=>date('Y-m-d H:i:s',now()),'HelperID'=> $post['HelperID']);
						}

						$query=$this->db->insert('matchmst', $insertData);
						$menuoption1 = array('menCode'=> $post['matchId'],'menName'=> $post['matchName'],'menDesc'=> $post['matchName'],'mstType'=> '7');
						$query1=$this->db->insert('menuoption', $menuoption1); 
						$this->db->trans_complete();
					}else{
						$dataArray = array('active' => 0,'HelperID'=> $post['HelperID'],'MstDate'=> $post['openDate']);
						$this->db->where('MstCode', $post['matchId']);
						$this->db->update('matchmst', $dataArray);
					}	
				}
			}
			
			function chkSportsMatch($id){

					$this->db->select('MstCode');
					$this->db->from('matchmst');
					$this->db->where('MstCode', $id);
					$query = $this->db->get();
					$num=$query->num_rows();

					if($num==1){
						return $num;
					}
					else{
						return $num;
					}
			}
			function saveSportSeries()//sourabh 9-dec-2016
			{
                if(isset($_POST['is_manual']) && $_POST['is_manual']==1){
                    $seriesId = time();
                    $is_manual= 1;
                }else{
                    $seriesId = $_POST['matchId'];
                    $is_manual= 0;
                }
				$chk=$this->chkSportsSeries($seriesId);
				
				if ($chk==0) {
					$this->db->trans_start();


					$insertData = array('seriesId'=> $seriesId,'Name'=> $_POST['matchName'],'mCount'=> '','region'=> '','SportID'=> $_POST['sportId'],'HelperID'=> $_POST['HelperID'],'is_manual'=>$is_manual);
					$query=$this->db->insert('seriesmst', $insertData);

					$menuoption1 = array('menCode'=> $seriesId,'menName'=> $_POST['matchName'],'menDesc'=> $_POST['matchName'],'mstType'=> '6');
					$query1=$this->db->insert('menuoption', $menuoption1);
					$this->db->trans_complete();  
					
				}else{

					$GetpId=$this->Get_SeriesActive( $_POST['matchId']);
					$Active=$GetpId[0]->active;
					if($Active==0){
                        $dataArray = array('active' => 1,'HelperID'=> $_POST['HelperID']);

                    }else
						$dataArray = array('active' => 0,'HelperID'=> $_POST['HelperID']);
                        $this->db->where('seriesId', $_POST['matchId']);
                        $this->db->update('matchmst', ['active'=>0]);

    				$this->db->where('seriesId', $_POST['matchId']);
				    $this->db->update('seriesmst', $dataArray);


                    if($Active==0)
					{
						return $Active = 0;
					}
					else
					{
						return $Active = 1;
					}
				
				}	
			}

			function saveAllCheckboxSportSeries(){
				foreach($_POST as $post){
					$chk=$this->chkSportsSeries($post['matchId']);
					if ($chk==0) {
						$this->db->trans_start();
						$insertData = array('seriesId'=> $post['matchId'],'Name'=> $post['matchName'],'mCount'=> $post['marketCount'],'region'=> $post['region'],'SportID'=> $post['sportId'],'HelperID'=> $post['HelperID']);
						$query=$this->db->insert('seriesmst', $insertData);

						$menuoption1 = array('menCode'=> $post['matchId'],'menName'=> $post['matchName'],'menDesc'=> $post['matchName'],'mstType'=> '6');
						$query1=$this->db->insert('menuoption', $menuoption1);
						$this->db->trans_complete();  

					}else{
						$dataArray = array('active' => 1,'HelperID'=> $post['HelperID']); 
						$this->db->where('seriesId', $post['matchId']);
						$this->db->update('seriesmst', $dataArray);  
					}
				}
			}

			function saveSportSeriesDeSelectAll(){
				foreach($_POST as $post){
					$chk=$this->chkSportsSeries($post['matchId']);
					if ($chk==0) {
						$this->db->trans_start();
						$insertData = array('active' => 0 ,'seriesId'=> $post['matchId'],'Name'=> $post['matchName'],'mCount'=> $post['marketCount'],'region'=> $post['region'],'SportID'=> $post['sportId'],'HelperID'=> $post['HelperID']);
						$query=$this->db->insert('seriesmst', $insertData);

						$menuoption1 = array('menCode'=> $post['matchId'],'menName'=> $post['matchName'],'menDesc'=> $post['matchName'],'mstType'=> '6');
						$query1=$this->db->insert('menuoption', $menuoption1);
						$this->db->trans_complete();  

					}else{

						$dataArray = array('active' => 0,'HelperID'=> $post['HelperID']); 
						$this->db->where('seriesId', $post['matchId']);
						$this->db->update('seriesmst', $dataArray);  
					}	
				}
			}

			 function Get_SeriesActive($id){
				$this->db->select('active');
				$this->db->from('seriesmst');
				$this->db->where('seriesId', $id);
				$query = $this->db->get();
				return $query->result();
			}
			function chkSportsSeries($id)//sourabh 9-dec-2016
			{
					$this->db->select('seriesId');
					$this->db->from('seriesmst');
					$this->db->where('seriesId', $id);
					$query = $this->db->get();
					$num=$query->num_rows();

					if($num==1){
						return $num;
						//return false;
					}
					else{
						return $num;
						//return true;
					}
			}
            function getAllSports(){
                $horseRiding = 7;
                $this->db->select('*');
                $this->db->from('sportmst');
                $this->db->where('id !=',$horseRiding);
                $query = $this->db->get();
                return $query->result_array();
            }

            function updateSportById($id,$status){
                $dataArray = array('active' => $status);
                $this->db->where('id', $id);
                if($this->db->update('sportmst', $dataArray)){
                    return true;
                }else{
                    return false;
                }
            }
            function updateMarketById($id,$status){
                $dataArray = array('visibility' => $status);
                $this->db->where('Id', $id);
                if($this->db->update('market', $dataArray)){

                    return true;
                }else{
                    return false;
                }

            }
			function getSportData(){
				$horseRiding = 7;
				$this->db->select('id,name,active');
				$this->db->from('sportmst');			
				$this->db->where('id !=',$horseRiding);			
				$query = $this->db->get();
				return $query->result_array();
			}

			function getMatchLst($sportId,$seriesId,$userId){

				$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
				$userData = $Modelcreatemaster->userParent($userId);

				$dealerId = $userData['dealer_id'];
				$masterId = $userData['master_id'];
				$usetype = $userData['usetype'];

				if($sportId==0){$condition="";$condition1="";$condition2="";}
				else{
					$condition=$this->db->where('mtchMst.SportID', $sportId);
					if($seriesId!=0)$condition2=$this->db->where('mtchMst.seriesId', $seriesId);
					$condition1=$this->db->where('mtchMst.active', 1);
				}

					$this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.scoreboard_id,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.active,mtchMst.SportID,mtchMst.MstCode, DATE_FORMAT(mtchMst.MstDate,'%d-%m-%Y') as date ,mtchMst.minStack,mtchMst.maxStack");
			$this->db->from('matchmst mtchMst');
			$this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');

                $this->db->join('market mar','mtchMst.MstCode=mar.matchId ', 'INNER');
                $this->db->join('tblresult r','mar.Id=r.marketId', 'LEFT');

                $this->db->where('mar.Name',"Match Odds");
                $this->db->where('r.result is null');

			if($usetype==3){
				$this->db->join('user_deactive_match ddm', "mtchMst.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
				$this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
				$this->db->where('ddm.id IS NULL');
				$this->db->where('mdm.id IS NULL');
			}elseif($usetype==2){
				$this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
				$this->db->where('mdm.id IS NULL');
			}
			$condition;
			$condition1;
			$condition2;
			$this->db->order_by("matchName asc");
			$query = $this->db->get();
			 //echo "<pre>"; print_r($this->db->queries);die();
			return $query->result_array();
			}

            function getMatchLstForMatchSetting($sportId,$seriesId,$userId,$fromDate=0,$toDate=0){

                $Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
                $userData = $Modelcreatemaster->userParent($userId);

                $dealerId = $userData['dealer_id'];
                $masterId = $userData['master_id'];
                $usetype = $userData['usetype'];

                if($sportId==0){$condition="";$condition1="";$condition2="";}
                else{
                    $condition=$this->db->where('mtchMst.SportID', $sportId);
                    if($seriesId!=0)$condition2=$this->db->where('mtchMst.seriesId', $seriesId);
                    //$condition1=$this->db->where('mtchMst.active', 1);
                }
                if($fromDate!=0 and $fromDate!='undefined'){
                    $this->db->where("date_format(str_to_date(mtchMst.MstDate, '%Y-%m-%d'), '%Y-%m-%d') >=",$fromDate );
                }
                if($toDate!=0 and $toDate!='undefined'){
                    $this->db->where("date_format(str_to_date(mtchMst.MstDate, '%Y-%m-%d'), '%Y-%m-%d') <=",$toDate );
                }
                $this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.active,mtchMst.SportID,mtchMst.MstCode,sersmst.Name as seriesName, date_format(CONVERT_TZ(mtchMst.MstDate,'+00:00','+5:30'), '%d-%m-%Y %H:%i') as matchDate");
                $this->db->from('matchmst mtchMst');
                $this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
                $this->db->join('seriesmst sersmst', 'sersmst.seriesId=mtchMst.seriesId', 'INNER');
                if($usetype==3){
                    $this->db->join('user_deactive_match ddm', "mtchMst.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
                    $this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
                    $this->db->where('ddm.id IS NULL');
                    $this->db->where('mdm.id IS NULL');
                }elseif($usetype==2){
                    $this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
                    $this->db->where('mdm.id IS NULL');
                }
                $condition;
                $condition1;
                $condition2;
                $this->db->order_by("matchDate asc");
                $query = $this->db->get();
                //echo "<pre>"; print_r($this->db->queries);die();
                return $query->result_array();
            }


            function getMasterMatchLst($userId=NULL){
				$this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.SportID,mtchMst.MstCode,(
			        CASE WHEN udm.id IS NULL
			            THEN 1
			            ELSE 0
			            END
			        ) AS active");
				$this->db->from('matchmst mtchMst');
				$this->db->join('user_deactive_match udm', "mtchMst.MstCode = udm.match_id AND udm.user_id = $userId", 'LEFT');
				$this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
				$this->db->where('mtchMst.active', 1);
				$this->db->order_by("matchName asc");
				$query = $this->db->get();
				// echo $this->db->queries[0];die();		
				return $query->result_array();
			}  

			function getDealerMatchLst($userId=NULL,$parentId=NULL){

				$this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.SportID,mtchMst.MstCode,(
			        CASE WHEN dealermatch.id IS NULL
			            THEN 1
			            ELSE 0
			            END
			        ) AS active");
				$this->db->from('matchmst mtchMst');
				$this->db->join('user_deactive_match mastermatch', "mtchMst.MstCode = mastermatch.match_id AND mastermatch.user_id = $parentId", 'LEFT');
				$this->db->join('user_deactive_match dealermatch', "mtchMst.MstCode = dealermatch.match_id AND dealermatch.user_id = $userId", 'LEFT');
				$this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
				$this->db->where('mastermatch.id IS NULL');
				$this->db->where('mtchMst.active', 1);
				$this->db->order_by("matchName asc");
				$query = $this->db->get();
				return $query->result_array();
			}  


			function getBettedMatchLst($sportId,$seriesId){
				if($sportId==0){$condition="";$condition1="";$condition2="";}
				else{
					$condition=$this->db->where('SportID', $sportId);
					if($seriesId!=0)$condition2=$this->db->where('seriesId', $seriesId);
					$condition1=$this->db->where('mtchMst.active', 1);
				}
				$this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.active,mtchMst.SportID,mtchMst.MstCode");
				$this->db->from('matchmst mtchMst');
				$this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
			//	$this->db->join('tblbets bets', 'mtchMst.MstCode=bets.MatchId', 'INNER');
				$condition;
				$condition1;
				$condition2;
				$this->db->group_by('mtchMst.MstCode'); 
				$this->db->order_by("matchName asc");
				$query = $this->db->get();
			     //echo $this->db->last_query();die();
				return $query->result_array();
			}

			


			function getSeriesLst($matchId)//sourabh 9-dec-2016
			{
				if($matchId==0){$condition="";$condition1="";}
				else{
					$condition=$this->db->where('SportID', $matchId);
					$condition1=$this->db->where('mtchMst.active', 1);
				}
			
				$this->db->select("mtchMst.seriesId,mtchMst.Name,mtchMst.region,mtchMst.mCount,mtchMst.SportID,spmst.name as sportname,mtchMst.active");
				$this->db->from('seriesmst mtchMst');
				$this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
				$condition;
				$condition1;
				
				//$this->db->where('active', 1);
				$this->db->order_by("Name asc");
				$query = $this->db->get();
				//echo $this->db->last_query();die;
				return $query->result_array();
			}
			function getMarketLst(){ //Manish 20-03-2017				
				
				$this->db->select("seriesMst.Name,mtcmst.matchName,seriesMst.active,mrkt.visibility,mrkt.Name as MarketName,mrkt.Id as MarketId,mrkt.gin_play_stake as stake,mrkt.max_bet_liability,mrkt.max_market_liability,mrkt.max_market_profit");
				$this->db->from('seriesmst seriesMst');
				$this->db->join('matchmst mtcmst', 'mtcmst.seriesId=seriesMst.seriesId', 'INNER');
				$this->db->join('market mrkt', 'mrkt.matchId=mtcmst.MstCode', 'INNER');
				$this->db->join('tblresult res', 'res.marketId=mrkt.Id', 'LEFT');
                $this->db->where('res.result is null');
				$this->db->where('seriesMst.active', 1);

				$this->db->order_by("Name asc");
				$query = $this->db->get();
				//echo $this->db->last_query();die;
				return $query->result_array();
			}
			function matchMarketTypeLst(){
				//print_r($_POST);die();

				$this->db->select("*");
				$this->db->from('markettype');
				$this->db->where('sportsId', $_POST['sportsId']);
				$this->db->where('matchId', $_POST['matchId']);
				$this->db->where('active', 1);
				
				$query = $this->db->get();
				/*echo $this->db->queries[0];die();		*/
				return $query->result_array();
			}
      		function matchMarketLst(){
				/*//print_r($_POST);die();

				$this->db->select("*");
				$this->db->from('market');
				$this->db->where('sportsId', $_POST['sportsId']);
				$this->db->where('matchId', $_POST['matchId']);
				$this->db->where('active', 1);
				
				$query = $this->db->get();
				//echo $this->db->queries[0];die();		
				return $query->result_array();*/
				$sportId=$_POST['sportsId'];
				$matchId=$_POST['matchId'];
				$user_id=$_POST['user_id'];
				$query =$this->db->query("call GetMarket($sportId,$matchId,$user_id)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}

			function matchMarketLstPublic(){
				$sportId=$_POST['sportsId'];
				$matchId=$_POST['matchId'];
				$query =$this->db->query("call GetMarketPublic($sportId,$matchId)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}
			function mbdip_matchFancyList($matchId,$userId){				
				$query =$this->db->query("call getFancyList($matchId,$userId)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}


			function mbdip_adminFancyList($matchId,$userId){

                try {

                    $redis = new Redis();
                    $redis->connect(REDIS_UN_MATCH_BET_SERVER, 6379);
                    $key = $this->db->database.'ind_' . $matchId . '*';
                    $data = $redis->mget($redis->keys($key));
                    $sessionOdds = array_map(function ($a){
                        return json_decode($a,true);
                    },$data);
                    $redis->close();

                } catch (Exception $e) {
                    $sessionOdds= [];
                }


                return $sessionOdds;
			}

			 


			function matchFancyList(){
				//$sportId=$_POST['sportsId'];
				$matchId=$_POST['matchId'];
				$user_id=$_POST['user_id'];
				
				$query =$this->db->query("call getFancyList($matchId,$user_id)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
				/*$this->db->select("MatchID,HeadName,ID,TypeID,SprtId");
				$this->db->from('matchfancy');
				$this->db->where('matchId', $_POST['matchId']);
				if($onlySession==1){
                    $this->db->where('TypeID',2);
                    $this->db->where('active!=','2');
                }
				else{
                    $this->db->where('active!=','2');
                }
				$this->db->order_by("ID desc");
				$query = $this->db->get();
				return $query->result_array();*/
			}
			function matchFancyListPublic(){
				
				$matchId=$_POST['matchId'];
				$query =$this->db->query("call getFancyListPublic($matchId)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}

			function matchFancyListPublicByMatchId($matchId=0){
				$query =$this->db->query("call getFancyListPublic($matchId)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}
            function SessionFancyData(){
                $this->db->select("*,MFancyID as FncyId");
                $this->db->from('matchfancy');
                /*$this->db->where('sportsId', $_POST['sportsId']);*/
                $this->db->where('matchId', $_POST['matchId']);
                $this->db->where('TypeID',2);//sourabh 170107
                $this->db->where('active<>',2);
				$this->db->where('result',NULL);
                $query = $this->db->get();
                return $query->result_array();
            }
			function SaveSelection($selectionData,$matchId,$sportId,$MarketId){
				/*print_r($selectionData);
				die();*/
				$arr = json_decode($selectionData, true);
				/* $arrne['runnerName'] = "dsds";*/
				$index = 1;
				foreach ($arr as $key) {
					//echo $key['runnerName'];

					$file_data = array(	
								'sportId' => $sportId,
								'matchId' => $matchId,
								'marketId' => $MarketId,
								'selectionId' => $key['selectionId'],
								'selectionName' => $key['runnerName'],
								'teamType' => $index
							);
					$this->db->insert('tblSelection', $file_data);

					$index++;
				}
				return true;
				

			}
			function GetSelectionFrmDatabase($marketId){
				$this->db->select("*");
				$this->db->from('tblSelection');
				$this->db->where('marketId', $marketId);
				$query = $this->db->get();
				return $query->result_array();
			}
			function SetResult(){
				$sportsId=$_POST['Sport_id'];
				$Match_id=$_POST['Match_id'];
				$market_id=$_POST['market_id'];
				$selectionId=$_POST['selectionId'];
				$result=1;
				$isFancy=$_POST['isFancy'];
				$sportName=$_POST['sportName'];
				$matchName=$_POST['matchName'];
				$MarketName=$_POST['MarketName'];
				$selectionName=$_POST['selectionName'];		
				$selectionName = str_replace("'"," ",$selectionName);	
					
				$query = $this->db->query("call SP_SetResult($sportsId,$Match_id,$market_id,'$selectionId',$isFancy,$result,'$sportName','$matchName','$MarketName','$selectionName')");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();

                $query1=$this->db->query("call p_l_by_match($Match_id)");
				return $res;
				//print_r($res);
			}

			function SetAbandonedResult(){
				$sportsId=$_POST['Sport_id'];
				$Match_id=$_POST['Match_id'];
				$market_id=$_POST['market_id'];
				$selectionId=$_POST['selectionId'];
				$result=1;
				$isFancy=$_POST['isFancy'];
				$sportName=$_POST['sportName'];
				$matchName=$_POST['matchName'];
				$MarketName=$_POST['MarketName'];
                $selectionName=$_POST['selectionName'];
                $selectionName = str_replace("'"," ",$selectionName);

				$query = $this->db->query("INSERT INTO `tblabandonedbets` (`MstCode`,`LogInId`,`UserId`,`ParantId`,`MatchId`,`MarketId`,`SelectionId`,`selectionName`,`MstDate`,`MatchedDate`,`Odds`,`Stack`,`P_L`,`isBack`,`IsMatched`,`Result`,`ResultID`,`IP_ADDESSS`,`deviceInfo`,`SAdmin`,`Admin`,`Master`,`Dealer`,`Chips`,`IsInPlay`,`isApp`,`type`) SELECT * FROM `tblbets` where MatchId = $Match_id AND MarketId = $market_id;");

				$query = $this->db->query("INSERT INTO `tblresult` (`sportId`, `matchId`, `marketId`, `date`, `isFancy`, `selectionId`, `result`) VALUES ($sportsId,$Match_id,$market_id,NOW(),0,$selectionId,$result);");

				$query = $this->db->query("DELETE FROM `tblbets` WHERE  MatchId = $Match_id AND MarketId = $market_id;");			

				if($MarketName=='Match Odds'){
					$query = $this->db->query("UPDATE matchmst SET active = 0 WHERE SportID = $sportsId AND MstCode = $Match_id ;");
				}		

				$query = $this->db->query("UPDATE market SET active = 0 WHERE sportsId = $sportsId AND matchId = $Match_id AND Id = $market_id ;");			
				
				return true;
				//print_r($res);
			}

			function SetAbandonedSessionResult($data){ 

				$cmModel = $this->model_load_model('Modelcreatemaster');	
				
				$sportId = $data['sportId'];
				$match_id = $data['match_id'];
				$fancy_Id = $data['fancy_Id'];

				$this->load->model('Betentrymodel');
				$userIds = $this->Betentrymodel->getUsersMatchSession($match_id,$fancy_Id);
		
				$query = $this->db->query("INSERT INTO `abandoned_bet_entry` (`bet_id`,`bet_value`,`OddValue`,`OddsNumber`,`TypeID`,`matchId`,`fancyId`,`parantId`,`userId`,`dateTime`,`loginid`,`ResultID`,`fncy_refId`,`FheadName`,`SessInpYes`,`SessInpNo`,`sportId`,`ponitDiff`,`IP_ADDRESS`,`DeviceDesc`,`SAdmin`,`Admin`,`Master`,`Dealer`,`Chips`,`session_no_size`,`session_yes_size`) SELECT `bet_id`,`bet_value`,`OddValue`,`OddsNumber`,`TypeID`,`matchId`,`fancyId`,`parantId`,`userId`,`dateTime`,
					`loginid`,`ResultID`,`fncy_refId`,`FheadName`,`SessInpYes`,`SessInpNo`,`sportId`,`ponitDiff`,`IP_ADDRESS`,`DeviceDesc`,`SAdmin`,
					`Admin`,`Master`,`Dealer`,`Chips`,`session_no_size`,`session_yes_size` FROM `bet_entry` where matchId = $match_id AND fancyId = $fancy_Id;");

				$query = $this->db->query("INSERT INTO tblresult (sportId, matchId, DATE, isFancy, selectionId, result) VALUES ($sportId, $match_id, (NOW() + INTERVAL '5:30' HOUR_MINUTE), 1, $fancy_Id, -1);");

				$query = $this->db->query("DELETE FROM `bet_entry` WHERE  matchId = $match_id AND fancyId = $fancy_Id;");	
			
				$query = $this->db->query("UPDATE matchfancy SET result = -1, active = 2, DisplayMsg = 'Close Fancy ' WHERE SprtId = $sportId AND MatchID = $match_id AND ID = $fancy_Id;");			

				foreach($userIds as $uid){
					$cmModel->updateUserBalLiablity($uid);
				}
				
				return true;
				
			}
			

			function SetMatchResult($data){

				$sportsId=$data['Sport_id'];
				$Match_id=$data['Match_id'];
				$market_id=$data['market_id'];
				$selectionId=$data['selectionId'];
				$result=1;
				$isFancy=$data['isFancy'];
				$sportName=$data['sportName'];
				$matchName= str_replace("'"," ",$data['matchName']);
				$MarketName= str_replace("'"," ",$data['MarketName']);
                $selectionName= str_replace("'"," ",$_POST['selectionName']);
                $selectionName = str_replace("'"," ",$selectionName);

				$query = $this->db->query("call SP_SetResult($sportsId,$Match_id,$market_id,'$selectionId',$isFancy,$result,'$sportName','$matchName','$MarketName','$selectionName')");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();

				$query1=$this->db->query("call p_l_by_match($Match_id)");
				return $res;
				//print_r($res);
			}

			function GetMatchOddsResult(){
				 
				/*$this->db->select("mtchmst.matchName as MatchName,mrkt.Name as MarketName,sprt.name as sportName ,slectName.selectionName as SelectionName,tblres.date,tblres.result,tblres.resId,tblres.sportId,tblres.matchId,tblres.marketId");
				$this->db->from('tblresult tblres');
				$this->db->join('matchmst mtchmst', 'tblres.matchId=mtchmst.MstCode', 'INNER');
				$this->db->join('market mrkt', 'tblres.marketId=mrkt.id', 'INNER');
				$this->db->join('tblSelection slectName', 'tblres.selectionId=slectName.selectionId and tblres.marketId=slectName.marketId and slectName.matchId=tblres.matchId', 'INNER');
				$this->db->join('sportmst sprt', 'sprt.id= tblres.sportId', 'INNER');
				$this->db->where('tblres.result', 1);

				$query = $this->db->get();
				//echo $this->db->queries[0];
				return $query->result_array();*/
				$query = $this->db->query("call GetResult()");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}
			function DeleteMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy){
                if($isFancy==1){
                    //echo "call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,$selectionId)";
                    $query = $this->db->query("call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,$selectionId)");
                }
                else
                {
                   //echo "call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,0)";
                   $query = $this->db->query("call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,0)");
                }
                $res = $query->result_array();
                $query->next_result();
                $query->free_result();
                return $res;

			}

			function DeleteAbandonedMatchResult($resId,$sportId,$matchId,$marketId,$selectionId,$isFancy){
				
                if($isFancy==1){
 					
					$pFancyID = $selectionId;
			
					$query = $this->db->query("INSERT INTO `bet_entry` (`bet_id`,`bet_value`,`OddValue`,`OddsNumber`,`TypeID`,`matchId`,`fancyId`,`parantId`,`userId`,`dateTime`,`loginid`,`ResultID`,`fncy_refId`,`FheadName`,`SessInpYes`,`SessInpNo`,`sportId`,`ponitDiff`,`IP_ADDRESS`,`DeviceDesc`,`SAdmin`,`Admin`,`Master`,`Dealer`,`Chips`,`session_no_size`,`session_yes_size`) SELECT `bet_id`,`bet_value`,`OddValue`,`OddsNumber`,`TypeID`,`matchId`,`fancyId`,`parantId`,`userId`,`dateTime`,
						`loginid`,`ResultID`,`fncy_refId`,`FheadName`,`SessInpYes`,`SessInpNo`,`sportId`,`ponitDiff`,`IP_ADDRESS`,`DeviceDesc`,`SAdmin`,
						`Admin`,`Master`,`Dealer`,`Chips`,`session_no_size`,`session_yes_size` FROM `abandoned_bet_entry` where matchId = $matchId AND fancyId = $pFancyID");

					$query = $this->db->query("DELETE FROM `abandoned_bet_entry` WHERE matchId = $matchId AND fancyId = $pFancyID;");	

					$query = $this->db->query("DELETE FROM tblresult WHERE resid = $resId");

					$query = $this->db->query("UPDATE matchfancy SET result = NULL , active =4 , DisplayMsg ='Decision  Pending' WHERE MatchID = $matchId AND ID = $pFancyID;");			

					$query = $this->db->query("UPDATE matchmst SET active = 1 WHERE SportID = $sportId AND MstCode = $matchId ;");						
				
                //    echo "call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,$selectionId)";
                //    $query = $this->db->query("call SP_DelResult($sportId,$matchId,$marketId,$selectionId,$resId,$selectionId)");
                }
                else
                {
                	$query = $this->db->query("INSERT INTO `tblbets` (`MstCode`,`LogInId`,`UserId`,`ParantId`,`MatchId`,`MarketId`,`SelectionId`,`selectionName`,`MstDate`,`MatchedDate`,`Odds`,`Stack`,`P_L`,`isBack`,`IsMatched`,`Result`,`ResultID`,`IP_ADDESSS`,`deviceInfo`,`SAdmin`,`Admin`,`Master`,`Dealer`,`Chips`,`IsInPlay`,`isApp`,`type`) SELECT * FROM `tblabandonedbets` where MatchId = $matchId AND MarketId = $marketId;");

					$query = $this->db->query("DELETE FROM tblresult WHERE resid = $resId");

					$query = $this->db->query("DELETE FROM `tblabandonedbets` WHERE MatchId = $matchId AND MarketId = $marketId;");		

					$query = $this->db->query("UPDATE matchmst SET active = 1 WHERE SportID = $sportId AND MstCode = $matchId ;");

					$query = $this->db->query("UPDATE market SET active = 1 WHERE sportsId = $sportId AND matchId = $matchId AND Id = $marketId ;");		
                }
                
                return true;

			}


			function mobileGetUserMatchLst($sportId){

				$baseUrl = base_url();
				$cricketSocketUrl = BR_LIVE_CRICKET_SOCKET_URL;
				$soccerSocketUrl = BR_LIVE_SOCCER_SOCKET_URL;
				$tennisSocketUrl = BR_LIVE_TENNIS_SOCKET_URL;

				$selectQuery = "mf.matchName,series.Name as series_name,mf.MstCode as matchid,GROUP_CONCAT(mrkt.Id) as marketid,ROUND(UNIX_TIMESTAMP(mf.MstDate)) as matchdate,sm.name as sportname,sm.id as SportId";

				if($sportId==4){
					$selectQuery .= ",'$cricketSocketUrl"."' socket_url,'$baseUrl"."uploads/cricket.png' sport_image";	
				}elseif ($sportId==2) {
					$selectQuery .= ",'$tennisSocketUrl"."' socket_url,'$baseUrl"."uploads/tennis.png' sport_image";	
				}elseif ($sportId==1) {
					$selectQuery .= ",'$soccerSocketUrl"."' socket_url,'$baseUrl"."uploads/soccer.png' sport_image";	
				}

				$this->db->select($selectQuery);
				$this->db->from('matchmst mf');
				$this->db->join('market ma','ma.matchId=mf.MstCode', 'INNER');
				$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'INNER');
				$this->db->join('seriesmst series', 'series.seriesId=mf.seriesId', 'INNER');
				$this->db->join('market mrkt', 'mrkt.matchId = mf.MstCode', 'INNER');

				if($sportId!=0){
					$this->db->where('mf.SportID', $sportId);	
				}
				$this->db->where('mf.active', 1);

				$this->db->where('ma.Name', 'Match Odds');
				$this->db->order_by("mf.matchName asc,mf.MstDate asc");
				$this->db->group_by("mrkt.matchId");
				$query = $this->db->get();
				return $query->result_array();

			}



			function mobileGetUserFavouriteMatchLst($sportId,$userId,$seriesId=0){

				$Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
				$userData = $Modelcreatemaster->userParent($userId);

				$dealerId = $userData['dealer_id'];
				$masterId = $userData['master_id'];
				$usetype = $userData['usetype'];

				$baseUrl = base_url();
				$cricketSocketUrl = BR_LIVE_CRICKET_SOCKET_URL;
				$soccerSocketUrl = BR_LIVE_SOCCER_SOCKET_URL;
				$tennisSocketUrl = BR_LIVE_TENNIS_SOCKET_URL;

				$selectQuery = " CASE WHEN `my`.`id` is null THEN 0 else 1 END isfancy , mf.active ,mf.matchName,series.Name as series_name,mf.MstCode as matchid,mrkt.market_runner_json runner_json,mrkt.Id as marketid,mrkt.visibility,mrkt.max_bet_liability,mrkt.max_market_liability,mf.MstDate,ROUND(UNIX_TIMESTAMP(mf.MstDate)) as matchdate,DATEDIFF(DATE_ADD(mf.MstDate,INTERVAL 330 MINUTE),DATE_ADD(mf.startDate,INTERVAL 330 MINUTE))+1 as day,sm.name as sportname,(
			        CASE WHEN fav_mrkt.id IS NULL
			            THEN 'N'
			            ELSE 'Y'
			            END
			        ) AS is_favourite,sm.id as SportId,mf.is_manual,mrkt.isBetAllowedOnManualMatchOdds ";

				if($sportId==4){
					$selectQuery .= ",'$cricketSocketUrl"."' socket_url,'$baseUrl"."uploads/cricket.png' sport_image";	
				}elseif ($sportId==2) {
					$selectQuery .= ",'$tennisSocketUrl"."' socket_url,'$baseUrl"."uploads/tennis.png' sport_image";	
				}elseif ($sportId==1) {
					$selectQuery .= ",'$soccerSocketUrl"."' socket_url,'$baseUrl"."uploads/soccer.png' sport_image";	
				}

				$this->db->select($selectQuery,false);
				$this->db->from('market mrkt');
				$this->db->join('matchmst mf', "mrkt.matchId = mf.MstCode", 'LEFT');
				$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'LEFT');
				$this->db->join('matchfancy my', 'my.MatchID=mrkt.matchId', 'LEFT');
				$this->db->join('seriesmst series', 'series.seriesId=mf.seriesId', 'LEFT');
				$this->db->join('favourite_market fav_mrkt', "mrkt.Id = fav_mrkt.market_id AND fav_mrkt.user_id = $userId", 'LEFT');
				if($usetype==3){
					$this->db->join('user_deactive_match ddm', "mf.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
					$this->db->join('user_deactive_match mdm', "mf.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
					$this->db->where('ddm.id IS NULL');
					$this->db->where('mdm.id IS NULL');
                    $this->db->where('sm.active', 1);
                    $this->db->where('mf.completed', 0);
				}elseif($usetype==2){
					$this->db->join('user_deactive_match mdm', "mf.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
					$this->db->where('mdm.id IS NULL');
				}

				if($sportId!=0){
					$this->db->where('mf.SportID', $sportId);	
				}
				if($seriesId!=0){
				    //var_dump($seriesId);die;
                    $this->db->where('mf.seriesId', $seriesId);
                }
				$this->db->where('mrkt.active', 1);

				$this->db->where('mf.active', 1);
				$this->db->where('mrkt.Name', 'Match Odds');
				$this->db->group_by("matchid");
				$this->db->order_by("mf.MstDate asc,mf.matchName asc");
			//	$this->db->group_by("mrkt.matchId");
			//	
				
				$query = $this->db->get();

				//echo $this->db->last_query();
				//die;
				return $query->result_array();

			}

			function mobileGetFavouriteMatchLst($sportId,$userId){

				$baseUrl = base_url();
				$cricketSocketUrl = BR_LIVE_CRICKET_SOCKET_URL;
				$soccerSocketUrl = BR_LIVE_SOCCER_SOCKET_URL;
				$tennisSocketUrl = BR_LIVE_TENNIS_SOCKET_URL;

				$selectQuery = "mf.matchName,series.Name as series_name,mf.MstCode as matchid,mrkt.market_runner_json as runner_json,mrkt.Id as marketid,mrkt.visibility,mrkt.Name as market_name,mrkt.max_bet_liability,mrkt.max_market_liability,mf.MstDate,ROUND(UNIX_TIMESTAMP(mf.MstDate)) as matchdate,DATEDIFF(DATE_ADD(mf.MstDate,INTERVAL 330 MINUTE),DATE_ADD(mf.startDate,INTERVAL 330 MINUTE))+1 as day,sm.name as sportname,(
			        CASE WHEN fav_mrkt.id IS NULL
			            THEN 'N'
			            ELSE 'Y'
			            END
			        ) AS is_favourite,sm.id as SportId,mf.is_manual";

				if($sportId==4){
					$selectQuery .= ",'$cricketSocketUrl"."' socket_url,'$baseUrl"."uploads/cricket.png' sport_image";	
				}elseif ($sportId==2) {
					$selectQuery .= ",'$tennisSocketUrl"."' socket_url,'$baseUrl"."uploads/tennis.png' sport_image";	
				}elseif ($sportId==1) {
					$selectQuery .= ",'$soccerSocketUrl"."' socket_url,'$baseUrl"."uploads/soccer.png' sport_image";	
				}

				$this->db->select($selectQuery,false);
				$this->db->from('market mrkt');
				$this->db->join('matchmst mf', 'mf.MstCode = mrkt.matchId', 'LEFT');
				$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'LEFT');
				$this->db->join('seriesmst series', 'series.seriesId=mf.seriesId', 'LEFT');
				$this->db->join('favourite_market fav_mrkt', "mrkt.Id = fav_mrkt.market_id AND fav_mrkt.user_id = $userId", 'LEFT');

				if($sportId!=0){
					$this->db->where('mf.SportID', $sportId);	
				}
				$this->db->where('mf.active', 1);
				$this->db->where('mrkt.active', 1);
                $this->db->where('sm.active', 1);
				$this->db->where('fav_mrkt.id IS NOT NULL');
				$this->db->order_by("mf.matchName asc,mf.MstDate asc");
			
				$query = $this->db->get();
				//echo $this->db->last_query();die;
				return $query->result_array();

			}

			function getMrktIds(){
				$this->db->select("GROUP_CONCAT(Id) as market_ids");
				$this->db->from('market');
				$this->db->where('market.active', 1);
				$this->db->order_by("createdOn DESC");
				$query = $this->db->get();
				return $query->row_array();
			}


			function mobileGetMrktByMatchId($matchId){
				$this->db->select("GROUP_CONCAT(mrkt.Id) as marketid");
				$this->db->from('matchmst mf');
				$this->db->join('market mrkt', 'mrkt.matchId = mf.MstCode', 'INNER');
				$this->db->where('mf.MstCode', $matchId);
				$this->db->where('mf.active', 1);
				$this->db->order_by("mf.matchName asc,mf.MstDate asc");
				$this->db->group_by("mrkt.matchId");
				$query = $this->db->get();
				return $query->result_array();
			}

			function mobileGetFavMrktByMatchId($matchId,$userId,$userType=null){
				$baseUrl = base_url();
				$cricketSocketUrl = BR_LIVE_CRICKET_SOCKET_URL;
				$soccerSocketUrl = BR_LIVE_SOCCER_SOCKET_URL;
				$tennisSocketUrl = BR_LIVE_TENNIS_SOCKET_URL; 

				$selectQuery = "mrkt.Id as marketid,mrkt.isManualMatchOdds,mrkt.isBetAllowedOnManualMatchOdds,mrkt.visibility,mrkt.Name as market_name,mrkt.max_bet_liability,mrkt.max_market_liability,mf.matchName,series.Name as series_name,mf.MstCode as matchid,mf.scoreboard_id as scoreboard_id,mrkt.market_runner_json as runner_json,mf.runner_json as match_runner_json ,mf.MstDate,ROUND(UNIX_TIMESTAMP(mf.MstDate)) as matchdate,DATEDIFF(DATE_ADD(mf.MstDate,INTERVAL 330 MINUTE),DATE_ADD(mf.startDate,INTERVAL 330 MINUTE))+1 as day,mrkt.IsRs,sm.name as sportname,(
			        CASE WHEN fav_mrkt.id IS NULL
			            THEN 'N'
			            ELSE 'Y'
			            END
			        ) AS is_favourite,sm.id as SportId,'$cricketSocketUrl"."' cricket_socket_url,'$baseUrl"."uploads/cricket.png' cricket_sport_image,'$tennisSocketUrl"."' tennis_socket_url,'$baseUrl"."uploads/tennis.png' tennis_sport_image,'$soccerSocketUrl"."' soccer_socket_url,'$baseUrl"."uploads/soccer.png' soccer_sport_image , mf.is_manual,mrkt.isBetAllowedOnManualMatchOdds , mf.score_board_json";

				$this->db->select($selectQuery,false);
				$this->db->from('market mrkt');
				$this->db->join('matchmst mf','mrkt.matchId = mf.MstCode','LEFT');
				$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'LEFT');
				$this->db->join('seriesmst series', 'series.seriesId = mf.seriesId', 'LEFT');
				$this->db->join('favourite_market fav_mrkt', "mrkt.Id = fav_mrkt.market_id AND fav_mrkt.user_id = $userId", 'LEFT');
				$this->db->where('mf.MstCode', $matchId);
				$this->db->where('mrkt.active', 1);
				$this->db->where('mf.active', 1);
				if($userType==3){
                    $this->db->where('mf.completed', 0);
                }
				$this->db->order_by("mf.matchName asc,mf.MstDate asc");
			//	$this->db->group_by("mrkt.matchId");
				$query = $this->db->get();
				//echo $this->db->last_query();die;
			//	die;

				return $query->result_array();
			}

			function mobileMrktByMatchId($matchId){
				$this->db->select("Id,Name");
				$this->db->from('market mrkt');
				$this->db->where('mrkt.active', 1);
				$this->db->where('mrkt.matchId', $matchId);
				$query = $this->db->get();
				return $query->result_array();
			}


            function getMatchLstBySportId($sportId,$userId){

                $Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
                $userData = $Modelcreatemaster->userParent($userId);

                $dealerId = $userData['dealer_id'];
                $masterId = $userData['master_id'];
                $usetype = $userData['usetype'];

                if($sportId==0){$condition="";$condition1="";$condition2="";}
                else{
                    $condition=$this->db->where('mtchMst.SportID', $sportId);
                    $condition1=$this->db->where('mtchMst.active', 1);
                }

                $this->db->select("mtchMst.volumeLimit,mtchMst.oddsLimit,mtchMst.matchName,mtchMst.countryCode,mtchMst.marketCount,mtchMst.MstDate,spmst.name,mtchMst.active,mtchMst.SportID,mtchMst.MstCode, DATE_FORMAT(mtchMst.MstDate,'%d-%m-%Y') as date , mk.Id as marketid");
                $this->db->from('matchmst mtchMst');
                $this->db->join('sportmst spmst', 'mtchMst.SportID=spmst.id', 'INNER');
                $this->db->join('market mk', 'mk.matchId=mtchMst.MstCode', 'INNER');
                if($usetype==3){
                    $this->db->join('user_deactive_match ddm', "mtchMst.MstCode = ddm.match_id AND ddm.user_id = $dealerId", 'LEFT');
                    $this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
                    $this->db->where('ddm.id IS NULL');
                    $this->db->where('mdm.id IS NULL');
                }elseif($usetype==2){
                    $this->db->join('user_deactive_match mdm', "mtchMst.MstCode = mdm.match_id AND mdm.user_id = $masterId", 'LEFT');
                    $this->db->where('mdm.id IS NULL');
                }
                $this->db->where('mk.Name' ,'Match Odds');
                $this->db->order_by("matchName asc");
                $query = $this->db->get();

                return $query->result_array();
            }

			function getUserMatchLst($sportId)//sourabh 14-dec-2016
			{
				//if($sportId==0){$condition="";$condition1="";}else  sourabh 170106
				if($sportId==-1){
					$this->db->select("mchmst.matchName,mf.HeadName,mf.TypeID,mf.MatchID as matchid,mf.ID as marketid,'' as MstDate,0 as oddsLimit,sm.name as sportname,mchmst.SportID as SportId");//sourabh 170106
					$this->db->from('matchfancy mf');
					$this->db->join('matchmst mchmst', 'mf.MatchID=mchmst.MstCode', 'INNER');
					$this->db->join('sportmst sm', 'mchmst.SportID=sm.id', 'INNER');//sourabh 170106
					$this->db->where('mchmst.active',1);
					//$this->db->where('mf.active',1);sourabh 170118
					$this->db->where('mf.active!=','2');//sourabh 170118
					$this->db->order_by("mf.ID desc");
					$query = $this->db->get();
					return $query->result_array();
				}
				else
				{
					if($sportId!=0)$condition=$this->db->where('mf.SportID', $sportId);//sourabh 170106
					else $condition="";//sourabh 170106
					$condition1=$this->db->where('mf.active', 1);
				
					$this->db->select("mf.matchName,'' HeadName,0 as TypeID,mf.MstCode as matchid,ma.Id as marketid,mf.MstDate,oddsLimit,sm.name as sportname,sm.id as SportId");//sourabh 170106
					$this->db->from('matchmst mf');
					$this->db->join('market ma','ma.matchId=mf.MstCode', 'INNER');//sourabh new 161216
					$this->db->join('sportmst sm', 'mf.SportID=sm.id', 'INNER');//sourabh 170106
					$condition;
					$condition1;
					
					$this->db->where('ma.Name', 'Match Odds');//sourabh new 161216
					$this->db->order_by("mf.matchName asc");//sourabh new 161216
					$query = $this->db->get();
					return $query->result_array();
				}
			}


			function getUserMatchOdds($sportId)//sourabh 161227
			{
					if($sportId!=0)//sourabh 170106
					$condition=$this->db->where('mf.SportID', $sportId);
				else
					$condition="";
					$condition1=$this->db->where('mf.active', 1);
				
					$this->db->select("GROUP_CONCAT(ma.Id) as ids");
					$this->db->from('matchmst mf');
					$this->db->join('market ma','ma.matchId=mf.MstCode', 'INNER');
					$condition;
					$condition1;
					$this->db->where('ma.Name', 'Match Odds');//sourabh new 161216
					$this->db->order_by("mf.matchName asc");//sourabh new 161216
					$query = $this->db->get();
					return $query->result_array();
			}

			function getUserAllMatchOdds($sportId)//sourabh 161227
			{
					if($sportId!=0)//sourabh 170106
					$condition=$this->db->where('mf.SportID', $sportId);
					else
					$condition="";
				
					$this->db->select("GROUP_CONCAT(ma.Id) as ids");
					$this->db->from('matchmst mf');
					$this->db->join('market ma','ma.matchId=mf.MstCode', 'INNER');
					$condition;
					$this->db->where('ma.Name', 'Match Odds');//sourabh new 161216
					$this->db->order_by("mf.matchName asc");//sourabh new 161216
					$query = $this->db->get();
					return $query->result_array();
			}

			function getUserMatchResult($useId,$usetype)//sourabh 14-dec-2016
			{
				
			//	echo "call sp_getHomeUserD_matches($useId,$usetype)";
                //$query =$this->db->query("call sp_getHomeUserPra($useId,$usetype)");
				$query =$this->db->query("call sp_getHomeUserD_matches($useId,$usetype)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}

			function mb_getUserMatchResult($useId,$usetype)//sourabh 14-dec-2016
			{
				$query =$this->db->query("call mb_sp_getHomeUserD_matches($useId,$usetype)");
				$res = $query->result_array();
				$query->next_result();
				$query->free_result();
				return $res;
			}
			function GetSeriesFrmDatabase(){
				$this->db->select("*");
				$this->db->from('seriesmst');
				$this->db->where('active', '1');
				$query = $this->db->get();
				return $query->result_array();
			}

            function GetApiSeriesFrmDatabase(){
                $this->db->select("SportID,seriesId");
                $this->db->from('seriesmst');
                $this->db->where('active', '1');
                $this->db->where('is_manual', '0');
                $query = $this->db->get();
                return $query->result_array();
            }

            function GetManualSeriesFrmDatabase(){
                $this->db->select("* , name SeriesName");
                $this->db->from('seriesmst');
                $this->db->where('is_manual', '1');
                $query = $this->db->get();
                return $query->result_array();
            }

			function GetMatchFrmDatabase(){//sourabh 170105
				$this->db->select("*");
				$this->db->from('matchmst');
				$this->db->where('active', '1');
				$query = $this->db->get();
				return $query->result_array();
			}

            function GetManualMatchFrmDatabase($sportId,$seriesId){//sourabh 170105
                $this->db->select("* ,matchName eventName ,MstCode eventId,MstDate eventDate,SportID EventTypeId");
                $this->db->from('matchmst');
                $this->db->where('is_manual', '1');
                $this->db->where('SportID', $sportId);
                $this->db->where('seriesId', $seriesId);
                $query = $this->db->get();
                return $query->result_array();
            }

            function GetMatchBySeriesId($id){//sourabh 170105
                $this->db->select("*");
                $this->db->from('matchmst');
                $this->db->where('active', '1');
                $this->db->where('seriesId',$id);
                $query = $this->db->get();
                return $query->result_array();
            }

            function GetMatchByMatchId($id){//sourabh 170105
                $this->db->select("*");
                $this->db->from('matchmst');
                $this->db->where('MstCode',$id);
                $query = $this->db->get();
                return $query->row_array();
            }

			 function Get_MatchActive($id){//sourabh 170105
				$this->db->select('active');
				$this->db->from('matchmst');
				$this->db->where('MstCode', $id);
				$query = $this->db->get();
				return $query->result();
			}
			function GetMarketFrmDatabase(){//sourabh 170105
				$this->db->select("*");
				$this->db->from('market');
				$this->db->where('active', '1');
				$query = $this->db->get();
				return $query->result_array();
			}
			 function Get_MarketActive($id){//sourabh 170105
				$this->db->select('active');
				$this->db->from('market');
				$this->db->where('Id', $id);
				$query = $this->db->get();
				return $query->result();
			}

            function GetCollectionReport($userId){

                $Modelcreatemaster = $this->model_load_model('Modelcreatemaster');
                $get_child_users = $Modelcreatemaster->getChildUsers($userId);

                $child_users = array();
                $final_data['receivingFrom'] = $final_data['paidTo'] = $final_data['clear'] = array();
                $final_data['totalReceivingFrom'] = $final_data['totalPaidTo'] = 0;
                if(!empty($get_child_users)){

                    foreach ($get_child_users as $child) {
                        $child_users[] = $child['mstrid'];
                    }

                    $this->db->select("mstrid, mstrname, mstruserid, usetype, balance, p_l, freechips, chip");
                    $this->db->from('createmaster');
                    $this->db->where_in('mstrid',$child_users);
                    $query = $this->db->get();
                    $res = $query->result_array();

                    foreach ($res as $key => $value) {
                        $userBalance = $Modelcreatemaster->getUserTreeBalance($value['mstrid']);
                        $value['balance'] = $userBalance['total_balance'];
                        if($value['balance'] == 0){
                            $final_data['clear'][] = $value;
                        }
                        else if($value['balance'] > 0){
                            $final_data['paidTo'][] = $value;
                            $final_data['totalPaidTo'] = $final_data['totalPaidTo'] + $value['balance'];
                        }
                        else if($value['balance'] < 0){
                            $final_data['receivingFrom'][] = $value;
                            $final_data['totalReceivingFrom'] = $final_data['totalReceivingFrom']+ $value['balance'];
                        }
                    }
                }

                return $final_data;
            }
            
            function updateScoreBoardId($matchId, $sb_id){
				$data = array(
						'scoreboard_id' => $sb_id
				);
				$this->db->where('MstCode', $matchId);
				$this->db->update('matchmst', $data);
				return true;
			}
}
