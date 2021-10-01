<!doctype html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="app/assets/css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="app/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="app/assets/css/slick.css" rel="stylesheet" type="text/css"/>
<link href="app/assets/css/styles.css" rel="stylesheet" type="text/css"/>
<link href="app/assets/css/responsive.css" rel="stylesheet" type="text/css"/>

</head>


<body>
<div id="mainpage clearfix">
  <header>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header"> <a class="navbar-brand" href="#"><img src="{{$root.logo}}" alt="Logo"/></a> </div>
        <div class="mobile_login hidden-lg hidden-md hidden-sm">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Login</button>
        </div>
        <div class="login-box-body">
         <span class="login-error" ng-show="showGreeting ">{{message}}</span>
        <form class="navbar-form navbar-right" name="userForm" method="post" autocomplete>
          <div class="form-group has-feedback" ng-class="{ 'has-error' : userForm.username.$invalid && !userForm.username.$pristine }">
             <input type="text" name="username" id="username" class="form-control" ng-model="user.username1" required="required" placeholder="Username" autofocus/>     
         <center> <span style="color:red; text-align:left; font-weight:bold;" ng-show="userForm.username.$error.required && !userForm.username.$pristine"> Username is required.</span></center>
          </div>
          <div class="form-group has-feedback" ng-class="{ 'has-error' : userForm.password.$invalid && !userForm.password.$pristine }">
 <input type="password" name="password" class="form-control" ng-model="user.password1" placeholder="Password" required/>  
          <center><span style="color:red;text-align:left; font-weight:bold;"  ng-show="userForm.password.$error.required && !userForm.password.$pristine"> Password is required.</span></center>
           </div>
           <div class="form-group has-feedback">
                        <button type="submit" class="btn btn-default" ng-click="submitForm(user)" ng-disabled="userForm.$invalid">
          <span class=" glyphicon glyphicon-log-in"></span>&emsp;Login</button>
                </div>
         </form></div>
      </div>
    </nav>
  </header>
  <section class="nav_blk">
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
          <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="#">Games</a> </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="#"><i><img src="app/images/icon-1.png" alt="home"/></i> Home</a></li>
            <li><a href="#"><i><img src="app/images/icon-2.png" alt="All"/></i> All</a></li>
            <li ng-repeat="displyData in sprtData | orderBy : 'name'" ng-if="displyData.id != 7"	> <a class="accordion-toggle" ng-click="ShowHideAng(displyData.id,0)"  data-toggle="collapse" data-parent="#accordion" href="#{{displyData.id}}"><img src="app/images/sportsicon/{{displyData.name}}.png">&emsp; {{displyData.name}} </a></li>
            
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><i><img src="app/images/icon-6.png" alt="Soccer"/></i><strong>{{Currentdate | date:'d MMM, y h:mm:ss a'}}</strong></a></li>

          </ul>
        </div>
      </div>
    </nav>
  </section>
  <section class="middle_blk">
    <div class="container-fluid">
      <div class="middle_left">
        <div class="panel-group" id="accordion">
          <div class="panel panel-default"   ng-repeat="displyData in sprtData | orderBy : 'name'" ng-if="displyData.id != 7">
            <div class="panel-heading" >
              <h4 class="panel-title"> <a class="accordion-toggle" ng-click="ShowHideAng(displyData.id,0)"  data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);#{{displyData.id}}"><img src="app/images/sportsicon/{{displyData.name}}.png">&emsp; {{displyData.name}} </a> </h4>
            </div>
            <div id="{{displyData.id}}" class="panel-collapse collapse">
              <div class="panel-body">
                <ul s>
                  <li ng-repeat="match in GetSeriesData" ng-show="accordionLv2==0 || accordionLv2==match.seriesId" ng-click="getSeriesMatch(displyData.id,match.seriesId)"><a ng-class="{'m_active':$index+'main' == selectedRow}" ng-click="setClickedRow($index+'main')" href="javascript:void(0);">{{match.Name}}</a>
   <ul class="treeview-menu" ng-show="accordionLv2==match.seriesId">
                  <li ng-if="accordionLv2==match.seriesId && (GetMatchData.length > 0)"	 </li>
                    <ul class="accordion-content" ng-if="accordionLv2==match.seriesId && (GetMatchData.length > 0)">
                      <li  class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in inPlay" ng-if="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="javascript:void(0)"   ng-class="{'m_active':$index+'sub'==selectedRow}" ng-click="getMatchMarket(displyData.id,series.MstCode);setClickedRow($index+'sub')"> {{series.matchName}}</a>
                        <!--Fancy & market-->
                        <ul class="accordion-content " ng-show="accordionLv13==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href=""> </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a ng-if="displyData.id==4" ng-class="{'m_active':$index+'market' == selectedRow}"  href="javascript:void(0)" class="match-od-link" ng-click="reset(series.matchName,series.MstDate,market.Id,series.MstCode);callOddsFunc(market.Id,series.MstCode);setClickedRow($index+'market');" > {{market.Name}} </a> <a ng-if="displyData.id!=4"  href="javascript:void(0)" class="match-od-link" ng-class="{'m_active':$index+'market' == selectedRow}"  ng-click="reset(series.matchName,series.MstDate,market.Id,series.MstCode);callOddsFunc(market.Id,series.MstCode);setClickedRow($index+'market');isMarketShow=true"> {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> 
                              <!-- <button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC1</button> --> 
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link">{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
               
         
                  <li ng-show="accordionLv2==match.seriesId && (GetMatchData.length > 0)">
                    <ul class="accordion-content" ng-show="accordionLv2==match.seriesId && (GetMatchData.length > 0)">
                      <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in upComing" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" > {{series.matchName}} </a> 
                        <!--Fancy & market-->
                        <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href="" ng-click="sdMarketPP(displyData.id,series.MstCode,market.Id,0,market.IsPlayIcon)" > </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a  href="" class="match-od-link"  > {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a>
                              <button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC2</button>
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" >{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
            </ul>
               <!-- Horce Racing -->
        <ul class="treeview-menu" ng-if="accordion==displyData.id && displyData.id == 7">
          <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" ui-sref="dashboard.Getmarketlstapi({MatchId: series.MstCode,sportId:displyData.id})" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> 
            <!--Match--> 
          
            <!--Fancy & market-->
            <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
              <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPP]" ng-click="sdMarketPP(displyData.id,series.MstCode,market.Id,0,market.IsPlayIcon)" > 
                <!-- {{market.Name}} --> <!-- {{chkMarketPP}} --> 
                </a> 

                <!-- {{market.Name}} {{chkMarketPP}} --> 
                <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                
                <a  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})" > {{market.Name}} </a> </li>
              <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                <!-- Start Fancy Type -->
                <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href=""  ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
              </li>
            </ul>
          </li>
        </ul>
          </li>
        </ul>
        
</li>
         
              </div>
            </div>
          </div>
          
         
        </div>
      </div>
      <div class="middle_content">
        <div class="home_bannerarea">
          <div class="slider banner_slider">
            <div> <img src="app/images/slider.jpg" alt="slider"/>
              <div class="banner_textarea">
                <h1 class="hidden-sm hidden-xs">Live Sports Betting with the latest<br>
                  odds on every match</h1>
                <h1 class="hidden-lg hidden-md">Live Sports Betting with the latest odds on every match</h1>
                <h2>Trump was elected in the Rust Belt of the United States</h2>
                <a href="#"><span>BET NOW</span></a> </div>
            </div>
            <div> <img src="app/images/slider.jpg" alt="slider"/>
              <div class="banner_textarea">
                <h1 class="hidden-sm hidden-xs">Live Sports Betting with the latest<br>
                  odds on every match</h1>
                <h1 class="hidden-lg hidden-md">Live Sports Betting with the latest odds on every match</h1>
                <h2>Trump was elected in the Rust Belt of the United States</h2>
                <a href="#"><span>BET NOW</span></a> </div>
            </div>
            <div> <img src="app/images/slider.jpg" alt="slider"/>
              <div class="banner_textarea">
                <h1 class="hidden-sm hidden-xs">Live Sports Betting with the latest<br>
                  odds on every match</h1>
                <h1 class="hidden-lg hidden-md">Live Sports Betting with the latest odds on every match</h1>
                <h2>Trump was elected in the Rust Belt of the United States</h2>
                <a href="#"><span>BET NOW</span></a> </div>
            </div>
            <div> <img src="app/images/slider.jpg" alt="slider"/>
              <div class="banner_textarea">
                <h1 class="hidden-sm hidden-xs">Live Sports Betting with the latest<br>
                  odds on every match</h1>
                <h1 class="hidden-lg hidden-md">Live Sports Betting with the latest odds on every match</h1>
                <h2>Trump was elected in the Rust Belt of the United States</h2>
                <a href="#"><span>BET NOW</span></a> </div>
            </div>
          </div>
        </div>
<div>

   <div class="row match-heading" ng-if="isMarketShow" ng-show="GetMarketBackLayData.status=='OPEN'">
            <div class="col-md-8 col-xs-12"> <h4>{{matchName}}</h4></div>
        <div class="col-md-4 col-xs-12"><p>{{dateForm|date:'EEEE dd MMM, hh:mm a'}}</p></div>
        </div>
            <div class="fancy_wrapper market-cont box-cont" ng-if="isMarketShow" style="border-top: 2px solid #1a7051;" ng-show="GetMarketBackLayData.status=='OPEN'">
                

               <div class="matchodd-header">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="matchhead">{{GetMarketInfo.MarketName}} -</div>
                         <div class="matchinfo">Matched : <b>GLC {{GetMarketBackLayData.totalMatched|number:0}}</b></div>
                    </div>
                    <div class="col-sm-4 text-right">
                        <span class="goin-play"><span class="glyphicon glyphicon-ok"></span>{{GetMarketBackLayData.status=='OPEN'?GetMarketBackLayData.inplay?"In-Play":"Going In-Play":GetMarketBackLayData.status}}</span>
                        <button class="btn btn-warning btn-sm" ng-click="callOddsFunc()">Refresh</button>
                    </div>
                </div>
            </div>
                 <div class="market-wrapper">
                    <div class="matchodd_dis" ng-if="PLAYPAUSE==1"></div>
                    <div class="table-overflow">
                        <table class="table market-listing-table" ng-show="GetMarketBackLayData.status=='OPEN'">
                            <thead>
                                    <tr>
                                    <th class="title"><i class="fa fa-star-o"></i> Match Odds</th>
                                    <th class="back hidden-xs" colspan="2"></th>
                                    <th class="back">Back</th>
                                    <th class="lay">Lay</th>
                                    <th class="lay hidden-xs" colspan="2"></th>
                                 </tr>
                            </thead>
                            <tbody>
                                 <tr ng-repeat="marketInfo in GetMarketBackLayData.runners">
                                     <td class="running-cell">
                                         <strong class="odds">{{(GetMarketBackLayDataSelectionName|filter:{"selectionId":marketInfo.selectionId})[0].runnerName}}
                                        <div ng-hide="true">

                                        {{testValue1=getSumValPnL((RunnerValue|filter: {"SelectionId":marketInfo.selectionId})[0].winValue,(RunnerValue|filter:{"SelectionId":marketInfo.selectionId})[0].lossValue)+(marketInfo.selectionId==selectionId?(formStatus==0?+((priceVal*stake)-stake):-(priceVal*stake)+stake):(formStatus==0?-stake:+stake))}} 

                                        </div>
                                        <input type="hidden" id="NewLiability_{{$index}}" name="NewLiability_" value="{{testValue1}}">

                                         </strong>
                                         <div class="size" style='{{getValColor((getSumValPnL((RunnerValue|filter: {"SelectionId":marketInfo.selectionId})[0].winValue,(RunnerValue|filter:{"SelectionId":marketInfo.selectionId})[0].lossValue)+(marketInfo.selectionId==selectionId?(formStatus==0?+((priceVal*stake)-stake):-(priceVal*stake)+stake):(formStatus==0?-stake:+stake))))}}'>
                                            <span> {{(getSumValPnL((RunnerValue|filter:{"SelectionId":marketInfo.selectionId})[0].winValue,(RunnerValue|filter:{"SelectionId":marketInfo.selectionId})[0].lossValue)+(marketInfo.selectionId==selectionId?(formStatus==0?+((priceVal*stake)-stake):-(priceVal*stake)+stake):(formStatus==0?-stake:+stake)))|number:2}}</span>
                                         </div> 
                                    </td> 
                                    <td class="back unhighlighted hidden-xs" ng-class="{'callYlCss':marketInfo.ex.availableToBack[2].SELECTED}">
                                         <strong class="odds">{{getOddCalcVal(marketInfo.ex.availableToBack[2].price,1)}}</strong>
                                         <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToBack[2].size,2)}}</span>
                                        </div>
                                     </td> 
                                    <td class="back unhighlighted hidden-xs" ng-class="{'callYlCss':marketInfo.ex.availableToBack[1].SELECTED}"> 
                                         <strong class="odds">{{getOddCalcVal(marketInfo.ex.availableToBack[1].price,1)}}</strong> 
                                        <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToBack[1].size,2)}}</span>
                                            </div>
                                     </td>
                                     <td class="back" ng-class="{'callYlCss':marketInfo.ex.availableToBack[0].SELECTED}" ng-click="getOddValue($event,marketInfo.ex.availableToBack[0].price+oddsLimit,marketInfo.MstCode,0,GetMarketBackLayDataSelectionName[$index].runnerName,marketInfo.selectionId)">
                                          <strong class="odds">{{getOddCalcVal(marketInfo.ex.availableToBack[0].price,1)}}</strong>
                                         <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToBack[0].size,2)}}</span>
                                            </div>
                                    </td>
                                    <td class="lay" ng-class="{'callCYanCss':marketInfo.ex.availableToLay[0].SELECTED}" ng-click="getOddValue($event,marketInfo.ex.availableToLay[0].price+oddsLimit,marketInfo.MstCode,1,GetMarketBackLayDataSelectionName[$index].runnerName,marketInfo.selectionId)">

                                        <strong class="odds" ng-model="testModel">{{getOddCalcVal(marketInfo.ex.availableToLay[0].price,1)}}</strong>
                                        <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToLay[0].size,2)}}</span>
                                        </div>
                                     </td> 
                                    <td class="lay unhighlighted hidden-xs" ng-class="{'callCYanCss':marketInfo.ex.availableToLay[1].SELECTED}" >
                                        <strong class="odds" ng-model="testModel">{{getOddCalcVal(marketInfo.ex.availableToLay[1].price,1)}}</strong>
                                        <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToLay[1].size,2)}}</span>
                                         </div>
                                    </td>
                                    <td class="lay unhighlighted hidden-xs" ng-class="{'callCYanCss':marketInfo.ex.availableToLay[2].SELECTED}" >

                                        <strong class="odds" ng-model="testModel">{{getOddCalcVal(marketInfo.ex.availableToLay[2].price,1)}}</strong>
                                        <div class="size">
                                            <span>{{getOddCalcVal(marketInfo.ex.availableToLay[2].size,2)}}</span>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
</div>
</div>
<div>
<!-- start code for Fancy -->
    
    <div ng-repeat="FancyObject in FancyData" ng-show="GetMarketBackLayData.status=='OPEN'">

            <div ng-hide="FancyObject.active==2 || FancyData.length==0">
                
                <div class="fancy">
                    <div class="row sport-high fancyListDiv" style="margin-bottom:1px;">
                        <div class="ses-fan-box clearfix">
                        <div class="col-sm-5">
                            <div class="fancy-heading fancy-head" ng-click="$parent.accBet(3);showSessionFancy(FancyObject.TypeID,FancyObject.ID);" ng-show="FancyData.length>0">
                                {{FancyObject.HeadName}} <!-- || {{FancyObject.active}} -->
                                <span class="rigt-ref">
                                    <i class="glyphicon " class="glyphicon-minus"></i>
                                </span>
                            </div>

                            <div style="display:block; vertical-align:top; margin-right:0; border:solid 2px #666; margin-bottom:0;min-height:40px;">
                                        <div style="display:inline-block;min-height: 40px;width: 25%;vertical-align:middle;">
                                            <div class="head-ses-fan" style=" line-height: 20px;margin-right:3px; margin-left:5px; text-align:center; margin-top:0;min-height:40px;"><span style="color:#FCA4B7; font-size:15px;">Lay</span><br />[NO]</div>
                                        </div>
                                        <div style="display:inline-block;min-height: 40px;width: 25%;vertical-align:middle;">
                                            <button style="min-height:20px;" class="lay-cell cell-btn" ng-disabled="FancyObject.active==0"  ng-click="display_Yesfancy(FancyObject.SessInptNo, FancyObject.ID,$index);">{{FancyObject.SessInptNo}}</button>
                                            <button style="min-height:20px;" disabled class="disab-btn">{{FancyObject.NoValume}} </button>
                                        </div>
                                        <div style="display:inline-block;width: 24%;min-height: 40px;vertical-align:middle;">
                                            <div class="head-ses-fan" style="     line-height: 20px;margin-right:3px; margin-left:3px; text-align:center; margin-top:0px;"><span style="color:#7CC4F7; font-size:15px;">Back</span><br />[Yes]</div>
                                        </div>
                                        <div style="display:inline-block;width: 20.4%;vertical-align:middle;min-height: 40px;float: right;">
                                            <button style="min-height:20px;" class="back-cell cell-btn" ng-disabled="FancyObject.active==0 || SessionbtnPlaceDis" ng-click="display_Nofancy(FancyObject.SessInptYes, FancyObject.ID,$index);">{{FancyObject.SessInptYes}}</button>
                                            <button style="min-height:20px;" disabled class="disab-btn">{{FancyObject.YesValume}}</button>
                                        </div>
                                    </div>
                        </div>
                         <div ng-if="FancyObject.active==0" class="ball-msg-box">
                                        <h1>Ball Started</h1>
                                    </div>
                                    <div ng-if="FancyObject.active==4 || FancyObject.active==2" class="ball-msg-box">
                                        <h1>{{FancyObject.DisplayMsg}}</h1>
                                    </div>
                    </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- end the code of Fancy -->
</div>
        <div class="sport_blk">
          <h2>Sport Highlights</h2>
          <ul class="nav nav-tabs">
	  <!--  <li ng-click='SportTab=displyData.id' ng-repeat="displyData in sprtData | orderBy : 'name'" ng-if="displyData.id != 7" ng-class="{'active':'tab'+displyData.id==SportTabSelected}"><a data-toggle="tab" href="#home{{displyData.id}}">{{displyData.name}}</a></li> -->
            <li class="active" ng-click='SportTab=4'><a data-toggle="tab" href="#home">Cricket</a></li>
            <li><a data-toggle="tab"  ng-click='SportTab=1' href="#menu1">Soccer</a></li>
            <li><a data-toggle="tab"  ng-click='SportTab=2' href="#menu2">Tennis</a></li>
          </ul>
          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
              <div class="bet_table table-responsive  table-bordered">
                <table class="table">
                  <tr class="blank_blk hidden-xs" ng-if="(sportDetail | filter:{ SportId: 4 }).length>0" ng-show="isShow4">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>1</td>
                    <td>X</td>
                    <td>2</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="blank_blk hidden-lg hidden-md hidden-sm">
                    <td class="team_name">&nbsp;</td>
                    <td class="team_name"><span>Back</span> <span>Lay</span></td>
                  </tr>
                  <tr ng-repeat="match in sportDetail | filter:{ SportId: 4 }" ng-show='isShow4=od.status=="OPEN"'>
                    <td>{{match.MstDate | date : 'EEE'}} <span>{{match.MstDate | date : 'HH:mm'}}</span></td>
                    <td class="team_name"><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}}<span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span></td>
                    <td ng-init="checkStaus(4,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'><a  class="td_btn blue_btn"  data-toggle="collapse" data-target="#demo">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToBack[0].size | currency}}</span> </a> <a  class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td class="odds_blk" ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'><a  class="td_btn blue_btn"  href="">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}<span>{{od.runners[2].ex.availableToBack[0].size | currency}}</span> </a> <a  class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[2].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'><a class="td_btn blue_btn"  href="">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[1].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn"  href="">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}<span>{{od.runners[1].ex.availableToLay[0].size | currency}}</span> </a></td>
                  
                  </tr>
                  <tr class="collapse" id="demo" ng-if="false">
                    <td>BACK <span>(Bet for)</span></td>
                    <td class="team_name">Pakistan</td>
                    <td><button class="cancel_btn" type="button">Cancel</button></td>
                    <td><input type="text" class="form-control" placeholder="1.25"></td>
                    <td><input type="text" class="form-control" placeholder="Stack"></td>
                    <td><button class="yello_btn" type="submit">Place Bet</button></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
	<div class="nofound" ng-show="!isShow4">
			No record found
		</div>
              </div>
            </div>
            <div id="menu1" class="tab-pane fade table-bordered table-responsive">
 <div class="bet_table  table-bordered">
              <table class="table ">
                  <tr class="blank_blk hidden-xs" ng-if="(sportDetail | filter:{ SportId: 1 }).length>0" ng-show="isShow1">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>1</td>
                    <td>X</td>
                    <td>2</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="blank_blk hidden-lg hidden-md hidden-sm">
                    <td class="team_name">&nbsp;</td>
                    <td class="team_name"><span>Back</span> <span>Lay</span></td>
                  </tr>
                  <tr ng-repeat="match in sportDetail | filter:{ SportId: 1 }" ng-show='isShow1=od.status=="OPEN"'>
                    <td>{{match.MstDate | date : 'EEE'}} <span>{{match.MstDate | date : 'HH:mm'}}</span></td>
                    <td class="team_name"><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}}<span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span></td>
                    <td ng-init="checkStaus(1,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'><a class="td_btn blue_btn"  data-toggle="collapse" data-target="#demo">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td class="odds_blk" ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'><a class="td_btn blue_btn" href="">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}<span>{{od.runners[2].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[2].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'><a class="td_btn blue_btn" href="">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[1].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}<span>{{od.runners[1].ex.availableToLay[0].size | currency}}</span> </a></td>
                  
                  </tr>
                  <tr class="collapse" id="demo" ng-if="false">
                    <td>BACK <span>(Bet for)</span></td>
                    <td class="team_name">Pakistan</td>
                    <td><button class="cancel_btn" type="button">Cancel</button></td>
                    <td><input type="text" class="form-control" placeholder="1.25"></td>
                    <td><input type="text" class="form-control" placeholder="Stack"></td>
                    <td><button class="yello_btn" type="submit">Place Bet</button></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
		<div class="nofound" ng-show="!isShow1">
			No record found
		</div>
</div>
            </div>
            <div id="menu2" class="tab-pane fade table-bordered table-responsive">
 <div class="bet_table  table-bordered">
                <table class="table">
                  <tr class="blank_blk hidden-xs"  ng-if="(sportDetail | filter:{ SportId: 2 }).length>0" ng-show="isShow2">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>1</td>
                    <td></td>
                    <td>2</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="blank_blk hidden-lg hidden-md hidden-sm">
                    <td class="team_name">&nbsp;</td>
                    <td class="team_name"><span>Back</span> <span>Lay</span></td>
                  </tr>
                  <tr ng-repeat="match in sportDetail | filter:{ SportId: 2 }" ng-show='isShow2=od.status=="OPEN"'>
                    <td>{{match.MstDate | date : 'EEE'}} <span>{{match.MstDate | date : 'HH:mm'}}</span></td>
                    <td class="team_name"><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}}<span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span></td>
                    <td ng-init="checkStaus(2,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'><a class="td_btn blue_btn"  data-toggle="collapse" data-target="#demo">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[0].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td class="odds_blk" ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'><a class="td_btn blue_btn" href="">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}<span>{{od.runners[2].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}} <span>{{od.runners[2].ex.availableToLay[0].size | currency}}</span> </a></td>
                    <td ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'><a class="td_btn blue_btn" href="">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}} <span>{{od.runners[1].ex.availableToBack[0].size | currency}}</span> </a> <a class="td_btn pink_btn" href="">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}<span>{{od.runners[1].ex.availableToLay[0].size | currency}}</span> </a></td>
          
                  </tr>
                  <tr class="collapse" id="demo" ng-if="false">
                    <td>BACK <span>(Bet for)</span></td>
                    <td class="team_name">Pakistan</td>
                    <td><button class="cancel_btn" type="button">Cancel</button></td>
                    <td><input type="text" class="form-control" placeholder="1.25"></td>
                    <td><input type="text" class="form-control" placeholder="Stack"></td>
                    <td><button class="yello_btn" type="submit">Place Bet</button></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
		<div class="nofound" ng-show="!isShow2">
			No record found
		</div>
</div>
            </div>
          </div>
        </div>
      </div>

      <div class="middle_right">
        <div class="google_ad">
          <center>
            <img src="app/images/ad.jpg" class="img-responsive" alt="Ad"/>
          </center>
        </div>
      </div>
	</div>
    </div>
  </section>
  <footer>
    <div class="footer_top_blk">
      <center>
        <img src="app/images/footer-logo.png" alt=""/>
      </center>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Help</a></li>
        <li><a href="#">Secutiry</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms &amp; Conditions</a></li>
      </ul>
    </div>
    <div class="footer_bottom_blk">Copyright 2018 &copy; Betdip All Rights Reserved</div>
  </footer>
  <div class="modal fade login" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Please Login to below</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body">
 <span class="login-error" ng-show="showGreeting ">{{message}}</span>
          <form class="navbar-form navbar-right" name="userForm" method="post" autocomplete>
            <div class="form-group" ng-class="{ 'has-error' : userForm.username.$invalid && !userForm.username.$pristine }">
                <input type="text" name="username" id="username" class="form-control" ng-model="user.username1" required="required" placeholder="Username" autofocus/>     
         <center> <span style="color:red; text-align:left; font-weight:bold;" ng-show="userForm.username.$error.required && !userForm.username.$pristine"> Username is required.</span></center>
            </div>
            <div class="form-group" ng-class="{ 'has-error' : userForm.password.$invalid && !userForm.password.$pristine }">
              <input type="password" name="password" class="form-control" ng-model="user.password1" placeholder="Password" required/>  
          <center><span style="color:red;text-align:left; font-weight:bold;"  ng-show="userForm.password.$error.required && !userForm.password.$pristine"> Password is required.</span></center>
            </div>
            <button type="submit" class="btn btn-default" ng-click="submitForm(user)" ng-disabled="userForm.$invalid">
          <span class=" glyphicon glyphicon-log-in"></span>&emsp;Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  
<script type="text/javascript" src="app/assets/js/slick.js"></script> 
<script type="text/javascript" src="app/assets/js/custom.js"></script>
</body>
</html>
