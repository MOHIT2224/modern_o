
<div style="padding-left:0px;">
<style>
  .temlhigh {
      color: "red"; border: "2px solid red";
      }
      .highlight {
        background: #1ed61e;
      }

    </style>
  <div class="msg-alerts alert alert-success" role="alert" ng-show="msgShowHide==true" ng-click="msg_show();"> <strong>{{alertMessage}}.</strong> </div>
  <div ng-init="treeAcc=0" class="main-nav-admin">
    <div class="tab_container">
 <input id="tab1" class="tab-inp" type="radio" name="tabs"  ng-model="treeAcc" ng-value="false" ng-checked="!treeAcc"></input>
      <label for="tab1" class="tab-label" ng-click="treeAcc=0" ng-hide="sessionusetype==3">
        <span>Markets </span>
      </label>
      <input id="tab2" class="tab-inp" type="radio" name="tabs" ng-model="treeAcc" ng-value="true" ng-checked="treeAcc"/>
      <label for="tab2" class="tab-label" ng-click="treeAcc=1" ng-hide="sessionusetype==3" ng-if="IsShowMenu('user')">
       <button type="button" class="user-ref-btn" ng-click="refresh_tree()">
          <i class="glyphicon glyphicon-refresh"></i>
        </button>
        <span>User </span>
      </label>
      <input type="hidden" name='hdnVal' id='hdnVal' value='P'/>
      <input type="hidden" name='hdnVal1' id='hdnVal1' value='P'/>
      
      <section id="content1" ng-show="treeAcc==0" class="tab-content clearfix">
        
       <ul id="mainId" class="sidebar-menu" ng-init="ShowHideAng(0)" ng-show="sessionusetype==0">
 	<!--<li ui-sref-active="active" ng-click="ShowHideAng(0)" class="active treeview"> <a  ui-sref="dashboard.AddSubAdmin"> <i class="fa fa-dashboard"></i><span>Sub Admin</span> </a> </li>-->
 	     <li ng-click="ShowHideAng(-1)" ng-show="IsShowMenu('subAdmin') || IsShowMenu('role')"><a href=""><i class="fa fa-cog" aria-hidden="true"></i> Manage Subadmin <i class="fa fa-angle-left pull-right"></i></a>
                       <ul class="treeview-menu">
                         <li ng-show="IsShowMenu('role')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.RoleSubAdmin">Manage Role</a> </li>
                        <li ng-show="IsShowMenu('subAdmin')"  ui-sref-active="treeview active" class="active treeview"> <a  ui-sref="dashboard.AddSubAdmin" onclick="myfun()"> <i class="fa fa-dashboard"></i><span>Sub Admin</span> </a> </li>

           	        </ul>
           	        </li>
           	         <li ui-sref-active="active" ng-show="IsShowMenu('MasterList')" class="active treeview"> <a onclick="myfun()" ui-sref="dashboard.MasterList"> <i class="fa fa-list"></i><span>Master List</span> </a> </li>
 	<li ui-sref-active="active" ng-show="IsShowMenu('MarketWatch')" ng-click="ShowHideAng(0)" class="active treeview"> <a onclick="myfun()" ui-sref="{{getDashboardurl()}}"> <i class="fa fa-dashboard"></i><span>Market Watch</span> </a> </li>

<li ng-show="IsShowMenu('fancy')"><a onclick="myfun()" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.ManageFancy"><i class="fa fa-file" aria-hidden="true"></i>Manage Fancy</a> </li>

<li ng-show="IsShowMenu('onePageReportSetting')"><a onclick="myfun()" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.OnePageRprt"><i class="fa fa-file" aria-hidden="true"></i>  One Page Report</a> </li>
<li ng-show="IsShowMenu('scheduleMatch')"><a onclick="myfun()" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.Matches"><i class="fa fa-file" aria-hidden="true"></i>  Schedule Match</a> </li>
<li ng-show="false"><a onclick="myfun()" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.CollectionReport"><i class="fa fa-file" aria-hidden="true"></i>  Collection Report</a> </li>
<li ng-show="IsShowMenu('seriesMatch')"><a onclick="myfun()" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.SeriesMatches"><i class="fa fa-list" aria-hidden="true"></i>Manage Series/Matches</a> </li>
            <li ng-click="ShowHideAng(-1)"><a href=""><i class="fa fa-cog" aria-hidden="true"></i> Application Settings <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
			
             <!--  <li  ng-show="IsShowMenu('fancy')"><a onclick="myfun()" href="" ui-sref="dashboard.ApkVersion"><i class="fa fa-cog" aria-hidden="true"></i>Apk Setting</a> </li> -->
              <li  ng-show="IsShowMenu('term')"><a onclick="myfun()" href="" ui-sref="dashboard.TermCondition"><i class="fa fa-cog" aria-hidden="true"></i>Term & Condition</a> </li>
              <li ng-show="IsShowMenu('settlementEntryList')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.DelChipLst">Settlement Entry List</a> </li>
              <li ng-show="IsShowMenu('sportSetting')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.sportSetting">Sports Setting</a> </li>
	        <li ng-show="IsShowMenu('settledMatches')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.SettlementBet">Settled Matches</a> </li>
		 <li ng-show="IsShowMenu('trashBets')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.TrashBet">Trash Bets</a> </li>
              <li ng-show="IsShowMenu('seriesSetting')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.SeriesActDact">Series Setting</a> </li>
              <li ng-show="IsShowMenu('matchSetting')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.Createfancy">Match Setting</a> </li>
              <li ng-show="IsShowMenu('marketSetting')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.ListOfMarket">Market Setting</a> </li>

            <!--  <li ui-sref-active="treeview active"> <a ui-sref="dashboard.Listmarkettype">Market Type</a> </li> -->
              <li ui-sref-active="treeview active" ng-show="($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1) && IsShowMenu('setMatchResult')" > <a onclick="myfun()" ui-sref="dashboard.match_result">Set Match Result</a> </li>
           <!--   <li ui-sref-active="active"> <a ui-sref="dashboard.userRightsCntr">User permissions</a> </li> -->
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('settings')"> <a onclick="myfun()" ui-sref="dashboard.SetAdminLimitCntr">Settings</a> </li>
          <!--    <li ui-sref-active="treeview active"> <a ui-sref="dashboard.CrtSubAdminCntr">Create Sub Admin</a> </li>  -->
              <li ng-show="IsShowMenu('closedUsersAccount')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.closeUserListCntr">Closed Users Account</a> </li>
              <li ng-show="IsShowMenu('RemoveOldGameAndUser')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.Delete_old_data">Remove Old Game + Users Data</a> </li>
              <li ng-show="IsShowMenu('RemoveOldBetData')" ui-sref-active="treeview active"> <a onclick="myfun()" ui-sref="dashboard.Delete_old_bet_data">Remove Old Bet Data</a> </li>
            </ul>
          </li>
        
 <li ng-if="false" ui-sref-active="active" class="ul main-spr {{getCssVal(displyData.id)}}" ng-repeat="displyData in sprtData">
<a ng-click="ShowHideAng(displyData.id,0);"   ui-sref="dashboard.Getseriesfrmapi({sportId: displyData.id})" ng-if="displyData.id != 7"><img src="app/images/sportsicon/{{displyData.name}}.png">&emsp;
            {{displyData.name}}  <i class="fa fa-angle-left pull-right"></i></a>  
<!--<a ng-click="ShowHideAng(displyData.id,0)" ui-sref="dashboard.Getmatchfrmapi({seriesId: match.seriesId,sportId:displyData.id})" ng-if="displyData.id == 7"><img src="app/images/{{displyData.name}}.png">&emsp;
            {{displyData.name}} <i class="fa fa-angle-left pull-right"></i></a> -->
            <!--Series-->
            <ul  class="treeview-menu" ng-if="accordion==displyData.id && displyData.id != 7">
              <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="match in GetSeriesData" ng-show="accordionLv2==0 || accordionLv2==match.seriesId"> <a ng-click="getSeriesMatch(displyData.id,match.seriesId);" href="javascript:void();" ui-sref="dashboard.Getmatchfrmapi({seriesId: match.seriesId,sportId:displyData.id})"> {{match.Name}} </a> 
                <!--Match-->
                <ul class="treeview-menu" ng-show="accordionLv2==match.seriesId">
                  <li> <a href="javascript:void();">Inplay</a>
                    <ul class="accordion-content" ng-if="accordionLv2==match.seriesId">
                      <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in inPlay" ng-if="accordionLv1==0 || accordionLv1==series.MstCode"> <a  ng-init="dropdownInplay['field_'+$index]=false" href="" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> <span class="myMenu2" ng-click="printParent2($event,series,1,$index)" ng-show="displyData.id==4"> </span> 
                        <!--Fancy & market-->
                        <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href=""> </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a ng-if="displyData.id==4"  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate,sportId:series.SportID})" > {{market.Name}} </a> <a ng-if="displyData.id!=4"  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate,sportId:series.SportID})" > {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> 
                              <!-- <button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC1</button> --> 
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
     
            <ul class="treeview newtree" ng-show="InplayClick==$index && dropdownInplay['field_'+$index]">
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,1)">Odd Even Fancy</a>
              </li>
              <li>
                <a ui-sref="dashboard.CntrAdminSesssionFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Session Fancy</a>
              </li>
<li>
                <a ui-sref="dashboard.CntrAdminIndianFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Indian Fancy</a>
              </li>
<li>
                <a ui-sref="dashboard.CntrBetfairFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Betfair Market</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,3)">Khaddal Fancy</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,4)">Last Digit Fancy</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,5)">Up Down Fancy</a>
              </li>
            </ul>
                      </li>
                    </ul>
                  </li>
          
          
                  <li> <a href="javascript:void();">Upcoming</a>
                    <ul class="accordion-content" ng-show="accordionLv2==match.seriesId">
                      <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in upComing" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a ng-init="dropdownInplay['field_'+$index+'u']=false" href="" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> <span class="myMenu2" ng-click="printParent2($event,series,2,$index+'u')" ng-show="displyData.id==4"> </span> 
                        <!--Fancy & market-->
			
                        <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href=""  > </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate,sportId:series.SportID})" > {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                              <!--<button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC2</button>-->
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
     
            <ul class="treeview newtree" ng-show="InplayClick==$index+'u' && dropdownInplay['field_'+$index+'u']">
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,1)">Odd Even Fancy</a>
              </li>
              <li>
                <a ui-sref="dashboard.CntrAdminSesssionFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Session Fancy</a>
              </li>
<li>
                <a ui-sref="dashboard.CntrAdminIndianFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Indian Fancy</a>
              </li>
<li>
                <a ui-sref="dashboard.CntrBetfairFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Betfair Market</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,3)">Khaddal Fancy</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,4)">Last Digit Fancy</a>
              </li>
              <li ng-if="false">
                <a ng-click="showCreateFancy($event,5)">Up Down Fancy</a>
              </li>
            </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
            </ul>
               <!-- Horce Racing -->
        <ul class="treeview-menu" ng-if="accordion==displyData.id && displyData.id == 7">
          <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> 
            <!--Match--> 
            <span class="myMenu2" ng-click="printParent2($event,series);" ng-show="displyData.id==4"> </span> 
            <!--Fancy & market-->
            <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
              <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href=""  > 
                <!-- {{market.Name}} --> <!-- {{chkMarketPP}} --> 
                </a> 

                <!-- {{market.Name}} {{chkMarketPP}} --> 
                <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                
                <a  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate,sportId:series.SportID})" > {{market.Name}} </a> </li>
              <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                <!-- Start Fancy Type -->
                <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" > </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
              </li>
            </ul>
          </li>
        </ul>
          </li>
        </ul>
      </section>
      <section id="content2" class="tab-content clearfix" ng-show="treeAcc==1">
        <div class="col-sm-12 col-md-12">
          <div slim-scroll="">
          <input type="text" ng-if="true" placeholder="Search User" id="search12" name="usearch" ng-change="searchTree()" ng-model="txtSearch.params"/>
          <i class="fa fa-search" ng-click="searchTree()"></i>
          <h5 ng-show="treeNodes.length==0">No user found.</h5>
            <div data-angular-treeview="true" data-tree-id="tree01" data-tree-model="treeNodes" data-node-id="id" data-node-label="name" data-node-children="children" data-node-image="image" ng-click="printParent($event);" ></div>
          </div>
          <span class="dropdownLayout dropdown123"  style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;" >
          <ul>
                     <li> <a ng-click="setNodeToTable(node)" class="close-lft-clk"> <span class="cls-txt">Close</span> <span class="glyphicon glyphicon-remove cls-ico"></span> </a> </li>
                     <li> <a ng-click="setNodeToTable(node)"> Select {{node.name}}<span style="color:RED;">  ({{node.children.length?node.children.length:0}})</span> </a> </li>
                     <li ng-show="IsShowMenu('AddUser')"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.AddUser==1" href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2" ng-click="showAddSetting(node,currentScope1);">Add Acc.</a> </li>
                     <li  ng-show="IsShowMenu('ViewUser') || IsShowMenu('UpdateUser')" ng-hide="node.usetype == 0"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ng-click="showViewSetting(node,currentScope1);">View Acc.</a> </li>
          <!-- <li > <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.OnePageRprt({userId:node.id})">One Page Report</a> </li> -->
                     <li ng-show="IsShowMenu('ChangePwd')" > <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChangePwd==1" href="" ng-click="showChangePwd(node,currentScope1);">Change Pwd.</a> </li>
                     <hr ng-show="sessionuser_id!=node.id || node.usetype==0"/>
                     <li ng-show="sessionuser_id!=node.id || node.usetype==0"> <a href="" ng-click="showFreeChips(node,currentScope1);">A/c Chips In/Out</a> </li>
                     <!--  <li ng-show="sessionuser_id!=node.id || node.usetype==0">
                         <a href="" ng-click="showSettlement(node,currentScope1);">Settlement</a>
                       </li> -->
                     <hr ng-show="sessionuser_id!=node.id"/>
                     <li ng-show="(sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UserLock==1)) && IsShowMenu('UserLock')"> <a href="" ng-click="showLockUser1(node)" ng-if="node.mstrlock==0 && sessionusetype!=3?lckUse='Unlock User':lckUse='Lock User'" >{{lckUse}}</a> </li>
                     <li ng-show="(sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.BettingLock==1)) && IsShowMenu('BettingLock')"> <a href="" ng-click="showLockBetting(node)" ng-if="node.lgnusrlckbtng==0 && sessionusetype!=3?lckbting='Unlock Betting':lckbting='Lock Betting'">{{lckbting}}</a> </li>
                     <li ng-show="IsShowMenu('Close_Ac')"> <a ng-show="sessionuser_id!=node.id && $root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Close_Ac==1" href="" ng-click="showCloseAcc(node)" ng-if="node.lgnusrCloseAc==0 && sessionusetype!=3?acStatus='Open Acc.':acStatus='Close Acc.'">{{acStatus}}</a> </li>
                   </ul>
          </span> </div>
      </section>
      <div class="table-responsive">
<!--ng-show="tblNodeName || treeAcc==1" -->
        <table class="table chips-table" ng-show="treeAcc==1">
          <tr>
            <th style="font-weight:bold;">Name</th>
            <th style="font-weight:bold;color:#191919;">{{tblNodeName}}</th>
          </tr>
          <tr>
            <td>Chips</td>
            <td><span style="{{getValColor(ChipInOut)}}"> {{ChipInOut==null?0:ChipInOut}}</span></td>
          </tr>
          <tr>
            <td>A/C Chips</td>
            <td><span style="{{getValColor(FreeChips)}}">{{FreeChips==null?0:FreeChips}}</span></td>
          </tr>
          <tr>
            <td>Liability</td>
            <td><span style="{{getValColor(Liability)}}">{{Liability==null?0:Liability}}</span></td>
          </tr>
          <tr>
            <td>Wallet</td>
            <td><span style="{{getValColor(Balance)}}"> <b style="color:#eaab0c;">{{Balance | number}}</b> </span></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>


