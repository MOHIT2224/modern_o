app.controller('makebetCntr',['$scope','$http', 'sessionService', '$timeout', '$filter','$stateParams','Dialog','Base64','deviceDetector','$location', function ($scope,$http, sessionService, $timeout,$filter,$stateParams,Dialog,Base64,deviceDetector,$location) {
    var authdata = Base64.encode(sessionService.get('user') + ':' +    sessionService.get('lgPassword'));
    var Bauthdata='Basic ' + authdata;
    $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata;
$scope.UserType=sessionService.get('type');
    if(sessionService.get('makeBatUrl')!='Y')
    {
        $location.path('/dashboard/Home');
    }
    $scope.GetDealer=function(MasterId,Type){
	$scope.selectedId=MasterId;

        if(MasterId != null ){
       $http.get('Betentrycntr/GetDealer/'+MasterId).success(function (data, status, headers, config) { 
          //  
 		 if (Type==0) {
                 $scope.MasterData = data.jsonData;
                 
            }
            else if (Type==1) {
                $scope.DealerData = data.jsonData;
             $scope.formData.DealerId='';
             $scope.formData.UserId="";
                 
            }else if(Type==2){
                 $scope.userData = data.jsonData;

            }     



        }); 
   }
   else{
       $scope.DealerData='';
        $scope.userData='';
   }
    }

$scope.GetDealer(sessionService.get('user_id'),$scope.UserType);

 $http.get("Geteventcntr/GetSportFrmDatabase").success(function(e,s,c,n){$scope.sportLst=e.sportData});
$scope.GetMatch=function(s){$http.get("Geteventcntr/getDeclareMatchResult/"+s.id).success(function(e,s,c,n){$scope.matchLst=e.matchLst})};

$scope.GetMarket=function(s,c,a){$scope.loading=true;var r={matchId:s?s:0,sportsId:c,user_id:sessionService.get("user_id")};$http({method:"POST",url:"Geteventcntr/matchMarketLst/",data:r,headers:{"Content-Type":"application/x-www-form-urlencoded"}}).success(function(e){"fancy"==a?$scope.getMatchFancy=e.getMatchFancy:$scope.MatchMarket=e.MatchMarket,$scope.loading=false;})}

    $scope.SaveApkVersion=function(value)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiadmincontroller/save_apk_version";
        dataArray={"version_code":value.Code,"version_name":value.name};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }

    $scope.UpdateApkStatus=function(value)
    {

        var apkstatus=value==true ? 1 : 0;
        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiadmincontroller/update_apk_status";
        dataArray={"status":apkstatus};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
            $scope.GetApkInfo();
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
    $scope.apk={};
    $scope.GetApkInfo=function()
    {
        var LinkUrl = "";

        $scope.loading=true;
        LinkUrl ="Apimobilecontroller/chkAppVersion";
        $http.get(BASE_URL+LinkUrl).success(function(data, status, headers, config){

            if(data.error==0)
            {
                $scope.apk.Code=data.version_code;
                $scope.apk.name=data.version_name;
                $scope.apk.status=data.status;
            }
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }

    $scope.Getodds=function(s,m) {
        $http.get(BASE_URL + "Geteventcntr/get_drpdwnSelectionName/" + s).success(function (e, s, c, n) {

            $scope.runner = e.runner;
            if($scope.runner!=angular.isUndefinedOrNull && $scope.runner.length>0)
            {
                $scope.runner=$scope.runner.filter(function(item) {
                    return item.selectionId != 0
                });
            }
        })
    }
    $scope.search={};
    $scope.SaveMakeBet=function()
    {
        if(sessionService.get('makeBatUrl')=='Y')
        {

        if (deviceDetector.device == 'unknown') {
            var DIVICE = 'Desktop';
        } else {
            var DIVICE = deviceDetector.device;
        }
        var deviceInformation = " browser: " + deviceDetector.browser + " browser_version :" + deviceDetector.browser_version + "  device: " + DIVICE + "  OS : " + deviceDetector.os + " os_version: " + deviceDetector.os_version;
        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiusercontroller/save_make_bet";
        dataArray={"user_id":$scope.formData.UserId,"selectionId":$scope.Selection.selectionId,"matchId":$scope.Match_id.MstCode,"isback":$scope.TypeName,"stake":$scope.search.Stake,"priceVal":$scope.search.Odds,"MarketId":$scope.market_id.Id,"placeName":$scope.Selection.selectionName,"MatchName":$scope.market_id.name,"deviceInfo":deviceInformation,"inplay":"","is_session_fancy":"N","SportId":$scope.Sport_id.id};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){

            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });

        }
    }

}])
