'use strict';
angular.module('ApsilonApp').controller('homedashboard',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$rootScope','$location','$state','Dialog','Base64','get_userser', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter,$rootScope,$location,$state,Dialog,Base64,get_userser) {

  var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
    $http.defaults.headers.common['devicetype']='M';
	 $("#main-menu").show();   

$scope.socketCricket=[];
$scope.socketSoccer=[];
$scope.socketTennis=[];
//$scope.bindCricket=[];
$scope.bindSoccer=[];
$scope.bindTennis=[];
$rootScope.MatchStack=[];
$rootScope.one_click_stack=[];
$rootScope.SessionStack=[];
$scope.ajaxTimerC="";
$scope.ajaxTimerS="";
$scope.ajaxTimerT="";
$scope.InplayMarketId=[];
    if(sessionService.get('BindArrayItems') != angular.isUndefinedOrNull)
    {

        $scope.FBindArrayItems = JSON.parse(sessionService.get('BindArrayItems'));
        // $scope.bindCricket=[];
        $scope.BindArrayItems=$scope.FBindArrayItems;
    }
    else {

        var obj=[{'id':'Cricket','sportData':$scope.bindCricket},{'id':'Soccer','sportData':$scope.bindSoccer},{'id':'Tennis','sportData':$scope.bindTennis}];
        $scope.BindArrayItems=obj;
    }

//step 1
$scope.SetCommonProperty=function(SId,MId)
{

if(SId!=angular.isUndefinedOrNull && MId.matchid !=angular.isUndefinedOrNull && MId.marketid!=angular.isUndefinedOrNull)
{
$rootScope.$broadcast('callAssignKeyInit',{'SelectionId':SId,'MatchId':MId.matchid,'MarketId':MId.marketid});
}
}
$rootScope.$broadcast('MatchOddsTimeOut',{});
$scope.AllSocketData=[];
$scope.CallSocketData=function()
{
	$scope.ajaxTimerC = $timeout(function(){
		if($state.current.name=='userDashboard.Home' && $scope.AllMarketIds!=angular.isUndefinedOrNull){
	   get_userser.getSocketDataHomeApi($scope.AllMarketIds,function(data) {
		
			if(data.length>0){
				$scope.AllSocketData=data;
                $scope.CallCricketSocket(data);
                $scope.CallSoccerSocket(data);
                $scope.CallTennisSocket(data);
				
				
			}
			$scope.CallSocketData();
		});
		}
	},1000);
}

    $scope.SetSelectionName=function(type,i,runner,isTrue)
    {
        if(type==4)
        {
            $scope.market_sel_4=[];
            if($scope.bindCricket[i].runners.length>0)
            {
                for(var r=0;r<$scope.bindCricket[i].runners.length;r++)
                {
                    if($scope.bindCricket[i].runners[r].name=="" || $scope.bindCricket[i].runners[r].name==angular.isUndefinedOrNull)
                    {
                        var sId=$scope.bindCricket[i].marketid +"-"+ $scope.bindCricket[i].runners[r].id;
                        $scope.bindCricket[i].runners[r].selection_id=sId;
                        $scope.market_sel_4.push(sId);
                    }
                    if(false){
                        var indx = runner.runners.findIndex(x=>x.id==$scope.bindCricket[i].runners[r].id);
                        if(indx>-1)
                        {
                            $scope.bindCricket[i].runners[r].back=runner.runners[indx].back;
                            $scope.bindCricket[i].runners[r].lay=runner.runners[indx].lay;
                            $scope.bindCricket[i].runners[r].id=runner.runners[indx].id;
                            $scope.bindCricket[i].runners[r].status=runner.runners[indx].status;
                        }
                    }


                }

                if($scope.market_sel_4.length>0)
                {
                    $scope.SelectionName=[];
                    var selection_id=$scope.market_sel_4.join(',');
                    if(selection_id!="" && selection_id !=angular.isUndefinedOrNull) {
                        get_userser.getSelectionList(selection_id, function (data) {

                            $scope.SelectionName = data;
                            for (var s = 0; s < $scope.SelectionName.length; s++) {
                                var indx = $scope.bindCricket[i].runners.findIndex(x => x.selection_id == $scope.SelectionName[s].selection_id)
                                if (indx > -1) {
                                    $scope.bindCricket[i].runners[indx].name = $scope.SelectionName[s].runnername;
                                }
                            }


                        });
                    }
                }
            }
        }
        else if(type==2) //tennis
        {
            $scope.market_sel_2=[];
            if($scope.bindTennis[i].runners.length>0)
            {
                for(var r=0;r<$scope.bindTennis[i].runners.length;r++)
                {
                    if($scope.bindTennis[i].runners[r].name=="" || $scope.bindTennis[i].runners[r].name==angular.isUndefinedOrNull)
                    {
                        var sId=$scope.bindTennis[i].marketid +"-"+ $scope.bindTennis[i].runners[r].id;
                        $scope.bindTennis[i].runners[r].selection_id=sId;
                        $scope.market_sel_2.push(sId);
                    }
                    if(false){
                        var indx = runner.runners.findIndex(x=>x.id==$scope.bindTennis[i].runners[r].id);
                        if(indx>-1)
                        {
                            $scope.bindTennis[i].runners[r].back=runner.runners[indx].back;
                            $scope.bindTennis[i].runners[r].lay=runner.runners[indx].lay;
                            $scope.bindTennis[i].runners[r].id=runner.runners[indx].id;
                            $scope.bindTennis[i].runners[r].status=runner.runners[indx].status;
                        }
                    }


                }

                if($scope.market_sel_2.length>0)
                {
                    $scope.SelectionName=[];
                    var selection_id=$scope.market_sel_2.join(',');
                    if(selection_id!="" && selection_id !=angular.isUndefinedOrNull) {
                        get_userser.getSelectionList(selection_id, function (data) {

                            $scope.SelectionName = data;
                            for (var s = 0; s < $scope.SelectionName.length; s++) {
                                var indx = $scope.bindTennis[i].runners.findIndex(x => x.selection_id == $scope.SelectionName[s].selection_id)
                                if (indx > -1) {
                                    $scope.bindTennis[i].runners[indx].name = $scope.SelectionName[s].runnername;
                                }
                            }


                        });
                    }
                }
            }
        }
        else if(type==1)
        {
            $scope.market_sel_1=[];
            if($scope.bindSoccer[i].runners.length>0)
            {
                for(var r=0;r<$scope.bindSoccer[i].runners.length;r++)
                {
                    if($scope.bindSoccer[i].runners[r].name=="" || $scope.bindSoccer[i].runners[r].name==angular.isUndefinedOrNull)
                    {
                        var sId=$scope.bindSoccer[i].marketid +"-"+ $scope.bindSoccer[i].runners[r].id;
                        $scope.bindSoccer[i].runners[r].selection_id=sId;
                        $scope.market_sel_1.push(sId);
                    }
                    if(false){
                        var indx = runner.runners.findIndex(x=>x.id==$scope.bindSoccer[i].runners[r].id);
                        if(indx>-1)
                        {
                            $scope.bindSoccer[i].runners[r].back=runner.runners[indx].back;
                            $scope.bindSoccer[i].runners[r].lay=runner.runners[indx].lay;
                            $scope.bindSoccer[i].runners[r].id=runner.runners[indx].id;
                            $scope.bindSoccer[i].runners[r].status=runner.runners[indx].status;
                        }
                    }


                }

                if($scope.market_sel_1.length>0)
                {
                    $scope.SelectionName=[];
                    var selection_id=$scope.market_sel_4.join(',');
                    if(selection_id!="" && selection_id !=angular.isUndefinedOrNull) {
                        get_userser.getSelectionList(selection_id, function (data) {

                            $scope.SelectionName = data;
                            for (var s = 0; s < $scope.SelectionName.length; s++) {
                                var indx = $scope.bindSoccer[i].runners.findIndex(x => x.selection_id == $scope.SelectionName[s].selection_id)
                                if (indx > -1) {
                                    $scope.bindSoccer[i].runners[indx].name = $scope.SelectionName[s].runnername;
                                }
                            }


                        });
                    }
                }
            }
        }
    }

$scope.CallCricketSocket=function(data)
{
	 var dataResult = data;
				//data.result;
		                     var tempResult =[];
		             
		                         if(dataResult !=angular.isUndefinedOrNull && dataResult.length>0)
		                         {
						$scope.socketCricket=dataResult;
						for(var i=0;i<$scope.bindCricket.length;i++)
						{
						    var ind = $scope.socketCricket.findIndex(x=>x.id==$scope.bindCricket[i].marketid);
						    if(ind>-1)
						      {
								var tempResult ={};
								$scope.bindCricket[i].status=$scope.socketCricket[ind].status;
								$scope.checkStaus(4,$scope.bindCricket[i].status);
								
								$scope.bindCricket[i].inplay=$scope.socketCricket[ind].inPlay;
								if($scope.bindCricket[i].status=="OPEN" && $scope.bindCricket[i].inplay)
								{
									$scope.bindCricket[i].Sort=0;
                                    var indc=$scope.InplayMarketId.findIndex(x=>x==$scope.bindCricket[i].marketid);
                                    if(indc==-1)
                                    {
                                        $scope.InplayMarketId.push($scope.bindCricket[i].marketid);
                                    }
								}
								else
								{
									$scope.bindCricket[i].Sort=1;
								}
								//$scope.bindCricket[i].matchdate=$scope.socketCricket[ind].event.openDate;

                                  if($scope.bindCricket[i].runners.length>0)
                                  {
                                      $scope.SetSelectionName(4,i,$scope.socketCricket[ind]);
                                  }
                                  else
                                  {
                                      $scope.bindCricket[i].runners=$scope.socketCricket[ind].runners;
                                      $scope.SetSelectionName(4,i,null);
                                  }
								//$scope.bindCricket[i].runners=$scope.socketCricket[ind].runners;
								
								$scope.SocketMarket(i,$scope.socketCricket[ind].runners,4);
								$scope.bindCricket[i].IsMatchDisable=false;
								
						      }
							else{
								$scope.bindCricket[i].IsMatchDisable=true;
                                $scope.bindCricket[i].Sort=1;
							}
						}
						var obj={'id':'Cricket','sportData':$scope.bindCricket}
						$scope.BindArrayItems[0]=obj;
						
					 }
}
$scope.CallTennisSocket=function(data)
{

			//console.log(data);
	 			 var dataResult = data;
		                     var tempResult =[];
		                
		                         if(dataResult !=angular.isUndefinedOrNull && dataResult.length>0)
		                         {
						$scope.socketTennis=dataResult;
						for(var i=0;i<$scope.bindTennis.length;i++)
						{
						    var ind = $scope.socketTennis.findIndex(x=>x.id==$scope.bindTennis[i].marketid);
						    if(ind>-1)
						      {
								var tempResult ={};
								$scope.bindTennis[i].status=$scope.socketTennis[ind].status;
								$scope.checkStaus(2,$scope.bindTennis[i].status);
								$scope.bindTennis[i].inplay=$scope.socketTennis[ind].inPlay;
								//$scope.bindTennis[i].matchdate=$scope.socketTennis[ind].event.openDate;
								//$scope.bindTennis[i].runners=$scope.socketTennis[ind].runners;
                                  if($scope.bindTennis[i].runners.length>0)
                                  {
                                      $scope.SetSelectionName(2,i,$scope.socketTennis[ind]);
                                  }
                                  else
                                  {
                                      $scope.bindTennis[i].runners=$scope.socketTennis[ind].runners;
                                      $scope.SetSelectionName(2,i,null);
                                  }
								$scope.SocketMarket(i,$scope.socketTennis[ind].runners,2);
								$scope.bindTennis[i].IsMatchDisable=false;
								if($scope.bindTennis[i].status=="OPEN" && $scope.bindTennis[i].inplay)
								{
									$scope.bindTennis[i].Sort=0;
                                    var indt=$scope.InplayMarketId.findIndex(x=>x==$scope.bindTennis[i].marketid);
                                    if(indt==-1)
                                    {
                                        $scope.InplayMarketId.push($scope.bindTennis[i].marketid);
                                    }
								}
								else
								{
									$scope.bindTennis[i].Sort=1;
								}
						      }
							else
							{
								$scope.bindTennis[i].IsMatchDisable=true;
                                $scope.bindTennis[i].Sort=1;
							}
						}
					var obj={'id':'Tennis','sportData':$scope.bindTennis}
						$scope.BindArrayItems[2]=obj;
					 }
}
$scope.CallSoccerSocket=function(data)
{

	 			 var dataResult = data;
		                     var tempResult =[];
		                
		                         if(dataResult !=angular.isUndefinedOrNull && dataResult.length>0)
		                         {
						$scope.socketSoccer=dataResult;

						for(var i=0;i<$scope.bindSoccer.length;i++)
						{
						    var ind = $scope.socketSoccer.findIndex(x=>x.id==$scope.bindSoccer[i].marketid);
						    if(ind>-1)
						      {
								var tempResult ={};
								$scope.bindSoccer[i].status=$scope.socketSoccer[ind].status;
								$scope.checkStaus(1,$scope.bindSoccer[i].status);
								$scope.bindSoccer[i].inplay=$scope.socketSoccer[ind].inPlay;
								//$scope.bindSoccer[i].matchdate=$scope.socketSoccer[ind].event.openDate;
								//$scope.bindSoccer[i].runners=$scope.socketSoccer[ind].runners;
                                  if($scope.bindSoccer[i].runners.length>0)
                                  {
                                      $scope.SetSelectionName(1,i,$scope.socketSoccer[ind]);
                                  }
                                  else
                                  {
                                      $scope.bindSoccer[i].runners=$scope.socketSoccer[ind].runners;
                                      $scope.SetSelectionName(1,i,null);
                                  }
								$scope.SocketMarket(i,$scope.socketSoccer[ind].runners,1);
								$scope.bindSoccer[i].IsMatchDisable=false;
								if($scope.bindSoccer[i].status=="OPEN" && $scope.bindSoccer[i].inplay)
								{
									$scope.bindSoccer[i].Sort=0;
                                    var inds=$scope.InplayMarketId.findIndex(x=>x==$scope.bindSoccer[i].marketid);
                                    if(inds==-1)
                                    {
                                        $scope.InplayMarketId.push($scope.bindSoccer[i].marketid);
                                    }
								}
								else
								{
									$scope.bindSoccer[i].Sort=1;
								}
						      }
						      else
							{
								$scope.bindSoccer[i].IsMatchDisable=true;
                                $scope.bindSoccer[i].Sort=1;
							}
						}
						var obj={'id':'Soccer','sportData':$scope.bindSoccer}
						$scope.BindArrayItems[1]=obj;
					 }

}
    $scope.getSumValPnL = function(a, b) {

        if(a==angular.isUndefinedOrNull && b==angular.isUndefinedOrNull){
            a=0;
            b=0;
        }
        else
        {
            var temp = parseFloat(a) + parseFloat(b);
        }

        return (parseFloat(a) + parseFloat(b));
    }
function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min;
  return time;
}
    var callSport=1;
    $scope.sportDetail=[];
   $scope.getSportDetail = function(sportId)
	{
        $scope.sportTime=$timeout(function() {
            if ($state.current.name == 'userDashboard.Home') {
                if (callSport == 1) {
                    //$scope.loading = true;
                    if($scope.bindCricket==angular.isUndefinedOrNull)
                    {
                        $scope.bindCricket=[];
                    }

                }
                $http.defaults.headers.common['inplay']=$scope.InplayMarketId.join(',');
                $http({
                    method: 'GET',
                    url: BASE_URL+'Apiusercontroller/getUserFavouriteMatchLst/'+sportId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {

                    if(data!=angular.isUndefinedOrNull)
                    {
                        if(callSport==1){
                            var marketIdstr="";
                            $rootScope.MatchStack=data.match_stack;
                            $rootScope.SessionStack=data.session_stack;
                            //$rootScope.one_click_stack=data.one_click_stack;

                            $rootScope.$broadcast('BindAllBets',{});


                        }
                        $scope.sportDetail=data.data;
                        for(var i=0;i<$scope.sportDetail.length;i++)
                        {
                            if($scope.sportDetail[i].SportId==4 && $scope.sportDetail[i].values.length>0)
                            {
                                if($scope.bindCricket.length!=$scope.sportDetail[i].values.length)
                                {
                                    $scope.bindCricket=$scope.sportDetail[i].values;


                                    var obj={'id':'Cricket','sportData':$scope.bindCricket}
                                    $scope.BindArrayItems[0]=obj;

                                    var ind1=data.market_ids.findIndex(x=>x.SportId==4);
                                    if(ind1>-1)
                                    {
                                        //var marketId=data.market_ids[ind1].marketids;
                                        //marketIdstr+=marketId;
                                        //$scope.CallCricketSocket(marketId);
                                    }
                                }
                                else {

                                    angular.forEach($scope.sportDetail[i].values, function(value, key) {
                                        var indx = $scope.bindCricket.findIndex(x=>x.marketid==value.marketid);
                                        if(indx>-1)
                                        {
                                            $scope.bindCricket[indx].win_loss=value.win_loss;                                $scope.bindCricket[indx].visibility=value.visibility;
                                            $scope.bindCricket[indx].isBetAllowedOnManualMatchOdds=value.isBetAllowedOnManualMatchOdds;
                                            $scope.bindCricket[indx].day=value.day;
                                            $scope.bindCricket[indx].matchdate=value.matchdate;
                                            $scope.bindCricket[indx].isfancy=value.isfancy;
                                            if($scope.bindCricket[indx].is_manual==1)
                                            {
                                                $scope.bindCricket[indx].runners=value.runners;
                                            }
                                        }

                                    });
                                }


                            }
                            else if($scope.sportDetail[i].SportId==2 && $scope.sportDetail[i].values.length>0)
                            {
                            	if($scope.bindTennis.length!=$scope.sportDetail[i].values.length)						{

                                    $scope.bindTennis=$scope.sportDetail[i].values;
                                    var obj={'id':'Tennis','sportData':$scope.bindTennis}
                                    $scope.BindArrayItems[2]=obj;
                                    var ind1=data.market_ids.findIndex(x=>x.SportId==2);
                                    if(ind1>-1)
                                    {
                                        //var marketId=data.market_ids[ind1].marketids;
                                        //marketIdstr+=marketId;
                                        //$scope.CallTennisSocket(marketId);
                                    }
								}
                                else {

                                    angular.forEach($scope.sportDetail[i].values, function(value, key) {
                                        var indx = $scope.bindTennis.findIndex(x=>x.marketid==value.marketid);
                                        if(indx>-1)
                                        {
                                            $scope.bindTennis[indx].win_loss=value.win_loss;
                                            $scope.bindTennis[indx].visibility=value.visibility;
                                            $scope.bindTennis[indx].isBetAllowedOnManualMatchOdds=value.isBetAllowedOnManualMatchOdds;

                                            if($scope.bindTennis[indx].is_manual==1)
                                            {
                                                $scope.bindTennis[indx].runners=value.runners;
                                            }
                                        }

                                    });
                                }


                            }
                            else if($scope.sportDetail[i].SportId==1 && $scope.sportDetail[i].values.length>0)
                            {
                                if($scope.bindSoccer.length!=$scope.sportDetail[i].values.length)
								{
                                    $scope.bindSoccer=$scope.sportDetail[i].values;
                                    var obj={'id':'Soccer','sportData':$scope.bindSoccer}
                                    $scope.BindArrayItems[1]=obj;
                                    var ind1=data.market_ids.findIndex(x=>x.SportId==1);
                                    if(ind1>-1)
                                    {
                                        //var marketId=data.market_ids[ind1].marketids;
                                        //marketIdstr+=marketId;
                                        //$scope.CallSoccerSocket(marketId);
                                    }
								}
								else {

                                    angular.forEach($scope.sportDetail[i].values, function(value, key) {
                                        var indx = $scope.bindSoccer.findIndex(x=>x.marketid==value.marketid);
                                        if(indx>-1)
                                        {
                                            $scope.bindSoccer[indx].win_loss=value.win_loss;
                                            $scope.bindSoccer[indx].visibility=value.visibility;
                                            $scope.bindSoccer[indx].isBetAllowedOnManualMatchOdds=value.isBetAllowedOnManualMatchOdds;
                                            if($scope.bindSoccer[indx].is_manual==1)
                                            {
                                                $scope.bindSoccer[indx].runners=value.runners;
                                            }
                                        }

                                    });
                                }


                            }
                        }
                        if(data.all_market_ids!=""){
                            $scope.AllMarketIds=data.all_market_ids;
                            if(callSport==1) {
                                $scope.CallSocketData();
                            }
                        }
                    }
                    $scope.loading = false;
                    callSport=2;
                    $scope.getSportDetail(sportId);
                }).error(function(err){
                    $scope.loading = false;
                });

            }
    	},1000);

	}

   $scope.CallColor=function(Oldprice,NPrice)
    {
        if(Oldprice!=NPrice)
                    {
                        return true;
                    }
                    else{
                        return false;
                    }
    }
$scope.FArray={};
$scope.SocketMarket = function(j,newData,type)
{
	
    

     $scope.FArray['f_'+type];
     if(type==1)
	{
		$scope.FArray['f_'+type]=$scope.bindSoccer[j];
	}
     else if(type==2)
	{
		$scope.FArray['f_'+type]=$scope.bindTennis[j];
	}
     else if(type==4)
	{
		$scope.FArray['f_'+type]=$scope.bindCricket[j];
	}
    //$scope.CheckBet($scope.FArray['f_'+type]);
    //$scope.FancyLiveData = result.session;
    var market = newData;
 //   var j = $scope.FinalArray.findIndex(x=>x.id==result.data.id);
    if(market!=angular.isUndefinedOrNull)
    {
        
        for(var m=0;m<market.length;m++)
        {
         //  
		if(j>-1){
           var inde = $scope.FArray['f_'+type].runners.findIndex(img => img.id ===market[m].id);
           if(inde>-1)
           {
	      // $scope.FArray['f_'+type].IsMatchDisable=false;
               for(var b=0;b<$scope.FArray['f_'+type].runners[inde].back.length;b++)
               {
                var count = b+1;  
                try{
                    $scope.FArray['f_'+type].runners[inde].back[b].selected =  $scope.CallColor($scope.FArray['f_'+type].runners[inde].back[b].price, market[m].back[b].price);
                }
                catch(e)
                {

                }
               try{
                $scope.FArray['f_'+type].runners[inde].back[b].price = market[m].back[b].price;
               }
               catch(e)
               {
                if($scope.FArray['f_'+type].runners[inde].back[b]!=angular.isUndefinedOrNull)
                {
                    $scope.FArray['f_'+type].runners[inde].back[b].price="";
                }
               }
               try{
                $scope.FArray['f_'+type].runners[inde].back[b].size = market[m].back[b].size;
                 }
                catch(e)
                {
                    
                }
                
              
               }
               for(var b=0;b<$scope.FArray['f_'+type].runners[inde].lay.length;b++)
               {
                var count = b+1;  
		try{
                $scope.FArray['f_'+type].runners[inde].lay[b].selected =  $scope.CallColor($scope.FArray['f_'+type].runners[inde].lay[b].price, market[m].lay[b].price);
		}
		catch(e)
		{
		}               
                try{
                    $scope.FArray['f_'+type].runners[inde].lay[b].price = market[m].lay[b].price;
                }
                catch(e)
                {
                        if($scope.FArray['f_'+type].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                    {
                        $scope.FArray['f_'+type].runners[inde].lay[b].price="";
                    }
                }
                try{
                $scope.FArray['f_'+type].runners[inde].lay[b].size = market[m].lay[b].size;
		}
		catch(e){
			}
               }
      

           }
	   else
		{
			//$scope.FArray['f_'+type].IsMatchDisable=true;
		}
        }
	 if(type==1)
	{
		$scope.bindSoccer[j]=$scope.FArray['f_'+type];
	}
     else if(type==2)
	{
		$scope.bindTennis[j]=$scope.FArray['f_'+type];
	}
     else if(type==4)
	{
		$scope.bindCricket[j]=$scope.FArray['f_'+type];
	}
    }

                   
}   

}

    $scope.FinalArray=[];
    $scope.CheckBet=function(dataresult)
    {

        var i=0;
        $scope.FinalArray[i]=dataresult;
        if ($scope.FinalArray[i] != angular.isUndefinedOrNull) {
            /*start code for Match UnMatch*/
            if ($scope.FinalArray[i] != angular.isUndefinedOrNull && $scope.FinalArray[i].status != "CLOSED" && $scope.MatchResult != "CLOSED") {
                try {
                    for (var u = 0; u < $rootScope.GUserData.length; u++) {
                        if ($scope.FinalArray[i] != angular.isUndefinedOrNull) {
                            $scope.FinalArray[i].runners.find(function (item, j) {
                                if (item.id == $rootScope.GUserData[u].SelectionId && ($scope.FinalArray[i].marketid == $rootScope.GUserData[u].MarketId) && ($rootScope.GUserData[u].MatchId == $scope.FinalArray[i].matchid) && ($rootScope.GUserData[u].IsMatched == 0)) {
                                    if ($rootScope.GUserData[u].isBack == 0) {
                                        if (item.back.length != 0 && $rootScope.GUserData[u].Odds <= (item.back[0].price + parseFloat($scope.FinalArray[i].volumeLimit[0].oddsLimit)).toFixed(2)) {
                                            $http.get(BASE_URL+'Betentrycntr/updateUnMatchedData/' + $rootScope.GUserData[u].MstCode + '/' + 0 + '/' + $scope.FinalArray[i].marketid + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $scope.FinalArray[i].matchid).success(function (data, status, headers, config) {
                                                $rootScope.$broadcast('BindAllBets', {});
                                                // $scope.getBetsData();
                                            });
                                        }
                                    } else {
                                        if (item.lay.length != 0 && $rootScope.GUserData[u].Odds >= (item.lay[0].price + parseFloat($scope.FinalArray[i].volumeLimit[0].oddsLimit)).toFixed(2)) {
                                            $http.get(BASE_URL+'Betentrycntr/updateUnMatchedData/' + $rootScope.GUserData[u].MstCode + '/' + 1 + '/' + $scope.FinalArray[i].marketid + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $scope.FinalArray[i].matchid).success(function (data, status, headers, config) {
                                                $rootScope.$broadcast('BindAllBets', {});
                                                // $scope.getBetsData();
                                            });
                                        }
                                    }
                                }
                            });
                        }
                    }
                } catch (e) {
                    //tem = true;
                }
            }
        }
    }
$scope.$on('$destroy', function () {
 $timeout.cancel($scope.ajaxTimerC);
$timeout.cancel($scope.getResultTime);

});

$scope.ClearAllTimeOut=function()
{
 $timeout.cancel($scope.ajaxTimerC);

}
$scope.$on('clearTimeOut',function(event,data){

$scope.ClearAllTimeOut();
});

$scope.isShow2=false;
	$scope.isShow4=false;
	$scope.isShow1=false;
	$scope.isShow7=false;
 $scope.checkStaus=function(type,status)
	{

		if(type==4)
		{
			if(status=='OPEN')
			{
			 $scope.isShow4=true; 
$scope.loading=false;	
			}	
			else{ $scope.isShow4=false;$scope.loading=false;}
$scope.loading=false;	
		}
else if(type==2)
		{
			if(status=='OPEN')
			{
			 $scope.isShow2=true; $scope.loading=false;	
			}	
			else{ $scope.isShow2=false;$scope.loading=false;}
$scope.loading=false;	
		}	
else if(type==1)
		{
			if(status=='OPEN')
			{
			 $scope.isShow1=true; $scope.loading=false;	
			}	
			else{ 
if(!$scope.isShow1)
{
$scope.isShow1=false;$scope.loading=false;
}
}
$scope.loading=false;	
		}
else if(type==7)
		{
			if(status=='OPEN')
			{
			 $scope.isShow7=true; $scope.loading=false;	
			}	
			else{ $scope.isShow7=false;$scope.loading=false;}
$scope.loading=false;	
		}
$scope.loading=false;
	};

if(localStorage.length >1){}
   // $scope.getMatchDetail(0);
else
   $location.path('/login');
    $scope.saveMatchoddsResult=function(MatchId, sportId,marketId, vSelectionID,model_result, sportName, MatchName, matchodds, selectionName1){
       // //
         var marketData = {
            Sport_id: sportId,
            Match_id: MatchId,
            market_id: marketId,
            selectionId: vSelectionID,
            result: model_result,
            isFancy: 1,
            sportName: sportName,
            matchName: MatchName,
            MarketName: matchodds,
            selectionName: selectionName1
        }
        //$timeout.cancel(marketTimer);
        //marketTimer = angular.isUndefinedOrNull;
        $http({ method: 'POST', url: BASE_URL+'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }).success(function (data) {
               try {  $scope.message = data.status.message;console.log("working "+data.status.message); }
               catch (e) { console.log(data.status.error); }
        });
        console.log("working ");
    }
    $scope.getUrl = function (type, matchid, marketid, matchname, matchdate,SportId)//sourabh 161231
    {
        switch (type) {
            case "0": return "Matchodds({MatchId: " + matchid + ",MarketId:" + marketid + ",matchName:'" + matchname.replace("'", "&quot;") + "',date:'" + matchdate + "',sportId:'"+SportId+"'})"; break;
            case "1": return "Evenoddfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "2": return "Sessionfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",sportId:"+SportId+",matchName:'"+matchname.replace("'", "&quot;")+"'})"; break;
            case "3": return "Khaddalfancy({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "4": return "Lastdigit({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
            case "5": return "Updown({matchId: " + matchid + ",FancyID:" + marketid + ",TypeID:" + type + ",matchName:'"+matchname.replace("'", "&quot;")+"',sportId:4 })"; break;
        }
    }
    var rcall=1;
    $scope.getMatchResult = function () {
	
	if(rcall==1)
	{
	 //$scope.loading = true;
	}

	$scope.getResultTime=$timeout(function(){
	if($state.current.name=="dashboard.Home"){
        $http.get(BASE_URL+'Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
            //
           /* $scope.matchResult = data.matchRslt;
            $scope.datapoints = data.matchRslt;*/
            $scope.TempResult=data;
	     $scope.loading = false;
	     rcall=2;
	     $scope.getMatchResult();
        }).error(function (data, status, header, config) {
            //$scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
		 $scope.loading = false;
        });
	}
      },rcall==1 ? 0 : 2000);
    }
if(localStorage.length > 1){
	if($state.current.name=="dashboard.Home"){
        if(sessionService.get('cart')!=angular.isUndefinedOrNull)
        {
            $scope.TempArray = JSON.parse(sessionService.get('cart'));
            $scope.matchResult = $scope.TempArray.matchRslt;
            $scope.datapoints = $scope.TempArray.matchRslt;
        }
	    $scope.getMatchResult();
		}
}

else
  $location.path('login');

    $scope.updateCart = function(){
        if($scope.TempResult!=angular.isUndefinedOrNull)
        {
            sessionService.set('cart',JSON.stringify($scope.TempResult));
            //alert('cart updated');
            $scope.TempArray = JSON.parse(sessionService.get('cart'));
            $scope.matchResult = $scope.TempArray.matchRslt;
            $scope.datapoints = $scope.TempArray.matchRslt;
        }

    };

    $scope.updateCricket = function(){
        // debugger;
        if($scope.BindArrayItems!=angular.isUndefinedOrNull)
        {
            sessionService.set('BindArrayItems',JSON.stringify($scope.BindArrayItems));
            //alert('cart updated');
            $scope.FBindArrayItems = JSON.parse(sessionService.get('BindArrayItems'));

        }

    };
    $scope.$watch('BindArrayItems', $scope.updateCricket, true);
    $scope.$watch('TempResult', $scope.updateCart, true);

    $scope.getValColor = function(val) { //20-dec-2016 asha
	
        if (val == angular.isUndefinedOrNull || val == 0)
            return 'color:#000000';
        else if (val > 0)
            return 'color:#007c0e !important';
        else
            return 'color:#ff0000 !important';
    }
        $scope.getOddCalcVal = function(a, ovType,oddsLimit,volumeLimit) { 
	$scope.oddsLimit=oddsLimit;
	$scope.volumeLimit=volumeLimit;
        var x = 0,
            y = 0,
            z = 0;
            
            if(isNaN(a) && a != angular.isUndefinedOrNull)
            {
                var ind = a.indexOf('$');
                if(ind>-1)
                {
                    a=a.replace('$','');
                }
                
            }
        switch (ovType) {
            case 1:
                if (a != angular.isUndefinedOrNull) {
                    x = a;
                    if ($scope.oddsLimit != angular.isUndefinedOrNull) y = $scope.oddsLimit;
                }
               // z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
                z=isInt_number(a) ? parseFloat((parseFloat(x) + parseFloat(y))) : parseFloat((parseFloat(x) + parseFloat(y))).toFixed(2);
                break;
            case 2:
                if (a != angular.isUndefinedOrNull) {
                    x = a;
                    if ($scope.volumeLimit != angular.isUndefinedOrNull) y = $scope.volumeLimit;
                }
                z = parseFloat((parseFloat(x) * parseFloat(y)).toFixed(0));
                break;
        }
        if (z > 0) return z;
        else return "";
    }
    function isInt_number(num) {
        return num % 1 === 0;
    }
   $scope.CallBackLay=function(priceVal,oddsLimit,matchs,index,isback,runner,IsMatchDisable){

	if((!matchs.IsMatchDisable && matchs.status!="SUSPENDED" && matchs.visibility==1) || matchs.is_manual==1){
	$rootScope.betslipshow=true;
	priceVal = parseFloat(priceVal) + parseFloat(oddsLimit);
        $scope.UserTypeId = sessionService.get('type');
        $scope.UserId = sessionService.get('slctUseID');
        $scope.ParantId = sessionService.get('slctParantID');
        $scope.loginId = sessionService.get('user_id');
        $scope.slctUseTypeID = sessionService.get('slctUseTypeID');
	if (deviceDetector.device == 'unknown') {
                var DIVICE = 'Desktop';
            } else {
                var DIVICE = deviceDetector.device;
            }
 var deviceInformation = " browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version + "  device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version;
	$scope.arrayObj={
                userId: $scope.UserId,
                ParantId: $scope.ParantId,
                loginId: $scope.loginId,
                selectionId: runner.id,
                matchId: matchs.matchid,
                isback: isback,
                stake:0,
                priceVal: priceVal,
                p_l: '',
                MarketId: matchs.marketid,
                isMatched: 1,
                UserTypeId: $scope.UserTypeId,
                placeName: runner.name,
                MatchName: matchs.matchName,
                deviceInfo: deviceInformation,
                inplay: matchs.inplay,
                ApiVal: 0,
		unique_id:index,
		is_session_fancy:'N',
		IsErrorShow:false,
		Message:'',
		SportId:matchs.SportId,
		matchdate:matchs.matchdate,
        max_bet_liability:parseFloat(matchs.max_bet_liability)
            };
	$rootScope.$broadcast('CallAddBackOrLay',$scope.arrayObj);
	}
	}
$scope.RemoveBackLay = function(uniqueId,type)
{

	$rootScope.$broadcast('RemoveAddBackOrLay',{'uniqueId':uniqueId,'type':type});
}
   
	$scope.isfavorite={};
    $scope.setfavourite = function(matchs,index)
	{
		 $scope.loading = true;
		

		 $http({
                    method: 'POST',
                    url: BASE_URL+'Apiusercontroller/favourite',//+$scope.MatchId+'/'+$scope.SPORTID+'/'+sessionService.get
                    data: {
                        "market_id":matchs.marketid,
                      
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
				$scope.message=data.message;
				Dialog.autohide($scope.message);
			      $scope.isfavorite['field' + index]=true;
			      matchs.is_favourite='Y';
             $scope.loading = false;
			}).error(function(err){
			 $scope.loading = false;
		});	
	};


    $scope.setUnfavourite = function(matchs,index)
	{

			 $scope.loading = true;
	

		 $http({
                    method: 'POST',
                    url: BASE_URL+'Apiusercontroller/unfavourite',//+$scope.MatchId+'/'+$scope.SPORTID+'/'+sessionService.get
                    data: {
                        "market_id":matchs.marketid,
                      
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
				$scope.getSportDetail(0);
				$scope.message=data.message;
				Dialog.autohide($scope.message);
			      	matchs.is_favourite='N';
             $scope.loading = false;
			}).error(function(err){
			 $scope.loading = false;
		});	
	};
	
$scope.$on('setunfavorite',function(event,data){
//alert();
$scope.setUnfavourite(data.markets,data.index);

});


$scope.checkfav  = function(market_id,$index){    /*It's used for set find favorite match */

  $scope.favMarkets = $scope.sportDetail.findIndex(x=>x.marketid==market_id);
		if($scope.favMarkets > -1){
		//$scope.favMarketsMarketId=$scope.match_markets[$scope.favMarkets].value.is_favorite;
		}

		if($scope.favMarketsMarketId == 1)
		$scope.isfavorite['field' + $index] =true;
	
}


}]);
