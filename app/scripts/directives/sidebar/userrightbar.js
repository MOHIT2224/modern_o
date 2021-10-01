'use strict';
(function() {
app.directive('userrightbar', ['$location', '$timeout', function ($window, $http, sessionService,$state, $timeout, get_userser,Matchoddscntr) {
    return {
        templateUrl: 'directives/userRightbar',
        restrict: 'E',
        replace: true,
       // controller: Matchoddscntr,
        scope: {
           // test_dir: '&',
        },
        link: function (scope, element, attrs,Matchoddscntr) {            
           // Matchoddscntr.test_dir();
	
            scope.$on('changeSidebar_Series', function (event, data) { scope.ShowHideAng(scope.sportsId); });
            scope.$on('changeSidebar_Match', function (event, data) { scope.getSeriesMatch(scope.sportsId, scope.seriesId); });
            scope.$on('changeSidebar_Market', function (event, data) { scope.getMatchMarket(data.sportsId, data.MatchId); });
        },
        controller: ['$scope', '$http', '$timeout', '$mdDialog', 'sessionService', '$rootScope', 'get_userser', 'Dialog','$state','Base64','loginService',function ($scope, $http, $timeout, $mdDialog, sessionService, $rootScope, get_userser, Dialog,$state,Base64,loginService) {
        	
            var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
            $scope.chkMarketPP = false;
            $scope.chkMarketPPF = false;
	    $rootScope.selectedRow = '';
	    $scope.IsToggle=false;
	    $scope.oneClickSelectedBet=0;
		if(sessionService.get('config_max_odd_limit')!=angular.isUndefinedOrNull){
	    $scope.config_max_odd_limit= parseInt(sessionService.get('config_max_odd_limit'));
		}
		
$scope.betslipinfo=true;
$scope.isconfirmbet=sessionService.get('config_unmatched');
$rootScope.stake2={};
$rootScope.FinalTeam=[];
$rootScope.stakeIds=[];
$rootScope.stake2={};
$scope.UserData =[];
$scope.SportIds=[];
$scope.timerAllbets='';
$rootScope.GUserData=[]; 

$scope.MatchId=0;
			$scope.OddReturn=function(odds)
			{

				var value= (odds+"");
				value = value.toString();


				if (value.indexOf('.') === -1) {
					return value;
				}

				// as long as the last character is a 0 or a dot, remove it
				while((value.slice(-1) === '0' || value.slice(-1) === '.') && value.indexOf('.') !== -1) {
					value = value.substr(0, value.length - 1);
				}
				return value;
			}
	   // $scope.ischeckconfirm=sessionService.get('is_confirm_bet');

	  //  $("#main-menu").show();
            /*start the code of js file sidebar.js*/
    $scope.GetUserData=function(MatchId){

	$scope.getUserBetTimer=$timeout(function(){
	var currentPage=$state.current.name;
        $scope.MatchId=MatchId;
	if($state.current.name=='userDashboard.Matchodds')
	{
	
        $http.get('Betentrycntr/GatBetData/' + 0 + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + MatchId).success(function(data, status, headers, config) {
		//
		if($scope.UserData==angular.isUndefinedOrNull || $scope.UserData.length==0)
		{

			  $scope.UserData = data.betUserData;

		}
		else
		{
			if($scope.UserData.length != data.betUserData.length)
			{
				  $scope.UserData = data.betUserData;
                if($state.current.name=='userDashboard.Matchodds')
                {
                    $rootScope.$broadcast('MarketWinLoss_Matchodds',{});

                }
			}
			else
			{
                for(var i=0;i<data.betUserData.length;i++)
				{
					var ind=$scope.UserData.findIndex(x=>x.MstCode==data.betUserData[i].MstCode && (x.IsMatched!=data.betUserData[i].IsMatched || x.void!=data.betUserData[i].void));
					if(ind>-1)
					{
                        $scope.UserData[ind]=data.betUserData[i];
                        if($state.current.name!='userDashboard.Matchodds')
                        {
                            $rootScope.$broadcast('InitMarketWinLoss',{'marketId':$scope.UserData[ind].MarketId,'matchId':$scope.UserData[ind].MatchId});
                        }
                        else
                        {
                            if($state.current.name=='userDashboard.Matchodds')
                            {
                                $rootScope.$broadcast('MarketWinLoss_Matchodds',{});
                                $rootScope.$broadcast('CallMarketLiability',{});
                            }
                        }
					}
				}
			}
		}
		$rootScope.GUserData=data.betUserData;
	    $scope.GetUserData(MatchId)
        });
	}
	},1000);
    }

    $scope.UpdateUnMatchedBet=function(type,MstCode,marketid,matchId) {
        $http.get('Betentrycntr/updateUnMatchedData/' + MstCode + '/' + type + '/' + marketid + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + matchId).success(function (data, status, headers, config) {
        });
	}
   $scope.deleteUser = function (betId, userId) {
	
        var result = confirm("Are you sure want to delete Records Unmatched");
        if (result) {
		$scope.loading=true;
            $http.get('Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {
                /*Dialog.autohide(data.message);*/
                Dialog.autohide("Record Deleted Successfully...");
      $scope.loading=false;
                if($state.current.name!='userDashboard.Matchodds')
                {
                    $scope.GetAllBets();
                }
                else{
                    $scope.GetUserData($scope.MatchId);
                }
	});
        }

    }
$scope.GetAllBets=function(){
	if($state.current.name=='userDashboard.Home' || $state.current.name=='userDashboard.Favorite')
	{
$scope.timerAllbets=$timeout(function(){
      $http.get('Apiusercontroller/get_all_bets').success(function(data, status, headers, config) {

          if($scope.UserData==angular.isUndefinedOrNull || $scope.UserData.length==0)
		{
			  $scope.UserData = data.data;

		}
		else
		{
			if($scope.UserData.length != data.data.length)
			{
				  $scope.UserData = data.data;
			}
            else
            {
                for(var i=0;i<data.data.length;i++)
                {
                    var ind=$scope.UserData.findIndex(x=>x.MstCode==data.data[i].MstCode && (x.IsMatched!=data.data[i].IsMatched || x.void != data.data[i].void));
                    if(ind>-1)
                    {
                        $scope.UserData[ind]=data.data[i];

                            $rootScope.$broadcast('CallMarketLiabilityFromFav',{'marketId':$scope.UserData[ind].MarketId,'matchId':$scope.UserData[ind].MatchId});


                    }
                }
            }
		}
		$rootScope.GUserData=data.data;
		$scope.GetAllBets();
        });
},1000);
	}
}

$scope.$on('BindUserBets',function(event,data){
	//
	 $scope.UserData=[];
	 $scope.GetUserData(data.MatchId);
});
$scope.$on('BindAllBets',function(event,data){
	if($state.current.name!='userDashboard.Matchodds')
	{
	   $scope.UserData=[];
	  $scope.GetAllBets();
	}
});
if($state.current.name!='userDashboard.Matchodds')
	{
	  $scope.GetAllBets();
	}
if($state.current.name=='userDashboard.Matchodds'){
	$rootScope.$broadcast('CallBackFromRightBar',{});
}
            $scope.displaysubmenu=function(id){
                  // 
                     $("#main-menu").hide();
		$("#SportType").val(id);
                $("#"+id+"-sub-nav").toggle();
               
            }
            $scope.backButton=function(){
               $("#4-sub-nav").hide();
                $("#1-sub-nav").hide();
                $("#2-sub-nav").hide();
                $("#7-sub-nav").hide();
                $("#main-menu").show(); 
            }
            $scope.oddsdisplay=function(MatchName){
               // 
                
                $("#not-sub-nav").toggle();
                $("#main-menu").hide();
            }
  		$scope.ShowHideAng1 = function (sportsId) {
				
				
			//	
                $scope.accordion = sportsId;
                $scope.sportsId = sportsId;
                $scope.accordionLv2 = 0;

                    $scope.GetSeriesData = angular.isUndefinedOrNull;
                    $http.get('Geteventcntr/getSeriesLst/' + sportsId).success(function (data, status, headers, config) {
                        $scope.GetSeriesData = data.seriesLst;
                        $rootScope.GetSeriesData = data.seriesLst.length;
					 
                    });
                
            }

$scope.ischeckconfirm={};
$scope.update_confirmation_setting = function(ischeckconfirm){
//
		 var formData = {"is_confirm_bet":ischeckconfirm == true?'Y':'N'};
                $http({ method: 'POST', url: 'Apiusercontroller/confirm_bet/', data: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                   if(!data.error){
			  var status=ischeckconfirm == true?'Y':'N';
		          sessionService.set('is_confirm_bet',status);
	                  $scope.message=data.message;
			 Dialog.autohide($scope.message); 
			}else{
	                  $scope.message=data.message;		
				}
				
		    })
			
}
$rootScope.total_liability=0;
$scope.back={};
$scope.stake1=0;
$scope.oldstake2={};
$scope.clearAll=function()
{

	for(var i=0;i<$rootScope.stakeIds.length;i++){
			$rootScope.stake2['field_'+$rootScope.stakeIds[i].UId]=0;
		}
}

/////////////////////////////////////////////////////


$scope.FinalCalCulation=function(MatchId,MarketId)
{
	
	var filteredTeam = $rootScope.FinalTeam.filter(function (element) { 
	    return element.MatchId === MatchId && element.MarketId===MarketId;
	});
	var filteredStakeIds = $rootScope.stakeIds.filter(function (element) { 
	    return element.MatchId === MatchId && element.MarketId===MarketId;
	});
	if(filteredTeam!=angular.isUndefinedOrNull && filteredStakeIds !=angular.isUndefinedOrNull)
	{
		$scope.FinalResult(filteredTeam,filteredStakeIds);
	}
   
}


$scope.FinalResult = function(FinalTeam,stakeIds)
{
	if(FinalTeam.length==stakeIds.length)
	{
		if(FinalTeam[2]!=angular.isUndefinedOrNull){
   $rootScope.stake2['field_'+stakeIds[0].UId]=FinalTeam[0].TeamW + FinalTeam[1].TeamL + FinalTeam[2].TeamL;
	}
	else
	{
		if(stakeIds[0]!=angular.isUndefinedOrNull)
		 $rootScope.stake2['field_'+stakeIds[0].UId]=FinalTeam[0].TeamW +FinalTeam[1].TeamL + 0;
	}
if(FinalTeam[2]!=angular.isUndefinedOrNull){
   $rootScope.stake2['field_'+stakeIds[1].UId]=FinalTeam[1].TeamW + FinalTeam[0].TeamL+FinalTeam[2].TeamL;
}
else
{
	if(stakeIds[1]!=angular.isUndefinedOrNull)
 $rootScope.stake2['field_'+stakeIds[1].UId]=FinalTeam[1].TeamW + FinalTeam[0].TeamL+0;
}
if(FinalTeam[2]!=angular.isUndefinedOrNull){
   $rootScope.stake2['field_'+stakeIds[2].UId]=FinalTeam[2].TeamW+FinalTeam[0].TeamL + FinalTeam[1].TeamL;
}
	}
}
$scope.SetWinLoss=function(team)
{

	var ind=$rootScope.FinalTeam.findIndex(x=>x.UId==team.unique_id);
	if(ind>-1)
	{
		$rootScope.FinalTeam[ind].placeName=team.placeName;
		$rootScope.FinalTeam[ind].TeamW=$rootScope.stake2['win_'+team.unique_id];
		$rootScope.FinalTeam[ind].TeamL=$rootScope.stake2['loss_'+team.unique_id];
		//$scope.FinalCalCulation($rootScope.FinalTeam[ind].MatchId);
		$scope.FinalCalCulation($rootScope.FinalTeam[ind].MatchId,$rootScope.FinalTeam[ind].MarketId);
	}
}

$scope.CalCulateWinLoss=function(UId,back)
{

	var recorde=$scope.BackLayArray;
	var sum=0;
	var isSame=true;
	for(var j=0;j<recorde.length;j++)
	  {
		var ind = recorde.findIndex(x=>x.unique_id==UId)
	     if(ind>-1)
	     {
		if(recorde[j].unique_id==UId)
		{
			
			if (isNaN(recorde[j].TWin) || recorde[j].TWin==angular.isUndefinedOrNull) {
			recorde[j].TWin=0;

			}
			if (isNaN(recorde[j].TLoss) || recorde[j].TLoss==angular.isUndefinedOrNull) {
			recorde[j].TLoss=0;

			}
			if(recorde[j].isback==0)
			{
				recorde[j].Bwin=recorde[j].p_l;
				recorde[j].Bloss=-recorde[j].stake;
				//recorde[j].TWin+=recorde[j].Bwin 
				//recorde[j].TLoss+=recorde[j].Bloss
				
			}
			else
			{
				recorde[j].Lwin=recorde[j].stake;
				recorde[j].Lloss=-recorde[j].p_l;
				//recorde[j].TWin+=recorde[j].Lwin;
				//recorde[j].TLoss+=recorde[j].Lloss;
			}
			//$rootScope.stake2['field_'+UId]+= recorde[j].Bwin + recorde[j].Lloss;
			$rootScope.stake2['win_' + UId]+= recorde[j].Bwin + recorde[j].Lloss;
			$rootScope.stake2['loss_' + UId]+= recorde[j].Bloss + recorde[j].Lwin;
			$scope.SetWinLoss(recorde[j]);
			
		}
		else
		{
			if(recorde[j].isback==0)
			{
				recorde[j].Bwin=recorde[j].p_l;
				recorde[j].Bloss=-recorde[j].stake;

				//recorde[j].TWin+=recorde[j].Bwin 
				//recorde[j].TLoss+=recorde[j].Bloss
			}
			else
			{
				recorde[j].Lwin=recorde[j].stake;
				recorde[j].Lloss=-recorde[j].p_l;
				//recorde[j].TWin+=recorde[j].Lwin;
				//recorde[j].TLoss+=recorde[j].Lloss;
			}
		}
	     }	//if end
		else
		{
			//$rootScope.stake2['field_'+UId]+=back.isback==0 ? -back.stake : back.stake;
		}
	  	
	 }
}
$scope.RemainCalculation = function(UId,back)
{

   $rootScope.stake2['field_'+UId]=back.isback==0 ? -back.stake : back.stake

}

$scope.CalculateProfitLoss=function(back,type)
{

		
        if(type==0)
	{
		$scope.ResetP_L(back);//it will call during remove or cross;
		if($scope.BackLayArray.length==0)
		{
			$scope.FinalCalCulation(back.matchId,back.MarketId);
		}
		else
		{
			var filteredTeam = $scope.BackLayArray.filter(function (element) { 
			    return element.matchId === back.matchId && element.marketId === back.MarketId;
			});
			for(var i=0;i<filteredTeam.length;i++)
			{
				$scope.FinalCalCulation(filteredTeam[i].matchId,filteredTeam[i].MarketId);
			}
			if(filteredTeam.length==0)
			{
				$scope.FinalCalCulation(back.matchId,back.MarketId);
			}
		}
		
	}
	for(var i=0;i<$rootScope.stakeIds.length;i++){
			var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==$rootScope.stakeIds[i].UId)
		  if(ind>-1)
			{
			  $rootScope.stake2['field_'+$rootScope.stakeIds[i].UId]=0;
			  $rootScope.stake2['loss_' + $rootScope.stakeIds[i].UId] =0;
   			  $rootScope.stake2['win_' + $rootScope.stakeIds[i].UId] =0;
			  $scope.CalCulateWinLoss($rootScope.stakeIds[i].UId,back);
			}
		  else
			{
				//$scope.RemainCalculation($rootScope.stakeIds[i],back);
			}
		}
}

$scope.ResetP_L=function(back)
{
	var ind=$rootScope.FinalTeam.findIndex(x=>x.UId==back.unique_id);
	if(ind>-1)
	{
		$rootScope.FinalTeam[ind].TeamW=0;
		$rootScope.FinalTeam[ind].TeamL=0;
	}
}

////////////////////////////////////////////////////

$scope.CalculateSum=function()
{
var sum=0;
	for(var i=0;i<$scope.BackLayArray.length;i++)
	{
		var obj=$scope.BackLayArray[i];
		var objstake=(obj.stake=="" || obj.stake==null) ? 0 : obj.stake;
		if(obj.is_session_fancy=='Y')
		{
			sum=(parseFloat(sum)+parseFloat(objstake)).toFixed(2);
		}
		else{
		if(obj.isback==0)
		{
			sum=(parseFloat(sum)+parseFloat(objstake)).toFixed(2);
		}
		else
		{
			if(obj.isManual)
			{
                sum=(parseFloat(sum)+parseFloat(((obj.priceVal+1)*objstake)-objstake)).toFixed(2);
			}
			else
			{
                sum=(parseFloat(sum)+parseFloat((obj.priceVal*objstake)-objstake)).toFixed(2);
			}

		}
		}
	}
return sum;
}
$scope.updateLiability = function(back)
{

    if(back.stake<=parseFloat(back.max_bet_liability) || true) {
        if (back.priceVal <= $scope.config_max_odd_limit || back.is_session_fancy == 'Y' || true) {
            if (back.priceVal > 0) {
                back.isMaxOdds = false;
                if (back.stake <= 0) {
                    $scope.isActive = false;
                }
                else {
                    $scope.isActive = true;
                }
                if (isNaN(back.stake) || back.stake == angular.isUndefinedOrNull) {
                    //back.stake=0;

                }
                if(back.isManual)
                {
                    var pval=back.priceVal;
                    back.p_l = (((pval+1) * back.stake) - back.stake);
                }
				else
				{
                    back.p_l = ((back.priceVal * back.stake) - back.stake);
				}


                $scope.CalculateProfitLoss(back, 0);
                var stake = ((back.priceVal * back.stake) - back.stake);//lay
                var stkbtn = back.stake;//back
                if (isNaN($rootScope.total_liability)) {
                    $rootScope.total_liability = 0;
                }

                $rootScope.total_liability = $scope.CalculateSum();
            }
        }
        else {
            //back.priceVal=0;
            if (back.priceVal > $scope.config_max_odd_limit) {
                back.priceVal = $scope.config_max_odd_limit;
            }
            else if (back.priceVal == angular.isUndefinedOrNull) {
                back.isMaxOdds = true;
                back.stake = 0;
                $scope.isActive = false;
            }
        }
    }
    else
	{
		var msg="";
		if(back.is_session_fancy == 'Y')
		{
			msg="Max session bet liability is "+parseFloat(back.max_bet_liability);
		}
		else
		{
            msg="Max market bet liability is "+parseFloat(back.max_bet_liability);
		}
        Dialog.autohide(msg);
        back.stake=parseFloat(back.max_bet_liability);
        $rootScope.total_liability = $scope.CalculateSum();
        $scope.isActive = false;
	}
}
$scope.prevBack={};
$scope.prevSelection={};
$scope.addStake = function(stake,stkbtn,isback,back){

    $scope.stackval = (back.stake == "" || back.stake == null) ? 0 : back.stake;
    back.stake = parseFloat(stkbtn) + parseFloat($scope.stackval);
	if(parseFloat(back.stake)<=parseFloat(back.max_bet_liability) || true) {
        if (back.priceVal <= $scope.config_max_odd_limit || back.is_session_fancy == 'Y' || true) {
            if (back.priceVal > 0) {
                var tempback = back;
                $scope.isActive = true;
                $rootScope.total_liability = 0;
               // $scope.stackval = (back.stake == "" || back.stake == null) ? 0 : back.stake;//stake zero problem resolved
                //$scope.stackval= back.stake;
                if(back.isManual)
                {
                    var pval=back.priceVal+1;
                    back.p_l = ((pval * back.stake) - back.stake);
                }
                else{
                    back.p_l = ((back.priceVal * back.stake) - back.stake);
				}

                //
                $rootScope.total_liability = $scope.CalculateSum();
                if ($rootScope.stakeIds != angular.isUndefinedOrNull) {
                    $scope.CalculateProfitLoss(back, 1);//1 for add
                }

            }
        }
    }
    else{
        var msg="";
        if(back.is_session_fancy == 'Y')
        {
            msg="Max session bet liability is "+parseFloat(back.max_bet_liability);
        }
        else
        {
            msg="Max market bet liability is "+parseFloat(back.max_bet_liability);
        }
        back.stake=parseFloat(back.max_bet_liability);
        $scope.isActive = false;
        $rootScope.total_liability = $scope.CalculateSum();
        Dialog.autohide(msg);
    }
}

$scope.stakeval=[];
            $scope.saveMatchoddstake = function () {
		//
               $scope.loading=true;
                var stakeData = {
                    match_stake: $scope.stakesettingData,
              
                }
                $http({ method: 'POST', url: 'Apiusercontroller/stake_setting/', data: stakeData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    $scope.loading=false;
                    $scope.stakemsg = data.message;
		    Dialog.autohide($scope.stakemsg);
		    $("#edit_popup .btn-secondary").click();
                }).error(function(err){
			$scope.loading=false;
			});
            }
  $scope.SaveOneClick = function () {
	//	
                $scope.loading=true;
                var stakeData = {
                    one_click_stake: $scope.one_click_stack,
              
                }
                $http({ method: 'POST', url: 'Apiusercontroller/one_click_stake_setting/', data: stakeData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    //
			 $scope.loading=false;
                    $scope.stakemsg = data.message;
		    Dialog.autohide($scope.stakemsg);
		    $scope.isEdit=true;
                }).error(function(err){
			$scope.loading=false;
			});
            }

$scope.setstacksetng = function(currentpos){
		$rootScope.currentpos=currentpos;
		
}

           $scope.isShow=true;
	   $scope.isShowTitle=true;
            $scope.getStakesett = function (type) {
		
		 if($scope.BackLayArray.length>0){
			var isConfirm = confirm('All bets in your betslip will be cleared. Is this ok?');
			if(isConfirm)
			{
                $scope.backArray=[];
                $scope.layArray=[];
                $scope.BackLayArray=[];
                $scope.TempArray=[];
				$scope.IsToggle=true;
				$scope.CallOnclickSetting();
				if(type==1)//one click
				{
				calltoggle();
				}
			}
			else
			{
				$scope.IsToggle=false;
			
			}
		}
		else{
			$scope.isShowTitle=true;
			if(type==1)//one click
				{
		      calltoggle();
				}
		      $scope.CallOnclickSetting();
		}

	    }
$scope.CallOnclickSetting=function()
{
	$scope.loading=true;
   $http.get('Apiusercontroller/get_stake_setting/').success(function (data, status, headers, config) {
			
		    $rootScope.MatchStack=data.data.match_stake;
			
		   if($scope.IsToggle)
			{
				$scope.btnActive=0;
				 $scope.one_click_stack=data.data.one_click_stake;
				 $scope.oneClickSelectedBet=$scope.one_click_stack[0];
				       $scope.stakesettingData =data.data.match_stake;
			}
			else
			{
				       $scope.stakesettingData =data.data.match_stake;
			}
       $scope.loading=false;
		});
}

function calltoggle()
{
	$("#form1").toggle();
	$(".clickbet").toggle();

}
	
    $scope.backArray=[];
    $scope.layArray=[];
    $scope.BackLayArray=[];
    $scope.TempArray=[];
    $scope.AssignKey=function(SelectionId)
	{
		if(!$rootScope.stake2.hasOwnProperty('field_' + SelectionId));
		{
			$rootScope.stake2['field_' + SelectionId];
		}
		 var obj={'placeName':'','TeamW':0,'TeamL':0,'UId':date};
		var ind = $rootScope.FinalTeam.findIndex(x=>x.UId==SelectionId);
		if(ind==-1)
		{
   		 $rootScope.FinalTeam.push(obj);
		}
	}
//Step 1
$scope.AssignKeyInit=function(SelectionId,MatchId,MarketId)
{
	var UId=SelectionId+"_"+MatchId+"_"+MarketId; //for uniqueness we combine selction id and match id
	var obj={'placeName':'','TeamW':0,'TeamL':0,'UId':UId,'MatchId':MatchId,'MarketId':MarketId};
		var ind = $rootScope.FinalTeam.findIndex(x=>x.UId==UId);
		if(ind==-1)
		{
   		 $rootScope.FinalTeam.push(obj);
		}
	var obj1={'MatchId':MatchId,'UId':UId,'MarketId':MarketId};
	var ind1= $rootScope.stakeIds.findIndex(x=>x.UId==UId);
	if(ind1==-1){
		
	  	$rootScope.stakeIds.push(obj1);
		$rootScope.stake2['field_' + UId] =0;
    		$rootScope.stake2['loss_' + UId] =0;
    		$rootScope.stake2['win_' + UId] =0;
		}
}
$scope.$on('callAssignKeyInit',function(event,data){
	$scope.AssignKeyInit(data.SelectionId,data.MatchId,data.MarketId);
});
            $scope.AddBackOrLay = function(obj)
		{
		//	
		 if(sessionService.get('config_max_odd_limit')!=angular.isUndefinedOrNull){
	   		 $scope.config_max_odd_limit= parseInt(sessionService.get('config_max_odd_limit'));
			}
		  if(obj.priceVal<=$scope.config_max_odd_limit || obj.is_session_fancy=='Y' || true){
			$scope.isActive=false;
			$scope.betslipinfo=true;
			$scope.arrayObj = obj;
			$scope.arrayObj.stake=0;
			$scope.arrayObj.Lwin=0;
			$scope.arrayObj.Lloss=0;
			$scope.arrayObj.Bwin=0;
			$scope.arrayObj.Bloss=0;
			//var indSport = $scope.SportIds.findIndex(x=>x==obj.SportId);
			//if(indSport==-1)
			//{
				
			//}
			if(obj.is_session_fancy=='Y')
			$scope.arrayObj.unique_id=obj.FancyID+"_"+obj.matchId;
			else
			$scope.arrayObj.unique_id=obj.selectionId+"_"+obj.matchId+"_"+obj.MarketId;

			//$scope.AssignKey(obj.selectionId);
			if($scope.IsToggle) //one click bet
			{
				if($scope.oneClickSelectedBet >0 && $scope.oneClickSelectedBet!=angular.isUndefinedOrNull)
				{
					$scope.arrayObj.stake = parseInt($scope.oneClickSelectedBet); //assign stack
					$scope.arrayObj.p_l = (($scope.arrayObj.priceVal*$scope.arrayObj.stake)-$scope.arrayObj.stake).toFixed(2);
				}
			}
			      if(obj.isback==0) //back
				{
					var ind=$scope.backArray.findIndex(x=>(x.unique_id==obj.unique_id || x.selectionId==obj.selectionId) && x.matchId==obj.matchId && x.isManual==obj.isManual);
					if(ind==-1)
					{
					//$scope.backArray.push($scope.arrayObj);
					 
					$scope.backArray.splice(0,0,$scope.arrayObj);
					//$scope.BackLayArray.splice(0,0,$scope.arrayObj);
					$scope.TempArray=$scope.backArray.concat($scope.layArray);
					$scope.SportIds.push(obj.SportId);
					$scope.betslipinfo=true;$scope.betinfo=false;$scope.isBetSlip=1;
					}

				}
				else{
					var ind=$scope.layArray.findIndex(x=>(x.unique_id==obj.unique_id || x.selectionId==obj.selectionId) && x.matchId==obj.matchId && x.isManual==obj.isManual);
					if(ind==-1)
					{
						//$scope.layArray.push($scope.arrayObj);
						
					$scope.layArray.splice(0,0,$scope.arrayObj);
					$scope.TempArray=$scope.layArray.concat($scope.backArray);
					//$scope.BackLayArray.splice(0,0,$scope.arrayObj);
					$scope.SportIds.push(obj.SportId);
                        $scope.betslipinfo=true;$scope.betinfo=false;$scope.isBetSlip=1;
					}
                    else
                    {
                        //$scope.layArray[ind]=$scope.arrayObj;
                        //$scope.TempArray=$scope.layArray.concat($scope.backArray);
                    }
				   }
			$scope.BackLayArray = $scope.TempArray;
			if($scope.IsToggle) //one click bet
			{
                if(parseFloat(obj.stake)<=parseFloat(obj.max_bet_liability) || true) {
                    $scope.Place_bet();
                }
                else {
                    var msg="";
                    if(obj.is_session_fancy == 'Y')
                    {
                        msg="Max session bet liability is "+parseFloat(obj.max_bet_liability);
                    }
                    else
                    {
                        msg="Max market bet liability is "+parseFloat(obj.max_bet_liability)
                    }
                    $scope.RemoveBackLay(obj.unique_id,obj.isback,0,0,obj.isback,obj);
                    Dialog.autohide(msg);
				}



			}
		  }
		else
		{
			Dialog.autohide("Max odds limit is " +$scope.config_max_odd_limit);
		}
				
		}

  

    
	    $scope.$on('CallAddBackOrLay',function(event, data){
		//

            var isMultiBet=sessionService.get('isMultiBet');

            if(isMultiBet=="N")
            {

                if($scope.BackLayArray<=1)
				{
                    $scope.AddBackOrLay(data);
				}
               else {

                    $scope.backArray=[];
                    $scope.layArray=[];
                    $scope.BackLayArray=[];
                    $scope.SportIds=[];
                    $scope.prevSelection=0;
                    $scope.AddBackOrLay(data);
                    for(var j=0;j<$rootScope.stakeIds.length;j++)
					{
                        $rootScope.stake2['field_'+$rootScope.stakeIds[j].UId]=0;
                        $rootScope.stake2['field_' + $rootScope.stakeIds[j].UId] =0;
                        $rootScope.stake2['loss_' + $rootScope.stakeIds[j].UId] =0;
                        $rootScope.stake2['win_' + $rootScope.stakeIds[j].UId] =0;
					}
					for(var t=0;t<$rootScope.FinalTeam.length;t++)
					{
                        $rootScope.FinalTeam[t].TeamW=0;
                        $rootScope.FinalTeam[t].TeamL=0;
					}

                    $rootScope.total_liability= $scope.CalculateSum();

				}

            }
            else if(isMultiBet=="Y") {
                $scope.AddBackOrLay(data);
            }
		});
	$scope.RemoveBackLay = function(unique_id,type,stake,stkbtn,isback,back)
	{
	//

		if(type==0) //back
		{
			var ind=$scope.backArray.findIndex(x=>x.unique_id==unique_id);
			if(ind>-1)
			{
			$scope.backArray.splice(ind,1);
			
			}
		}	
		else
		{
			var ind=$scope.layArray.findIndex(x=>x.unique_id==unique_id);
			if(ind>-1)
			{
			$scope.layArray.splice(ind,1);
			
			}
		}
	   var ind1 = $scope.BackLayArray.findIndex(x=>x.unique_id==unique_id && x.isback==type);
			if(ind1>-1)
			{
				 //0 for remove;
			var indSport = $scope.SportIds.findIndex(x=>x==back.SportId);
			if(indSport>-1)
			{
				$scope.SportIds.splice(indSport,1);
			}
			$scope.BackLayArray.splice(ind1,1);
			$scope.prevSelection=0;
			//$scope.prevBack['field_'+back.unique_id]=-1;
			if($rootScope.stakeIds !=angular.isUndefinedOrNull){
			  if(back.is_session_fancy=='N')
				{
				  $scope.CalculateProfitLoss(back,0);
				}
			}
			
			}
	  $rootScope.total_liability= $scope.CalculateSum();
	  
	}
	

        $scope.setRef = 0;
	$scope.setbtn=function(indx)
	{
		$scope.setRef = indx;
	}

	$scope.Place_bet=function(ask_confirmbet)
	{
		$scope.MarketIdList=[];
		$scope.marketLst="";
		var isconfirm=true;
		if(sessionService.get('is_confirm_bet') == 'Y'){
		    isconfirm= confirm('Are you sure you want to place your bet?');
		}
		else
		{
			//isconfirm=false;
		}

	 if(isconfirm){
		if($scope.backArray.length>0 || $scope.layArray.length>0)
		{
			
			var combindata = angular.copy($scope.BackLayArray);
			var IsError=false;
			for(var i=0;i<combindata.length;i++)
			{
				if(combindata[i].isManual)
				{
                    combindata[i].unique_id=combindata[i].unique_id+"_M"+combindata[i].isback;
                    $scope.BackLayArray[i].tempId=$scope.BackLayArray[i].unique_id+"_M"+$scope.BackLayArray[i].isback;
				}
				else {
                    combindata[i].unique_id=combindata[i].unique_id+"_A"+combindata[i].isback;
                    $scope.BackLayArray[i].tempId=$scope.BackLayArray[i].unique_id+"_A"+$scope.BackLayArray[i].isback;
				}


			if(combindata[i].stake <=0 || combindata[i].stake==angular.isUndefinedOrNull)
				{
					combindata[i].isError=true;
					IsError=true;
				}
			else if(combindata[i].priceVal <=0 || combindata[i].priceVal==angular.isUndefinedOrNull){
				combindata[i].isMaxOdds=true;
				IsError=true;
				}
				var mind=$scope.MarketIdList.findIndex(x=>x==combindata[i].MarketId);
				if(mind==-1)
				{
					var betType=combindata[i].isback=="0" ? "back" : "lay";
					if(combindata[i].is_session_fancy=="N")
					{
					$scope.MarketIdList.push(combindata[i].MarketId+"_"+combindata[i].selectionId+"_"+betType);
					}
					else
					{
				$scope.MarketIdList.push(combindata[i].MarketId+"_s"+combindata[i].ind_fancy_selection_id+"_"+betType);
					}
				}
			}
		$scope.marketLst=$scope.MarketIdList.join(',');
		if(!IsError)
		{
			var tUrl="Apiusercontroller/save_multiple_bets";
			//'Apiusercontroller/save_multi_bet';
			$scope.loading=true;
			$scope.FormDataPost={"back_lay_ids":$scope.marketLst,"bet_slip":combindata}
			$http({ method: 'POST', url: tUrl, data:$scope.FormDataPost , headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    
                 $scope.loading=false;
		 var result = data;
		 var checkError=false;
                    if($state.current.name!='userDashboard.Matchodds')
                    {
                        $scope.GetAllBets();
                    }
                    else{
                        $scope.GetUserData($scope.MatchId);
					}
		for(var j=0;j<result.length;j++)
		{
			var ind = $scope.BackLayArray.findIndex(x=>x.tempId==result[j].unique_id);

			if(ind>-1)
			{
				if(result[j].result.error)
				{
					$scope.BackLayArray[ind].IsErrorShow=true;
					$scope.BackLayArray[ind].Message=result[j].result.message;
					checkError=true;
                    if($scope.IsToggle)
                    {
                        Dialog.autohide(result[j].result.message);
                        $scope.backArray=[];
                        $scope.layArray=[];
                        $scope.BackLayArray=[];
                        $scope.TempArray=[];
                    }

				}
				else
				{
					$scope.BackLayArray[ind].IsErrorShow=false;
					$scope.BackLayArray[ind].Message=result[j].result.message;
					//var tObj={'MarketId':$scope.BackLayArray[ind].MarketId,'obj':result[j].result.data.RunnerValue}
					//$rootScope.$broadcast('InitTRunnerValue',tObj);
					if($state.current.name!='userDashboard.Matchodds')
					{
						$rootScope.$broadcast('InitMarketWinLoss',{'marketId':$scope.BackLayArray[ind].MarketId,'matchId':$scope.BackLayArray[ind].matchId});	
					}
					else
					{
						if($state.current.name=='userDashboard.Matchodds')
						{
							$rootScope.$broadcast('MarketWinLoss_Matchodds',{'marketId':$scope.BackLayArray[ind].MarketId});
						}
					}
					
					$scope.RemoveBackLay($scope.BackLayArray[ind].unique_id,$scope.BackLayArray[ind].isback,0,0,$scope.BackLayArray[ind].isback,$scope.BackLayArray[ind]);
					
					
					
					
				}
			}
		}
		if(!checkError)
		{
            var isMultiBet=sessionService.get('isMultiBet');

            if(isMultiBet=="N") {
                Dialog.autohide(result[0].result.message);

            }
            else {
                Dialog.autohide("Bet placed successfully.");

            }
		}
		else
		{

            $scope.count=0;
            $scope.lenth=result.length;
            $scope.ErrorResult=result;
            DoTaskForError();


		}
		
                });
		}
		 }
		}
		else
		{
			if($scope.IsToggle) //one click bet
			{
			   $scope.backArray=[];
			   $scope.layArray=[];
			   $scope.BackLayArray=[];
			}
			
		}

	
}


            function DoTaskForError() {
                var ind = $scope.BackLayArray.findIndex(x=>x.tempId==$scope.ErrorResult[$scope.count].unique_id);

                if( $scope.count < $scope.lenth ){
                    if($scope.ErrorResult[$scope.count].result.message=="Insufficient Balance" && ind>-1)
                    {


                        setTimeout( function(){
                            $scope.RemoveBackLay($scope.BackLayArray[ind].unique_id,$scope.BackLayArray[ind].isback,0,0,$scope.BackLayArray[ind].isback,$scope.BackLayArray[ind]);
                            $scope.count++;
                            DoTaskForError();
						}, 1000 );

                    }

                }
            }

  $scope.setOneClickBetStake=function(stake,ind)
	{
		$scope.btnActive=ind;
		$scope.oneClickSelectedBet=stake;
	}
  $scope.$on('RemoveAddBackOrLay',function(event, data){
			
			$scope.RemoveBackLay(data.unique_id,data.type);
		});
            $scope.getMatchMarket = function (sportsId, matchId,MatchName) {
		//
                $scope.MatchName=MatchName;
                $scope.accordion = sportsId;
                $scope.accordionLv1 = matchId;
                $scope.MatchId = matchId;
                $scope.sportsId = sportsId;
                var marketData = {
                    matchId: matchId,
                    sportsId: sportsId,
                    user_id: sessionService.get('user_id')
                }
                $http({ method: 'POST', url: 'Geteventcntr/matchMarketLst/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    //
                    $scope.MatchMarket = data.MatchMarket;
                    $scope.getMatchFancy = data.getMatchFancy;
                });
            }


            /*$(".not").click(function(){ 
                $("#not-sub-nav").toggle();
                $("#main-menu").hide();
            });*/

            $(".sub-back").click(function(){ 
		
                $("#not-sub-nav").hide(200);
		var id = $("#SportType").val();
//alert(id);
		$("#"+id+"-sub-nav").show();
		
            });
            /*end of sidebar.js*/
           /* $scope.refresh_tree = function () {
                $http.get('Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
                    $scope.treeNodes = data.tree;
                });
            }*/
            /*$(".myMenu1").click(function () { $(".dropdown123").show(); });
            $(document).click(function (e) {
                if (!$(e.target).hasClass("myMenu1") && $(e.target).parents(".dropdown").length === 0) { $(".dropdown123").hide(); }
                if (!$(e.target).hasClass("myMenu2") && $(e.target).parents(".dropdown").length === 0) { $scope.dropdown124 = false; }
            });*/
            /*$scope.showAddSetting = function (node, currentScope1) {
                $scope.mid = node.id;
                $scope.fancyType = node.usetype;
                if (node.usetype == 1) {
                    $scope.HeadingType = "Create Dealer";
                    $scope.HeadingName = "Dealer Name";
                    $scope.HeadingTypeId = 2;
                }
                else if (node.usetype == 2) {
                    $scope.HeadingType = "Create User";
                    $scope.HeadingName = "User Name";
                    $scope.HeadingTypeId = 3;
                }
                else if (node.usetype == 0) {
                    $scope.HeadingType = "Create Master";
                    $scope.HeadingName = "Master Name";
                    $scope.HeadingTypeId = 1;
                }
                $mdDialog.show({
                    controller: showAddSettingController,
                    templateUrl: 'app/scripts/directives/popupform/add_account.html?var='+Math.random(),
                    locals: { prntScope: $scope, node: node, currentScope1: currentScope1 },
                    clickOutsideToClose: false,
                    fullscreen: false,
                });
            };*/
           
            $scope.getDate = new Date();
            $scope.$watch('sessionService', function (newVal, oldVal) {
                $scope.FreeChips = sessionService.get('FreeChips');
                $scope.ChipInOut = sessionService.get('ChipInOut');
                $scope.Liability = sessionService.get('Liability');
                $scope.Balance = sessionService.get('Balance');
            });
            $scope.sessionusetype = sessionService.get('type');
            $scope.sessionuser_id = sessionService.get('user_id');
            $scope.getDashboardurl = function () {
                switch ($scope.sessionusetype) {
                    case "0": return "dashboard.Home"; break;
                    case "1": return "dashboard.Masterdashboard"; break;
                    case "2": return "dashboard.Dealerdashboard"; break;
                    case "3": return "dashboard.Userdashboard"; break;
                }
            }
            $http.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                $scope.sprtData = data.sportData;
            }).error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
            });
            
            
            $scope.open = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.opened = true;
            };
            $scope.dateOptions = {
                formatYear: 'yy',
                startingDay: 1
            };
            
            $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate'];
            $scope.format = $scope.formats[1];
           $scope.setClickedRow = function(index,series){
		console.log(series);
			$state.go('userDashboard.Matchodds',{'MatchId': series.MstCode,'matchName':series.matchName,'date':series.MstDate,'sportId':series.SportID})

		}
           
            $scope.getSeriesMatch = function (sportsId, seriesId) {
		//
                $scope.inPlay = [];
                $scope.upComing = [];
                $scope.accordion = sportsId;
                $scope.accordionLv1 = 0;
                $scope.accordionLv2 = seriesId;
                $scope.seriesId = seriesId;
                $scope.GetMatchData = angular.isUndefinedOrNull;
                $http.get('Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
                    $scope.GetMatchData = data.matchLst;
					
				 var date = new Date();
		          var d = date.getDate();
							if(d<10)
								d = 0+""+d;
				 var y = date.getFullYear();
		          var m = date.getMonth();
		          m= m+1;
							if(m<10)
								m = 0+""+m;
					//	alert(""+y+"-"+m+"-"+d+" 00:00:00");
					var hours = date.getHours();
		if(hours<10)
			hours = 0+""+hours;
		var min = date.getMinutes();
		min = min+1;
		if(min<10)
			min = 0+""+min;
		var sec = date.getSeconds();
		if(sec<10)
			sec = 0+""+sec;
		var currentTime = new Date(""+y+"-"+m+"-"+d+" "+hours+":"+min+":"+sec+"");
								var date = new Date(""+y+"-"+m+"-"+d+" 00:00:00");
								//console.log("newDate : " + newDate);
					
						angular.forEach($scope.GetMatchData, function(value, key) {
							  console.log(key + ': ' + value);
							  var d = new Date(value.MstDate);
								
					//			alert("n : " + n);
								console.log("n " + d);
								if(date < d && d < currentTime){
									$scope.inPlay.push(value);
								}
else if(d>currentTime)
{$scope.upComing.push(value);
			  }else{}
								console.log($scope.inPlay);
								console.log($scope.upComing);
							});
		
		if($scope.SelectedMId!=0)
		{
		//$('.submenu').removeClass("active");
		//$('#MId'+$scope.SelectedMId).addClass("active");
		}
                });
		
            }
            var UserId = sessionService.get('user_id');
           $rootScope.$on('step1', function (event, data) {
		//
		$scope.displaysubmenu(data.id);
		//$scope.getSeriesMatch(data.id, 0);
	   });
   	   $rootScope.$on('step2', function (event, data) {
		//
		$("#not-sub-nav").show();
                $("#main-menu").hide();
		$scope.getMatchMarket(data.id,data.MstCode,data.matchName)
	   });
	    $rootScope.$on('step10', function (event, data) {
		//
		$scope.SelectedMId =data.MstCode;
		//$scope.getSeriesMatch(data.id, 0);
		$scope.ManageDLL(data.id,data.MstCode,data.matchName)
	   });
	    $scope.ManageDLL = function (sid,matchid,name){
 		$("#sp"+sid).trigger('click');
		$('#'+sid).addClass('in').attr("aria-expanded","true").removeAttr("style").trigger('click');
		
			
		}
		$scope.returnPriceValManual=function(pval)
		{
			var pv="0.0" +pval;
			return parseFloat(pv);
		}
$scope.upBal="";
            $scope.callbal=1;
 $scope.updateBal=function()
    {

        $scope.upBal = $timeout(function(){   $http.get('Chipscntrl/getChipDataById/' +  sessionService.get('user_id')).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
if($scope.cipsData != angular.isUndefinedOrNull ){
            sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
            sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
            sessionService.set('Liability', $scope.cipsData[0].Liability);
            sessionService.set('Balance', $scope.cipsData[0].Balance);
            sessionService.set('P_L', $scope.cipsData[0].P_L);
            $scope.$watch('sessionService', function (newVal, oldVal) {
                $scope.FreeChips = $scope.cipsData[0].FreeChip;
                $scope.ChipInOut = $scope.cipsData[0].Chip;
                $scope.Liability = $scope.cipsData[0].Liability;
                $scope.Balance = $scope.cipsData[0].Balance;
                $scope.P_L = $scope.cipsData[0].P_L;
            });
}

            $rootScope.user = sessionService.get('slctUseName');
            $rootScope.Balance = sessionService.get('Balance');
            $rootScope.Liability = sessionService.get('Liability');
            $rootScope.P_L = sessionService.get('P_L');
            $scope.callbal=2;
            if(!data.is_login) {
                loginService.logout();
            }
            else
			{
                $scope.updateBal();
			}


        });

        },$scope.callbal==1 ? 0 : 5000)
    }
   // $scope.updateBal(); this is calling from userheader.js

    $scope.$on("$destroy", function (event) {
               $timeout.cancel($scope.upBal);
	       $timeout.cancel($scope.timerAllbets);
	       $timeout.cancel($scope.getUserBetTimer);
    });
$scope.ClearRightSideBarATimeOut=function()
{
	    $timeout.cancel($scope.upBal);
	    $timeout.cancel($scope.timerAllbets);
	    $timeout.cancel($scope.getUserBetTimer);

}
$scope.$on('ClearRightSideBarATimeOut',function(event,data){
$scope.ClearRightSideBarATimeOut();
});
	    $scope.resetCall = function(id)
		{
			//$('#'+sid).removeClass('in').attr("aria-expanded","false").css("height","0px").trigger('click');
			$scope.SelectedMId =0;
			$scope.selectedSport=id;
			$(".submenu").removeClass('active');
			$("#si"+id).addClass('active');
			$('.lav'+id).animate({	height:"auto", right:"0px"}, 500); 
			$('.lav'+id).addClass('subactive');
			$('.lav'+id).css("position","relative");	
			//$('#menu2 li.lv1').animate({right:"-500px"}, 500); 
			
		}
 $scope.resetCall2 = function(id)
		{
			//$('#'+sid).removeClass('in').attr("aria-expanded","false").css("height","0px").trigger('click');
			$scope.SelectedMId =0;
			$scope.selectedSport=id;
			$(".submenu").removeClass('active');
			$("#s2"+id).addClass('active');
			$('.lav2'+id).animate({	height:"auto", right:"0px"}, 500); 
			$('.lav2'+id).addClass('subactive');
			$('.lav2'+id).css("position","relative");	
			//$('#menu2 li.lv1').animate({right:"-500px"}, 500); 
			
		}
   $scope.previous2 = function(prev)
		{
			$(".lav2"+prev).removeClass('active');
			$('.lav2'+prev+'.submenu').addClass('active');
			$('.lav2'+prev).animate({right:"-500"}, 0); 	
			$('.lv2'+prev).animate({height:"auto", right:"0px"}, 500); 
			$('.lav2'+prev).animate({right:"-500px"}, 500);
			$('.lav2'+prev).css("position","absolute");
	
	//$('#menu2 ul.lav1').animate({right:"-500"}, 0); 		
	//$('#menu2 ul.lav2').animate({right:"-500px"}, 500); 		
   // $('#menu2 li.lv1 ').animate({height:"auto", right:"0px"}, 500); 
	//$('#menu2 ul.lav1').animate({right:"0"}, 500);	
			//$('#menu2 li.lv1').animate({right:"-500px"}, 500); 
			
		}
	    $scope.previous = function(id)
		{
				$('#menu2 .lav1').removeClass('subactive');	
	$('.lav'+id+'.submenu').removeClass('active');	
	$('.lav'+id).animate({right:"-500"}, 0); 
	$('.lav'+id).css("position","absolute");		
	$('.lav2'+id).animate({right:"-500px"}, 500); 		
    $('.lv1'+id).animate({height:"auto", right:"0px"}, 500); 
	$('.lav1'+id).animate({right:"0"}, 500);
		}
            $scope.showCreateFancy = function (ev, type) {
                $mdDialog.show({
                    controller: 'showCreateFancyCntr',
                    templateUrl: 'app/scripts/directives/popupform/create_fancy.html?var='+Math.random(),
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose: false,
                    locals: { prntScope: $scope, matchInfo: $scope.CreateFancyMatchInfo, type: type },
                })
              .then(function () {
              }, function () {
              });
            };
            $scope.createAllTypeFancy = function (formData) {
                 
                var url = BASE_URL + "Createmastercontroller/SaveFancy";
                $http.post(url, formData).success(function (response) {
                    Dialog.autohide(response.message);
                    $scope.loading=false;
                    $scope.SubmitBtnDis=false;
                });
            };
            $scope.sdMarketPP = function (sportId,matchId,MarketId,FancyId,IsPlay) {
                
                var user_id=sessionService.get("user_id");
                var user_type=sessionService.get("type");
                var $promise = $http.get(BASE_URL + 'Lstsavemstrcontroller/chaneMarketPPStatus/' +user_id+'/'+matchId+'/'+MarketId+'/'+FancyId+'/'+user_type+'/'+IsPlay);
                $promise.then(function (response) {
                   // 
                     Dialog.autohide(response.data.message);
                     $scope.getMatchMarket(sportId, matchId);
                   
                });
            };
        }]
    }


}]);
})();
app.controller('showCreateFancyCntr',['$scope', '$mdDialog', 'prntScope', 'matchInfo', 'type', function ($scope, $mdDialog, prntScope, matchInfo, type) {
    $scope.SubmitBtnDis=false;
    $scope.dt = null;
    if (type == 1) {
        $scope.fancyHeaderName = "Odd Even";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 2) {
        $scope.fancyHeaderName = "Session";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 3) {
        $scope.fancyHeaderName = "Khaddal";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 4) {
        $scope.fancyHeaderName = "Last Digit";
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    else if (type == 5) {
        $scope.fancyHeaderName = "Up Down";
        $scope.ratediff = 1;
        $scope.maxStake = 10000;
        $scope.pointDiff = 10;
        var t = matchInfo.MstDate.split(/[- :]/);
        var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
        $scope.mytime = new Date(matchInfo.MstDate);
        $scope.dt = t[0] + "-" + (t[1]) + "-" + t[2];
    }
    $scope.mid = matchInfo.MstCode;
    $scope.SportID = matchInfo.SportID;
    $scope.fancyType = type;
    $scope.oddEvenFancy = function (formData) {
       // 
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
       // alert("test");
        var setFancyTime = document.getElementById('setFancyTime').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1, setFancyTime);
    };
    $scope.SessionFancyForm = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeS').value;
        var inputYes = document.getElementById('inputYes').value;
        var inputNo = document.getElementById('inputNo').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, inputYes: inputYes, inputNo: inputNo, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.KhaddalFancyForm = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeK').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, fancyRange: formData.range, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.LastDigitFancy = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var setFancyTime = document.getElementById('setFancyTimeL').value;
        var liabilityLstDigit = document.getElementById('liabilityLstDigit').value;
        var formData1 = { HeadName: formData.HeadName, remarks: formData.remarks, mid: $scope.mid, fancyType: $scope.fancyType, date: $scope.dt, time: setFancyTime, liability: liabilityLstDigit, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.UpDownFancy = function (formData) {
        $scope.loading=true;
        $scope.SubmitBtnDis=true;
        var liability = document.getElementById('liability').value;
        var upDownHead = document.getElementById('upDownHead').value;
        var ratediffUpdwn = document.getElementById('ratediffUpdwn').value;
        var pointDiffUpdwn = document.getElementById('pointDiffUpdwn').value;
        var maxStakeUpdwn = document.getElementById('maxStakeUpdwn').value;
        var formData1 = { HeadName: upDownHead, mid: $scope.mid, remarks: formData.remarks, fancyType: $scope.fancyType, date: $scope.dt, time: $scope.mytime, rateDiff: ratediffUpdwn, pointDiff: pointDiffUpdwn, MaxStake: maxStakeUpdwn, liability: liability, sid: $scope.SportID }
        prntScope.createAllTypeFancy(formData1);
    };
    $scope.hide = function () { $mdDialog.hide(); };

}]);

