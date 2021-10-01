app.controller('SetAdminLimitCntr', function($scope,$http, Dialog){
    $scope.message="";
            $http.get( BASE_URL+'setting/index/').success(function (data, status, headers, config) { 

                $scope.adminLimit = data[0];
               $scope.setMessage = $scope.adminLimit.Marquee;
$scope.adminLimit1= $scope.adminLimit.adminLImit;
$scope.gngSetLimit= $scope.adminLimit.going_in_play_limit;
$scope.PointValue= $scope.adminLimit.match_detection_point;
$scope.betDelay= $scope.adminLimit.bet_delay;
                $scope.max_bet_liability=$scope.adminLimit.max_bet_liability;
                $scope.max_market_liability=$scope.adminLimit.max_market_liability;
                $scope.max_session_bet_liability=$scope.adminLimit.max_session_bet_liability;
                $scope.max_session_liability=$scope.adminLimit.max_session_liability;
                $scope.max_market_profit=$scope.adminLimit.max_market_profit
		//console.log( $scope.adminLimit1);
     
            });
       $scope.saveData=function(limit,id){
        
            $http.get( BASE_URL+'Betentrycntr/UpdateAdminLimit/'+id+'/'+limit).success(function (data, status, headers, config) {

                if(data.error==0)
                {
                    $scope.adminLimit = data.adminLimit;
                    //console.log($scope.adminLimit);
                    Dialog.autohide(data.message);
                    $scope.message=data.message;
                }
                else
                {
                    Dialog.autohide(data.message);
                }
            });
           
       }
       $scope.UpdateGngInPlayLimitLimit=function(limit){

            $http.get( BASE_URL+'Betentrycntr/UpdateGngInPlayLimitLimit/'+limit ).success(function (data, status, headers, config) { 
                   Dialog.autohide(data.message);

                  $scope.GngInPlayLimit = data.adminLimit;
                    //$scope.message=data.message;
            });
       }
       
      $scope.UpdatePoint=function(pointVal){

            $http.get( BASE_URL+'Betentrycntr/UpdatepointVal/'+pointVal ).success(function (data, status, headers, config) { 
                    Dialog.autohide(data.message);
                    $scope.GetPoint();
                    //$scope.message=data.message;
            });
       }

       $scope.UpdateBetDelay=function(betDelay){

            $http.get( BASE_URL+'Betentrycntr/UpdateBetDelay/'+betDelay ).success(function (data, status, headers, config) { 
                  Dialog.autohide(data.message);
                    $scope.GetPoint();
                    //$scope.message=data.message;
            });
       }
    $scope.UpdateLiability=function(values,type)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        if(type=="bet")
        {
            LinkUrl ="Apiadmincontroller/save_global_bet_liablity";
            dataArray={"max_bet_liability":values};
        }
        else
        {
            LinkUrl ="Apiadmincontroller/save_global_market_liablity";
            dataArray={"max_market_liability":values};
        }
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }

    $scope.UpdateSessionLiability=function(values,type)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        if(type=="bet")
        {
            LinkUrl ="Apiadmincontroller/save_global_session_bet_liablity";
            dataArray={"max_session_bet_liability":values};
        }
        else
        {
            LinkUrl ="Apiadmincontroller/save_global_session_liablity";
            dataArray={"max_session_liability":values};
        }
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
    $scope.UpdateProfit=function(values)
    {

        var LinkUrl = "";
        var dataArray={};
        $scope.loading=true;
        LinkUrl ="Apiadmincontroller/save_global_market_profit";
        dataArray={"max_market_profit":values};
        $http.post(BASE_URL+LinkUrl,dataArray).success(function(data, status, headers, config){
            Dialog.autohide(data.message);
            $scope.loading=false;
        }).error(function(data, status, headers, config){
            $scope.loading=false;
        });
    }
       $scope.setHeaderMsg=function(e){
        //
        var dataArray={setMessage:e};
        $http.post( BASE_URL+"Betentrycntr/setHeaderMsg/",dataArray).success(function(data, status, headers, config){
                  Dialog.autohide(data.message);
        });
      }
       $scope.GetPoint=function(){

            $http.get( BASE_URL+'Betentrycntr/PointValue/').success(function (data, status, headers, config) { 
            	
if(data.GetPoint.length > 1)
                   $scope.PointValue = parseInt(data.GetPoint[0].value);
                    console.log($scope.PointValue);
                    //$scope.message=data.message;
            });
       }
       $scope.GetPoint();
	$scope.GetBettingTime=function()
	{
		
		 $http.get(BASE_URL+'GeneralSettingsCntrl/getGeneralSettings/BET_START_TIME_BEFORE').success(function (data, status, headers, config) { 
			
			if(data.error==0)
			{
				$scope.BetTime=(parseFloat(data.data.key_value)/60).toFixed(1);
			}
			
		});
	}
	$scope.UpdateBettingTime=function()
	{
		
		$scope.loading=true;
		if($scope.BetTime!=angular.isUndefinedOrNull)
		{
			$scope.BetTime=(parseFloat($scope.BetTime) * 60).toFixed(1);
		}
		else
		{
			$scope.BetTime=0;
		}
		var obj = {key:'BET_START_TIME_BEFORE',value:$scope.BetTime}
		  $http.post(BASE_URL+'GeneralSettingsCntrl/updateGeneralSettings',obj).success(function (data, status, headers, config) { 
                    Dialog.autohide(data.message);
                    //$scope.GetBettingTime();
			$scope.loading=false;
			$scope.BetTime=(parseFloat($scope.BetTime) / 60).toFixed(1);
                    //$scope.message=data.message;
            });
	}
	 $scope.GetBettingTime();
});



 
