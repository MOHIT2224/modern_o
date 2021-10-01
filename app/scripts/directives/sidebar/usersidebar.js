'use strict';
(function() {
app.directive('usersidebar', ['$location', '$timeout', function ($window, $http, sessionService, $timeout, get_userser,Matchoddscntr) {
    return {
        templateUrl: 'directives/userSidebar',
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
        controller: ['$scope', '$http', '$timeout', '$mdDialog', 'sessionService', '$rootScope', 'get_userser', 'Dialog','$state',function ($scope, $http, $timeout, $mdDialog, sessionService, $rootScope, get_userser, Dialog,$state) {
            $scope.chkMarketPP = false;
            $scope.chkMarketPPF = false;
	    $rootScope.selectedRow = '';



	  //  $("#main-menu").show();
            /*start the code of js file sidebar.js*/
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
				
				
				
                $scope.accordion = sportsId;
                $scope.sportsId = sportsId;
                $scope.accordionLv2 = 0;

                    $scope.GetSeriesData = angular.isUndefinedOrNull;
                    $http.get('Geteventcntr/getSeriesLst/' + sportsId).success(function (data, status, headers, config) {
                        $scope.GetSeriesData = data.seriesLst;
                        $rootScope.GetSeriesData = data.seriesLst.length;
					 
                    });
                
            }
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


$scope.GetSoprtName=function()
{
    if(sessionService.get('sportData') != angular.isUndefinedOrNull)
    {

        $scope.FsportData = JSON.parse(sessionService.get('sportData'));
    }
    $http.get('Geteventcntr/GetSportFrmDatabase').success(function (data, status, headers, config) {
        $scope.sprtData = data.sportData;
    }).error(function (data, status, header, config) {
        // $scope.ResponseDetails = "Data: " + data + "<br />status: " + status + "<br />headers: " + jsonFilter(header) + "<br />config: " + jsonFilter(config);
    });
}



            $scope.updateSportData = function(){
                if($scope.sprtData!=angular.isUndefinedOrNull)
                {
                    sessionService.set('sportData',JSON.stringify($scope.sprtData));
                    //alert('cart updated');
                    $scope.FsportData = JSON.parse(sessionService.get('sportData'));

                }

            };
            $scope.$watch('sprtData', $scope.updateSportData, true);
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
			$state.go('userDashboard.Matchodds',{'MatchId': series.MstCode,'matchName':series.matchName,'date':series.MstDate,'sportId':series.SportID})

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
        upBal = $timeout(function(){   $http.get('Chipscntrl/getChipDataById/' +  sessionService.get('user_id')).success(function (data, status, headers, config) {
            $scope.cipsData = data.betLibility;
if($scope.cipsData != angular.isUndefinedOrNull ){
            sessionService.set('FreeChips', $scope.cipsData[0].FreeChip);
            sessionService.set('ChipInOut', $scope.cipsData[0].Chip);
            sessionService.set('Liability', $scope.cipsData[0].Liability);
            sessionService.set('Balance', $scope.cipsData[0].Balance);
            sessionService.set('P_L', $scope.cipsData[0].P_L);
               sessionService.set('is_confirm_bet', $scope.cipsData[0].is_confirm_bet);
		$rootScope.ischeckconfirm=sessionService.get('is_confirm_bet');
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
   // $scope.updateBal();
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

