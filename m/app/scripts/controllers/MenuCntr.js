'use strict';
angular.module('ApsilonApp').controller('menuctrl',['$scope', '$http', 'sessionService', '$timeout', 'deviceDetector','$filter','$rootScope','$location','$state','Dialog','Base64','get_userser','$stateParams', function ($scope, $http, sessionService, $timeout, deviceDetector,$filter,$rootScope,$location,$state,Dialog,Base64,get_userser,$stateParams) {

  var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; 
	 $("#main-menu").show();   

$scope.socketCricket=[];
$scope.socketSoccer=[];
$scope.socketTennis=[];
$scope.bindCricket=[];
$scope.bindSoccer=[];
$scope.bindTennis=[];
$rootScope.MatchStack=[];
$rootScope.one_click_stack=[];
$rootScope.SessionStack=[];
$scope.ajaxTimerC="";
$scope.ajaxTimerS="";
$scope.ajaxTimerT="";
$scope.SportType=4;
$scope.SeriesId=0;
var obj=[{'id':'Cricket','value':4,'sportData':$scope.bindCricket},{'id':'Soccer','value':1,'sportData':$scope.bindSoccer},{'id':'Tennis','value':2,'sportData':$scope.bindTennis}];
$scope.BindArrayItems=[];
if($stateParams.SportType!=angular.isUndefinedOrNull)
{
$scope.SportType=$stateParams.SportType;
}
if($stateParams.SeriesId!=angular.isUndefinedOrNull)
{
$scope.SeriesId=$stateParams.SeriesId;
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
$scope.CallSocketData=function(marketIds)
{
	$scope.ajaxTimerC = $timeout(function(){
		if($state.current.name=='userDashboard.Menu'){
	   get_userser.getSocketDataHomeApi(marketIds,function(data) {
		
			if(data.length>0){
				$scope.AllSocketData=data;
				var cricket = $scope.AllSocketData.filter(function (element) { 
				    return element.eventTypeId === "4";
				});
				if(cricket!=angular.isUndefinedOrNull)
				{
					$scope.CallCricketSocket(cricket);	
				}
				var Soccer = $scope.AllSocketData.filter(function (element) { 
				    return element.eventTypeId === "1";
				});
				if(Soccer!=angular.isUndefinedOrNull)
				{
					$scope.CallSoccerSocket(Soccer);
				}
				var Tennis = $scope.AllSocketData.filter(function (element) { 
				    return element.eventTypeId === "2";
				});
				if(Tennis!=angular.isUndefinedOrNull)
				{
					$scope.CallTennisSocket(Tennis);
				}
				
				
			}
			$scope.CallSocketData(marketIds);
		});
		}
	},1000);
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
								}
								else
								{
									$scope.bindCricket[i].Sort=1;
								}
								$scope.bindCricket[i].matchdate=$scope.socketCricket[ind].event.openDate;
								//$scope.bindCricket[i].runners=$scope.socketCricket[ind].runners;
								
								$scope.SocketMarket(i,$scope.socketCricket[ind].runners,4);
								$scope.bindCricket[i].IsMatchDisable=false;
								
						      }
							else{
								$scope.bindCricket[i].IsMatchDisable=true;
							}
						}
						var obj={'id':'Cricket','value':4,'sportData':$scope.bindCricket}
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
								$scope.bindTennis[i].matchdate=$scope.socketTennis[ind].event.openDate;
								//$scope.bindTennis[i].runners=$scope.socketTennis[ind].runners;
								$scope.SocketMarket(i,$scope.socketTennis[ind].runners,2);
								$scope.bindTennis[i].IsMatchDisable=false;
								if($scope.bindTennis[i].status=="OPEN" && $scope.bindTennis[i].inplay)
								{
									$scope.bindTennis[i].Sort=0;
								}
								else
								{
									$scope.bindTennis[i].Sort=1;
								}
						      }
							else
							{
								$scope.bindTennis[i].IsMatchDisable=true;
							}
						}
					var obj={'id':'Tennis','value':2,'sportData':$scope.bindTennis}
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
								$scope.bindSoccer[i].matchdate=$scope.socketSoccer[ind].event.openDate;
								//$scope.bindSoccer[i].runners=$scope.socketSoccer[ind].runners;
								$scope.SocketMarket(i,$scope.socketSoccer[ind].runners,1);
								$scope.bindSoccer[i].IsMatchDisable=false;
								if($scope.bindSoccer[i].status=="OPEN" && $scope.bindSoccer[i].inplay)
								{
									$scope.bindSoccer[i].Sort=0;
								}
								else
								{
									$scope.bindSoccer[i].Sort=1;
								}
						      }
						      else
							{
								$scope.bindSoccer[i].IsMatchDisable=true;
							}
						}
						var obj={'id':'Soccer','value':1,'sportData':$scope.bindSoccer}
						$scope.BindArrayItems[1]=obj;
					 }

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
$scope.matchList=[];
$scope.StepFirst=true;
$scope.StepSecond=false;
////////////////////////////////////////////////
$scope.bindInplay=function()
{
	$scope.UserId = sessionService.get('slctUseID');
 $scope.loading = true;
		 $http({
                    method: 'GET',
                    url: BASE_URL+'/Geteventcntr/getInPlayMatchBySportId/'+$scope.SportType+'/'+$scope.UserId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
				 $scope.loading = false;
			
			if($scope.SportType==4)
			{
				var obj={'id':'Cricket','value':4,'InPlay':data.inPlay,'Series':data.series}
				$scope.BindArrayItems[0]=obj;	
			}
			else if($scope.SportType==1)
			{
				
				var obj={'id':'Soccer','value':1,'InPlay':data.inPlay,'Series':data.series}
				$scope.BindArrayItems[0]=obj;
			}
			else if($scope.SportType==2)
			{
				var obj={'id':'Tennis','value':2,'InPlay':data.inPlay,'Series':data.series}
				$scope.BindArrayItems[0]=obj;
			}
		})
}

$scope.bingMatchList=function(series)
{
	$scope.SeriesHead=series.Name;
	$scope.UserId = sessionService.get('slctUseID');
	$scope.loading = true;
		 $http({
                    method: 'GET',
                    url: BASE_URL+'/Geteventcntr/getSeriesWithMatchData/'+series.SportID+"/"+series.seriesId+"/"+$scope.UserId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
				
				 $scope.loading = false;
				$scope.matchList=data;
				$scope.StepFirst=false;
				$scope.StepSecond=true;
		});
}
///////////////////////////////////////////////
  $scope.getSportDetail1 = function(sportId)
	{
	 $scope.loading = true;
		 $http({
                    method: 'GET',
                    url: BASE_URL+'Apiusercontroller/getUserFavouriteMatchLst/'+$scope.SportType+'/'+$scope.SeriesId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
		
			if(data!=angular.isUndefinedOrNull)
			{	
				
				var marketIdstr="";
				$rootScope.MatchStack=data.match_stack;
				$rootScope.SessionStack=data.session_stack;
				//$rootScope.one_click_stack=data.one_click_stack;
				$scope.sportDetail=data.data;
				$rootScope.$broadcast('BindAllBets',{});
				//$scope.getAllMatchList(data.market_ids);
				for(var i=0;i<$scope.sportDetail.length;i++)
				{
					if($scope.sportDetail[i].SportId==4 && $scope.sportDetail[i].values.length>0)
					{
						$scope.bindCricket=$scope.sportDetail[i].values;
						
						 $scope.bindCricket=$scope.sportDetail[i].values;
						var obj={'id':'Cricket','value':4,'sportData':$scope.bindCricket}
						        $scope.BindArrayItems[0]=obj;	
							
						var ind1=data.market_ids.findIndex(x=>x.SportId==4);
						if(ind1>-1)
						{
							//var marketId=data.market_ids[ind1].marketids;
							//marketIdstr+=marketId;
							//$scope.CallCricketSocket(marketId);
						}
						
					}
					else if($scope.sportDetail[i].SportId==2 && $scope.sportDetail[i].values.length>0)
					{
						$scope.bindTennis=$scope.sportDetail[i].values;
						 $scope.bindTennis=$scope.sportDetail[i].values;
						  var obj={'id':'Tennis','value':2,'sportData':$scope.bindTennis}
						        $scope.BindArrayItems[2]=obj;
						var ind1=data.market_ids.findIndex(x=>x.SportId==2);
						if(ind1>-1)
						{
							//var marketId=data.market_ids[ind1].marketids;
							//marketIdstr+=marketId;
							//$scope.CallTennisSocket(marketId);
						}
						
					}
					else if($scope.sportDetail[i].SportId==1 && $scope.sportDetail[i].values.length>0)
					{
						$scope.bindSoccer=$scope.sportDetail[i].values;
						var obj={'id':'Soccer','value':1,'sportData':$scope.bindSoccer}
						$scope.BindArrayItems[1]=obj;
						var ind1=data.market_ids.findIndex(x=>x.SportId==1);
						if(ind1>-1)
						{
							//var marketId=data.market_ids[ind1].marketids;
							//marketIdstr+=marketId;
							//$scope.CallSoccerSocket(marketId);
						}
						
					}
				}
				if(data.all_market_ids!=""){
				$scope.CallSocketData(data.all_market_ids);
				}
			}
			 $scope.loading = false;
			
		}).error(function(err){
			 $scope.loading = false;
        	});	
	}
   $scope.CallColor=function(Oldprice,Oldsize,NPrice,NSize)
    {
        if(Oldprice!=NPrice || Oldsize!=NSize)
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
                    $scope.FArray['f_'+type].runners[inde].back[b].selected =  $scope.CallColor($scope.FArray['f_'+type].runners[inde].back[b].price,$scope.FArray['f_'+type].runners[inde].back[b].size, market[m].back[b].price,market[m].back[b].size);                 
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
                $scope.FArray['f_'+type].runners[inde].lay[b].selected =  $scope.CallColor($scope.FArray['f_'+type].runners[inde].lay[b].price,$scope.FArray['f_'+type].runners[inde].lay[b].size, market[m].lay[b].price,market[m].lay[b].size);  
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
$scope.$on('$destroy', function () {
 $timeout.cancel($scope.ajaxTimerC);

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
	 $scope.loading = true;
	}
	$scope.getResultTime=$timeout(function(){
		if($state.current.name=="dashboard.Home"){
        $http.get(BASE_URL+'Geteventcntr/getUserMatchResult/' + sessionService.get('slctUseID') + '/' + sessionService.get('slctUseTypeID')).success(function (data, status, headers, config) {
            //
            $scope.matchResult = data.matchRslt;
            $scope.datapoints = data.matchRslt;
	     $scope.loading = false;
	     rcall=2;
	     $scope.getMatchResult();
        }).error(function (data, status, header, config) {
            //$scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
		 $scope.loading = false;
        });
	}
      },1000);
    }
if(localStorage.length > 1){
	if($state.current.name=="dashboard.Home"){
	    $scope.getMatchResult();
		}
}
else
  $location.path('login');
    $scope.getOddCalcVal = function (a, b)//sourabh 161231
    {
        var x = 0, y = 0, z = 0;
        if (a != angular.isUndefinedOrNull) {
            x = a;
            if (b != angular.isUndefinedOrNull) y = b;
        }
        z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
        if (z > 0) return z; else return "-";
    }
   $scope.CallBackLay=function(priceVal,oddsLimit,matchs,index,isback,runner,IsMatchDisable){

	if(!matchs.IsMatchDisable && matchs.status!="SUSPENDED"){
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
		SportId:matchs.SportId
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
$scope.GetLstSeries = function () {

                    $scope.GetSeriesData = angular.isUndefinedOrNull;
                    $http.get( BASE_URL+'Geteventcntr/getSeriesLst/' + $scope.SportType).success(function (data, status, headers, config) {
                        $scope.GetSeriesData = data.seriesLst;
              
					 
                    });
                
            }
$scope.getSeriesMatch = function (sportsId, seriesId) {
		if(seriesId>0)
		{
		 //localStorage.setItem('SId',seriesId);
		}
                $scope.inPlay = [];
                $scope.upComing = [];
                $scope.accordion = sportsId;
                $scope.accordionLv1 = 0;
                $scope.accordionLv2 = seriesId;
                $scope.seriesId = seriesId;
                $scope.GetMatchData = angular.isUndefinedOrNull;
                $http.get( BASE_URL+'Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
                    $scope.GetMatchData = data.matchLst;
                });
		
        }

}]);
