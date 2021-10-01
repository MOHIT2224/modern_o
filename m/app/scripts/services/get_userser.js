'use strict';
app.factory('get_userser', function ($http, $location, sessionService, $rootScope, $filter,Base64) {
    var response = [];
    var surlArray=$rootScope.scketArray;
    
    return {
        GetFancyBal: function (FancyID, $callback) {
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/getFancySomOfBet/' + FancyID);
            $promise.then(function (response) {
                $callback(response.data.sumOfBetFancy[0].TotalBet);
            });
        },
        partnerValue: function (UserID, $callback) {
            var $promise = $http.post(BASE_URL + 'Createmastercontroller/partnerValue/' + UserID);
            $promise.then(function (response) {
                $callback(response.data);
            });
        },
        GetFancyData: function (matchId, FancyID, UserId, type, TypeID, $callback) {
            var $promise = $http.get(BASE_URL + 'Lstsavemstrcontroller/getFancyData/' + matchId + '/' + FancyID + '/' + UserId + '/' + type + '/' + TypeID);
            $promise.then(function (response) {
                $callback(response);
            });
        },
        GetFancyData1: function (matchId, FancyID, UserId, type, TypeID, $callback) {
            var $promise = $http.get(BASE_URL + 'Lstsavemstrcontroller/getFancyDataM/' + matchId + '/' + FancyID + '/' + UserId + '/' + type + '/' + TypeID);
            $promise.then(function (response) {
                $callback(response);
            });
        },
        updateUserBal: function (user_id, $callback) {
            var $promise = $http.post(BASE_URL + 'Chipscntrl/getChipDataById/' + user_id);
            $promise.then(function (response) {
                //$rootScope.FreeChips;
                var SFreechips = sessionService.get('FreeChips');
                var DFreeChip = response.data.betLibility[0].FreeChip;
                if (SFreechips == DFreeChip) {
                    var status = false;
                }
                else {
                    $rootScope.FreeChips = response.data.betLibility[0].FreeChip;
                    $rootScope.Balance = response.data.betLibility[0].Balance;
                    $rootScope.Liability = response.data.betLibility[0].Liability;
                    sessionService.set('FreeChips', response.data.betLibility[0].FreeChip);
                    sessionService.set('ChipInOut', response.data.betLibility[0].Chip);
                    sessionService.set('Liability', response.data.betLibility[0].Liability);
                    sessionService.set('Balance', response.data.betLibility[0].Balance);
                    var status = true;
                }
                $callback(status);

            });
        },
        GetAllSessFancyBet: function (FancyID, $callback) {
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/GetAllSessFancyBet/' + FancyID);
            $promise.then(function (response) {
                $callback(response.data.GetSesFancyUserLst);
            });
        },
        GetWALLibiInfo: function (userId) {
            if (sessionService.get('slctUseTypeID') == 3) {
                $http.get( BASE_URL+'Chipscntrl/getChipDataById/' + userId).success(function (data, status, headers, config) {
                    var cipsData = data.betLibility;
                    sessionService.set('FreeChips', cipsData[0].FreeChip);
                    sessionService.set('ChipInOut', cipsData[0].Chip);
                    sessionService.set('Liability', cipsData[0].Liability);
                    sessionService.set('Balance', cipsData[0].Balance);
                    $rootScope.user = sessionService.get('slctUseName');
                    $rootScope.Balance = sessionService.get('Balance');
                    $rootScope.Liability = sessionService.get('Liability');
                });
            }
        },
        getAdminLImit: function () {
            var $promise = $http.post(BASE_URL + 'Betentrycntr/adminLimit/');
            $promise.then(function (response) {
                $callback(response.data.adminLimit);
                sessionService.set('adminLimit', response.data.adminLimit[0].adminLImit);
            });
        },
        userChipSetting: function ($callback) {
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/UserChipSetting/' + sessionService.get('user_id'));
            $promise.then(function (response) {
                $rootScope.userPlcBtn = response.data.getChipsetting;
                $callback(response.data.getChipsetting);
            });
        },
        updateUserChipSetting: function (ChipData, $callback) {
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/updateUserChipSetting/', ChipData);
            $promise.then(function (response) {
                $rootScope.userPlcBtn = response.data.getChipsetting;
                $rootScope.MyLenth = 1;
                $callback(response.data.status);
            });
        },
        getCheckLimitOfPlaceBet: function (slctUseID, MatchId, MarketId, $callback) {
            var $promise = $http.post(BASE_URL + 'Createmastercontroller/viewUserAcData/' + slctUseID + '/' + MatchId + '/' + MarketId);
            $promise.then(function (response) {
                $callback(response.data);
            });
        },
     /*   get_OddsFromApi: function (MarketId, selectionId1, matchId, isback, $callback) {
		
            var $promise = $http.post(BASE_URL + 'Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + matchId);
            $promise.then(function (response) {
                var odlm = 0;
                if (response.data.MatchOddsVolVal != angular.isUndefinedOrNull) {
                    if (response.data.MatchOddsVolVal[0].oddsLimit != angular.isUndefinedOrNull)
                        odlm = parseFloat(response.data.MatchOddsVolVal[0].oddsLimit);
                    else
                        odlm = 0;
                }
                else
                    odlm = 0;
                if (isback == "0") {
if($filter('filter')(response.data.MarketRunner.result[0].runners, { id: selectionId1 })[0].back.length >0)
			{
                    var oddsValue = ($filter('filter')(response.data.MarketRunner.result[0].runners, { id: selectionId1 })[0].back[0].price) + odlm;

                }
}
                else {
	if($filter('filter')(response.data.MarketRunner.result[0].runners, { id: selectionId1 })[0].lay.length >0)
			{
                    var oddsValue = ($filter('filter')(response.data.MarketRunner.result[0].runners, { id: selectionId1 })[0].lay[0].price) + odlm;
                }
}

                var inplay = response.data.MarketRunner.result[0].inPlay;
                var ApiData={ oddsValue: oddsValue,inplay: inplay};
                $callback(ApiData);
            });
        }, */
        get_OddsFromApi: function (MarketId, selectionId1, matchId, isback,tempArray,oddsLimit ,$callback) {
		
         //  var $promise = $http.post(BASE_URL + 'Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + matchId);
          // $promise.then(function (response) {
                var odlm = 0;
                if (tempArray != angular.isUndefinedOrNull) {
                    if (oddsLimit != angular.isUndefinedOrNull)
                        odlm = parseFloat(oddsLimit);
                    else
                        odlm = 0;
                }
                else
                    odlm = 0;
                if (isback == "0") {
if($filter('filter')(tempArray.runners, { id: selectionId1 })[0].back.length >0)
			{
                    var oddsValue = ($filter('filter')(tempArray.runners, { id: selectionId1 })[0].back[0].price) + odlm;

                }
}
                else {
	if($filter('filter')(tempArray.runners, { id: selectionId1 })[0].lay.length >0)
			{
                    var oddsValue = ($filter('filter')(tempArray.runners, { id: selectionId1 })[0].lay[0].price) + odlm;
                }
}

                var inplay = tempArray.inPlay;
                var ApiData={ oddsValue: oddsValue,inplay: inplay};
                $callback(ApiData);
           // });
        },
        getSessionFancy: function (matchId, sportId, $callback) {
            var marketData = { matchId: matchId, sportsId: sportId }
            var $promise = $http({ method: 'POST', url: BASE_URL+'Geteventcntr/SessionFancyData/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            $promise.then(function (response) {
                $callback(response.data.SessionFancyData);
            });
        },
   getBothSessionFancy: function (matchId, $callback) {
            var $promise = $http({ method: 'GET', url: BASE_URL+'Apicontroller/matchLstIndianSession/'+matchId, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            $promise.then(function (response) {
                $callback(response.data);
            });
        },
        getUserPartnerShip: function (userId, $callback) {
            var $promise = $http.post(BASE_URL + 'Lstsavemstrcontroller/getPartnerShip/' + userId);
            $promise.then(function (response) {
                $callback(response);
            });
        },
        changePassword: function(oldPassword,newPassword,userId, $callback){
		
             var Data = { oldPassword: oldPassword, newPassword: newPassword , userId: userId};
             
             var $promise = $http.post(BASE_URL + 'Lstsavemstrcontroller/changeLgnPassword/', Data);
            //var $promise = $http({ method: 'POST', url: 'Lstsavemstrcontroller/changeLgnPassword/', Data: Data });
            $promise.then(function (response) {
		if(response.data.error==0)
		{
		var pass=response.data.data !=null ? response.data.data.mstrpassword : "";
		sessionService.set('lgPassword',pass);
               
		}
 $callback(response.data);
            });
        },
        getBetDelay:function(userId, $callback){
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/getBetDelay/' + userId);
            $promise.then(function (response) {
                //
                $callback(response.data.BetDelay[0].set_timeout);
            });
        },
        getCricketData:function( $callback){
		var $promise = $http({ method: 'GET', url: $rootScope.apiCricket, headers: { 'Content-Type': 'application/json; charset=utf-8' } });
          //  var $promise = $http.get( BASE_URL+'http://139.162.242.237/cricket_data.php');
            $promise.then(function (response) {
                //
                $callback(response.data);
            });
        },
  	getSocketData1:function(SportId, $callback){
		
		 var indurl = surlArray.findIndex(x=>x.SportId==SportId);
            if(indurl>-1)
            {
var $promise = $http({ method: 'GET', url: surlArray[indurl].url, headers: { 'Content-Type': 'application/json; charset=utf-8' } });

            $promise.then(function (response) {
                //
                $callback(response.data);
            });
	  }
        },
	getSocketData:function(SportId, $callback){
	
		 var indurl = surlArray.findIndex(x=>x.SportId==SportId);
            if(indurl>-1)
            {
		$.ajax({
			url:surlArray[indurl].url,
			type:'GET',
			dataType:'JSON',
			success:function(response){
				$callback(response);
			}
		});
/*var $promise = $http({ method: 'GET', url: surlArray[indurl].url, headers: { 'Content-Type': 'application/json; charset=utf-8' } });

            $promise.then(function (response) {
                //
                $callback(response.data);
            });*/
	  }
        },
	getSocketDataApi:function(market_ids, $callback){
		$.ajax({
			url:'http://139.162.242.237/get_match_and_session.php?market_id='+market_ids,
			type:'GET',
			dataType:'JSON',

			success:function(response){
				$callback(response);
			}
		});
	
        },
        getSocketDataApiDetail:function(market_ids, $callback){
            var url='';
           	
		if($rootScope.GApiPath=="https://my.betdip.com/ExchangeController/"){
			
			url=$rootScope.GApiPath+'get_match_betfair_session?market_id='+market_ids;
			
					$.ajax({
                url:url,
                type:'GET',
                dataType:'JSON',
				 headers: { 'TokenId': sessionService.get('TokenId')},
                success:function(response){
                    $callback(response);
                },
                error :function(data, status, header, config) {
                    $callback(data);

                }
            });
			
		}else {
		
        if($rootScope.Provider=='betfair')
        {
            url=$rootScope.GApiPath+'get_match_betfair_session.php?market_id='+market_ids;
        }
        else
        {
            url=$rootScope.GApiPath+'get_match_betfair_session.php?market_id='+market_ids;
        }

		$.ajax({
                url:url,
                type:'GET',
                dataType:'JSON',
				
                success:function(response){
                    $callback(response);
                },
                error :function(data, status, header, config) {
                    $callback(data);

                }
            });
		
		
		}
           

        },
        getSocketDataHomeApi:function(market_ids, $callback){
            var url='';
            if($rootScope.GApiPath=="https://my.betdip.com/ExchangeController/"){
			
			url=$rootScope.GApiPath+'get_odds_by_market_ids?market_id='+market_ids;
			
			$.ajax({
                url:url,
                type:'GET',
                dataType:'JSON',
				 headers: { 'TokenId': sessionService.get('TokenId')},
                success:function(response){
                    $callback(response);
                },
                error :function(data, status, header, config) {
                    $callback(data);

                }
            });
			
		}else {
        if($rootScope.Provider=='betfair')
        {
            url=$rootScope.GApiPath+'get_odds_by_market_ids.php?market_id='+market_ids;
        }
        else
        {
            url=$rootScope.GApiPath+'get_odds_by_market_ids.php?market_id='+market_ids;
        }
		
		
		$.ajax({
                url:url,
                type:'GET',
                dataType:'JSON',			 
                success:function(response){
                    $callback(response);
                },
                error :function(data, status, header, config) {
                    $callback(data);

                }
            });
		}
            

        },
        getSelectionList:function(selection_id, $callback){
            var $promise = $http({ method: 'GET', url: $rootScope.GApiPath+"get_selectionname_by_market_ids.php?market_sel_id="+selection_id});

            $promise.then(function (response) {

                $callback(response.data);
            });
        },
        getTennisData:function( $callback){
var $promise = $http({ method: 'GET', url: $rootScope.apiTennis, headers: { 'Content-Type': 'application/json; charset=utf-8' } });

            $promise.then(function (response) {
                //
                $callback(response.data);
            });
        },
        getSessionData:function( $callback){
var $promise = $http({ method: 'GET', url: $rootScope.apiSession, headers: { 'Content-Type': 'application/json; charset=utf-8' } });

            $promise.then(function (response) {
                //
                $callback(response.data);
            });
        },
    
        GetFancyLength:function($callback){
            var $promise = $http.post(BASE_URL + 'Createdealercontroller/getFancyLength/');
            $promise.then(function (response) {
                //
                $callback(response.data.FancyNum[0].ID);
            });
        }
    }
});
