app.controller('Matchoddscntr', ['$scope', '$http', '$stateParams', 'sessionService', '$timeout', 'Dialog', '$rootScope', 'deviceDetector', 'get_userser', '$mdDialog', 'speech', '$filter', '$location', '$state', '$interval','Base64', function($scope, $http, $stateParams, sessionService, $timeout, Dialog, $rootScope, deviceDetector, get_userser, $mdDialog, speech, $filter, $location, $state, $interval,Base64) {
    $scope.$on('test_dir', function(event, data) { $scope.getNameFunc(); });
    var marketTimer;
    $scope.loading = false;
    $scope.loadingM = false;
    $scope.dateForm = new Date($stateParams.date);
    $scope.sportId = $stateParams.sportId;
    var stopped;
    var currentdate = new Date();
    $scope.btnPlaceDis = false;
    $scope.netConn = true;
    $scope.gtTypeId = sessionService.get('type');
    $scope.matchName = $stateParams.matchName;
    $scope.MatchId = $stateParams.MatchId;
    $scope.MarketId = $stateParams.MarketId;
    $scope.date = $stateParams.date;
    $scope.UserTypeId = sessionService.get('slctUseTypeID');
    $scope.UserId = sessionService.get('slctUseID');
    $scope.displayTable = false;
    $scope.logInTypeId = sessionService.get('slctUseTypeID');
    $scope.logInId = sessionService.get('slctUseID');
    $scope.FinalArray = [];
    $scope.Manuald={};
    $scope.Manuald.pointDiff=0;
    var MarketId = $stateParams.MarketId;
    $scope.ajaxTimer="";
    $scope.PLAYPAUSE=0;
    var urlIp=$rootScope.gurlIp;
    var urlArray=$rootScope.gUrlArray;
    $scope.IsManual='';
    $scope.team1backodd=[];
    $scope.team1layodd=[];
    $scope.team1Suspend=[];
    $scope.counter = 0;
    var totalMatch = 0;
    var selectedRunner = null;
    var scorePosition;
    $scope.scorePosition1=[];
    $scope.result=[];
    var OldFanyID=0;
    var oldFancyTypeId=0;
    var callscorepostype=0;
    var old_fancy_id = 0;
    var scoreOddPosition;
    var callscoreOddpostype=0;
    $scope.Fdata={};
    $scope.saleName2='';
    var callType1="1";
    $scope.FancyData=[];
    $scope.FancyDataTemp=[];
    var tempval="";
    $scope.SelectedTextBox=0;
    $scope.state=false;
    $scope.ManualMsg="";
    $scope.IsError=false;
    $scope.tempIndex=0;
    $scope.PointAuto=false;

    $scope.gotoCharts = function(){
        $state.go('dashboard.chartGraph');

    }

	
	$scope.GetIndianFancy=function()
{
//debugger
    if($state.current.name=="dashboard.Matchodds")
    {
       $scope.callFancy= $timeout(function(){
           $http.get('Apicontroller/get_indian_session/'+$stateParams.MatchId).success(function (data, status, headers, config) {

               if(data.error)
               {
                   $scope.Message = data.message;
                   $scope.GetindianfancyData=[];
               }
               else
               {
                   $scope.GetindianfancyData = data.data;
               }
               $scope.GetIndianFancy();


           });
       },10000);
    }

}
    $scope.GetIndianFancy();
 $scope.saveIndianSeries = function (indianseries) {
	
        var formData = { SelectionId: indianseries.SelectionId, RunnerName: indianseries.RunnerName, LayPrice1: indianseries.LayPrice1, LaySize1: indianseries.LaySize1,BackSize1:indianseries.BackSize1, BackPrice1: indianseries.BackPrice1, GameStatus: indianseries.GameStatus,FinalStatus:indianseries.FinalStatus,is_exists:indianseries.is_exists ,match_id:indianseries.match_id,super_admin_fancy_id:indianseries.id}
        var url = BASE_URL + "Apicontroller/save_indian_session";
        $http.post(url, formData).success(function (response) {
		if(!response.error){
    indianseries.is_exists=1;

}
 var toast = $mdToast.simple().content(response.message).position('top right').hideDelay(1000);
            $mdToast.show(toast);
       
        });
    }


	

    $scope.updateStack=function(mitem)
    {
        debugger;
        var formData={
            market_id:mitem.id,
            min_stack:mitem.MinStack,
            max_stack:mitem.MaxStack
        }
        if(mitem.MinStack!=angular.isUndefinedOrNull || mitem.MaxStack!=angular.isUndefinedOrNull) {
            $http.post(BASE_URL + 'Lstsavemstrcontroller/saveManualMarketStack', formData).success(function (data, status, headers, config) {
                Dialog.autohide(data.message);
                $scope.loading = false;

            }).error(function (data, status, headers, config) {
                $scope.loading = false;
            });
        }
        else{
            Dialog.autohide('Please enter min or max stack.');
            $scope.loading=false;
        }
    }
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
    document.addEventListener('keydown', function(evt) {
        var gind=$scope.KeyValue.findIndex(x=>x.key==evt.keyCode);
        if(gind>-1)
        {
            var vindx=$scope.KeyValue[gind].value;
            $scope.FinalArray[vindx].IsRs=!$scope.FinalArray[vindx].IsRs;
            $scope.FinalArray[vindx].pointDiff=0;
            for(var j=0;j<$scope.FinalArray[vindx].runners.length;j++)
            {
                $scope.team1backodd[j+$scope.FinalArray[vindx].id]="";
                $scope.team1layodd[j+$scope.FinalArray[vindx].id]="";

            }
            $scope.updateManualbetAllow(0,$scope.FinalArray[vindx]);
            $scope.updateIsRupees($scope.FinalArray[vindx].IsRs,$scope.FinalArray[vindx].id);

        }
        //different
        var ltinx=$scope.lstKeyValue.findIndex(x=>x.key==evt.keyCode);
        if(ltinx>-1)
        {
            var vindx1=$scope.lstKeyValue[ltinx].value;
            $scope.FinalArray[vindx1].IsPointDiff=!$scope.FinalArray[vindx1].IsPointDiff;
            $scope.SetPointDiff($scope.FinalArray[vindx1].IsPointDiff,$scope.FinalArray[vindx1].id);


        }
        if(evt.keyCode==37)
        {
            //$scope.PointDiffObj.pointDiff+=$scope.PointDiffObj.btnDiff;
            $scope.team1backodd[$scope.SelectedTextBox]+=$scope.PointDiffObj.btnDiff;
            $scope.CalCulatePointDiff($scope.SelectedTextBox,$scope.PointDiffObj);
        }
        if(evt.keyCode==39)
        {
            if(($scope.team1backodd[$scope.SelectedTextBox]-$scope.PointDiffObj.btnDiff)>0)
            {
               // $scope.PointDiffObj.pointDiff-=$scope.PointDiffObj.btnDiff;
                $scope.team1backodd[$scope.SelectedTextBox]-=$scope.PointDiffObj.btnDiff;
                $scope.CalCulatePointDiff($scope.SelectedTextBox,$scope.PointDiffObj);
            }
            else
            {
                //$scope.PointDiffObj.pointDiff=0;
            }

        }
    });

    $scope.updateIsRupees=function(status,mid)
    {
        var vstatus=status==true ? 1 : 0;
        $http.get('Lstsavemstrcontroller/updateIsRs/' + mid + '/' + vstatus).success(function (data, status, headers, config) {

        })
    }
    //$scope.KeyValue=[];
    //a to z (65 to 90)
    $scope.firstKey=65;
    $scope.lstKey=88;
    $scope.lstKeyValue=[];
    $scope.SetManualMarketKey=function()
    {
        debugger;
        var len=$scope.FinalArray.length;
        if(len>0)
        {
            for(var i=0;i<len;i++)
            {
                var kind=$scope.KeyValue.findIndex(x=>x.value==i);
                if(kind==-1)
                {
                    $scope.KeyValue.push({'key':$scope.firstKey,'value':i});
                    $scope.firstKey+=1;
                }

                var lind=$scope.lstKeyValue.findIndex(x=>x.value==i);
                if(lind==-1)
                {
                    $scope.lstKeyValue.push({'key':$scope.lstKey,'value':i});
                    $scope.lstKey+=1;
                }

            }
        }
    }
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
    var matchStatus = "OPEN";
	 $scope.UserData=[];
   // var audio = new Audio('app/images/noti.mp3');


	$scope.RunOvers = function(){

	var input = document.querySelector( "[name='overs']" );


		input.addEventListener( "input", function(){
		   var value = this.value;
		   if ( value - parseInt( value ) >= 0.6 )
		   {
		      this.value = Math.ceil( value );
		   }
		})
	}
	 


    $scope.updateScoreBoard = function(a,b,c,d,e) {

        var scoreData = {
           betting_team:b,
            toss_winner: a,
            Overs: c,
            Runs: d,
            Wickets:e,
            id:$stateParams.MatchId,
            Selection1:$scope.selectionNames[0].id,
            Selection2:$scope.selectionNames[1].id,

    
        }
      
        $http({ method: 'POST', url: 'Lstsavemstrcontroller/updateScoreBoard/', data: scoreData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .success(function(data) {
              
            });
    }

   /* $scope.GetUserDataTemp=function(){

        $scope.FancyBet = $timeout(function(){
            if($state.current.name=="dashboard.Matchodds") {
                $http.get('Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {

                    if (true) {
                        if ($scope.UserData.length != data.betUserData.length) {
                          //  audio.play();
                        }
                        $scope.UserData = data.betUserData;


                    }
                    else if ($scope.UserData.length != data.betUserData.length) {
                        $scope.UserData = data.betUserData;
                    }

                    $scope.GetUserData();
                });
            }
        },3000);
    }*/

    $scope.GetUserData=function(){

        $scope.FancyBet = $timeout(function(){
            if($state.current.name=="dashboard.Matchodds") {
                $http.get(BASE_URL+'Betentrycntr/GatBetData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId).success(function (data, status, headers, config) {
					//alert(data.betUserData);

                    if($scope.UserData==angular.isUndefinedOrNull || $scope.UserData.length==0)
                    {

                        $scope.UserData = data.betUserData;
                        if($scope.UserData.length==1)
                        $scope.MarketWinLoss($scope.MarketLst);

                    }
                    else
                    {
                        if($scope.UserData.length != data.betUserData.length)
                        {
                            //  audio.play();
                            $scope.UserData = data.betUserData;
                            if($state.current.name=='dashboard.Matchodds')
                            {
                                $scope.MarketWinLoss($scope.MarketLst);

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
                                    if($state.current.name=='dashboard.Matchodds')

                                    {
                                        $scope.MarketWinLoss($scope.MarketLst);
                                    }

                                }
                            }
                        }
                    }

                    $scope.GetUserData();
                });
            }
        },3000);
    }

   // $scope.FancyBet = $interval($scope.GetUserData, 3000);
    $scope.$on("$destroy", function(event) {
        $interval.cancel($scope.FancyBet);
        $timeout.cancel($scope.FancyBet);
        $scope.FancyBet = angular.isUndefinedOrNull;
    });
    
    $scope.styleColor=function(value){
        if(value < 0){ return "RED"; }else{ return "GREEN";  }
    }   
    if($scope.gtTypeId !=3){       
        var scorePosition = $interval(function () {        
        if($stateParams.MatchId == sessionService.get('MatchId') && $scope.gtTypeId !=3){
            var indx = $scope.FancyData.findIndex(x=>x.ID==sessionService.get('fancyId'));
            if(indx>-1)
            {
                var result = $scope.FancyData[indx];
            }
            var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + sessionService.get('fancyId') + '/' + sessionService.get('slctUseID') + '/' + sessionService.get('FancyType'));
            $promise.then(function (response) {
					//alert(response);
                $scope.scorePosition=response.data.scorePosition;
            });
        } 
        }, 2000);
    }
/////////////////////////////////////////

    $scope.IsManualOdds = function(items){

        items.IsManual= !items.IsManual;
        //$scope.IsManualTitle = $scope.IsManual == true ? 'Manual' : 'Auto';
        $scope.IsManualVal = items.isManualMatchOdds == '0' ? '1' : '0';

        $http.get('Lstsavemstrcontroller/updateMatchOddsStatus/' + items.id + '/' + $scope.IsManualVal).success(function(data, status, headers, config) {
            items.isManualMatchOdds =   items.isManualMatchOdds == '0' ? '1' : '0';
            $scope.IsManualTitle =items.isManualMatchOdds == '1' ? 'Manual' : 'Auto';
            $scope.ManualData = data.message;
            Dialog.autohide($scope.ManualData);


        });
    }


    $scope.ConvertBackLay=function(backLay)
    {
        return parseFloat(backLay);
    }

    ///////////////////////////
    $scope.deleteUser = function (betId, userId) {
                var result = confirm("Are you sure want to delete Unmatched Records");
                if (result) {
                    $http.get('Betentrycntr/deleteGetbetting/' + betId + '/' + userId).success(function (data, status, headers, config) {
                        
                        Dialog.autohide("Record Deleted Successfully...");
                    });

                }

            }
    $scope.movetoMatchedBet = function (betId, userId) {

        var result = confirm("Are you sure want to move Unmatched to Matched bet");
        if (result) {
            $http.get(BASE_URL+'Betentrycntr/moveUnMatchToMatchBetByAdmin/' + betId+'/' + userId).success(function (data, status, headers, config) {

                Dialog.autohide("Bet moved Successfully...");
            });

        }

    }

    $scope.deleteUser1 = function (betId, userId,MarketId,voids) {
        var result = confirm("Are you sure want to delete Matched Records?");
        if (result) {
		  $scope.loading = true;
            $http.get('Betentrycntr/deleteGetbettingmat/' + betId + '/' + userId+'/'+MarketId+'/'+voids).success(function (data, status, headers, config) {
                
                 Dialog.autohide("Record Deleted Successfully...");
                 $scope.loading = false;
                //$scope.MarketWinLoss(MarketId);
            });

        }

    }
    $scope.deleteVoidBet = function (betId, userId,MarketId) {
        
        var result = confirm("Are you sure want to delete void bet?");
        if (result) {
            $scope.loading = true;
            $http.get(BASE_URL+'Betentrycntr/moveToAvoidBet/' + betId + '/' + userId+'/'+MarketId).success(function (data, status, headers, config) {

                Dialog.autohide("Record Deleted Successfully...");
                $scope.loading = false;
                //$scope.MarketWinLoss(MarketId);
            });

        }

    }
    $scope.sum = function (items, prop) {
            return items.reduce(function (a, b) {
                var t = parseFloat(a) + parseFloat(b[prop]);
                return parseFloat(a) + parseFloat(b[prop]);
            }, 0);
    };
    
    $scope.GetUserData();
    /*end of new function*/
  /*  $scope.saveMatchoddsResult = function(Match_id, Sport_id, market_id, selectionId, model_result, spartName, matchName, MarketName, selectionName) {
        var marketData = {
            Sport_id: Sport_id,
            Match_id: Match_id,
            market_id: market_id,
            selectionId: selectionId,
            result: model_result,
            isFancy: 1,
            sportName: spartName,
            matchName: matchName,
            MarketName: MarketName,
            selectionName: selectionName
        }
        $timeout.cancel(marketTimer);
        marketTimer = angular.isUndefinedOrNull;
        $http({ method: 'POST', url: 'Geteventcntr/SetResult/', data: marketData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .success(function(data) {
                try {
                    $scope.message = data.status.message;
                    $rootScope.$broadcast('changeSidebar_Market', {});

                    if (sessionService.get('type') == "1")
                        $state.go('dashboard.Masterdashboard');
                    else if (sessionService.get('type') == "2")
                        $state.go('dashboard.Dealerdashboard');
                    else if (sessionService.get('type') == "3")
                        $state.go('dashboard.Userdashboard');
                } catch (e) { console.log(data.status.error); }
            });
    }*/
    $scope.getNameFunc = function() {
        
        var user_id = sessionService.get('slctUseID');
        var user_type = sessionService.get('slctUseTypeID');
        
        $http.get('Geteventcntr/getBackLaysOfMarketSelectionName/' + $scope.MarketId + '/' + user_id + '/' + user_type + '/' + $scope.MatchId).success(function(data, status, headers, config)
            {

                if (data.runnerSlName != angular.isUndefinedOrNull && data.runnerSlName.length > 0)
                    $scope.GetMarketBackLayDataSelectionName = data.runnerSlName[0].runners;
                if (data.RunnerValue != angular.isUndefinedOrNull && data.RunnerValue.length != 0)
                    $scope.RunnerValue = data.RunnerValue;
                else
                    $scope.RunnerValue = [{}];

                if (data.MarketData != angular.isUndefinedOrNull && data.MarketData.length != 0)
                    $scope.GetMarketInfo = data.MarketData[0];

                $scope.ManualMatchOddsData=data.ManualMatchOddsData;
                if($scope.ManualMatchOddsData != null && false){
                    $scope.team1backodd[0]=  $scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.team1_back-1),1);
                    $scope.team1backodd[1]=  $scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.team2_back-1),1);
                    $scope.team1backodd[2]=  $scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.draw_back-1),1);
                    $scope.team1layodd[0]=$scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.team1_lay-1),1);
                    $scope.team1layodd[1]=$scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.team2_lay-1),1);
                    $scope.team1layodd[2]=$scope.getOddCalcVal(parseFloat($scope.ManualMatchOddsData.draw_lay-1),1);
                    $scope.team1Suspend[0]=$scope.ManualMatchOddsData.active_team1==0 ? false : true;
                    $scope.team1Suspend[1]=$scope.ManualMatchOddsData.active_team2==0 ? false : true;
                    $scope.team1Suspend[2]=$scope.ManualMatchOddsData.active_draw==0 ? false : true;
                    $scope.Manuald.pointDiff=parseFloat($scope.ManualMatchOddsData.dlay_time);
                }
            });
    }

    $scope.changeMarketStatus = function(id,status)
    {

        var active=status==true ? 1 : 0;
        var formData={id:id,visibility:active}
        $scope.loading=true;
        $http.post(BASE_URL+'Geteventcntr/updateMarketById',formData).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;

        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }


    $scope.InitializeManualData=function(marketitem)
    {
        $scope.ManualMatchOddsData=marketitem.manual_market_data;
        if($scope.ManualMatchOddsData != null){
            debugger;
            $scope.team1backodd[0+$scope.ManualMatchOddsData.market_id]= parseFloat($scope.ManualMatchOddsData.team1_back);
            $scope.team1backodd[1+$scope.ManualMatchOddsData.market_id]= parseFloat(($scope.ManualMatchOddsData.team2_back));
            $scope.team1backodd[2+$scope.ManualMatchOddsData.market_id]= parseFloat(($scope.ManualMatchOddsData.draw_back));
            $scope.team1layodd[0+$scope.ManualMatchOddsData.market_id]=parseFloat(($scope.ManualMatchOddsData.team1_lay));
            $scope.team1layodd[1+$scope.ManualMatchOddsData.market_id]=parseFloat(($scope.ManualMatchOddsData.team2_lay));
            $scope.team1layodd[2+$scope.ManualMatchOddsData.market_id]=parseFloat(($scope.ManualMatchOddsData.draw_lay));
            $scope.team1Suspend[0+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_team1==0 ? false : true;
            $scope.team1Suspend[1+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_team2==0 ? false : true;
            $scope.team1Suspend[2+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_draw==0 ? false : true;
            marketitem.pointDiff=parseFloat($scope.ManualMatchOddsData.dlay_time);
            marketitem.MaxStack=$scope.ManualMatchOddsData.max_stack;
            marketitem.MinStack=$scope.ManualMatchOddsData.min_stack;
            /*$scope.team1backodd[0+$scope.ManualMatchOddsData.market_id]= parseInt(($scope.ManualMatchOddsData.team1_back+"").split(".")[1]);
            $scope.team1backodd[1+$scope.ManualMatchOddsData.market_id]= parseInt(($scope.ManualMatchOddsData.team2_back+"").split(".")[1]);
            $scope.team1backodd[2+$scope.ManualMatchOddsData.market_id]= parseInt(($scope.ManualMatchOddsData.draw_back+"").split(".")[1]);
            $scope.team1layodd[0+$scope.ManualMatchOddsData.market_id]=parseInt(($scope.ManualMatchOddsData.team1_lay+"").split(".")[1]);
            $scope.team1layodd[1+$scope.ManualMatchOddsData.market_id]=parseInt(($scope.ManualMatchOddsData.team2_lay+"").split(".")[1]);
            $scope.team1layodd[2+$scope.ManualMatchOddsData.market_id]=parseInt(($scope.ManualMatchOddsData.draw_lay+"").split(".")[1]);
            $scope.team1Suspend[0+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_team1==0 ? false : true;
            $scope.team1Suspend[1+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_team2==0 ? false : true;
            $scope.team1Suspend[2+$scope.ManualMatchOddsData.market_id]=$scope.ManualMatchOddsData.active_draw==0 ? false : true;
            marketitem.pointDiff=parseFloat($scope.ManualMatchOddsData.dlay_time);*/
        }
    }
    $scope.getSumValPnL = function(a, b) {
        if(a==angular.isUndefinedOrNull && b==angular.isUndefinedOrNull){ a=0;b=0; }
        return (parseFloat(a) + parseFloat(b));
    }

    $scope.$on("$destroy", function(event) {
    $interval.cancel($scope.stopScore);
        $timeout.cancel($scope.callOddsFunc);
        $scope.callOddsFunc = angular.isUndefinedOrNull;
    });

    $scope.Clean=function()
    {
        $timeout.cancel(scoreOddPosition);
        $scope.UserSessionData = [];
        callscoreOddpostype=0;
    }
    $scope.formData = {};
    $scope.scorePosition=function(FancyId,FancyTypeId){

        if(FancyId!=OldFanyID || FancyTypeId != oldFancyTypeId){
            OldFanyID = FancyId;
            oldFancyTypeId=FancyTypeId;
            $scope.scorePosition1= [];
            $timeout.cancel(scorePosition);

        }
        if(FancyId!=angular.isUndefinedOrNull){
            scorePosition = $timeout(function(){
                var indx = $scope.FancyData.findIndex(x=>x.ID==$scope.formData.FancyVal);
                var fValue=0;
                if(indx>-1)
                {
                    var result = $scope.FancyData[indx];
                    var inputNo=result.SessInptNo!="" ? parseFloat(result.SessInptNo) :0;
                    var inputYes=result.SessInptYes!="" ? parseFloat(result.SessInptYes) : 0;
                    fValue=inputNo>=inputYes ? inputNo : inputYes;

                }
                var $promise = $http.get(BASE_URL + 'Sessioncntr/FancyScorePosition/'  + $scope.formData.FancyVal + '/' + sessionService.get('slctUseID') + '/' + FancyTypeId+'/'+fValue);
                $promise.then(function (response) {

                    if(callscorepostype == 0){
                        $scope.scorePosition1=[];
                        $scope.result=[];

                        $scope.scorePosition1=response.data.scorePosition;
                        callscorepostype=1;
                    }
                    else{
                        $scope.result=response.data.scorePosition;

                    }

                    for(var i= 0;i< $scope.result.length;i++){

                        $scope.scorePosition1[i] =($scope.result[i]);


                    }


                    $scope.scorePosition(FancyId,FancyTypeId);
                });

            },1000);
        }
    }
    $scope.GetSeesionBetData=function(fancy_id){

        var abc=$scope.Fdata.FancyVal1;

        if($scope.Fdata.FancyVal1!=old_fancy_id ){
            old_fancy_id = $scope.Fdata.FancyVal1;
            $timeout.cancel(scoreOddPosition);
            $scope.UserSessionData = [];
            callscoreOddpostype=0;
        }
        scoreOddPosition = $timeout(function(){
            $http.get(BASE_URL +'Betentrycntr/GatBetFancyData/' + $stateParams.MarketId + '/' + sessionService.get('type') + '/' + sessionService.get('user_id') + '/' + $stateParams.MatchId + '/' + $scope.Fdata.FancyVal1).success(function(data, status, headers, config) {


                if(callscoreOddpostype == 0){
                    $scope.UserSessionData = [];
                    $scope.UserSessionData = data.betUserData;
                    callscoreOddpostype=1;
                }
                else{
                    var result=data.betUserData;

                }
                if(data.betUserData != angular.isUndefinedOrNull)
                {
                    if($scope.UserSessionData.length > 0)
                    {
                        if(data.betUserData[0].selectionName!=$scope.UserSessionData[0].selectionName)
                        {
                            $scope.UserSessionData=result;
                        }
                    }
                }
                for(var i in result){

                    var ind = $scope.UserSessionData.findIndex(x=>x.SrNo==result[i].SrNo);
                    if(ind==-1)
                    {
                        $scope.UserSessionData.push(result[i]);
                    }
                    else
                    {
                        $scope.UserSessionData[i]=result[i];
                    }

                }
                $scope.GetSeesionBetData($scope.Fdata.FancyVal1);
            });

        },1000);
    }
   /* $scope.callOddsFunc = function() {
        
        var maxloop = 0;
        if (sessionService.get('slctUseTypeID') == 3) {
            $scope.UserId = sessionService.get('slctUseID');
            get_userser.GetWALLibiInfo($scope.UserId);
        } else {
            $scope.UserId = sessionService.get('user_id');
            get_userser.GetWALLibiInfo($scope.UserId);
        }
        var $promise = $http.get('Geteventcntr/getBackLaysOfMarket/' + MarketId + '/' + $stateParams.MatchId);
        $promise.then(function(response) {
            //For Play Pause start
            if (sessionService.get('type') == "0") {
                $http({
                    method: 'POST',
                    url: 'Geteventcntr/matchMarketLst/',
                    data: {
                        matchId: $stateParams.MatchId,
                        sportsId: 4,
                        user_id: sessionService.get('user_id')
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {
                    try {
                        $scope.FancyLength=data.getMatchFancy.length;
                        if($scope.FancyLength > 0 && $scope.FancyData != angular.isUndefinedOrNull && $scope.FancyData.length>0){
                            if($scope.FancyData.length == data.getMatchFancy.length){
                                for (var i = 0; i < data.getMatchFancy.length; i++) {
                                    if($scope.FancyData[i].SessInptYes==data.getMatchFancy[i].SessInptYes && $scope.FancyData[i].SessInptNo==data.getMatchFancy[i].SessInptNo && $scope.FancyData[i].active==data.getMatchFancy[i].active && $scope.FancyData[i].DisplayMsg==data.getMatchFancy[i].DisplayMsg){ 

                                    }else{
                                         $scope.FancyData=data.getMatchFancy;
                                    }
                                } 
                            }else{
                               $scope.FancyData=data.getMatchFancy; 
                            }
                            
                        }else{
                            $scope.FancyData=data.getMatchFancy;
                        }
                        if ($filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay == "1") {                            
                            $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;
                        }else{
                          $scope.PLAYPAUSE=$filter('filter')(data.MatchMarket, { Id: $scope.MarketId })[0].IsPlay;  
                        }
                    } catch (e) {}
                });
            }
            //For Play Pause end
            if (response.data.MatchOddsVolVal != angular.isUndefinedOrNull) {
                if (response.data.MatchOddsVolVal[0].oddsLimit != angular.isUndefinedOrNull)
                    $scope.oddsLimit = parseFloat(response.data.MatchOddsVolVal[0].oddsLimit);
                else
                    $scope.oddsLimit = 0;
                if (response.data.MatchOddsVolVal[0].volumeLimit != angular.isUndefinedOrNull && response.data.MatchOddsVolVal[0].volumeLimit != 0)
                    $scope.volumeLimit = parseFloat(response.data.MatchOddsVolVal[0].volumeLimit);
                else
                    $scope.volumeLimit = 1;
                if (response.data.MatchOddsVolVal[0].result != "0")
                    $scope.MatchResult = "CLOSED";
                else
                    $scope.MatchResult = "OPEN";
            }
            if ($scope.GetMarketBackLayData == angular.isUndefinedOrNull) {
                $scope.GetMarketBackLayData = response.data.MarketRunner;
                if (response.data.MarketRunner == angular.isUndefinedOrNull) {
                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") {
                        $scope.callOddsCloseMatch();
                    }
                }
            } else if (MarketId == $scope.GetMarketBackLayData.marketId) {
                selectedRunner = null;
                if (response.data.MarketRunner != angular.isUndefinedOrNull && response.data.MarketRunner.totalMatched > totalMatch) {
                    selectedRunner = response.data.MarketRunner.runners;
                    try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                        console.log(response.data.MarketRunner); }
                    $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                    matchStatus = response.data.MarketRunner.status;
                    //$scope.GetMarketBackLayData.IsActive = data.IsActive;
                    totalMatch = response.data.MarketRunner.totalMatched;
                    $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
                    if ($scope.MatchResult == "OPEN" && $scope.GetMarketBackLayData.status == "OPEN" && $scope.GetMarketBackLayData.runners != angular.isUndefinedOrNull && $scope.GetMarketBackLayData.runners.length > 0) { //&& selectedRunner != angular.isUndefinedOrNull
                        try {
                            if ($scope.GetMarketBackLayData.runners.length < selectedRunner.length) //170204
                                maxloop = selectedRunner.length;
                            else
                                maxloop = $scope.GetMarketBackLayData.runners.length;
                            for (var j = 0; j < maxloop; j++) { //170204 $scope.GetMarketBackLayData.runners.length
                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack.length == selectedRunner[j].ex.availableToBack.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price != selectedRunner[j].ex.availableToBack[0].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].size != selectedRunner[j].ex.availableToBack[0].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = selectedRunner[j].ex.availableToBack[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].size = selectedRunner[j].ex.availableToBack[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price != selectedRunner[j].ex.availableToBack[1].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].size != selectedRunner[j].ex.availableToBack[1].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = selectedRunner[j].ex.availableToBack[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].size = selectedRunner[j].ex.availableToBack[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price != selectedRunner[j].ex.availableToBack[2].price || $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].size != selectedRunner[j].ex.availableToBack[2].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].price = selectedRunner[j].ex.availableToBack[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].size = selectedRunner[j].ex.availableToBack[2].size;
                                    } catch (e) {g
                                        if ($scope.GetMarketBackLayData.rgunners[j].ex.availableToBack[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.rgunners[j].ex.availableToBack[2].price = "";
                                        }
                                    }
                                } else {

                                    $scope.GetMarketBackLayData.runners[j].ex.availableToBack = selectedRunner[j].ex.availableToBack;
                                }
                                if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay.length == selectedRunner[j].ex.availableToLay.length) {
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price != selectedRunner[j].ex.availableToLay[0].price || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size != selectedRunner[j].ex.availableToLay[0].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = selectedRunner[j].ex.availableToLay[0].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].size = selectedRunner[j].ex.availableToLay[0].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[0] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].price = "";
                                        }
                                    }g
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price != selectedRunner[j].ex.availableToLay[1].price  || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].size != selectedRunner[j].ex.availableToLay[1].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = selectedRunner[j].ex.availableToLay[1].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].size = selectedRunner[j].ex.availableToLay[1].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[1] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].price = "";
                                        }
                                    }
                                    try {
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = false;
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price != selectedRunner[j].ex.availableToLay[2].price  || $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size != selectedRunner[j].ex.availableToLay[2].size) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = true;
                                        }
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = selectedRunner[j].ex.availableToLay[2].price;
                                        $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].size = selectedRunner[j].ex.availableToLay[2].size;
                                    } catch (e) {
                                        if ($scope.GetMarketBackLayData.runners[j].ex.availableToLay[2] != angular.isUndefinedOrNull) {
                                            $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].price = "";
                                        }
                                    }
                                } else {

                                    $scope.GetMarketBackLayData.runners[j].ex.availableToLay = selectedRunner[j].ex.availableToLay;
                                }
                            }
                        } catch (e) {

                            $scope.GetMarketBackLayData = angular.isUndefinedOrNull;
                        }
                        $scope.counter = $scope.counter + 1;
                    } else if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") //170201
                    {
                        $scope.GetMarketBackLayData = response.data.MarketRunner;
                        $scope.callOddsCloseMatch();
                    }
                } else if ($scope.GetMarketBackLayData.status == "CLOSED" || $scope.MatchResult == "CLOSED") //170201
                {
                    $scope.GetMarketBackLayData = response.data.MarketRunner;
                    $scope.callOddsCloseMatch();

                }
            } else {
                $scope.GetMarketBackLayData = response.data.MarketRunner;
                try { $scope.GetMarketBackLayData.inplay = response.data.MarketRunner.inplay; } catch (e) { console.log('inplay--');
                    console.log(response.data.MarketRunner); }
                $scope.GetMarketBackLayData.status = response.data.MarketRunner.status;
                matchStatus = response.data.MarketRunner.status;
                $scope.GetMarketBackLayData.totalMatched = response.data.MarketRunner.totalMatched;
            }
            marketTimer = $timeout(function() {
                
                if ($scope.GetMarketBackLayData != angular.isUndefinedOrNull) { //sourabh 170107
                    for (var j = 0; j < maxloop; j++) { // $scope.GetMarketBackLayData.runners.length 170204
                        //for (var i = 0; i < 3; i++) {//$scope.GetMarketBackLayData.runners[j].ex.availableToBack.length
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[0].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[1].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToBack[2].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[0].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[1].SELECTED = false; } catch (e) {}
                        try { $scope.GetMarketBackLayData.runners[j].ex.availableToLay[2].SELECTED = false; } catch (e) {}
                        //}
                    }
                    if ($scope.GetMarketBackLayData.Status != 3) {
                        if ($scope.GetMarketBackLayData.marketId != null) {
                            $scope.callOddsFunc();
                            $scope.getNameFunc();
                        }
                    }
                } else {
                    $scope.callOddsFunc();
                    $scope.getNameFunc();
                }
            }, 1000);
            /!*{aakash 161226*!/
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
                    if (!$scope.netConn) {
                        $mdDialog.hide();
                        $scope.netConn = true;
                        $scope.callOddsFunc();
                        $scope.getNameFunc();
                    }
                } else {
                    Changed = $interval(updatedOnline, 100)
                    if ($scope.netConn) {
                        $mdDialog.show({
                            clickOutsideToClose: false,
                            escapeToClose: false,
                            template: "<md-dialog style='border: rgb(255, 0, 0) solid 2px;width: 300px;height: 100px;font-size:14px;font-weight:bold;'><md-dialog-content>Internet Connection is Disconnect... Please Wait...</md-dialog-content></md-dialog>",
                            locals: { prntScope: $scope },
                            fullscreen: false,
                            controller: function DialogController(prntScope) {
                                prntScope.netConn = false;
                            }
                        });
                    }
                }
            }
            /!*}aakash 161226*!/
        });
        //}
    }*/
    /*$scope.callOddsCloseMatch = function() {
       // 
        if ($scope.GetMarketBackLayData.status == "CLOSED") {
            var vSelectionID = $filter('filter')($scope.GetMarketBackLayData.runners, { status: "WINNER" })[0].selectionId;
            var selectionName1 = "";
            //for (var j = 0; j < $scope.GetMarketBackLayData.runners.length; j++) {
            //if ($scope.GetMarketBackLayData.runners[j].status == "WINNER") {

            if ($scope.RunnerValue != angular.isUndefinedOrNull && $scope.RunnerValue.length > 0 && $scope.RunnerValue[0].length > 0) //sourabh 170131
            {
                //
                selectionName1 = $filter('filter')($scope.RunnerValue, { SelectionId: vSelectionID })[0].selectionName;
                //for (var i = 0; i < $scope.RunnerValue.length; i++) {
                //if ($scope.RunnerValue[i].SelectionId == $scope.GetMarketBackLayData.runners[j].selectionId || $scope.RunnerValue[i].selectionId == $scope.GetMarketBackLayData.runners[j].selectionId) {
                //selectionName1 = $scope.RunnerValue[i].selectionName;
                if (selectionName1 != "")
                    $scope.saveMatchoddsResult($scope.MatchId, $scope.GetMarketInfo.SportID, $scope.MarketId, vSelectionID, 1, $scope.GetMarketInfo.SportName, $stateParams.matchName, $scope.GetMarketInfo.MarketName, selectionName1);
                //}
                //}
            } else {
                $http.get('Geteventcntr/getSelectionName/' + $scope.MarketId + '/' + $scope.MatchId).success(function(data, status, headers, config) {
                    //$scope.RunnerValue = data.RunnerValue;
                    selectionName1 = $filter('filter')(data.RunnerValue, { selectionId: vSelectionID })[0].selectionName;
                    if (selectionName1 != "")
                        //
                        $scope.saveMatchoddsResult($scope.MatchId, $scope.GetMarketInfo.SportID, $scope.MarketId, vSelectionID, 1, $scope.GetMarketInfo.SportName, $stateParams.matchName, $scope.GetMarketInfo.MarketName, selectionName1);
                });
            }
            //}
            //}
        } else if ($scope.MatchResult == "CLOSED") {
            $scope.GetMarketBackLayData.status = "CLOSED";
            $timeout.cancel(marketTimer);
            marketTimer = angular.isUndefinedOrNull;
            $rootScope.$broadcast('changeSidebar_Market', {});
            if (sessionService.get('type') == "1")
                $state.go('dashboard.Masterdashboard');
            else if (sessionService.get('type') == "2")
                $state.go('dashboard.Dealerdashboard');
            else if (sessionService.get('type') == "3")
                $state.go('dashboard.Userdashboard');

        }

    };*/
    $scope.stakeValReset = function(val) {
        $scope.stake = parseInt(val);
    };
    $scope.getCalculation = function(priceVal, stake) {
        //
        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        } else {
            $scope.sumOfVal = parseFloat(priceVal) * parseInt(stake) - parseInt(stake);
            $scope.sumOfVal = $scope.sumOfVal.toFixed(2);
        }
    }
    $scope.stakeVal = function(val, selectionId, stake) { 

        if (stake == angular.isUndefinedOrNull) {
            stake = 0;
        }
        if (stake == 0) {}
        $scope.sumOfVal = parseFloat(val) * parseInt(stake) - parseInt(stake);
        $scope.sumOfVal = $scope.sumOfVal.toFixed(2);
        $scope.stake = parseInt(val);//$scope.stake + 
        $scope.MarketId = $stateParams.MarketId;
        $scope.UserTypeId = sessionService.get('type');
        var userId = document.getElementById('userId').value;
        var ParantId = document.getElementById('ParantId').value;
        var loginId = document.getElementById('loginId').value;
        var selectionId = document.getElementById('selectionId').value;
        var matchId = document.getElementById('matchId').value;
        var isback = document.getElementById('isback').value;
        var MarketId = document.getElementById('MarketId').value;
        var priceVal = $scope.priceVal;
        var stake = $scope.stake;
        var placeName = document.getElementById('placeName').value;
        var chkValPrice = document.getElementById('chkValPrice').value;
        chkValPrice = parseFloat(chkValPrice);
        if (chkValPrice == priceVal) {
            var isMatched = 1;
        } else {
            var isMatched = 0;
        }
        var P_and_l = (priceVal * stake) - stake;
        $scope.formData = {
            userId: sessionService.get('slctUseID'),
            ParantId: ParantId,
            loginId: loginId,
            selectionId: selectionId,
            matchId: $stateParams.MatchId,
            isback: isback,
            stake: stake,
            priceVal: priceVal,
            p_l: P_and_l,
            MarketId: MarketId,
            isMatched: isMatched,
            UserTypeId: $scope.UserTypeId,
            placeName: placeName,
            MatchName: $stateParams.matchName
        }
        $scope.getCheckLimitorVal($scope.formData);
    }  
    $scope.getValColor = function(val) {
        if (val == angular.isUndefinedOrNull || val == 0)
            return 'color:#000000';
        else if (val > 0)
            return 'color:#1ed61e !important';
        else
            return 'color:#ff0000 !important';
    }
$scope.CommonFun=function(msg)
{
	//websocket.onclose();
	Dialog.autohide(msg);
	$scope.loading = false;
	//$scope.IsShowPage=false;
	//$state.go("dealerDashboard.Home");
}

   $scope.GetMarketListId = function()
	{
	$scope.getMarketlstTimer=$timeout(function(){	
		
	if($state.current.name=="dashboard.Matchodds"){
	    if(callType1==1)
		{
	   $scope.loading = true;
		}
else{$scope.loading = false;}
	   
	 //  socket.emit('CallGetMarketListId', {auth:Bauthdata,MatchId:$scope.MatchId,UserId:$scope.UserId});
		 $http({
                    method: 'GET',
                    url: 'Apicontroller/getMarketListing/'+$scope.MatchId,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                }).success(function(data) {

		 if(data.error)
			{
			$scope.ClearAllTimeOut();
			Dialog.autohide(data.message);
                        $scope.loading = false;
                        $scope.IsShowPage=false;
                        $state.go("dashboard.Home");
			}
		else{


             $scope.isManualMatchOdds=data.isManualMatchOdds;
             if(data.is_manual==1)
             {
                 $scope.selectionNames=data.selection;
                 $scope.scoreboardjson=data.data[0].score_board_json[0];
                 if($scope.scoreboardjson != angular.isUndefinedOrNull)
                 {
                     $scope.betting_team= $scope.scoreboardjson.selectedScore.bettingTeam;
                     $scope.toss_winner= $scope.scoreboardjson.selectedScore.toss;
                     $scope.manualOver= $scope.scoreboardjson.selectedScore.overs;
                     $scope.manualWickets= $scope.scoreboardjson.selectedScore.wickets;
                 }

             }


                $scope.isManualMatch=data.is_manual;
             $scope.isBetAllowedOnManualMatchOdds = data.isBetAllowedOnManualMatchOdds;
		 if(data.data.result==0 || data.data.marketid=="")
                    {
			$scope.ClearAllTimeOut();
                        Dialog.autohide("Match Closed.");
                        $scope.loading = false;
                        $scope.IsShowPage=false;
                        $state.go("dashboard.Home");
                    }
                  
   		else if(callType1==1){
		     var tResult = data;

            // $scope.BindManulalOdds(data.runners_manual);
                    //  $scope.oddsLimit=parseFloat(tResult.data.volumeLimit[0].oddsLimit);
                    // $scope.volumeLimit=parseFloat(tResult.data.volumeLimit[0].volumeLimit);

		     $timeout.cancel($scope.ajaxTimer);
		     $scope.ajaxTimer=null;
		      if(tResult.error)
                     {
                           
                        $scope.BindSoketMarket(tResult);
                     }
                     else
                     {
                        $scope.IsShowPage=true;
                        $scope.GetScore();
                        $scope.BindSoketMarket(tResult);
                     }
                }
         else if(callType1==2)
         {
             $scope.BindSoketMarketSecondStep(data);
         }
		}
			 $scope.GetMarketListId();
			 callType1=2;
			}).error(function(err){
			 $scope.loading = false;
        });	
	}
        },1000);
	
    }
    $scope.isUpdateRunner=false;
    $scope.BindSoketMarketSecondStep=function(result)
    {

        if(result.data != angular.isUndefinedOrNull)
        {
            if(result.data.length!=$scope.FinalArray.length)
            {
                $scope.loading = true;
                $scope.FinalArray=[];
                for(var i=0;i<result.data.length;i++){
                    $scope.FinalArray.push(result.data[i]);
                }
                $scope.BindSoketMarket(result)
                $scope.loading = false;
            }
            else
            {
                for(var i=0;i<result.data.length;i++){
                    var ind=$scope.FinalArray.findIndex(x=>x.marketid==result.data[i].marketid);
                    if(ind>-1)
                    {
                        $scope.FinalArray[ind].visibility=result.data[i].visibility;
                        $scope.FinalArray[ind].isBetAllowedOnManualMatchOdds=result.data[i].isBetAllowedOnManualMatchOdds;
                        $scope.FinalArray[ind].id=result.data[i].marketid;
                        if($scope.FinalArray[ind].is_manual==1 && $scope.isUpdateRunner)
                        {
                            $scope.FinalArray[ind].runners=result.data[i].runners;
                            $scope.isUpdateRunner=false;
                        }
                    }
                    $scope.FinalArray[ind].name=result.data[i].market_name;


                }
            }
        }
    }
    $scope.BindManulalOdds=function(result)
    {
        if(result!=angular.isUndefinedOrNull)
        {

            $scope.ManualMatchOddsData=result;

        }
    }
//step 1
    $scope.GetScore=function(){      
$scope.GetScoreTimer=$timeout(function(){
            var eventIds = $stateParams.MatchId;
         //var eventIds = '28448035';
        $http.get(BASE_URL+'Geteventcntr/GetScoreApi/'+eventIds).then(function(result) {
            
            if (result.data.length!= 0) {
                $scope.Documents=result.data[0];
                $scope.displayScore=true;
                if($scope.Documents.eventTypeId==2){
                    $scope.Home=result.data[0].score.home.gameSequence;
                    $scope.away=result.data[0].score.away.gameSequence;
                }
            }else{
                $scope.displayScore=false;
                $interval.cancel($scope.stopScore);
            }
        });
		},1000);
    }
$scope.BindSoketMarket=function(result)
    {

	$scope.marketIdLst=[];
        $scope.KeyValue=[];
        $scope.lstKeyValue=[];
        if(result.data != angular.isUndefinedOrNull)
            {

		  for(var i=0;i<result.data.length;i++){
			 $scope.marketIdLst.push(result.data[i].marketid);

              var kind=$scope.KeyValue.findIndex(x=>x.value==i);
              if(kind==-1)
              {
                  $scope.KeyValue.push({'key':$scope.firstKey,'value':i});
                  $scope.firstKey+=1;
              }
              var lind=$scope.lstKeyValue.findIndex(x=>x.value==i);
              if(lind==-1)
              {
                  $scope.lstKeyValue.push({'key':$scope.lstKey,'value':i});
                  $scope.lstKey+=1;
              }
			if(callType1==1)
			{
	 	   	  $scope.FinalArray.push(result.data[i]);
	 	   	  if(result.data[i].market_name=="Match Odds")
              {
                  $scope.GetMarketBackLayDataSelectionName=$scope.FinalArray[0].runners;
              }

			}

		    }
                if($scope.sportId == 4) {
                    $scope.getFancyList(result.data[0].marketid);
                    $scope.MOddsMarketId=result.data[0].marketid;
                    //$scope.UpdateAdminFancyList(result.data[0].marketid);
                }
            //var callbackResult=$scope.SetResult($scope.marketIdLst);
         
            $scope.MarketLst=$scope.marketIdLst.join(',');
            $scope.MarketWinLoss($scope.MarketLst);
	    $scope.MarketId= $scope.MarketLst;
            $scope.AllMarket =  $scope.MarketLst;
		 $scope.GetScore();
		if(true){
}

                get_userser.getSocketDataApiDetail($scope.MarketLst,function(data) {
                    $scope.market_sel_id=[];
                    //console.log(data);
                    var dataResult = data;
                    var tempResult =[];

                    if(dataResult !=angular.isUndefinedOrNull && dataResult['cricket'].length>0 && dataResult['cricket'][0]!=angular.isUndefinedOrNull)
                    {
                        for(var j=0;j<$scope.FinalArray.length;j++)
                        {
                            var ind = dataResult['cricket'].findIndex(x=>x.id==$scope.FinalArray[j].marketid);

                            if(ind>-1)
                            {
                                tempResult.data=dataResult['cricket'][ind];
                                var tempArray=$scope.FinalArray[j];
                                $scope.FinalArray[j].volumeLimit=tempArray.volumeLimit;
                                $scope.FinalArray[j].IsMatchDisable=tempArray.IsMatchDisable;
                                $scope.FinalArray[j].is_favourite=tempArray.is_favourite;
                                $scope.FinalArray[j].id=tempResult.data.id;
                                $scope.FinalArray[j].mtype=tempResult.data.mtype;
                                $scope.FinalArray[j].btype=tempResult.data.btype;
                                $scope.FinalArray[j].name=tempArray.market_name;
                                $scope.FinalArray[j].status=tempResult.data.status;
                                $scope.FinalArray[j].inplay=tempResult.data.inPlay;
                                $scope.FinalArray[j].groupById=tempResult.data.matchid;
                                $scope.FinalArray[j].isBetAllowedOnManualMatchOdds=tempArray.isBetAllowedOnManualMatchOdds;
                                if($scope.FinalArray[j].name=="Match Odds") {
                                    $scope.GetMarketBackLayDataSelectionName = $scope.FinalArray[j].runners;
                                }
                                //data base runners exist
                                if($scope.FinalArray[j].runners.length>0)
                                {


                                    for(var r=0;r<$scope.FinalArray[j].runners.length;r++)
                                    {
                                        if($scope.FinalArray[j].runners[r].selectionId != angular.isUndefinedOrNull){
                                            $scope.FinalArray[j].runners[r].id=$scope.FinalArray[j].runners[r].selectionId;
                                        }

                                        if($scope.FinalArray[j].runners[r].name=="" || tempArray.runners[r].name==angular.isUndefinedOrNull){
                                            var sId=$scope.FinalArray[j].id +"-"+ $scope.FinalArray[j].runners[r].id;
                                            $scope.FinalArray[j].runners[r].selection_id=sId;

                                            $scope.market_sel_id.push(sId);
                                        }
                                        else
                                        {
                                            var rindx=  $scope.FinalArray[j].runners.findIndex(x=>x.id==tempArray.runners[r].id);
                                            if(rindx>-1)
                                            {
                                                $scope.FinalArray[j].runners[rindx].name=tempArray.runners[r].name;
                                            }
                                            if($scope.FinalArray[j].mtype=="MATCH_ODDS" || $scope.FinalArray[j].btype=="ODDS"){
                                                $scope.BindIndianFancy(dataResult['session'],$scope.FinalArray[j].id);
                                            }
                                            $scope.CallSocketMarket();
                                        }
                                    }


                                }
                                else if($scope.FinalArray[j].runners.length==0)
                                {
                                    $scope.FinalArray[j].runners=tempResult.data.runners;
                                    for(var r=0;r<$scope.FinalArray[j].runners.length;r++)
                                    {
                                        if($scope.FinalArray[j].runners[r].name=="" || $scope.FinalArray[j].runners[r].name==angular.isUndefinedOrNull || true){
                                            var sId=$scope.FinalArray[j].id +"-"+ $scope.FinalArray[j].runners[r].id;
                                            $scope.FinalArray[j].runners[r].selection_id=sId;
                                            $scope.market_sel_id.push(sId);
                                        }

                                    }


                                }

                                $scope.loading = false;
                            }
                            else{
                                //Dialog.autohide("Odds not comming from api.");
                                $scope.loading = false;
                                //$scope.CallSocketMarket();
                                //$scope.IsShowPage=false;
                                //$timeout.cancel($scope.ajaxTimer);
                                //$state.go("userDashboard.Home");
                            }
                        }

                        if($scope.market_sel_id.length>0)
                        {
                            $scope.SelectionName=[];
                            var selection_id=$scope.market_sel_id.join(',');

                            get_userser.getSelectionList(selection_id,function(data) {

                                $scope.SelectionName=data;
                                for(var j=0;j<$scope.FinalArray.length;j++)
                                {

                                    if($scope.FinalArray[j].mtype=="MATCH_ODDS" || $scope.FinalArray[j].btype=="ODDS"){
                                        $scope.BindIndianFancy(dataResult['session'],$scope.FinalArray[j].id);
                                    }
                                    for(var s=0;s<$scope.SelectionName.length;s++)
                                    {
                                        var indx=$scope.FinalArray[j].runners.findIndex(x=>x.selection_id==$scope.SelectionName[s].selection_id)
                                        if(indx>-1)
                                        {
                                            $scope.FinalArray[j].runners[indx].name=$scope.SelectionName[s].runnername;
                                        }
                                    }
                                    if($scope.FinalArray[j].name=="Match Odds") {
                                        $scope.GetMarketBackLayDataSelectionName = $scope.FinalArray[j].runners
                                    }
                                    // $scope.FinalArray[j].runners=tempArray.runners;

                                    $scope.CallSocketMarket();
                                }

                            });
                        }




                    }
                    else {
                        if($scope.SPORTID == 4 && dataResult['session'].length==0 && dataResult['session'][0]==angular.isUndefinedOrNull)
                        {
                            $scope.isSessionNull=true;
                        }
                        $scope.loading = false;

                    }
                    $scope.CallSocketMarket();
                });
      

            }

    }
$scope.$on('$destroy', function () {
    $timeout.cancel($scope.ajaxTimer);
    $timeout.cancel($scope.GetScoreTimer);
});

//step 2
  $scope.CallSocketMarket=function(){
$scope.ajaxTimer = $timeout(function(){
if($state.current.name=="dashboard.Matchodds"){
callType1="2";
 //$scope.GetMarketListId();
		   get_userser.getSocketDataApiDetail($scope.MarketLst,function(data) {
		       try{


	 			 var dataResult = data;
		                     var tempResult =[];
		                
		                         if(dataResult !=angular.isUndefinedOrNull && dataResult['cricket'].length>0 && dataResult['cricket'][0] !=angular.isUndefinedOrNull)
		                         {
		                            for(var j=0;j<$scope.FinalArray.length;j++)
						{

					             var ind = dataResult['cricket'].findIndex(x=>x.id==$scope.FinalArray[j].id);
				                     if(ind>-1)
				                     {
		                                        tempResult.data=dataResult['cricket'][ind];
                                         $scope.FinalArray[j].status=tempResult.data.status;
                                         $scope.FinalArray[j].inPlay=tempResult.data.inPlay;
							if($scope.FinalArray[j].mtype=="MATCH_ODDS"){
		                                         $scope.BindIndianFancy(dataResult['session'],$scope.FinalArray[j].id);
							}
							 $scope.SocketMarket(tempResult);
			                                 $scope.loading = false;
						
						     }
						}
					}
					$timeout.cancel($scope.ajaxTimer);
					$scope.CallSocketMarket();
               }
               catch (e) {
                   $scope.CallSocketMarket();
               }
				});

if($scope.SPORTID==4)
{
	//$scope.getFancyList($scope.MarketId);
	
	//$scope.BindIndianFancy($scope.MarketId);
}	

// $scope.GetScore();
}
},1000);
	}
    $scope.BindIndianFancy=function(data,mId)
    {


            // var temp  =  JSON.parse(event.data);

            var dataResult=data;

            // dataResult.push(data.message);
            var tempResult =[];
            if(dataResult!=angular.isUndefinedOrNull)
            {

                if(dataResult.length>0 && dataResult[0]!=null)
                {
                    var ind = dataResult.findIndex(x=>x.market_id==mId);
                    if(ind>-1)
                    {
                        tempResult.data=dataResult[ind].value.session;
                        $scope.FancyLiveData=tempResult.data;
                        $scope.loading = false;
                        //$scope.SocketMarket(tempResult);
                        if($scope.FancyData!=angular.isUndefinedOrNull)
                        {

                            for(var i=0;i<$scope.FancyData.length;i++)
                            {

                                if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A")
                                {
                                    if($scope.FancyLiveData!=angular.isUndefinedOrNull)
                                    {
                                        var inde = $scope.FancyLiveData.findIndex(img => img.SelectionId === $scope.FancyData[i].ind_fancy_selection_id);
                                        if(inde > -1)
                                        {
                                            var obj = $scope.FancyLiveData[inde];
                                            if(obj!=angular.isUndefinedOrNull)
                                            {
                                                $scope.FancyData[i].SessInptNo=obj.LayPrice1;
                                                $scope.FancyData[i].NoValume=obj.LaySize1;
                                                $scope.FancyData[i].SessInptYes=obj.BackPrice1;
                                                $scope.FancyData[i].YesValume=obj.BackSize1;
                                                if(obj.GameStatus=='SUSPENDED1')
                                                {
                                                    $scope.FancyData[i].DisplayMsg='Ball Running';
                                                }
                                                else{
                                                    $scope.FancyData[i].DisplayMsg=obj.GameStatus;
                                                }
                                                if($scope.FancyDataTemp.length>0 && $scope.FancyDataTemp[i].active==0)
                                                {
                                                    $scope.FancyData[i].active=$scope.FancyDataTemp[i].active;
                                                }
                                                else{
                                                    $scope.FancyData[i].active=(obj.GameStatus =="" || obj.GameStatus=='ACTIVE') ? 1 : 4;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $scope.FancyData[i].SessInptNo='';
                                            $scope.FancyData[i].SessInptYes='';
                                            $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                            $scope.FancyData[i].active= 4;
                                        }


                                    }
                                }

                            }

                        }
                        else{
                            // sessionsocket.onclose();
                            //  Dialog.autohide("Betting not allow.");
                            $scope.loading = false;
                            //   $scope.IsShowPage=false;
                            //  $state.go("userDashboard.Home");


                        }

                    }
                    else
                    {

                        //sessionsocket.onclose();
                        //Dialog.autohide("Record not found.");
                        $scope.loading = false;
                        //$scope.IsShowPage=false;
                        if($scope.FancyData!=angular.isUndefinedOrNull)
                        {

                            for(var i=0;i<$scope.FancyData.length;i++)
                            {
                                if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A" && $scope.FancyData[i].market_id==$scope.MarketId)
                                {
                                    $scope.FancyData[i].SessInptNo='';
                                    $scope.FancyData[i].SessInptYes='';
                                    $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                    $scope.FancyData[i].active= 4;

                                }
                            }
                        }


                    }
                }
                else{
                    // sessionsocket.onclose();
                    // Dialog.autohide("Betting not allow.");
                    $scope.loading = false;
                    // $scope.IsShowPage=false;
                    //$state.go("userDashboard.Home");

                }

                // $('#odds').html(JSON.stringify(event.data));
                //var Data = JSON.parse(event.data);
                //   alert(JSON.stringify(event.data));
                //console.log(JSON.stringify(event.data));
                //showMessage("<div class='"+Data.buy+"'>"+Data.sell+Data.average+"</div>");
                //$('#chat-message').val('');
            }

    }
// step 4

     $scope.UpdateAdminFancyList = function(marketId) {
$scope.UpdateAdminFancyTimer=$timeout(function(){
    if($state.current.name=="dashboard.Matchodds")
    {
        $http({
            method: 'GET',
            url: 'Apicontroller/matchLstAdminSession/'+$stateParams.MatchId+'/'+marketId,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(result) {

            $scope.AdminFancyLiveData=result.data;
            if($scope.AdminFancyLiveData.length>0)
            {
                for(var i=0;i<$scope.FancyData.length;i++)
                {

                    if($scope.FancyData[i].fancy_mode=="M")
                    {

                        var  inde = $scope.AdminFancyLiveData.findIndex(img => img.ID ===$scope.FancyData[i].ID);
                        if(inde > -1)
                        {
                            var obj = $scope.AdminFancyLiveData[inde];
                            if(obj!=angular.isUndefinedOrNull)
                            {
                                $scope.FancyData[i].SessInptNo=obj.SessInptNo;
                                $scope.FancyData[i].SessInptYes=obj.SessInptYes;
                                $scope.FancyData[i].NoValume=obj.NoValume;
                                $scope.FancyData[i].YesValume=obj.YesValume;
                                $scope.FancyData[i].MaxStake=obj.MaxStake;
                                $scope.FancyData[i].pointDiff=obj.pointDiff;
                                $scope.FancyData[i].rateDiff=obj.rateDiff;
                                $scope.FancyData[i].fancyRange=obj.fancyRange;
                                $scope.FancyData[i].DisplayMsg=obj.DisplayMsg;
                                $scope.FancyData[i].active=obj.active;
                            }

                        }
                        else
                        {
                            $scope.FancyData[i].SessInptNo='';
                            $scope.FancyData[i].SessInptYes='';
                            $scope.FancyData[i].DisplayMsg='Result Awaiting';
                            $scope.FancyData[i].active= 4;
                        }


                    }
                }
            }
            $scope.UpdateAdminFancyList(marketId);

        }).error(function(err){
            $scope.loading = false;
            $scope.UpdateAdminFancyList(marketId);
        });
    }

},1000);
    }

//step5
$scope.SocketMarket = function(result)
{
	
    
    //$scope.FancyLiveData = result.session;
    var market = result.data.runners;
    var j = $scope.FinalArray.findIndex(x=>x.id==result.data.id);
    if(market!=angular.isUndefinedOrNull)
    {
     
        for(var m=0;m<market.length;m++)
        {
         //   ;
	 
		if(j>-1){
           var inde = $scope.FinalArray[j].runners.findIndex(img => img.id ===market[m].id);
           if(inde>-1)
           {
	       $scope.FinalArray[j].IsMatchDisable=false;
               for(var b=0;b<$scope.FinalArray[j].runners[inde].back.length;b++)
               {
                var count = b+1;  
                try{
                    $scope.FinalArray[j].runners[inde].back[b].selected =  $scope.CallColor($scope.FinalArray[j].runners[inde].back[b].price,$scope.FinalArray[j].runners[inde].back[b].size, market[m].back[b].price,market[m].back[b].size);                 
                }
                catch(e)
                {

                }
               try{
                $scope.FinalArray[j].runners[inde].back[b].price = market[m].back[b].price;
               }
               catch(e)
               {
                if($scope.FinalArray[j].runners[inde].back[b]!=angular.isUndefinedOrNull)
                {
                    $scope.FinalArray[j].runners[inde].back[b].price="";
                }
               }
               try{
                $scope.FinalArray[j].runners[inde].back[b].size = market[m].back[b].size;
                 }
                catch(e)
                {
                    
                }
                
              
               }
               for(var b=0;b<$scope.FinalArray[j].runners[inde].lay.length;b++)
               {
                var count = b+1;  
		try{
                $scope.FinalArray[j].runners[inde].lay[b].selected =  $scope.CallColor($scope.FinalArray[j].runners[inde].lay[b].price,$scope.FinalArray[j].runners[inde].lay[b].size, market[m].lay[b].price,market[m].lay[b].size); 
		}
		catch(e)
                {
		}                
                try{
                    $scope.FinalArray[j].runners[inde].lay[b].price = market[m].lay[b].price;
                }
                catch(e)
                {
                        if($scope.FinalArray[j].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                    {
                        $scope.FinalArray[j].runners[inde].lay[b].price="";
                    }
                }

                   try{
                       $scope.FinalArray[j].runners[inde].lay[b].size = market[m].lay[b].size;
                   }
                   catch (e) {

                   }
               }
      

           }
	   else
		{
			$scope.FinalArray[j].IsMatchDisable=true;
		}
        }
    }

                   
}   

}
       $scope.MarketWinLoss = function(lstMarket)
	{
		
	  // $scope.WinLossTimeOut = $timeout(function(){
		//if($state.current.name=="dashboard.Matchodds"){
		$http({
			method:"POST",
			url:'Apicontroller/market_win_loss',
			data: {"matchId":$scope.MatchId,"MarketId":lstMarket},
			headers:{'Content-Type':'application/x-www-form-urlencoded'}
			}).success(function(data){
                		$scope.TRunnerValue = data.data;
				//$scope.MarketWinLoss(lstMarket);

			})
		//}
		//},3000);
	}
    $scope.MarketWinLossByMId = function(MarketId)
	{

		
		var obj = null;
		if($scope.TRunnerValue != angular.isUndefinedOrNull)
		{
			if($filter('filter')($scope.TRunnerValue, { marketId: MarketId })[0]!= angular.isUndefinedOrNull){
		  obj = $filter('filter')($scope.TRunnerValue, { marketId: MarketId })[0].runners;
			}
		  
		  
		}
		return obj;
	
	}


    $scope.getFancyList = function(marketId) {
        $scope.getFancyTimer=$timeout(function() {
            if($state.current.name=="dashboard.Matchodds")
            {
                $http({
                    method: 'GET',
                    url: 'Apicontroller/matchLstIndianSession/' + $scope.MatchId + '/' + marketId,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                    if (result.data != angular.isUndefinedOrNull && result.data.length != $scope.FancyData.length) {

                        $scope.FancyData = result.data;

                    }
                    else {
                        if(result.data!=angular.isUndefinedOrNull){
                            $scope.FancyDataTemp= result.data;
                            for(var i=0;i<$scope.FancyData.length;i++) {
                                var inde = $scope.FancyDataTemp.findIndex(x=>x.ID==$scope.FancyData[i].ID);
                                if (inde > -1) {
                                    /*      if($scope.FancyDataTemp[inde].fancy_position.length>0)
                                          {
                                              $scope.FancyData[i].fancy_position=$scope.FancyDataTemp[inde].fancy_position;
                                              $scope.FancyData[i].max_exposure=$scope.FancyDataTemp[inde].max_exposure;
                                          }*/
                                    $scope.FancyData[i].max_session_bet_liability=$scope.FancyDataTemp[inde].max_session_bet_liability;
                                    $scope.FancyData[i].max_session_liability=$scope.FancyDataTemp[inde].max_session_liability;
                                    $scope.FancyData[i].HeadName=$scope.FancyDataTemp[inde].HeadName;
                                    $scope.FancyData[i].MaxStake=$scope.FancyDataTemp[inde].MaxStake;
                                    $scope.FancyData[i].fancy_mode=$scope.FancyDataTemp[inde].fancy_mode;
                                    if($scope.FancyDataTemp[inde].fancy_mode=='M')
                                    {


                                        $scope.FancyData[i].SessInptNo=$scope.FancyDataTemp[inde].SessInptNo;
                                        $scope.FancyData[i].NoValume=$scope.FancyDataTemp[inde].NoValume;
                                        $scope.FancyData[i].SessInptYes=$scope.FancyDataTemp[inde].SessInptYes;
                                        $scope.FancyData[i].YesValume=$scope.FancyDataTemp[inde].YesValume;
                                        $scope.FancyData[i].isIndianShow = true;
                                        $scope.FancyData[i].pointDiff=$scope.FancyDataTemp[inde].pointDiff;
                                        $scope.FancyData[i].rateDiff=$scope.FancyDataTemp[inde].rateDiff;
                                        $scope.FancyData[i].fancyRange=$scope.FancyDataTemp[inde].fancyRange;
                                        $scope.FancyData[i].DisplayMsg=$scope.FancyDataTemp[inde].DisplayMsg;
                                        $scope.FancyData[i].active=$scope.FancyDataTemp[inde].active;
                                    }
                                }

                            }
                        }
                    }
                    $scope.getFancyList(marketId);
                }).error(function (err) {
                    $scope.loading = false;
                    $scope.getFancyList(marketId);
                });
            }

        },1000);
    }
            $scope.getLiveFancyList = function() {
                $timeout(function() {
               // socket.emit('CallLiveFancy', {auth:Bauthdata,MarketId:$scope.MarketId,UserId:$scope.UserId});
                   /*get_userser.getBothSessionFancy($stateParams.MatchId,function(response) {
                   $scope.loading=false;
                       $scope.FancyData = response.data;            
                   });*/
                   $.ajax({
                       url:'http://139.162.52.34/customer/v1/market.php?market_id='+$scope.MarketId,
                       type:'GET',
                       dataType:'JSON',
                       success:function(result){
                        $scope.loading=false;
                        //result = JSON.parse(result);
                        $scope.FancyLiveData = result.session;
                        var market = result.market[0].events;
                        if(market!=angular.isUndefinedOrNull)
                        {
                            for(var m=0;m<market.length;m++)
                            {
                             //   ;
                               var inde = $scope.FinalArray[0].runners.findIndex(img => img.id ===market[m].SelectionId);
                               if(inde>-1)
                               {
                                for(var b=0;b<$scope.FinalArray[0].runners[inde].back.length;b++)
                                {
                                 var count = b+1;  
                                 try{
                                     $scope.FinalArray[0].runners[inde].back[b].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[b].price,$scope.FinalArray[0].runners[inde].back[b].size, market[m]["BackPrice"+count],market[m]["BackSize"+count]);                 
                                 }
                                 catch(e)
                                 {

                                 }
                                try{
                                 $scope.FinalArray[0].runners[inde].back[b].price = market[m]["BackPrice"+count];
                                }
                                catch(e)
                                {
                                 if($scope.FinalArray[0].runners[inde].back[b]!=angular.isUndefinedOrNull)
                                 {
                                     $scope.FinalArray[0].runners[inde].back[b].price="";
                                 }
                                }
                                try{
                                 $scope.FinalArray[0].runners[inde].back[b].size = market[m]["BackSize"+count];
                                  }
                                 catch(e)
                                 {
                                     
                                 }
                                 
                               
                                }
                                for(var b=0;b<$scope.FinalArray[0].runners[inde].lay.length;b++)
                                {
                                 var count = b+1;  
                                 $scope.FinalArray[0].runners[inde].lay[b].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[b].price,$scope.FinalArray[0].runners[inde].lay[b].size, market[m]["LayPrice"+count],market[m]["LaySize"+count]);                 
                                 try{
                                     $scope.FinalArray[0].runners[inde].lay[b].price = market[m]["LayPrice"+count];
                                 }
                                 catch(e)
                                 {
                                         if($scope.FinalArray[0].runners[inde].lay[b]!=angular.isUndefinedOrNull)
                                     {
                                         $scope.FinalArray[0].runners[inde].lay[b].price="";
                                     }
                                 }
                                 
                                 $scope.FinalArray[0].runners[inde].lay[b].size = market[m]["LaySize"+count];
                                }
                                  /* try{
                                   $scope.FinalArray[0].runners[inde].back[0].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[0].price,$scope.FinalArray[0].runners[inde].back[0].size, market[m].BackPrice1,market[m].BackSize1);                 
                                   $scope.FinalArray[0].runners[inde].back[0].price = market[m].BackPrice1;
                                   $scope.FinalArray[0].runners[inde].back[1].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[1].price,$scope.FinalArray[0].runners[inde].back[1].size, market[m].BackPrice2,market[m].BackSize2);  
                                   $scope.FinalArray[0].runners[inde].back[1].price = market[m].BackPrice2;
                                   $scope.FinalArray[0].runners[inde].back[2].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].back[2].price,$scope.FinalArray[0].runners[inde].back[2].size, market[m].BackPrice3,market[m].BackSize3); 
                                   $scope.FinalArray[0].runners[inde].back[2].price = market[m].BackPrice3;
                                   $scope.FinalArray[0].runners[inde].lay[0].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[0].price,$scope.FinalArray[0].runners[inde].lay[0].size, market[m].LayPrice1,market[m].LaySize1); 
                                   $scope.FinalArray[0].runners[inde].lay[0].price = market[m].LayPrice1;
                                   $scope.FinalArray[0].runners[inde].lay[1].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[1].price,$scope.FinalArray[0].runners[inde].lay[1].size, market[m].LayPrice2,market[m].LaySize2); 
                                   $scope.FinalArray[0].runners[inde].lay[1].price = market[m].LayPrice2;
                                   $scope.FinalArray[0].runners[inde].lay[2].selected =  $scope.CallColor($scope.FinalArray[0].runners[inde].lay[2].price,$scope.FinalArray[0].runners[inde].lay[2].size, market[m].LayPrice3,market[m].LaySize3); 
                                   $scope.FinalArray[0].runners[inde].lay[2].price = market[m].LayPrice3;
               
                                   $scope.FinalArray[0].runners[inde].back[0].size = market[m].BackSize1;
                                   $scope.FinalArray[0].runners[inde].back[1].size = market[m].BackSize2;
                                   $scope.FinalArray[0].runners[inde].back[2].size = market[m].BackSize3;
                                   $scope.FinalArray[0].runners[inde].lay[0].size = market[m].LaySize1;
                                   $scope.FinalArray[0].runners[inde].lay[1].size = market[m].LaySize2;
                                   $scope.FinalArray[0].runners[inde].lay[2].size = market[m].LaySize3;
                               }
                               catch(e)
                               {
                                       if($scope.FinalArray[0].runners[inde].back[0]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[0].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].back[1]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[1].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].back[2]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].back[2].price="";
                                       }
               
                                       if($scope.FinalArray[0].runners[inde].lay[0]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[0].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].lay[1]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[1].price="";
                                       }
                                       if($scope.FinalArray[0].runners[inde].lay[2]!=angular.isUndefinedOrNull)
                                       {
                                           $scope.FinalArray[0].runners[inde].lay[2].price="";
                                       }
                               }*/
               
                               }
                            }
                        }
                           /*for(var i=0;i<$scope.FancyLiveData.length;i++)
                                   {
                                       if($scope.FancyLiveData!=null)
                                       {
                                           var inde = $scope.FancyData.findIndex(img => img.ind_fancy_selection_id === 											$scope.FancyLiveData[i].SelectionId);
                                           if(inde > -1)
                                           {
                                           var obj = $scope.FancyLiveData[i];
                                           $scope.FancyData[inde].SessInptNo=obj.LayPrice1;
                                           $scope.FancyData[inde].SessInptYes=obj.BackPrice1;
                                           $scope.FancyData[inde].DisplayMsg=obj.GameStatus;
                                             $scope.FancyData[inde].active=obj.GameStatus ==" " ? 1 : 4;
               
                                           }
               
                                       
                                       }
                                   }*/
                   for(var i=0;i<$scope.FancyData.length;i++)
                                   {
                                       
                                       if($scope.FancyData[i].is_indian_fancy==1 && $scope.FancyData[i].fancy_mode=="A")
                                       {
                                           var inde = $scope.FancyLiveData.findIndex(img => img.SelectionId === 											$scope.FancyData[i].ind_fancy_selection_id);
                                           if(inde > -1)
                                           {
                                           var obj = $scope.FancyLiveData[inde];
                                           if(obj!=angular.isUndefinedOrNull)
                                           {
                                           $scope.FancyData[i].SessInptNo=obj.LayPrice1;
                                           $scope.FancyData[i].SessInptYes=obj.BackPrice1;
                                           $scope.FancyData[i].DisplayMsg=obj.GameStatus;
                                             $scope.FancyData[i].active=obj.GameStatus =="" ? 1 : 4;
                                           }
               
                                           }
                                           else
                                           {
                                           $scope.FancyData[i].SessInptNo='';
                                           $scope.FancyData[i].SessInptYes='';
                                           $scope.FancyData[i].DisplayMsg='Result Awaiting';
                                               $scope.FancyData[i].active= 4;	
                                           }
               
                                       
                                       }
                                   }
                           
                      $scope.getLiveFancyList();
                       },
                       error:function(err){
                        $scope.loading = false;
                       }
                       
                   })
                   $scope.getNameFunc();
           },1000);
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
  $scope.getOddCalcVal = function(a, ovType,oddsLimit,volumeLimit) { //sourabh 161229
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
    $scope.getNameFunc();
  //  $scope.callOddsFunc();
    $scope.GetMarketListId();
    $scope.$on('$destroy', function () {
	$scope.ClearAllTimeOut();
   });
$scope.ClearAllTimeOut=function()
{
	    $timeout.cancel($scope.getMarketlstTimer);
	    $timeout.cancel($scope.ajaxTimer);
	    $timeout.cancel($scope.getFancyTimer);
	    $timeout.cancel($scope.GetScoreTimer);
	    $timeout.cancel($scope.UpdateAdminFancyTimer);
		$scope.GetScoreTimer=null;
}

  $scope.CalCulatePointDiff=function(indx,item)
    {
        if(!item.IsPointDiff)
        {
            $scope.team1layodd[indx]=$scope.team1backodd[indx]>0 ? parseFloat((parseFloat($scope.team1backodd[indx])+parseFloat(item.pointDiff)).toFixed(2)) : 0;

        }

       // tempval=$scope.team1backodd[indx];
    }

    $scope.callEnter=function(pointDiff,runnerlength,index,marketitem)
    {
        var isBetAllowedOnManualMatchOdds = marketitem.isBetAllowedOnManualMatchOdds == 0 ? 1 : 0;
       /* var isBetAllowedOnManualMatchOdds = marketitem.isBetAllowedOnManualMatchOdds == 0 ? 1 : 0;
        if(isBetAllowedOnManualMatchOdds==0)
        {
            $scope.team1backodd[0+marketitem.id]="";
            $scope.team1layodd[0+marketitem.id]="";
            $scope.team1backodd[1+marketitem.id]="";
            $scope.team1layodd[1+marketitem.id]="";
            $scope.team1backodd[2+marketitem.id]="";
            $scope.team1layodd[2+marketitem.id]="";
        }*/
        $scope.updateManualbetAllow(isBetAllowedOnManualMatchOdds,marketitem);
        $scope.UpdateManualOdds(pointDiff,runnerlength,index,marketitem);
    }
    $scope.RupeesChange="";
    $scope.UpdateManualOdds = function(pointDiff,runnerlength,index,marketitem)
    {

        if($scope.RupeesChange=="")
        {
            $scope.RupeesChange=marketitem.IsRs;

        }
        else if($scope.RupeesChange!=marketitem.IsRs) {
            $scope.RupeesChange=true;
        }
        else
        {
            $scope.RupeesChange=false;
        }
       if(runnerlength ==3 && false) {

            if (index == 0) {
                $scope.team1layodd[0] = (parseFloat($scope.team1backodd[0]) + pointDiff).toFixed(2);
                $scope.team1layodd[0]=parseFloat($scope.team1layodd[0]);



            } else if (index == 1) {

                $scope.team1layodd[1] = (parseFloat($scope.team1backodd[1]) + pointDiff).toFixed(2);
                $scope.team1layodd[1]=parseFloat($scope.team1layodd[1]);


            }
            else {

                $scope.team1layodd[2] = (parseFloat($scope.team1backodd[2]) + pointDiff).toFixed(2);
                $scope.team1layodd[2]=parseFloat($scope.team1layodd[2]);
            }
        }
        else if(runnerlength ==2 || runnerlength ==3)  {
            if(!marketitem.IsPointDiff){
                if (index == 0) {

                    $scope.team1layodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? (parseFloat($scope.team1backodd[0+marketitem.id]) + marketitem.pointDiff).toFixed(2) : "";
                    $scope.team1layodd[0+marketitem.id]=$scope.team1Suspend[0+marketitem.id]!=true ? parseFloat($scope.team1layodd[0+marketitem.id]) : "";
                    $scope.team1layodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";

                } else if (index == 1) {

                    $scope.team1layodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[1+marketitem.id] = $scope.team1Suspend[1+marketitem.id]!=true ? (parseFloat($scope.team1backodd[1+marketitem.id]) + marketitem.pointDiff).toFixed(2) : "";
                    $scope.team1layodd[1+marketitem.id] = $scope.team1Suspend[1+marketitem.id]!=true ? parseFloat($scope.team1layodd[1+marketitem.id]) : "";
                    $scope.team1layodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[2+marketitem.id] = $scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";


                }
                else {
                    $scope.team1layodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[2+marketitem.id] = $scope.team1Suspend[2+marketitem.id]!=true ? (parseFloat($scope.team1backodd[2+marketitem.id]) + marketitem.pointDiff).toFixed(2) : "";
                    $scope.team1layodd[2+marketitem.id]=$scope.team1Suspend[2+marketitem.id]!=true ? parseFloat($scope.team1layodd[2+marketitem.id]) : "";
                }
            }else {

                if (index == 0) {
                    $scope.team1layodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? $scope.team1backodd[0+marketitem.id]:"";
                    $scope.team1backodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? $scope.team1backodd[0+marketitem.id] : "";


                } else if (index == 1) {

                    $scope.team1layodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[2+marketitem.id] =$scope.team1Suspend[2+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[0+marketitem.id] = $scope.team1Suspend[0+marketitem.id]!=true ? $scope.team1backodd[1+marketitem.id] : "";
                    $scope.team1backodd[2+marketitem.id] = $scope.team1Suspend[2+marketitem.id]!=true ? $scope.team1backodd[1+marketitem.id] : "";


                }
                else {
                    $scope.team1layodd[0+marketitem.id] = $scope.team1Suspend[0+marketitem.id]!=true ? 0 : "";
                    $scope.team1layodd[1+marketitem.id] = $scope.team1Suspend[1+marketitem.id]!=true ? 0 : "";
                    $scope.team1backodd[0+marketitem.id] =$scope.team1Suspend[0+marketitem.id]!=true ? $scope.team1backodd[2+marketitem.id] : "";
                    $scope.team1backodd[1+marketitem.id] =$scope.team1Suspend[1+marketitem.id]!=true ? $scope.team1backodd[2+marketitem.id] : "";
                    $scope.team1layodd[2+marketitem.id] = $scope.team1Suspend[2]!=true ? 0 : "";
                }
            }

        }

        $scope.loading=true;
        var formData = {
            "market_id":marketitem.id,
            "team1_back":$scope.team1backodd[0+marketitem.id],
            "team1_lay":$scope.team1layodd[0+marketitem.id],
            "team2_back":$scope.team1backodd[1+marketitem.id],
            "team2_lay":$scope.team1layodd[1+marketitem.id],
            "draw_back":$scope.team1backodd[2+marketitem.id],
            "draw_lay":$scope.team1layodd[2+marketitem.id],
            "active_team1":$scope.team1Suspend[0+marketitem.id],
            "active_team2":$scope.team1Suspend[1+marketitem.id],
            "active_draw":$scope.team1Suspend[2+marketitem.id],
            "dlay_time":marketitem.pointDiff,
            "selection_1":marketitem.runners[0].selectionId,
            "selection_2":marketitem.runners[1].selectionId,
            "selection_3":marketitem.runners.length==3 ? marketitem.runners[2].selectionId : '',
            "IsChange":$scope.RupeesChange,
            "IsRs":marketitem.IsRs
        };

        var url = BASE_URL + "Lstsavemstrcontroller/saveManualMatchOdds";
        $http.post(url, formData).success(function (response) {

            if(!response.error){
                //Dialog.autohide(response.message);
                $scope.IsError=false;
                marketitem.ManualMsg=response.message;
                tempval="";
                $scope.isUpdateRunner=true;

            }
            else {
                $scope.IsError=true;
                marketitem.ManualMsg=response.message;
            }
            $timeout(function(){marketitem.ManualMsg=""},1500);
            $scope.loading=false;
        });
    }
    $scope.SetPointDiff=function(type,mid)
    {

        $scope.IsPointDiff=type;
        if($scope.IsPointDiff) //same
        {
            $scope.team1backodd[1+mid]=$scope.team1Suspend[1+mid]!=true ? $scope.team1backodd[0+mid] : "";
            $scope.team1backodd[2+mid]=$scope.team1Suspend[2+mid]!=true ? $scope.team1backodd[0+mid] :"";
            $scope.team1layodd[0+mid]= $scope.team1Suspend[0+mid]!=true ? 0 : "";
            $scope.team1layodd[1+mid]=$scope.team1Suspend[1+mid]!=true ? 0 : "";
            $scope.team1layodd[2+mid]=$scope.team1Suspend[2+mid]!=true ? 0 : "";
        }

    }
    $scope.SetPointAuto=function(type,mid)
    {

    }


    $scope.SetIndex=function(tindx,indx,pointDiff)
    {

        $scope.SelectedTextBox=indx;
        $scope.tempIndex=tindx;
    }
    $scope.PointDiffObj=null;
    $scope.SetPointFocus=function(item)
    {

        //$scope.PointDiffObj=item;
        //$scope.PointDiffObj.btnDiff=item.pointDiff;
    }
    $scope.InitializeNumberButton=function()
    {
        $scope.NumArray=[];
        for(var i=1;i<100;i++)
        {
            $scope.NumArray.push(i);
        }
    }
    $scope.InitializePointDiffButton=function()
    {
        $scope.PointArray=[25,50,75,100,125,150];

    }
    $scope.InitializePointDiffButton();
    $scope.InitializeNumberButton();
    $scope.toggle = function (marketitem) {
        marketitem.state = !marketitem.state;
    }

    $scope.IsPointDiff=false;
    $scope.BackSpace=function(item)
    {
        $scope.team1backodd[$scope.SelectedTextBox]=parseFloat($scope.team1backodd[$scope.SelectedTextBox].toString().slice(0,-1));

        $scope.CalCulatePointDiff($scope.SelectedTextBox,item);
    }
    $scope.PointDiffUpdate=function(btn,item,indx)
    {
        $scope.isActive=indx;
        //item.pointDiff=btn;
        item.btnDiff=btn;
        $scope.PointDiffObj=item;
    }
$scope.UpdateOdds=function(btnval,pointDiff,indx,runnerlength,marketitem)
{

    $scope.isActive=indx;

    if(tempval=="")
    {
        if($scope.team1backodd[$scope.SelectedTextBox]!=angular.isUndefinedOrNull && !isNaN($scope.team1backodd[$scope.SelectedTextBox]))
        {
            tempval=$scope.team1backodd[$scope.SelectedTextBox];
            tempval=btnval;
        }
        else
        {
            tempval=btnval.toString();
        }

    }
    else {
        if(tempval!=$scope.team1backodd[$scope.SelectedTextBox])
        {
            if($scope.team1backodd[$scope.SelectedTextBox]!=angular.isUndefinedOrNull && !isNaN($scope.team1backodd[$scope.SelectedTextBox]))
            {
                tempval=$scope.team1backodd[$scope.SelectedTextBox];
            }
            else {
                tempval="";
            }


        }
        //tempval+=btnval.toString();
        tempval=btnval;
    }
    if(tempval!="")
    {
        $scope.team1backodd[$scope.SelectedTextBox]=parseFloat(tempval);
        $scope.CalCulatePointDiff($scope.SelectedTextBox,marketitem);
        $scope.UpdateManualOdds(pointDiff,runnerlength,$scope.tempIndex,marketitem);
    }

    //$scope.UpdateManualOdds(pointDiff);
}
$scope.SuspendManual=function(indx,pointDiff,length,marketitem)
{
    var msg=$scope.team1Suspend[indx]==true ? 'Are you sure to suspend this?' :'Are you sure to unsuspend this?'
    var isTrue=confirm(msg);
    if(isTrue)
    {
        if($scope.team1Suspend[indx])
        {
            $scope.team1backodd[indx]="";
            $scope.team1layodd[indx]="";
        }
        $scope.UpdateManualOdds(pointDiff,length,indx,marketitem);



    }
    else{
       $scope.team1Suspend[indx]=!$scope.team1Suspend[indx];
    }
}
    $scope.updateManualbetAllow = function (value,item) {
        item.id=item.is_manual==1 ? item.marketid : item.id;
        $http.get('Lstsavemstrcontroller/updateBetAllowedOnManualMatch/' + item.id + '/' + value).success(function (data, status, headers, config) {
            if (value == 0)
              //  $scope.setMessage('Bet Open')
                item.isBetAllowedOnManualMatchOdds = value;
            $scope.ManualData = data.message;
            //  Dialog.autohide($scope.ManualData);

        });
    }
}]);
app.directive('crntusrpsn', function() { 
    return {
        templateUrl: 'app/scripts/directives/timeline/Matchodds_crntusr_psn.html',
        restrict: 'E',
        replace: true,
        scope: {},
        controller: ['$scope', '$http', '$stateParams', 'sessionService', '$interval','$timeout', function($scope, $http, $stateParams, sessionService, $interval,$timeout) {
	var callType=1;
	var timeDelay=0;
	var oldUId=0;var oldUType="";
            $scope.getUserPosition = function(userId, userType) {

if(userId!=oldUId || userType != oldUType){
	oldUType = userType;
	oldUId=userId;
	$scope.loading1=true;
	$timeout.cancel($scope.si_getCrntUserPosition);
    callType=1;

	}
$scope.si_getCrntUserPosition = $timeout(function(){
                $scope.crntusep_userId = userId;
                $scope.crntusep_userType = userType;
                if (userType != "3") {
                    $http.get(BASE_URL + 'Usercurrntposicntr/getUserPosition/' + userId + '/' + userType + '/' + $stateParams.MatchId + '/' + $stateParams.MarketId).success(function(data, status, headers, config) {
                         $scope.loading1=false;
                        $scope.totalTeamA = 0;
                        $scope.totalTeamB = 0;
                        $scope.totaltheDraw = 0;
                        $scope.userPosition = data.userPosition;
                        $scope.userOwnPosition = data.userOwnPosition;
                        if ($scope.userPosition != angular.isUndefinedOrNull) //sourabh 170107
                            for (var i = 0; i < $scope.userPosition.result_array.length; i++) {
                                if($scope.userPosition.result_array[i].TypeId==3 && false)
                                {
                                    $scope.totalTeamA = parseFloat($scope.totalTeamA) + parseFloat($scope.userPosition.result_array[i].TeamA);
                                    $scope.totalTeamB = parseFloat($scope.totalTeamB) + parseFloat($scope.userPosition.result_array[i].TeamB);
                                    $scope.totaltheDraw = parseFloat($scope.totaltheDraw) + parseFloat($scope.userPosition.result_array[i].theDraw);
                                    $scope.totalTeamA =  $scope.totalTeamA < 0 ?  $scope.totalTeamA * -1 : $scope.totalTeamA > 0 ? $scope.totalTeamA * -1 : $scope.totalTeamA;
                                    $scope.totalTeamB =  $scope.totalTeamB < 0 ?  $scope.totalTeamB * -1 : $scope.totalTeamB > 0 ?  $scope.totalTeamB * -1 : $scope.totalTeamB;
                                    $scope.totaltheDraw =  $scope.totaltheDraw < 0 ?  $scope.totaltheDraw * -1 : $scope.totaltheDraw > 0 ?  $scope.totaltheDraw * -1 : $scope.totaltheDraw;

                                    $scope.userPosition.result_array[i].TeamA=$scope.userPosition.result_array[i].TeamA < 0 ? $scope.userPosition.result_array[i].TeamA * -1 : $scope.userPosition.result_array[i].TeamA > 0 ? $scope.userPosition.result_array[i].TeamA * -1 : $scope.userPosition.result_array[i].TeamA;

                                    $scope.userPosition.result_array[i].TeamB=$scope.userPosition.result_array[i].TeamB < 0 ? $scope.userPosition.result_array[i].TeamB * -1 : $scope.userPosition.result_array[i].TeamB > 0 ? $scope.userPosition.result_array[i].TeamB * -1 : $scope.userPosition.result_array[i].TeamB;
                                    $scope.userPosition.result_array[i].theDraw=$scope.userPosition.result_array[i].theDraw < 0 ? $scope.userPosition.result_array[i].theDraw * -1 : $scope.userPosition.result_array[i].theDraw > 0 ? $scope.userPosition.result_array[i].theDraw * -1 : $scope.userPosition.result_array[i].theDraw ;

                                    $scope.userOwnPosition.result_array[0].TeamA=$scope.userOwnPosition.result_array[0].TeamA < 0 ? $scope.userOwnPosition.result_array[0].TeamA * -1 : $scope.userOwnPosition.result_array[0].TeamA > 0 ? $scope.userOwnPosition.result_array[0].TeamA * -1 : $scope.userOwnPosition.result_array[0].TeamA;

                                    $scope.userOwnPosition.result_array[0].TeamB=$scope.userOwnPosition.result_array[0].TeamB < 0 ? $scope.userOwnPosition.result_array[0].TeamB * -1 : $scope.userOwnPosition.result_array[0].TeamB > 0 ? $scope.userOwnPosition.result_array[0].TeamB * -1 : $scope.userOwnPosition.result_array[0].TeamB;
                                    $scope.userOwnPosition.result_array[0].theDraw=$scope.userOwnPosition.result_array[0].theDraw < 0 ? $scope.userOwnPosition.result_array[0].theDraw * -1 : $scope.userOwnPosition.result_array[0].theDraw > 0 ? $scope.userOwnPosition.result_array[0].theDraw * -1 : $scope.userOwnPosition.result_array[0].theDraw ;
                                }
                                else
                                {
                                    $scope.totalTeamA = parseFloat($scope.totalTeamA) + parseFloat($scope.userPosition.result_array[i].TeamA);
                                    $scope.totalTeamB = parseFloat($scope.totalTeamB) + parseFloat($scope.userPosition.result_array[i].TeamB);
                                    $scope.totaltheDraw = parseFloat($scope.totaltheDraw) + parseFloat($scope.userPosition.result_array[i].theDraw);
                                }

                            }
                        /*console.log($scope.totalTeamA);
                         //
                         alert($scope.totalTeamA);*/
$scope.getUserPosition(userId, userType);
callType=2;
                    });
                }
},callType==1 ? 0 : 5000);
            }


            $scope.getCrntUserPosition_Back = function() {
                $scope.crntusep_userId = sessionService.get('user_id');
                $scope.crntusep_userType = sessionService.get('type');
		$timeout.cancel($scope.si_getCrntUserPosition);
                $scope.getCrntUserPosition();
            }
            $scope.getUserPosition(sessionService.get('user_id'), sessionService.get('type'));
            $scope.getCrntUserPosition = function() {
                $scope.getUserPosition($scope.crntusep_userId, $scope.crntusep_userType);
            } //sourabh 170127
         //   $scope.si_getCrntUserPosition = $timeout($scope.getCrntUserPosition, 5000);
            $scope.$on("$destroy", function(event) {
                $timeout.cancel($scope.si_getCrntUserPosition);
                //clearInterval($scope.si_getCrntUserPosition);
            }); //sourabh 170124
        }]
    }



});

app.directive('ngEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if(event.which === 13) {
                scope.$apply(function(){
                    scope.$eval(attrs.ngEnter, {'event': event});
                });

                event.preventDefault();
            }
        });
    };
});

