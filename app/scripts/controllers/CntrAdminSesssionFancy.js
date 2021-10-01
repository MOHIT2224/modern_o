
app.controller('CntrAdminSesssionFancy', function($scope,$http, $timeout,$stateParams,$document){
	   $scope.ismeridian = false;
       /*$scope.NoLayRange=100;
       $scope.YesLayRange=100;*/
       $scope.RateDiff=1;
       $scope.PointDiff=10;
       $scope.MaxStake=9999999999999999;
     //{sourabh 170117
       shortcut.add("Enter", function (event) {
           if (!$scope.userForm.$invalid)
               $scope.SessionFancyForm(1);
           else
              $scope.userForm.$error.required[0].$name.focus();
       });
    //}sourabh 170117
	shortcut.add("Alt+S", function (){ 

        if ($scope.example==undefined || $scope.example_1==undefined) {
            alert("Plz Fill Required field...");
            
        }else{
          
            $scope.SessionFancyForm(1);      
            $timeout(callAtTimeout, 3000);
            function callAtTimeout() { $scope.$apply(function () { $scope.Message = ""; }); } 
        }
         
	});
	shortcut.add("Alt+R", function () {
	     document.getElementById('inputYes').value = '';
	     document.getElementById('inputNo').value = "";
	     document.getElementById('inputYes').focus();
	});
	 
	$scope.$on("$destroy", function (event) {
	     //$timeout.cancel(timer);
	     shortcut.remove("Alt+X");
	     shortcut.remove("Alt+R");
	     shortcut.remove("Alt+I");
		 shortcut.remove("Enter");//sourabh 170117
	});
    
  
    $scope.SessionFancyForm = function (formData) {
         /*start Session Fancy Save the Data*/
                    
             	 	var setFancyTime = document.getElementById('setFancyTime123').value;
			        //var inputYes = document.getElementById('inputYes').value;
			       // var inputNo = document.getElementById('inputNo').value;
			        var remarks = document.getElementById('remarks123').value;
			        var headName = document.getElementById('headName').value;
                    var gtFtime=""+$scope.mytime.getHours()+":"+$scope.mytime.getMinutes()+'';
                    /*var NoLayRange=$scope.NoLayRange;
                    var YesLayRange=$scope.YesLayRange;*/
                    var RateDiff=$scope.RateDiff;
                    var MaxStake=$scope.MaxStake;
                    var PointDiff=$scope.PointDiff;
			         
			        var formData = { HeadName: headName, remarks: remarks, mid: MatchId, fancyType: 2, date: $scope.mytime, time: gtFtime, inputYes: 0, inputNo: 0,sid:$stateParams.sportId,NoLayRange:100,YesLayRange:100,RateDiff:RateDiff,MaxStake:MaxStake,PointDiff:PointDiff }
			         
			        var url = BASE_URL + "Createmastercontroller/SaveFancy";
			        $http.post(url, formData).success(function (response) {
			            
			            $scope.message= response.message;
                        $timeout(callAtTimeout, 1000);
                        function callAtTimeout() {
                            $scope.$apply(function() { 
                                //alert("HI...");
                                $scope.message = "";
                               
                                $scope.fancyHeaderName="Session Fancy";
                                $scope.formData= {
                                    HeadName: "Session Fancy",
                                    remarks: "5% Commission Applied per winning bets, for eg. if you place 100chips and you win you will get 95chips otherwise lose 100chips. OddEven Means Score Last Digit, If it is ODD then ODD Win else EVEN, 0 is Even, 1 is Odd. Place bet and win. Market suspended before 4 over. Bets will be cancelled if there are bets after given suspend time. Good Luck.",
                                    dt:         $scope.NewCDate,
                                    mytime:     $scope.mytime,
                                };
                                
                                 $scope.PointDiff=10;
                                 $scope.MaxStake=10000;                                
                                 $scope.RateDiff=1;
                                document.getElementById('headName').focus();         
       
                            });
                            
                        }
			           
			        });
             /*End of session Fancy*/
           // document.getElementById('inputYes').focus();
    };
   
    $scope.toggleMode = function () { $scope.ismeridian = !$scope.ismeridian;  };

    $scope.update = function () { var d = new Date();d.setHours(14);d.setMinutes(0);$scope.mytime = d;};

    $scope.changed = function () { $log.log('Time changed to: ' + $scope.mytime); };

    $scope.clear = function () { $scope.mytime = null; };
    //end of Time Format

    /*for Date Calender*/
    $scope.open = function ($event) { $event.preventDefault();$event.stopPropagation(); $scope.opened = true; };

    $scope.dateOptions = { formatYear: 'yy', startingDay: 1  };

    $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[1];
    /*End of Date Calender*/

     var MatchId=$stateParams.matchId;
     var SportId = $stateParams.sportId;$scope.message="";

    
      var d = new Date(); var hours=d.getHours();var Minutes=d.getMinutes(); var Seconds=d.getMinutes(); var Time=hours+":"+Minutes+":"+Seconds;
      var date =d.getDate(); console.log(Time); $scope.mytime = d;$scope.dt=d.getFullYear()+"-"+d.getMonth()+"-"+d.getDate();$scope.NewCDate = new Date();
        
     $scope.fancyHeaderName="Session Fancy";
     console.log(MatchId);
     
     document.getElementById('headName').focus();


});
