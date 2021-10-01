<div style="padding-left:0px;">
  <div class="msg-alerts alert alert-success" role="alert" ng-show="msgShowHide==true" ng-click="msg_show();"> <strong>{{alertMessage}}.</strong> </div>
  <div ng-init="treeAcc=0" class="main-nav-admin">
    <div class="tab_container">
 <input id="tab1" class="tab-inp" type="radio" name="tabs"  ng-model="treeAcc" ng-value="false" ng-checked="!treeAcc"></input>
      <label for="tab1" class="tab-label" ng-click="treeAcc=0" ng-hide="sessionusetype==3">
        <span>Markets </span>
      </label>
      <input id="tab2" class="tab-inp" type="radio" name="tabs" ng-model="treeAcc" ng-value="true" ng-checked="treeAcc"/>
      <label for="tab2" class="tab-label" ng-click="treeAcc=1" ng-hide="sessionusetype==3">
       <!-- <button type="button" class="user-ref-btn" ng-click="refresh_tree()">
          <i class="glyphicon glyphicon-retweet"></i>
        </button> -->
        <span>User</span>
      </label>
      <input type="hidden" name='hdnVal' id='hdnVal' value='P'/>
      <input type="hidden" name='hdnVal1' id='hdnVal1' value='P'/>
      
      <section id="content1" ng-show="treeAcc==0" class="tab-content clearfix">
        
       <ul class="sidebar-menu" ng-init="ShowHideAng(0)" ng-show="sessionusetype==0">
 	<li ui-sref-active="active" ng-click="ShowHideAng(0)" class="active treeview"> <a  ui-sref="{{getDashboardurl()}}"> <i class="fa fa-dashboard"></i><span>Market Watch</span> </a> </li>
            
            <li><a href=""><i class="fa fa-cog" aria-hidden="true"></i> Application Settings <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.DelChipLst">Settlement Entry List</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.SeriesActDact">Series Setting</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.ListOfMarket">Market Setting</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.Createfancy">Match Setting</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.Listmarkettype">Market Type</a> </li>
              <li ui-sref-active="treeview active" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1" > <a ui-sref="dashboard.match_result">Set Match Result</a> </li>
              <li ui-sref-active="active"> <a ui-sref="dashboard.userRightsCntr">User permissions</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.SetAdminLimitCntr">Settings</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.CrtSubAdminCntr">Create Sub Admin</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.closeUserListCntr">Closed Users Account</a> </li>
              <li ui-sref-active="treeview active"> <a ui-sref="dashboard.Delete_old_data">Remove Old Game Data</a> </li>
            </ul>
          </li>
          
        
          
 <li ui-sref-active="active" class="ul main-spr {{getCssVal(displyData.id)}}" ng-repeat="displyData in sprtData">
 <a ng-click="ShowHideAng(displyData.id,0)"   ui-sref="dashboard.Getseriesfrmapi({sportId: displyData.id})" ng-show="displyData.id != 7"><img src="app/images/sportsicon/{{displyData.name}}.png">&emsp;
            {{displyData.name}} </a> <a ng-click="ShowHideAng(displyData.id,0)" ui-sref="dashboard.Getmatchfrmapi({seriesId: match.seriesId,sportId:displyData.id})" ng-show="displyData.id == 7"><img src="app/images/{{displyData.name}}.png">&emsp;
            {{displyData.name}} </a> 
            <!--Series-->
            <ul class="treeview-menu" ng-show="accordion==displyData.id && displyData.id != 7">
              <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="match in GetSeriesData" ng-show="accordionLv2==0 || accordionLv2==match.seriesId"> <a href="" ui-sref="dashboard.Getmatchfrmapi({seriesId: match.seriesId,sportId:displyData.id})" ng-click="getSeriesMatch(displyData.id,match.seriesId)"> {{match.Name}} </a> 
                <!--Match-->
                <ul class="ul-sub" ng-show="accordionLv2==match.seriesId">
                  <li> <a href="#">Inplay</a>
                    <ul class="accordion-content" ng-show="accordionLv2==match.seriesId">
                      <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in inPlay" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" ui-sref="dashboard.Getmarketlstapi({MatchId: series.MstCode,sportId:displyData.id,seriesId: match.seriesId})" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> <span class="myMenu2" ng-click="printParent2($event,series);" ng-show="displyData.id==4"> </span> 
                        <!--Fancy & market-->
                        <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPP]" ng-click="sdMarketPP(displyData.id,series.MstCode,market.Id,0,market.IsPlayIcon)" > </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a ng-if="displyData.id==4"  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})" > {{market.Name}} </a> <a ng-if="displyData.id!=4"  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})" > {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> 
                              <!-- <button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC1</button> --> 
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li> <a href="#">Upcoming</a>
                    <ul class="accordion-content" ng-show="accordionLv2==match.seriesId">
                      <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in upComing" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" ui-sref="dashboard.Getmarketlstapi({MatchId: series.MstCode,sportId:displyData.id,seriesId: match.seriesId})" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> <span class="myMenu2" ng-click="printParent2($event,series);" ng-show="displyData.id==4"> </span> 
                        <!--Fancy & market-->
                        <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                          <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active"> <a ng-init="market.IsPlayIcon==1?chkMarketPP=false:chkMarketPP=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPP]" ng-click="sdMarketPP(displyData.id,series.MstCode,market.Id,0,market.IsPlayIcon)" > </a> 
                            <!--   css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir --> 
                            <a  href="" class="match-od-link" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})" > {{market.Name}} </a> </li>
                          <li class="market_type" ng-repeat="fancyArr in getMatchFancy"> 
                            <!-- Start Fancy Type 123-->
                            <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                              <button type="button" ng-click=displayScorePosition(fancyArr.ID,fancyArr.TypeID,series.MstCode)>SC2</button>
                            </div>
                            <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                            <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
            </ul>
          </li>
        </ul>
        
        <!-- Horce Racing -->
        <ul class="accordion-content main-ul-scroll" ng-show="accordion==displyData.id && displyData.id == 7">
          <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode"> <a href="" ui-sref="dashboard.Getmarketlstapi({MatchId: series.MstCode,sportId:displyData.id})" ng-click="getMatchMarket(displyData.id,series.MstCode)"> {{series.matchName}} </a> 
            <!--Match--> 
            <span class="myMenu2" ng-click="printParent2($event,series);" ng-show="displyData.id==4"> </span> 
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
                <div ng-if="fancyArr.TypeID==1" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==2" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==3" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==4" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
                <div ng-if="fancyArr.TypeID==5" ui-sref-active="active"> <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)"> </a> <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a> </div>
              </li>
            </ul>
          </li>
        </ul>
        </li>
          
          
          <!--<li ><a href=""><i class="fa fa-user-secret" aria-hidden="true"></i> Managers <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/Managers/add">Add</a> </li>
              <li class=""> <a href="/br_betting/admin/Managers">List</a> </li>
              <li class=""> <a href="/br_betting/admin/Managers/blocked">Blocked</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-user-secret" aria-hidden="true"></i> Agents <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/agents/add">Add</a> </li>
              <li class=""> <a href="/br_betting/admin/agents">List</a> </li>
              <li class=""> <a href="/br_betting/admin/agents/blocked">Blocked</a> </li>
              <li class=""> <a href="/br_betting/admin/agents/manager_to_agent_coin">Manager to agent coin</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-user-plus" aria-hidden="true"></i> Roles & Permission <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/admins/users"><i class="fa fa-users"></i><span>Sub-Admin Users</span></a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-bank" aria-hidden="true"></i> Commissions <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/Commissions/addAgent">Add Agent</a> </li>
              <li class=""> <a href="/br_betting/admin/Commissions/agent">Agent List</a> </li>
              <li class=""> <a href="/br_betting/admin/Commissions/addManager">Add Manager</a> </li>
              <li class=""> <a href="/br_betting/admin/Commissions/manager">Manager List</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-money" aria-hidden="true"></i> Coins <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/coins/assigned_to_user">Assigned to User</a> </li>
              <li class=""> <a href="/br_betting/admin/coins/assigned_to_manager">Assigned to Manager</a> </li>
              <li class=""> <a href="/br_betting/admin/coins/assigned_to_agent">Assigned to Agents</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-futbol-o" aria-hidden="true"></i> Cricket <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class='treeview'> <a href="/br_betting/admin/games/cricket">Game</a> </li>
              <li > <a href="/br_betting/admin/games/cricket_session_bet">Session Bet</a> </li>
              <li > <a href="/br_betting/admin/games/cricket_match_bet">Match Bet</a> </li>
              <li > <a href="/br_betting/admin/games/add_cricket_session">Add Session</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-futbol-o" aria-hidden="true"></i> Horse Riding <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class='treeview'> <a href="/br_betting/admin/games/horse_riding">Game</a> </li>
              <li > <a href="/br_betting/admin/games/horse_riding_bid">Bid</a> </li>
              <li > <a href="/br_betting/admin/games/add_horse_riding_session">Add Session</a> </li>
              <li > <a href="/br_betting/admin/games/horse_riding_session">Session</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-futbol-o" aria-hidden="true"></i> Soccer <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/games/soccer">Game</a> </li>
              <li class=""> <a href="/br_betting/admin/games/soccer_bid">Bid</a> </li>
              <li class=""> <a href="/br_betting/admin/games/add_soccer_session">Add Session</a> </li>
              <li class=""> <a href="/br_betting/admin/games/soccer_session">Session</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-futbol-o" aria-hidden="true"></i> Basketball <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/games/basketball">Game</a> </li>
              <li class=""> <a href="/br_betting/admin/games/basketball_bid">Bid</a> </li>
              <li class=""> <a href="/br_betting/admin/games/add_basketball_session">Add Session</a> </li>
              <li class=""> <a href="/br_betting/admin/games/basketball_session">Session</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-sticky-note" aria-hidden="true"></i> CMS Pages <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class="treeview "> <a href="/br_betting/admin/cms/pages/edit/2">Game rules</a> </li>
              <li class=""> <a href="/br_betting/admin/cms/pages/edit/1">About Us</a> </li>
              <li class=""> <a href="/br_betting/admin/cms/pages/edit/3">Privacy Policy</a> </li>
              <li class=""> <a href="/br_betting/admin/cms/pages/edit/4">App Version</a> </li>
              <li class=""> <a href="/br_betting/admin/cms/pages/edit/5">Terms and Conditions</a> </li>
            </ul>
          </li>
          <li ><a href=""><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li class='treeview'> <a href="/br_betting/admin/reports/manager">Manager</a> </li>
              <li > <a href="/br_betting/admin/reports/agent">Agent</a> </li>
              <li > <a href="/br_betting/admin/reports/user">User</a> </li>
              <li > <a href="/br_betting/admin/reports/cricket_history">Cricket History</a> </li>
              <li > <a href="/br_betting/admin/reports/horse_riding_history">Horse Riding History</a> </li>
              <li > <a href="/br_betting/admin/reports/soccer_history">Soccer History</a> </li>
              <li > <a href="/br_betting/admin/reports/basketball_history">Basketball History</a> </li>
              <li > <a href="/br_betting/admin/reports/latest_bet">Latest Bet</a> </li>
              <li > <a href="/br_betting/admin/reports/manager_to_agent_history">Transcation Manager to Agent</a> </li>
              <li > <a href="/br_betting/admin/reports/agent_to_user_history">Transcation Agent to User</a> </li>
              <li > <a href="/br_betting/admin/reports/logged_in_user">Logged in User</a> </li>
            </ul>
          </li>
-->          
        </ul>
        
        

      </section>
      <section id="content2" class="tab-content clearfix" ng-show="treeAcc==1">
        <div class="col-sm-12 col-md-12">
          <div slim-scroll="">
            <div data-angular-treeview="true" data-tree-id="tree01" data-tree-model="treeNodes" data-node-id="id" data-node-label="name" data-node-children="children" data-node-image="image" ng-click="printParent($event);" ></div>
          </div>
          <span class="dropdownLayout dropdown123"  style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;" >
          <ul>
            <li> <a ng-click="setNodeToTable(node)" class="close-lft-clk"> <span class="cls-txt">Close</span> <span class="glyphicon glyphicon-remove cls-ico"></span> </a> </li>
            <li> <a ng-click="setNodeToTable(node)"> Select {{node.name}}<span style="color:RED;"> ({{node.children.length}})</span> </a> </li>
            <li> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.AddUser==1" href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2" ng-click="showAddSetting(node,currentScope1);">Add Acc.</a> </li>
            <li> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ng-click="showViewSetting(node,currentScope1);">View Acc.</a> </li>
            <li> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChangePwd==1" href="" ng-click="showChangePwd(node,currentScope1);">Change Pwd.</a> </li>
            <hr ng-show="sessionuser_id!=node.id || node.usetype==0"/>
            <li ng-show="sessionuser_id!=node.id || node.usetype==0"> <a href="" ng-click="showFreeChips(node,currentScope1);">A/c Chips In/Out</a> </li>
            <!--  <li ng-show="sessionuser_id!=node.id || node.usetype==0">
                <a href="" ng-click="showSettlement(node,currentScope1);">Settlement</a>
              </li> -->
            <hr ng-show="sessionuser_id!=node.id"/>
            <li ng-show="sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UserLock==1)"> <a href="" ng-click="showLockUser1(node)" ng-if="node.mstrlock==0 && sessionusetype!=3?lckUse='Unlock User':lckUse='Lock User'" >{{lckUse}}</a> </li>
            <li ng-show="sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.BettingLock==1)"> <a href="" ng-click="showLockBetting(node)" ng-if="node.lgnusrlckbtng==0 && sessionusetype!=3?lckbting='Unlock Betting':lckbting='Lock Betting'">{{lckbting}}</a> </li>
            <li> <a ng-show="sessionuser_id!=node.id && $root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Close_Ac==1" href="" ng-click="showCloseAcc(node)" ng-if="node.lgnusrCloseAc==0 && sessionusetype!=3?acStatus='Open Acc.':acStatus='Close Acc.'">{{acStatus}}</a> </li>
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

