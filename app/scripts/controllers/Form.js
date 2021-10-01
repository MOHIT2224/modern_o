"use strict";
var app = angular.module("ApsilonApp");
app.controller("Formctrl", ["$scope", "$http", "loginService","sessionService","$timeout","$filter","$state","$rootScope","$interval","$mdDialog","deviceDetector","$sce",function(t, o, n,s,$timeout,$filter,$state,$rootScope,$interval,$mdDialog,deviceDetector,$sce) {
localStorage.clear();
var htt=o;
var scope=t;
t.status=s.get('lgnstatus');
var matchStatus = "OPEN";
var marketTimer = "";
t.matchName='';
t.FinalArray=[];
t.isMarketShow=false;
t.user={};
var tick = function(){
t.Currentdate =  Date.now();
  $timeout(tick,1000);
}
tick();
//$timeout(tick,1000);
 if (deviceDetector.device == 'unknown') {
                var DIVICE = 'Desktop';
            } else {
                var DIVICE = deviceDetector.device;
            }
t.user.device_info="device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version;
t.user.browser_info=" browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version;

/*o.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
                t.sprtData = data.sportData;
            }).error(function (data, status, header, config) {
                t.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
            });*/
    t.RefressCaptcha = function(){
        o.get('Loginauthcontroller/loadCaptcha/' ).success(function (data, status, headers, config) {
            t.image = $sce.trustAsHtml(data.image);


        });
    }

    t.RefressCaptcha();

    t.Submitcaptcha = function (captcha) {


        var captchaData = {
            captcha: captcha,
        }
        o({ method: 'POST', url: 'Loginauthcontroller/verifyCaptcha/', data: captchaData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .success(function (data) {
                t.showGreeting=true;
                t.message = data.message;
                if(data.error == 0){
                    var utype=sessionService.get('type');
                    if(utype==1)
                    {
                        $location.path('/dashboard/Home');
                    }
                    else if(utype==1)
                    {
                        $location.path('/masterDashboard/Home');
                    }
                    else if(utype==2)
                    {
                        $location.path('/dealerDashboard/Home');
                    }
                    else if(utype==3)
                    {
                        $location.path('/userDashboard/Home');
                    }

                    $rootScope.logincondition=false;
                    window.location.reload();

                }
            });
    }
			t.hello = function () {
				
				 
				$('#accordion .panel-heading').removeClass('active');
    if(!$(event.target).closest('.panel').find('.panel-collapse').hasClass('in'))
        $(event.target).parents('.panel-heading').addClass('active');
 
  
			}
			
			
t.ShowHideAng = function (sportsId) {
	t.isMarketShow=false;
         t.GetSeriesData=[];
                t.accordion = sportsId;
                t.sportsId = sportsId;
                t.accordionLv2 = 0;
                
              
                    t.GetSeriesData = angular.isUndefinedOrNull;
                    o.get('Geteventcntr/getSeriesLst/' + sportsId).success(function (data, status, headers, config) {
                        t.GetSeriesData = data.seriesLst;
                        $rootScope.GetSeriesData = data.seriesLst.length;


                    });
                
            }
t.selectedRow = null;  // initialize our variable to null
  t.setClickedRow = function(index){  //function that sets the value of selectedRow to current index
if(index !=  angular.isUndefinedOrNull)   { 
 if(index.indexOf('main') > -1 )
	{
	  t.accordionLv13=0;
	  t.isMarketShow=false;	
	}
     t.selectedRow = index;

  }}
t.getSeriesMatch = function (sportsId, seriesId) {
                ////
                t.inPlay = [];
                t.upComing = [];
                t.accordion = sportsId;
                t.accordionLv1 = 0;
                t.accordionLv2 = seriesId;
                t.seriesId = seriesId;
		t.GetMarketBackLayData="";
                t.GetMatchData = angular.isUndefinedOrNull;
		$timeout.cancel(marketTimer);
                marketTimer = angular.isUndefinedOrNull;
                o.get('Geteventcntr/getMatchLst/' + sportsId + '/' + seriesId).success(function (data, status, headers, config) {
                   // //
                    t.GetMatchData = data.matchLst;
console.log(t.GetMatchData.length);
                  if(t.GetMatchData.length==0)
		{
			$timeout.cancel(marketTimer);
			marketTimer = angular.isUndefinedOrNull;
		}

                 var date = new Date();
            var d = date.getDate();
                            if(d<10)
                                d = 0+""+d;
                 var y = date.getFullYear();
            var m = date.getMonth();
            m= m+1;
                            if(m<10)
                                m = 0+""+m;
                    //  alert(""+y+"-"+m+"-"+d+" 00:00:00");
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
                    
                        angular.forEach(t.GetMatchData, function(value, key) {
                              console.log(key + ': ' + value);
                              var d = new Date(value.MstDate);
                                
                    //          alert("n : " + n);
                                console.log("n " + d);
                                //change By Manish (&& d < currentTime) add in if()  
                                if(date < d || d < date){
                                    t.inPlay.push(value);
                                }
else if(d>currentTime)
{t.upComing.push(value);
              }else{}
                                console.log(t.inPlay);
                                console.log(t.upComing);
                            });
                });
            }
t.getMatchMarket = function (sportsId, matchId) {

                t.accordion = sportsId;
		t.accordionLv13 = matchId;
                
                t.MatchId = matchId;
                t.sportsId = sportsId;
                var marketData = {
                    matchId: matchId,
                    sportsId: sportsId
                }
                o({ method: 'POST', url: 'Geteventcntr/matchMarketLstPublic/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
                .success(function (data) {
                    ////
                    t.MatchMarket = data.MatchMarket;
                    t.getMatchFancy = data.getMatchFancy;
		    t.FancyData=t.getMatchFancy;
                });
            }

 var marketTimerNew;
	t.isShow2=false;
	t.isShow4=false;
	t.isShow1=false;
	t.isShow7=false;

 t.showGreeting = false;

    t.logout = function() {
      
        n.logout()
        s.destroy('type');
    }, t.submitForm = function(o) {

t.loading=true;
if($state.current.name=="subadmin")
{

    n.sublogin(o, t, function(t) {

        //  console.log(t.data.error);

        if(t.data.error==1)
        {
            t.showGreeting = true;
            t.loading=false;
        }
        else
        {
            "0" == t.data.type || "1" == t.data.type || "2" == t.data.type //|| setInterval(a, 6e4)
            var subAdminId= s.get('subAdminId');
            $rootScope.userType=t.data.type;
            htt.get(BASE_URL+'UserRightsCntr/getLoginUserRole').success(function (data, status, headers, config) {

                s.set('subAdminRole',JSON.stringify(data.data));

               // window.location.reload();
            });

        }

    })
}
else
{
    n.login(o, t, function(t) {
          
        if(t.data.error==1)
        {
            t.showGreeting = true;
            t.loading=false;
        }
        else
        {
            "0" == t.data.type || "1" == t.data.type || "2" == t.data.type //|| setInterval(a, 6e4)
            $rootScope.userType=t.data.type;
            setTimeout(function(){document.location.href = BASE_URL+'?login/';},250);
            window.location.href =  BASE_URL+'?login/';
        }

    })
}

        //t.RefressCaptcha();
    };

   t.checkStaus=function(type,status)
	{
	
		if(type==4)
		{
			if(status=='OPEN')
			{
			 t.isShow4=true; 
t.loading=false;	
			}	
			else{ t.isShow4=false;t.loading=false;}
t.loading=false;	
		}
else if(type==2)
		{
			if(status=='OPEN')
			{
			 t.isShow2=true; t.loading=false;	
			}	
			else{ t.isShow2=false;t.loading=false;}
t.loading=false;	
		}	
else if(type==1)
		{
			if(status=='OPEN')
			{
			 t.isShow1=true; t.loading=false;	
			}	
			else{
if(t.isShow1!=true)
{
 t.isShow1=false;t.loading=false;
}
}
t.loading=false;	
		}
else if(type==7)
		{
			if(status=='OPEN')
			{
			 t.isShow7=true; t.loading=false;	
			}	
			else{ t.isShow7=false;t.loading=false;}
t.loading=false;	
		}
t.loading=false;
	};

           function getDynamicOdds() 
    {

       marketTimerNew = $timeout(function ()
        {
		if (t.oddsDetail != angular.isUndefinedOrNull && t.oddsDetail.length > 0 && $state.current.name=="login")
		{
			var myStringArray = [4,1,2,7];
			var arrayLength =1; //myStringArray.length;
			if(t.SportTab == angular.isUndefinedOrNull)
			{
				t.SportTab=4;
				t.SportTabSelected='tab'+t.SportTab;
			}
			else
			{
				t.SportTabSelected='tab'+t.SportTab;
			}
			for (var i = 0; i < arrayLength; i++) {
			    //Do something
		
			o.get('GetPublicBetscntr/getUserMatchLst/' + t.SportTab).success(function (data, status, headers, config) {
                     //
                    var allRunner = data.matchOdds;
                    var sportDetail = data.matchLst;
                    if (t.oddsDetail != angular.isUndefinedOrNull)
                        t.oddsDetail.find(function (item, itemIndex) {
                            var selectedRunner = $filter('filter')(allRunner, { marketId: t.oddsDetail[itemIndex].marketId });// && selectedRunner[0].totalMatched > t.oddsDetail[itemIndex].totalMatched
                            if (selectedRunner != angular.isUndefinedOrNull && selectedRunner.length > 0 && selectedRunner[0].status == "OPEN") {
                                try { t.oddsDetail[itemIndex].runners[0].ex.availableToBack[0].price = selectedRunner[0].runners[0].ex.availableToBack[0].price; } catch (e) { try { t.oddsDetail.runners[0].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { t.oddsDetail[itemIndex].runners[1].ex.availableToBack[0].price = selectedRunner[0].runners[1].ex.availableToBack[0].price; } catch (e) { try { t.oddsDetail.runners[1].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { t.oddsDetail[itemIndex].runners[2].ex.availableToBack[0].price = selectedRunner[0].runners[2].ex.availableToBack[0].price; } catch (e) { try { t.oddsDetail.runners[2].ex.availableToBack[0].price = "" } catch (e) { } }
                                try { t.oddsDetail[itemIndex].runners[0].ex.availableToLay[0].price = selectedRunner[0].runners[0].ex.availableToLay[0].price; } catch (e) { try { t.oddsDetail.runners[0].ex.availableToLay[0].price = "" } catch (e) { } }
                                try { t.oddsDetail[itemIndex].runners[1].ex.availableToLay[0].price = selectedRunner[0].runners[1].ex.availableToLay[0].price; } catch (e) { try { t.oddsDetail.runners[1].ex.availableToLay[0].price = "" } catch (e) { } }
                                try { t.oddsDetail[itemIndex].runners[2].ex.availableToLay[0].price = selectedRunner[0].runners[2].ex.availableToLay[0].price; } catch (e) { try { t.oddsDetail.runners[2].ex.availableToLay[0].price = "" } catch (e) { } }
                            }
                            else {
                                //start for set Result
                                ////
				
                                if (selectedRunner != angular.isUndefinedOrNull && selectedRunner[0] != null && selectedRunner[0].status == "CLOSED") {
                                    var vSelectionID = $filter('filter')(selectedRunner[0].runners,{status : "WINNER"})[0] != null ?  $filter('filter')(selectedRunner[0].runners,{status : "WINNER"})[0].selectionId : '';
                                    var MatchId = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].matchid;
                                    var sportName = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].sportname;
                                    var MatchName = $filter('filter')(sportDetail,{marketid : selectedRunner[0].marketId})[0].matchName;
                                    
                                    var selectionName1 = "";
                                    o.get('GetPublicBetscntr/getSelectionName/' + selectedRunner[0].marketId + '/' + MatchId).success(function (data, status, headers, config) {                                                
                                               if((data.RunnerValue,{selectionId : vSelectionID}).length>0)
						{
                                               var sportId=$filter('filter')(data.RunnerValue,{selectionId : vSelectionID})[0].sportId;
                                               selectionName1=$filter('filter')(data.RunnerValue,{selectionId : vSelectionID})[0].selectionName;
                                                if(selectionName1!=""){
                                                   //    
                                                // MatchId; sportId; selectedRunner[0].marketId; vSelectionID;sportName; MatchName;selectionName1;
                                                    t.saveMatchoddsResult(MatchId, sportId,selectedRunner[0].marketId, vSelectionID, 1, sportName, MatchName, 'match Odds', selectionName1);  
                                                                                              
                                                }
						}
                                                   
                                    });
                                   
                                }
                                //end of set Result
                                try { t.oddsDetail.runners[0].ex.availableToBack[0].price = "" } catch (e) { }
                                try { t.oddsDetail.runners[1].ex.availableToBack[0].price = "" } catch (e) { }
                                try { t.oddsDetail.runners[2].ex.availableToBack[0].price = "" } catch (e) { }
                                try { t.oddsDetail.runners[0].ex.availableToLay[0].price = "" } catch (e) { }
                                try { t.oddsDetail.runners[1].ex.availableToLay[0].price = "" } catch (e) { }
                                try { t.oddsDetail.runners[2].ex.availableToLay[0].price = "" } catch (e) { }
                            }
                        });
                });
	}
getDynamicOdds();
		}
		     else {
                t.oddsDetail = angular.isUndefinedOrNull;
                $timeout.cancel(marketTimerNew);
                marketTimerNew = angular.isUndefinedOrNull;
            }
        }, 5000);
    }
   t.getMatchDetail = function (matchId) {
	if($state.current.name=="login")
	{	
        t.sportDetail = angular.isUndefinedOrNull;
        t.oddsDetail = angular.isUndefinedOrNull;
        t.sportid = matchId;
	getDynamicOdds();
	var url='GetPublicBetscntr/getMatchLst/0';
        o.get(url).success(function (data, status, headers, config) {
           t.sportDetail = data.matchLst;
           t.oddsDetail = data.matchOdds;
           getDynamicOdds();
        });
	}
    },
       t.getOddCalcVal = function (a, b)//sourabh 161231
    {
        var x = 0, y = 0, z = 0;
        if(isNaN(a) && a != angular.isUndefinedOrNull)
        {
            var ind = a.indexOf('$');
            if(ind>-1)
            {
                a=a.replace('$','');
            }
            
        }
        if (a != angular.isUndefinedOrNull) {
            x = a;
            if (b != angular.isUndefinedOrNull) y = b;
        }
        z = parseFloat((parseFloat(x) + parseFloat(y)).toFixed(2));
        if (z > 0) return z; else return "-";
    }
   // t.getMatchDetail(0);
    /*var a = function() {
        n.chkLoginStatus(function(t) {
            1 == t.status && n.logout()
        })
    }*/
 var selectedRunner = null;
 var totalMatch = 0;
 t.reset = function (MName,DateFrom,MarketId,MatchId)
{
	localStorage.clear();
	t.isMarketShow=true;
	t.matchName=MName;
	t.dateForm = DateFrom;
	t.MarketId=MarketId;
	t.MatchId=MatchId;
	$timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
	t.getNameFunc();
}

 t.callOddsFunc = function(MarketId,MatchId) {
        var maxloop = 0;
        
	if(s.get("MarketId") == angular.isUndefinedOrNull)
	{
               
		s.set("MarketId",MarketId);
	        MarketId=s.get("MarketId");
		t.MarketId=MarketId;
	}
	else
	{
		 MarketId=MarketId == angular.isUndefinedOrNull ? s.get("MarketId") : MarketId;
		 t.MarketId=MarketId;
	}
		if(s.get("MatchId") == angular.isUndefinedOrNull)
	{
		s.set("MatchId",MatchId);
	        MatchId=s.get("MatchId");
	}
	else
	{
		MatchId=MatchId == angular.isUndefinedOrNull ? s.get("MatchId") : MatchId;
	}
	
        var $promise = o.get('Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + MatchId);
        var maxloop = 0;
        $promise.then(function(response) {
            //For Play Pause start
            if (s.get('type') == "0") {
                o({
                    method: 'POST',
                    url: 'Geteventcntr/matchMarketLst/',
                    data: {
                        matchId: $stateParams.MatchId,
                        sportsId: 4,
                        user_id: s.get('user_id')
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    try {
                        t.FancyLength=data.getMatchFancy.length;
                        if(t.FancyLength > 0 && t.FancyData != angular.isUndefinedOrNull && t.FancyData.length>0){
                            if(t.FancyData.length == data.getMatchFancy.length){
                                for (var i = 0; i < data.getMatchFancy.length; i++) {
                                    if(t.FancyData[i].SessInptYes==data.getMatchFancy[i].SessInptYes && t.FancyData[i].SessInptNo==data.getMatchFancy[i].SessInptNo && t.FancyData[i].active==data.getMatchFancy[i].active && t.FancyData[i].DisplayMsg==data.getMatchFancy[i].DisplayMsg){ 

                                    }else{
                                         t.FancyData=data.getMatchFancy;
                                    }
                                } 
                            }else{
                               t.FancyData=data.getMatchFancy; 
                            }
                            
                        }else{
                            t.FancyData=data.getMatchFancy;
                        }
                        if ($filter('filter')(data.MatchMarket, { Id: t.MarketId })[0].IsPlay == "1") {                            
                            t.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: t.MarketId })[0].IsPlay;
                        }else{
                          t.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: t.MarketId })[0].IsPlay;  
                        }
                    } catch (e) {}
                });
            }
            //For Play Pause end
            if (response.data.MatchOddsVolVal != angular.isUndefinedOrNull) {
                if (response.data.MatchOddsVolVal[0].oddsLimit != angular.isUndefinedOrNull)
                    t.oddsLimit = parseFloat(response.data.MatchOddsVolVal[0].oddsLimit);
                else
                    t.oddsLimit = 0;
                if (response.data.MatchOddsVolVal[0].volumeLimit != angular.isUndefinedOrNull && response.data.MatchOddsVolVal[0].volumeLimit != 0)
                    t.volumeLimit = parseFloat(response.data.MatchOddsVolVal[0].volumeLimit);
                else
                    t.volumeLimit = 1;
                if (response.data.MatchOddsVolVal[0].result != "0")
                    t.MatchResult = "CLOSED";
                else
                    t.MatchResult = "OPEN";
            }
            if (t.GetMarketBackLayData == angular.isUndefinedOrNull) {
                t.GetMarketBackLayData = response.data.MarketRunner;
                if (response.data.MarketRunner == angular.isUndefinedOrNull) {
                    try { t.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    t.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    t.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if (t.GetMarketBackLayData.status == "CLOSED" || t.MatchResult == "CLOSED") {
                        t.callOddsCloseMatch();
                    }
                }
            } else if (MarketId == t.GetMarketBackLayData.marketId) {
                selectedRunner = null;
                if (response.data.MarketRunner != angular.isUndefinedOrNull && response.data.MarketRunner.totalMatched > totalMatch) {
                    selectedRunner = response.data.MarketRunner.runners;
                    try { t.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    t.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    //t.GetMarketBackLayData.IsActive = data.IsActive;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    t.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if (t.MatchResult == "OPEN" && t.GetMarketBackLayData.status == "OPEN" && t.GetMarketBackLayData.runners != angular.isUndefinedOrNull && t.GetMarketBackLayData.runners.length > 0) { //&& selectedRunner != angular.isUndefinedOrNull
                        try {
                            if (t.GetMarketBackLayData.runners.length < selectedRunner.length) //170204
                                maxloop = selectedRunner.length;
                            else
                                maxloop = t.GetMarketBackLayData.runners.length;
                            for (var j = 0; j < maxloop; j++) { //170204 t.GetMarketBackLayData.runners.length
                                if (t.GetMarketBackLayData.runners[j].ex.availableToBack.length == selectedRunner[j].ex.availableToBack.length) {
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[0].price != selectedRunner[j].ex.availableToBack[0].price || t.GetMarketBackLayData.runners[j].ex.availableToBack[0].size != selectedRunner[j].ex.availableToBack[0].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = selectedRunner[j].ex.availableToBack[0].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[0].size = selectedRunner[j].ex.availableToBack[0].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[0] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = "";
                                        }
                                    }
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[1].price != selectedRunner[j].ex.availableToBack[1].price || t.GetMarketBackLayData.runners[j].ex.availableToBack[1].size != selectedRunner[j].ex.availableToBack[1].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = selectedRunner[j].ex.availableToBack[1].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[1].size = selectedRunner[j].ex.availableToBack[1].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[1] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = "";
                                        }
                                    }
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[2].price != selectedRunner[j].ex.availableToBack[2].price || t.GetMarketBackLayData.runners[j].ex.availableToBack[2].size != selectedRunner[j].ex.availableToBack[2].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = selectedRunner[j].ex.availableToBack[2].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToBack[2].size = selectedRunner[j].ex.availableToBack[2].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToBack[2] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = "";
                                        }
                                    }
                                } else {

                                    t.GetMarketBackLayData.runners[j].ex.availableToBack = selectedRunner[j].ex.availableToBack;
                                }
                                if (t.GetMarketBackLayData.runners[j].ex.availableToLay.length == selectedRunner[j].ex.availableToLay.length) {
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[0].price != selectedRunner[j].ex.availableToLay[0].price || t.GetMarketBackLayData.runners[j].ex.availableToLay[0].size != selectedRunner[j].ex.availableToLay[0].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = selectedRunner[j].ex.availableToLay[0].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[0].size = selectedRunner[j].ex.availableToLay[0].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[0] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = "";
                                        }
                                    }
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[1].price != selectedRunner[j].ex.availableToLay[1].price  || t.GetMarketBackLayData.runners[j].ex.availableToLay[1].size != selectedRunner[j].ex.availableToLay[1].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = selectedRunner[j].ex.availableToLay[1].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[1].size = selectedRunner[j].ex.availableToLay[1].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[1] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = "";
                                        }
                                    }
                                    try {
                                        t.GetMarketBackLayData.runners[j].ex.availablecallOddToLay[2].SELECTED = false;
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[2].price != selectedRunner[j].ex.availableToLay[2].price  || t.GetMarketBackLayData.runners[j].ex.availableToLay[2].size != selectedRunner[j].ex.availableToLay[2].size) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = true;
                                        }
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = selectedRunner[j].ex.availableToLay[2].price;
                                        t.GetMarketBackLayData.runners[j].ex.availableToLay[2].size = selectedRunner[j].ex.availableToLay[2].size;
                                    } catch (e) {
                                        if (t.GetMarketBackLayData.runners[j].ex.availableToLay[2] != angular.isUndefinedOrNull) {
                                            t.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = "";
                                        }
                                    }
                                } else {

                                    t.GetMarketBackLayData.runners[j].ex.availableToLay = selectedRunner[j].ex.availableToLay;
                                }
                            }
                        } catch (e) {

                            t.GetMarketBackLayData = angular.isUndefinedOrNull;
                        }
                        t.counter = t.counter + 1;
                    } else if (t.GetMarketBackLayData.status == "CLOSED" || t.MatchResult == "CLOSED") //170201
                    {
                        t.GetMarketBackLayData = response.data.MarketRunner;
                        t.callOddsCloseMatch();
                    }
                } else if (t.GetMarketBackLayData.status == "CLOSED" || t.MatchResult == "CLOSED") //170201
                {
                    t.GetMarketBackLayData = response.data.MarketRunner;
                    t.callOddsCloseMatch();

                }
            } else {
                t.GetMarketBackLayData = response.data.MarketRunner;
                try { t.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                    console.log(response.data.MarketRunner); }
                t.GetMarketBackLayData.status = response.data.MarketRunner.status;
                matchStatus = response.data.MarketRunner.status;
                t.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
            }
            marketTimer = $timeout(function() {
                if (t.GetMarketBackLayData != angular.isUndefinedOrNull) { //sourabh 170107
                    for (var j = 0; j < maxloop; j++) { // t.GetMarketBackLayData.runners.length 170204
                        //for (var i = 0; i < 3; i++) {//t.GetMarketBackLayData.runners[j].ex.availableToBack.length
                        try { t.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false; } catch (e) {}
                        try { t.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false; } catch (e) {}
                        try { t.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false; } catch (e) {}
                        try { t.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false; } catch (e) {}
                        try { t.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false; } catch (e) {}
                        try { t.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = false; } catch (e) {}
                        //}
                    }
                    if (t.GetMarketBackLayData.Status != 3) {
                        if (t.GetMarketBackLayData.marketId != null) {
                            t.callOddsFunc();
                            t.getNameFunc();
                        }
                    }
                } else {
                    t.callOddsFunc();
                    t.getNameFunc();
                }
 		$timeout.cancel(marketTimer);
                marketTimer = angular.isUndefinedOrNull;
            }, 5000);
            /*{aakash 161226*/
            var OnlineStatus = $interval(OnlineStatusChanged, 10000)
            var updatedOnline = function() {
                //console.log("akash2", navigator.onLine)
                if (navigator.onLine) {
                    //clearInterval(Changed);
                    $interval.cancel(Changed);
                    Changed = angular.isUndefinedOrNull;
                    location.reload();
                }
            }
            var Changed;

            function OnlineStatusChanged() {
                if (navigator.onLine) {
                    if (!t.netConn) {
                        $mdDialog.hide();
                        t.netConn = true;
                        t.callOddsFunc();
                        t.getNameFunc();
                    }
                } else {
                    Changed = $interval(updatedOnline, 100)
                    if (t.netConn) {
                        $mdDialog.show({
                            clickOutsideToClose: false,
                            escapeToClose: false,
                            template: "<md-dialog style='border: rgb(255, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content>Internet Connection is Disconnect... Please Wait...</md-dialog-content></md-dialog>",
                            locals: { prntScope: t },
                            fullscreen: false,
                            controller: function DialogController(prntScope) {
                                prntScope.netConn = false;
                            }
                        });
                    }
                }
            }
            /*}aakash 161226*/
        });
        //}
    }
 
t.getNameFunc = function() {
     
       // localStorage.clear();
        o.get('Geteventcntr/getBackLaysOfMarketSelectionName/' + t.MarketId + '/' + 0 + '/' + 0 + '/' + t.MatchId).success(function(data, status, headers, config)
            {
                
                if (data.runnerSlName != angular.isUndefinedOrNull && data.runnerSlName.length > 0)
                    t.GetMarketBackLayDataSelectionName = data.runnerSlName[0].runners;
                if (data.RunnerValue != angular.isUndefinedOrNull && data.RunnerValue.length != 0)
                    t.RunnerValue = data.RunnerValue;
                else
                    t.RunnerValue = [{}];

                if (data.MarketData != angular.isUndefinedOrNull && data.MarketData.length != 0)
                    t.GetMarketInfo = data.MarketData[0];
            });
    }

    t.GetMarketListId = function(MarketId,MatchId)
	{
        
        t.MarketId=MarketId;
        t.MatchId=MatchId;
	   t.loading = true;
	   
	 //  socket.emit('CallGetMarketListId', {auth:Bauthdata,MatchId:t.MatchId,UserId:t.UserId});
		 o({
                    method: 'GET',
                    url: 'Apicontroller/getMarketListing/'+t.MatchId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    // 
                     t.oddsLimit=parseFloat(data.data.volumeLimit[0].oddsLimit);
                     t.volumeLimit=parseFloat(data.data.volumeLimit[0].volumeLimit);
				     t.BindMarket(data);
			}).error(function(err){
			 t.loading = false;
        });	
        
    }

    t.BindMarket=function(result)
{

	 if(result.data != angular.isUndefinedOrNull)
		{
		t.marketIdLst = result.data.marketid;
		//t.MarketWinLoss(result.data.marketid);
                t.MarketLst=result.data.marketid.split(',');
        t.AllMarket =  t.MarketLst;
        t.FinalArray[0] = result.data.selection.result[0];
        t.GetMarketBackLayData =result.data.selection;
		if(result.data.selection.result[0]!=null && result.data.selection.result.length > 0)
		{
			t.CallType='first';
            t.MarketId=result.data.marketid
            t.getFancyList(result.data.marketid);
          
            
		}
		else
		{
			
			t.MarketRunnerLst = result.data.marketRunner == null ? [] : result.data.marketRunner;
			t.TempArray =[];
			for(var j=0;j<t.MarketRunnerLst.length;j++)
			{
				if (t.MarketRunnerLst[j] != angular.isUndefinedOrNull && t.MarketRunnerLst[j].status == "CLOSED") 
				{
					  var vSelectionID = $filter('filter')(t.MarketRunnerLst[j].runners, { status: "WINNER" })[0].selectionId;
					 var obj ={"marketId":t.MarketRunnerLst[j].marketId,"selectionId":vSelectionID};
					t.TempArray.push(obj);
					
				}
			}
			if(t.TempArray.length>0)
			{
				t.saveMatchoddsResultAutoMatic(t.TempArray);
			}
		}
		}
		else{t.loading = false;}
}
    t.getFancyList = function(marketId) {
        // stopped = $timeout(function() {
              o({
                            method: 'GET',
                            url: 'Apicontroller/matchLstIndianSessionPublic/'+t.MatchId+'/'+marketId,
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).success(function(result) {
                //	
                
                     t.loading=false;
                 t.FancyData = result.data;
                 t.getLiveFancyList();
                    }).error(function(err){
                     t.loading = false;
                });	
         //t.getFancyList(marketId);
        //},10000);
            }
            t.getLiveFancyList = function() {
                marketTimer= $timeout(function() {
               // socket.emit('CallLiveFancy', {auth:Bauthdata,MarketId:t.MarketId,UserId:t.UserId});
                   /*get_userser.getBothSessionFancy($stateParams.MatchId,function(response) {
                   t.loading=false;
                       t.FancyData = response.data;            
                   });*/
                   $.ajax({
                       url:'http://139.162.52.34/customer/v1/market.php?market_id='+t.MarketId,
                       type:'GET',
                       dataType:'JSON',
                       success:function(result){
                        t.loading=false;
                        //result = JSON.parse(result);
                        t.FancyLiveData = result.session;
                        var market = result.market[0].events;
                        if(market!=angular.isUndefinedOrNull)
                        {
                            for(var m=0;m<market.length;m++)
                            {
                             //   ;
                               var inde = t.FinalArray[0].runners.findIndex(img => img.id ===market[m].SelectionId);
                               if(inde>-1)
                               {
                                  // try{
                                       for(var b=0;b<t.FinalArray[0].runners[inde].back.length;b++)
                                       {
                                        var count = b+1;  
                                        try{
                                            t.FinalArray[0].runners[inde].back[b].selected =  t.CallColor(t.FinalArray[0].runners[inde].back[b].price,t.FinalArray[0].runners[inde].back[b].size, market[m]["BackPrice"+count],market[m]["BackSize"+count]);                 
                                        }
                                        catch(e)
                                        {

                                        }
                                       try{
                                        t.FinalArray[0].runners[inde].back[b].price = market[m]["BackPrice"+count];
                                       }
                                       catch(e)
                                       {
                                        if(t.FinalArray[0].runners[inde].back[b]!=angular.isUndefinedOrNull)
                                        {
                                            t.FinalArray[0].runners[inde].back[b].price="";
                                        }
                                       }
                                       try{
                                        t.FinalArray[0].runners[inde].back[b].size = market[m]["BackSize"+count];
                                         }
                                        catch(e)
                                        {
                                            
                                        }
                                        
                                      
                                       }
                                       for(var b=0;b<t.FinalArray[0].runners[inde].lay.length;b++)
                                       {
                                        var count = b+1;  
                                        t.FinalArray[0].runners[inde].lay[b].selected =  t.CallColor(t.FinalArray[0].runners[inde].lay[b].price,t.FinalArray[0].runners[inde].lay[b].size, market[m]["LayPrice"+count],market[m]["LaySize"+count]);                 
                                        try{
                                            t.FinalArray[0].runners[inde].lay[b].price = market[m]["LayPrice"+count];
                                        }
                                        catch(e)
                                        {
                                                if(t.FinalArray[0].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                                            {
                                                t.FinalArray[0].runners[inde].lay[b].price="";
                                            }
                                        }
                                        
                                        t.FinalArray[0].runners[inde].lay[b].size = market[m]["LaySize"+count];
                                       }
                                  /* t.FinalArray[0].runners[inde].back[0].selected =  t.CallColor(t.FinalArray[0].runners[inde].back[0].price,t.FinalArray[0].runners[inde].back[0].size, market[m].BackPrice1,market[m].BackSize1);                 
                                   t.FinalArray[0].runners[inde].back[0].price = market[m].BackPrice1;
                                   t.FinalArray[0].runners[inde].back[1].selected =  t.CallColor(t.FinalArray[0].runners[inde].back[1].price,t.FinalArray[0].runners[inde].back[1].size, market[m].BackPrice2,market[m].BackSize2);  
                                   t.FinalArray[0].runners[inde].back[1].price = market[m].BackPrice2;
                                   t.FinalArray[0].runners[inde].back[2].selected =  t.CallColor(t.FinalArray[0].runners[inde].back[2].price,t.FinalArray[0].runners[inde].back[2].size, market[m].BackPrice3,market[m].BackSize3); 
                                   t.FinalArray[0].runners[inde].back[2].price = market[m].BackPrice3;
                                   t.FinalArray[0].runners[inde].lay[0].selected =  t.CallColor(t.FinalArray[0].runners[inde].lay[0].price,t.FinalArray[0].runners[inde].lay[0].size, market[m].LayPrice1,market[m].LaySize1); 
                                   t.FinalArray[0].runners[inde].lay[0].price = market[m].LayPrice1;
                                   t.FinalArray[0].runners[inde].lay[1].selected =  t.CallColor(t.FinalArray[0].runners[inde].lay[1].price,t.FinalArray[0].runners[inde].lay[1].size, market[m].LayPrice2,market[m].LaySize2); 
                                   t.FinalArray[0].runners[inde].lay[1].price = market[m].LayPrice2;
                                   t.FinalArray[0].runners[inde].lay[2].selected =  t.CallColor(t.FinalArray[0].runners[inde].lay[2].price,t.FinalArray[0].runners[inde].lay[2].size, market[m].LayPrice3,market[m].LaySize3); 
                                   t.FinalArray[0].runners[inde].lay[2].price = market[m].LayPrice3;
               
                                   t.FinalArray[0].runners[inde].back[0].size = market[m].BackSize1;
                                   t.FinalArray[0].runners[inde].back[1].size = market[m].BackSize2;
                                   t.FinalArray[0].runners[inde].back[2].size = market[m].BackSize3;
                                   t.FinalArray[0].runners[inde].lay[0].size = market[m].LaySize1;
                                   t.FinalArray[0].runners[inde].lay[1].size = market[m].LaySize2;
                                   t.FinalArray[0].runners[inde].lay[2].size = market[m].LaySize3;*/
                               //}
                              /* catch(e)
                               {
                                       if(t.FinalArray[0].runners[inde].back[0]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].back[0].price="";
                                       }
                                       if(t.FinalArray[0].runners[inde].back[1]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].back[1].price="";
                                       }
                                       if(t.FinalArray[0].runners[inde].back[2]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].back[2].price="";
                                       }
               
                                       if(t.FinalArray[0].runners[inde].lay[0]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].lay[0].price="";
                                       }
                                       if(t.FinalArray[0].runners[inde].lay[1]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].lay[1].price="";
                                       }
                                       if(t.FinalArray[0].runners[inde].lay[2]!=angular.isUndefinedOrNull)
                                       {
                                           t.FinalArray[0].runners[inde].lay[2].price="";
                                       }
                               }*/
               
                               }
                            }
                        }
                           /*for(var i=0;i<t.FancyLiveData.length;i++)
                                   {
                                       if(t.FancyLiveData!=null)
                                       {
                                           var inde = t.FancyData.findIndex(img => img.ind_fancy_selection_id === 											t.FancyLiveData[i].SelectionId);
                                           if(inde > -1)
                                           {
                                           var obj = t.FancyLiveData[i];
                                           t.FancyData[inde].SessInptNo=obj.LayPrice1;
                                           t.FancyData[inde].SessInptYes=obj.BackPrice1;
                                           t.FancyData[inde].DisplayMsg=obj.GameStatus;
                                             t.FancyData[inde].active=obj.GameStatus ==" " ? 1 : 4;
               
                                           }
               
                                       
                                       }
                                   }*/
                   for(var i=0;i<t.FancyData.length;i++)
                                   {
                                       
                                       if(t.FancyData[i].is_indian_fancy==1 && t.FancyData[i].fancy_mode=="A")
                                       {
                                           var inde = t.FancyLiveData.findIndex(img => img.SelectionId === 											t.FancyData[i].ind_fancy_selection_id);
                                           if(inde > -1)
                                           {
                                           var obj = t.FancyLiveData[inde];
                                           if(obj!=angular.isUndefinedOrNull)
                                           {
                                           t.FancyData[i].SessInptNo=obj.LayPrice1;
                                           t.FancyData[i].SessInptYes=obj.BackPrice1;
                                           t.FancyData[i].DisplayMsg=obj.GameStatus;
                                             t.FancyData[i].active=obj.GameStatus =="" ? 1 : 4;
                                           }
               
                                           }
                                           else
                                           {
                                           t.FancyData[i].SessInptNo='';
                                           t.FancyData[i].SessInptYes='';
                                           t.FancyData[i].DisplayMsg='Result Awaiting';
                                               t.FancyData[i].active= 4;	
                                           }
               
                                       
                                       }
                                   }
                           
                      t.getLiveFancyList();
                       },
                       error:function(err){
                        t.loading = false;
                       }
                       
                   })
           },1000);
               }
               t.CallColor=function(Oldprice,Oldsize,NPrice,NSize)
               {
                   if(Oldprice!=NPrice || Oldsize!=NSize)
                               {
                                   return true;
                               }
                               else{
                                   return false;
                               }
               }
  


}]);
