'use strict';
(function() {
var app = angular.module('ApsilonApp');
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
        controller: ['$scope', '$http', '$timeout', '$mdDialog', 'sessionService', '$rootScope', 'get_userser', 'Dialog','$state','Base64',function ($scope, $http, $timeout, $mdDialog, sessionService, $rootScope, get_userser, Dialog,$state,Base64) {
	 var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
            $scope.chkMarketPP = false;
            $scope.chkMarketPPF = false;
	    $rootScope.selectedRow = '';
	    $scope.IsToggle=false;
	    $scope.oneClickSelectedBet=0;
$scope.betslipinfo=true;
	   // $scope.ischeckconfirm=sessionService.get('is_confirm_bet');

	  //  $("#main-menu").show();
            /*start the code of js file sidebar.js*/
    $scope.GetUserData=function(MatchId){
	var currentPage=$state.current.name;
	
        $http.get( BASE_URL+'Betentrycntr/GatBetData/' + 0 + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + MatchId).success(function(data, status, headers, config) {
		//
            $scope.UserData = data.betUserData;
        });
    }
$scope.GetAllBets=function(){
      $http.get( BASE_URL+'Apiusercontroller/get_all_bets').success(function(data, status, headers, config) {
		
            $scope.UserData = data.data;
        });
}

$scope.$on('BindUserBets',function(event,data){
	//
	 $scope.GetUserData(data.MatchId);
});
$scope.$on('BindAllBets',function(event,data){
	//
	$scope.GetAllBets();
});
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
                    $http.get( BASE_URL+'Geteventcntr/getSeriesLst/' + sportsId).success(function (data, status, headers, config) {
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
			$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=0;
		}
}
$scope.CounRemainingStack=function(unique_id,type)
{

	var recorde=$scope.BackLayArray;
	var sum=0;
	var isSame=true;
		for(var j=recorde.length-1;j>=0;j--)
			{
				if(recorde[j].unique_id!=unique_id)
				{
					isSame=false;
					var ind = recorde.findIndex(x=>x.unique_id==unique_id);
				
					if(type==0)
					{
						if(recorde[j].isback==0)
						{
						sum+=recorde[j].stake;
						}
						else
						{
							if(ind==-1)
							sum+=-recorde[j].stake;
							else
							 sum+=recorde[j].stake;
						}
						
					}
					else
					{
						if(recorde[j].isback==0)
						{
							sum+=recorde[j].stake;
							
						}
						else
						{
							if(ind==-1)
						          sum+=-recorde[j].stake;
							else
							  sum+=recorde[j].stake;
						
						}
					}
				}
				
			}
	if(isSame && recorde.length>1)
					{
						var j = recorde.findIndex(x=>x.unique_id==unique_id);
						if($scope.prevSelection['field_'+unique_id]==recorde[j].selectionId && $scope.prevBack['field_'+unique_id]!=type)
						{
							var ind = recorde.findIndex(x=>x.unique_id==unique_id && x.isback==$scope.prevBack['field_'+unique_id]);
				
					if(type==0)
					{
						if(recorde[j].isback==0)
						{
						sum+=recorde[ind].p_l;
						}
						else
						{
							sum+=-recorde[ind].p_l;
						}
						
					}
					else
					{
						if(recorde[j].isback==0)
						{
							sum+=recorde[ind].p_l;
						}
						else
						{
							
							  sum+=recorde[ind].p_l;
						
						}
					}
				}
				else
				{
			if($scope.prevSelection['field_'+unique_id]==recorde[j].selectionId && $scope.prevBack['field_'+unique_id]==type)
					{
						var back=type==1 ? 0 : 1;
				var ind = recorde.findIndex(x=>x.unique_id==unique_id && x.isback==back);
							if(type==0)
							{
								 sum+=recorde[ind].p_l;
							}
							else
							{
								 sum+=recorde[ind].p_l;
							}
					}
				}
			}

	
return sum;
	
}
$scope.CalculateProfitLoss = function(back,type)
{
	
	for(var i=0;i<$rootScope.stakeIds.length;i++){
		if($rootScope.stakeIds[i]==back.unique_id)
				{
					if(back.isback==0)
					{
			
			var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==back.unique_id);
					if(ind>-1){
				if($scope.BackLayArray[ind].isback==0 && $scope.prevSelection['field_'+back.unique_id]!=back.selectionId)
							{
							 $rootScope.stake2['field_'+back.unique_id]=back.p_l - $scope.CounRemainingStack(back.unique_id,back.isback);
						}
						else
						{
						 $rootScope.stake2['field_'+back.unique_id]=back.p_l + $scope.CounRemainingStack(back.unique_id,back.isback);
						}
				
					}
					else
					{
				$rootScope.stake2['field_'+back.unique_id]=0 - $scope.CounRemainingStack(back.unique_id,back.isback);
					}
					
					}
					
					else
					{
						var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==back.unique_id);
					if(ind>-1){
					if($scope.BackLayArray[ind].isback==0)
							{
				 $rootScope.stake2['field_'+back.unique_id]=$scope.CounRemainingStack(back.unique_id,back.isback)-back.p_l;
						}else
					{
						if($scope.prevSelection['field_'+back.unique_id]==back.selectionId && $scope.prevBack['field_'+back.unique_id]!=back.isback){
				 $rootScope.stake2['field_'+back.unique_id]=$scope.CounRemainingStack(back.unique_id,back.isback)-back.p_l;
						}
						else{
				$rootScope.stake2['field_'+back.unique_id]=-$scope.CounRemainingStack(back.unique_id,back.isback)-back.p_l;
						}
						}
							}
							else
							{	
								if(ind==-1)
								{
									$rootScope.stake2['field_'+back.unique_id]=back.isback==1?-$scope.CounRemainingStack(back.unique_id,back.isback):$scope.CounRemainingStack(back.unique_id,back.isback)
}
								else
								{$rootScope.stake2['field_'+back.unique_id]=0-back.p_l;}
							}
						
					}
			        }
				else
				{
					if(back.isback==0)
					{
						var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==$rootScope.stakeIds[i]);
						if(ind>-1){
	if($scope.BackLayArray[ind].isback==0)
							{
	$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=$scope.BackLayArray[ind].p_l-$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback);
}
			else{
					$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-($scope.BackLayArray[ind].p_l+$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback));
			}
						}
						else
						{
							$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=- $scope.CounRemainingStack($rootScope.stakeIds[i],back.isback);
						}
					}
					else
					{
						var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==$rootScope.stakeIds[i]);
						if(ind>-1){
							if($scope.BackLayArray[ind].isback==1)
							{
							$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback)-$scope.BackLayArray[ind].p_l;
							}
							else
							{
							$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback)+$scope.BackLayArray[ind].p_l;
							}
						}
						else
						{
			if(back.isback==1)
			$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback)-0;
			else
			$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=$scope.CounRemainingStack($rootScope.stakeIds[i],back.isback)-0;
						}
					}
				}
	}
$scope.prevBack['field_'+back.unique_id]=back.isback;
$scope.prevSelection['field_'+back.unique_id]=back.selectionId;
}
$scope.CalculateProfitLoss121=function(back,type)
{

var recorde=$scope.BackLayArray;
		
			//$rootScope.stake2['field_'+back.unique_id]=0;

			if(recorde.length >0){
			for(var j=recorde.length-1;j>=0;j--)
			{
				if(recorde[j].unique_id==back.unique_id)
				{
					if(recorde[j].selectionId==back.selectionId && back.isback==0)
					{
						if(type==1)//add
						{
 					             $rootScope.stake2['field_'+back.unique_id]+=(recorde[j].p_l-$scope.prevBack['field_'+back.unique_id]);
						
						}
						else
						{
						  $rootScope.stake2['field_'+back.unique_id]-=recorde[j].p_l;
						}
						
					}
					else
					{
						if(type==1)//add
						{
						       $rootScope.stake2['field_'+back.unique_id]+=-recorde[j].p_l;
						}
						else
						{
							$rootScope.stake2['field_'+back.unique_id]+=recorde[j].p_l;
						}
					}
				}
				else
				{
					//$rootScope.stake2['field_'+back.unique_id]=0;
				       /*if(recorde[j].isback==0)
					{
						$rootScope.stake2['field_'+recorde[j].unique_id]=-back.stake;

					}
					else
					{
						$rootScope.stake2['field_'+recorde[j].unique_id]=+back.stake;

					}*/
				}
			}
			for(var i=0;i<$rootScope.stakeIds.length;i++){
					if($rootScope.stakeIds[i]!=back.unique_id)
					{
						if(back.isback==0)
						{
							if(type==1)//add
							{
							  // var temp=$rootScope.stake2['field_'+$rootScope.stakeIds[i]]-back.stake;
						          // $rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-temp;
							  // $rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-$rootScope.stake2['field_'+$rootScope.stakeIds[i]];
				if($scope.oldstake2['field_' + back.unique_id]==angular.isUndefinedOrNull)
				{
					$scope.oldstake2['field_' + back.unique_id]=0;
				}
			   $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=-back.stake+$scope.oldstake2['field_' + back.unique_id];
							}
							else
							{
								 $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=back.stake;
							}

						}
						else
						{
						    
							if(type==1)//add
							{
						            $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=back.stake;
							}
							else
							{
								 $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=-back.stake;
							}
						}
					}
					
			      }
				$scope.oldstake2['field_'+back.unique_id]=back.stake
			}
			else
			{
				for(var i=0;i<$rootScope.stakeIds.length;i++){
					$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=0;

				}
			}
		
		
}







$scope.CalculateProfitLoss123456=function(back,type)
{

var recorde=$scope.BackLayArray;
		
			//$rootScope.stake2['field_'+back.unique_id]=0;

			if(recorde.length >0){
			for(var j=recorde.length-1;j>=0;j--)
			{
				if(recorde[j].unique_id==back.unique_id)
				{
					if(recorde[j].selectionId==back.selectionId && back.isback==0)
					{
						if(type==1)//add
						{
 					             $rootScope.stake2['field_'+back.unique_id]+=(recorde[j].p_l-$scope.prevBack['field_'+back.unique_id]);
						
						}
						else
						{
						  $rootScope.stake2['field_'+back.unique_id]-=recorde[j].p_l;
						}
						
					}
					else
					{
						if(type==1)//add
						{
						       $rootScope.stake2['field_'+back.unique_id]+=-recorde[j].p_l;
						}
						else
						{
							$rootScope.stake2['field_'+back.unique_id]+=recorde[j].p_l;
						}
					}
				}
				else
				{
					//$rootScope.stake2['field_'+back.unique_id]=0;
				       /*if(recorde[j].isback==0)
					{
						$rootScope.stake2['field_'+recorde[j].unique_id]=-back.stake;
					}
					else
					{
						$rootScope.stake2['field_'+recorde[j].unique_id]=+back.stake;
					}*/
				}
			}
			for(var i=0;i<$rootScope.stakeIds.length;i++){
					if($rootScope.stakeIds[i]!=back.unique_id)
					{
						if(back.isback==0)
						{
							if(type==1)//add
							{
							   var temp=$rootScope.stake2['field_'+$rootScope.stakeIds[i]]-back.stake;
						           $rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-temp;
							   $rootScope.stake2['field_'+$rootScope.stakeIds[i]]=-$rootScope.stake2['field_'+$rootScope.stakeIds[i]];
							}
							else
							{
								 $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=back.stake;
							}

						}
						else
						{
						    
							if(type==1)//add
							{
						            $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=back.stake;
							}
							else
							{
								 $rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=-back.stake;
							}
						}
					}
					
			      }
				$scope.oldstake2['field_'+back.unique_id]=back.stake
			}
			else
			{
				for(var i=0;i<$rootScope.stakeIds.length;i++){
					$rootScope.stake2['field_'+$rootScope.stakeIds[i]]=0;
				}
			}
		
		
}

	$scope.ReCalculatePl=function(back)
	{
		
		for(var i=0;i<$rootScope.stakeIds.length;i++){
			if($rootScope.stakeIds[i]==back.unique_id){
				if(back.isback==0)
					{
						$rootScope.stake2['field_'+back.unique_id]-=back.p_l;
					}
				else{
						$rootScope.stake2['field_'+back.unique_id]+=back.p_l;
					}
			}
			else
			{
			//assign stack will be added
			if(back.isback==0)
			$rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=back.stake;
			else
			$rootScope.stake2['field_'+$rootScope.stakeIds[i]]+=-back.stake;
			}
		}
	}
$scope.CalculateSum=function()
{
var sum=0;
	for(var i=0;i<$scope.BackLayArray.length;i++)
	{
		var obj=$scope.BackLayArray[i];
		if(obj.isback==0)
		{
			sum=(parseFloat(sum)+parseFloat(obj.stake)).toFixed(2);
		}
		else
		{
			sum=(parseFloat(sum)+parseFloat((obj.priceVal*obj.stake)-obj.stake)).toFixed(2);
		}
	}
return sum;
}
$scope.updateLiability = function(back)
{

if (isNaN(back.stake) || back.stake==angular.isUndefinedOrNull) {
back.stake=0;

}
back.p_l=((back.priceVal*back.stake)-back.stake);
$scope.CalculateProfitLoss(back,0);
var stake=((back.priceVal*back.stake)-back.stake);//lay
var stkbtn=back.stake;//back
if (isNaN($rootScope.total_liability)) {
$rootScope.total_liability=0;
}

$rootScope.total_liability=$scope.CalculateSum();

}
$scope.prevBack={};
$scope.prevSelection={};
$scope.addStake = function(stake,stkbtn,isback,back){

var tempback=back;

$rootScope.total_liability=0;
$scope.stackval= back.stake;
back.stake=parseFloat(stkbtn)+parseFloat($scope.stackval);
back.p_l=((back.priceVal*back.stake)-back.stake);
//
$rootScope.total_liability=$scope.CalculateSum();
$scope.CalculateProfitLoss(back,1);//1 for add
}

$scope.stakeval=[];
            $scope.saveMatchoddstake = function () {
		//
               
                var stakeData = {
                    match_stake: $scope.stakesettingData,
              
                }
                $http({ method: 'POST', url: 'Apiusercontroller/stake_setting/', data: stakeData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    
                    $scope.stakemsg = data.message;
		    Dialog.autohide($scope.stakemsg);
		    $("#edit_popup .btn-secondary").click();
                });
            }
  $scope.SaveOneClick = function () {
	//	
               
                var stakeData = {
                    one_click_stake: $rootScope.one_click_stack,
              
                }
                $http({ method: 'POST', url: 'Apiusercontroller/one_click_stake_setting/', data: stakeData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    //
                    $scope.stakemsg = data.message;
		    Dialog.autohide($scope.stakemsg);
		    $scope.isEdit=true;
                });
            }

$scope.setstacksetng = function(currentpos){
		$rootScope.currentpos=currentpos;
		
}

           
            $scope.getStakesett = function (sportsId, seriesId) {
		//
		         $http.get( BASE_URL+'Apiusercontroller/get_stake_setting/').success(function (data, status, headers, config) {
			
		    $rootScope.MatchStack=data.data.match_stake;

		   if($scope.IsToggle)
			{
				$scope.btnActive=0;
				 $rootScope.one_click_stack=data.data.one_click_stake;
				 $scope.oneClickSelectedBet=$rootScope.one_click_stack[0];
				       $scope.stakesettingData =data.data.match_stake;
			}
			else
			{
				       $scope.stakesettingData =data.data.match_stake;
			}
			
		});
		

	    }

	
    $scope.backArray=[];
    $scope.layArray=[];
    $scope.BackLayArray=[];
            $scope.AddBackOrLay = function(obj)
		{
		//	
			$scope.betslipinfo=true;
			$scope.arrayObj = obj;
			$scope.arrayObj.stake=0;
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
					var ind=$scope.backArray.findIndex(x=>(x.unique_id==obj.unique_id || x.selectionId==obj.selectionId) && x.matchId==obj.matchId);
					if(ind==-1)
					{
					$scope.backArray.splice(0,0,$scope.arrayObj);
					$scope.BackLayArray.splice(0,0,$scope.arrayObj);
					}
				}
				else{
					var ind=$scope.layArray.findIndex(x=>(x.unique_id==obj.unique_id || x.selectionId==obj.selectionId) && x.matchId==obj.matchId);
					if(ind==-1)
					{
					$scope.layArray.splice(0,0,$scope.arrayObj);
					$scope.BackLayArray.splice(0,0,$scope.arrayObj);
					}
				   }
			if($scope.IsToggle) //one click bet
			{
		
				$scope.Place_bet();
			}
				
		}
      $scope.AddBackOrLay12 = function(obj)
		{
			$scope.arrayObj = obj;
			   
					var ind=$scope.BackLayArray.findIndex(x=>x.unique_id==obj.unique_id || x.selectionId==obj.selectionId);
					if(ind==-1)
					{
					$scope.BackLayArray.splice(0,0,$scope.arrayObj);
					}
			
		}
	    $scope.$on('CallAddBackOrLay',function(event, data){
		//	
			$scope.AddBackOrLay(data);
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
			
			$scope.BackLayArray.splice(ind1,1);
			$scope.prevSelection['field_'+back.unique_id]=0;
			$scope.CalculateProfitLoss(back,0);
			
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
			
			var combindata = $scope.BackLayArray;
			var IsError=false;
			for(var i=0;i<combindata.length;i++)
			{
				if(combindata[i].stake <=0 || combindata[i].stake==angular.isUndefinedOrNull)
				{
					combindata[i].isError=true;
					IsError=true;
				}
			}
		if(!IsError)
		{
			$scope.loading=true;
			$http({ method: 'POST', url: 'Apiusercontroller/save_multi_bet', data: combindata, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    
                 $scope.loading=false;
		 var result = data;
		 var checkError=false;
		
		for(var j=0;j<result.length;j++)
		{
			var ind = $scope.BackLayArray.findIndex(x=>x.unique_id==result[j].unique_id)
			{
				if(result[j].result.error)
				{
					$scope.BackLayArray[ind].IsErrorShow=true;
					$scope.BackLayArray[ind].Message=result[j].result.message;
					checkError=true;
				}
				else
				{
					$scope.BackLayArray[ind].IsErrorShow=false;
					$scope.BackLayArray[ind].Message=result[j].result.message;
					$scope.RemoveBackLay($scope.BackLayArray[ind].unique_id,$scope.BackLayArray[ind].isback,0,0,$scope.BackLayArray[ind].isback,$scope.BackLayArray[ind]);
					//$rootScope.$broadcast('callMarketWinLoss',{});
					
					
					
				}
			}
		}
		if(!checkError)
		{
			Dialog.autohide("Bet placed successfully.");
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
                $http.get( BASE_URL+'Lstsavemstrcontroller/lstSaveMaster/' + sessionService.get('user_id') + '/' + sessionService.get('type') + '/' + sessionService.get('HelperID') + '/' + sessionService.get('Helperype')).success(function (data, status, headers, config) {
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
            $http.get( BASE_URL+'Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
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
                $http.get( BASE_URL+'Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
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
		$scope.getSeriesMatch(data.id, 0);
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
		$scope.getSeriesMatch(data.id, 0);
		$scope.ManageDLL(data.id,data.MstCode,data.matchName)
	   });
	    $scope.ManageDLL = function (sid,matchid,name){
 		$("#sp"+sid).trigger('click');
		$('#'+sid).addClass('in').attr("aria-expanded","true").removeAttr("style").trigger('click');
		
			
		}
var upBal="";
 $scope.updateBal=function()
    {
        upBal = $timeout(function(){   $http.get( BASE_URL+'Chipscntrl/getChipDataById/' +  sessionService.get('user_id')).success(function (data, status, headers, config) {
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
        });
        $scope.updateBal(); 
    },1000)
    }
    $scope.updateBal();
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
                    $scope.loading=true;
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
})();
