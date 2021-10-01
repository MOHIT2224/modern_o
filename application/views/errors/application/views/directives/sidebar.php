<div class="left-sdr pushmenu pushmenu-left" ng-click="Hide_lftpnl()">
  <div class="msg-alerts alert alert-success" role="alert" ng-show="msgShowHide==true" ng-click="msg_show();">
    <strong>{{alertMessage}}.</strong>
  </div>
  <div>
    <div class="tab_container" style="box-shadow:0 0 5px #888;">
      <input id="tab1" class="tab-inp" type="radio" name="tabs" checked="" ></input>
      <label for="tab1" class="tab-label" ng-click="treeAcc=0" ng-hide="sessionusetype==3">
        <img src="app/images/market.png" />
        <span>Markets </span>
      </label>
      <input id="tab2" class="tab-inp" type="radio" name="tabs"/>
      <label for="tab2" class="tab-label" ng-click="treeAcc=1" ng-hide="sessionusetype==3">
        <button type="button" class="user-ref-btn" ng-click="refresh_tree()">
          <i class="glyphicon glyphicon-retweet"></i>
        </button>
        <img src="app/images/user.png" />
        <span>User</span>
      </label>
      <input type="hidden" name='hdnVal' id='hdnVal' value='P'/>
      <input type="hidden" name='hdnVal1' id='hdnVal1' value='P'/>
      <section id="content1" class="tab-content clearfix">
        <div class="all-sport" ng-click="ShowHideAng(0)" ui-sref="{{getDashboardurl()}}">Sports</div>
        <div ng-init="ShowHideAng(0)" class="cd-accordion animated" ng-show="sessionusetype==0">
          <h4 class="accordionCls main-spr game-ic" ng-class="{true:'flipImg',false:'active'}[accordion==-1]"  ng-show="accordion==-1 || accordion==0">
            <a href="" ng-click="ShowHideAng(-1)" ui-sref="dashboard.Home">Game Master</a>
          </h4>
          <ul class="accordion-content sub-item game-ic main-ul-scroll" ng-show="accordion==-1">
            <li ui-sref-active="active">
              <a ui-sref="dashboard.Playermaster">Player Master </a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.closeUserListCntr">CloseA/C Userlist</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.uploadImg">Upload Image</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.DelChipLst">Cash Entry List</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.SeriesActDact">List All Series</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.Createfancy">List All Match</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.Listmarkettype">Market Type</a>
            </li>
            <li ui-sref-active="active" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1" >
              <a ui-sref="dashboard.match_result">Set Match Result</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.userRightsCntr">User Rights</a>
            </li>
            <li ui-sref-active="active">
              <a ui-sref="dashboard.SetAdminLimitCntr">SET Admin Limit</a>
            </li>
            <li ui-sref-active="active" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull">
              <a ui-sref="dashboard.CrtSubAdminCntr">Create Helper</a>
            </li>
          </ul>
          <!--Sports-->
          <h4 class="accordionCls main-spr {{getCssVal(displyData.id)}}" ng-class="{true:'flipImg',false:'active'}[accordion==displyData.id]" ng-repeat="displyData in sprtData" ng-show=" accordion==0 || accordion==displyData.id">
            <a href="" ng-click="ShowHideAng(displyData.id,0)"  ui-sref="dashboard.Getseriesfrmapi({sportId: displyData.id})" >
              {{displyData.name}}
            </a>
            <!--Series-->
            <ul class="accordion-content main-ul-scroll" ng-show="accordion==displyData.id">
              <li class="sub-item {{getCssVal(sportsId)}}" ng-repeat="match in GetSeriesData" ng-show="accordionLv2==0 || accordionLv2==match.seriesId">
                <a href="" ui-sref="dashboard.Getmatchfrmapi({seriesId: match.seriesId,sportId:displyData.id})" ng-click="getSeriesMatch(displyData.id,match.seriesId)">
                  {{match.Name}}
                </a>
                <!--Match-->
                <ul class="accordion-content" ng-show="accordionLv2==match.seriesId">
                  <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode">
                    <a href="" ui-sref="dashboard.Getmarketlstapi({MatchId: series.MstCode,sportId:displyData.id,seriesId: match.seriesId})" ng-click="getMatchMarket(displyData.id,series.MstCode)">
                      {{series.matchName}}
                    </a>
                    <span class="myMenu2" ng-click="printParent2($event,series);" ng-show="displyData.id==4">
                    </span>
                    <!--Fancy & market-->
                    <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                      <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active">
                        <!--<a href="" ng-class="{true:'glyphicon-minus',false:'glyphicon-plus'}[chkMarketPP]" ng-click="sdMarketPP()">
                          > ||
                        </a> 
                        css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir -->
                        <a href="" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})">
                          {{market.Name}}
                        </a>
                      </li>
                      <li class="fancy_type" ng-repeat="fancyArr in getMatchFancy">
                        <!-- Start Fancy Type -->
                        <div ng-if="fancyArr.TypeID==1" ui-sref-active="active">
                          <a ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                        </div>
                        <div ng-if="fancyArr.TypeID==2" ui-sref-active="active">
                          <a ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                        </div>
                        <div ng-if="fancyArr.TypeID==3" ui-sref-active="active">
                          <a ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a>
                        </div>
                        <div ng-if="fancyArr.TypeID==3" ui-sref-active="active">
                          <a ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">UpandDown</a>
                        </div>
                        <div ng-if="fancyArr.TypeID==4" ui-sref-active="active">
                          <a ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a>
                        </div>
                        <div ng-if="fancyArr.TypeID==5" ui-sref-active="active">
                          <a ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName})">{{fancyArr.HeadName}}</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </h4>
          <span class="dropdownLayout" style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;" ng-show="dropdown124">
            <ul>
              <li>
                <a ng-click="showCreateFancy($event,1)">Odd Even Fancy</a>
              </li>
              <li>
                <a ui-sref="dashboard.CntrAdminSesssionFancy({matchId: CreateFancyMatchInfo.MstCode,sportId:CreateFancyMatchInfo.SportID})">Session Fancy</a>
              </li>
              <li>
                <a ng-click="showCreateFancy($event,3)">Khaddal Fancy</a>
              </li>
              <li>
                <a ng-click="showCreateFancy($event,4)">Last Digit Fancy</a>
              </li>
              <li>
                <a ng-click="showCreateFancy($event,5)">Up Down Fancy</a>
              </li>
            </ul>
          </span>
        </div>
        <!--User SideBar Sports-->
        <h4 class="accordionCls main-spr {{getCssVal(displyData.id)}}" ng-class="{true:'flipImg',false:'active'}[accordion==displyData.id]" ng-repeat="displyData in sprtData" ng-show="(accordion==0 || accordion==displyData.id) && sessionusetype!=0">
          <a href="" ng-click="ShowHideAng(displyData.id,0)" >{{displyData.name}}</a>
          <!--Match-->
          <ul class="accordion-content main-ul-scroll" ng-show="accordion==displyData.id">
            <li class="sub-item {{getCssVal(displyData.id)}}" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode" ui-sref-active="active">
              <a href="" ng-click="getMatchMarket(displyData.id,series.MstCode)">{{series.matchName}}</a>
              <ul class="accordion-content " ng-show="accordionLv1==series.MstCode">
                <li ng-repeat="market in MatchMarket" class="market_type"  ui-sref-active="active">
                  <!--<a href="" ng-class="{true:'glyphicon-minus',false:'glyphicon-plus'}[chkMarketPP]" ng-click="sdMarketPP()" ng-show="sessionusetype!=3">
                    > ||
                  </a>-->
                  <!--css add by aasha mam and sp create by ajay sir then uncomment this code pls concern to sourabh sir -->
                  <a href="" ui-sref="dashboard.Matchodds({MatchId: series.MstCode,MarketId:market.Id,matchName:series.matchName,date:series.MstDate})">{{market.Name}}</a>
                </li>
                <li class="fancy_type" ng-repeat="fancyArr in getMatchFancy">
                  <!-- Start Fancy Type -->
                  <div ng-if="fancyArr.TypeID==1" ui-sref-active="active">
                    <a ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==2" ui-sref-active="active">
                    <a ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==3" ui-sref-active="active">
                    <a ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==4" ui-sref-active="active">
                    <a ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==5" ui-sref-active="active">
                    <a ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </h4>
      </section>
      <section id="content2" class="tab-content clearfix">
        <div class="col-sm-12 col-md-12">
          <div slim-scroll="">
            <div data-angular-treeview="true" data-tree-id="tree01" data-tree-model="treeNodes" data-node-id="id" data-node-label="name" data-node-children="children" data-node-image="image" ng-click="printParent($event);" ></div>
          </div>
          <span class="dropdownLayout dropdown123"  style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;" >
            <ul>
              <li>
                <a ng-click="setNodeToTable(node)" class="close-lft-clk">
                  <span class="cls-txt">Close</span>
                  <span class="glyphicon glyphicon-remove cls-ico"></span>
                </a>
              </li>
              <li>
                <a ng-click="setNodeToTable(node)">
                  Select {{node.name}}<span style="color:RED;"> ({{node.children.length}})</span>
                </a>
              </li>
              <li>
                <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.AddUser==1" href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2" ng-click="showAddSetting(node,currentScope1);">Add Acc.</a>
              </li>
              <li>
                <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ng-click="showViewSetting(node,currentScope1);">View Acc.</a>
              </li>
              <li>
                <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChangePwd==1" href="" ng-click="showChangePwd(node,currentScope1);">Change Pwd.</a>
              </li>
              <hr ng-show="sessionuser_id!=node.id || node.usetype==0"/>
              <li ng-show="sessionuser_id!=node.id || node.usetype==0">
                <a href="" ng-click="showFreeChips(node,currentScope1);">Free Chips In/Out</a>
              </li>
              <hr ng-show="sessionuser_id!=node.id"/>
              <li ng-show="sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UserLock==1)">
                <a href="" ng-click="showLockUser1(node)" ng-if="node.mstrlock==0 && sessionusetype!=3?lckUse='Unlock User':lckUse='Lock User'" >{{lckUse}}</a>
              </li>
              <li ng-show="sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.BettingLock==1)">
                <a href="" ng-click="showLockBetting(node)" ng-if="node.lgnusrlckbtng==0 && sessionusetype!=3?lckbting='Unlock Betting':lckbting='Lock Betting'">{{lckbting}}</a>
              </li>
              <li>
                <a ng-show="sessionuser_id!=node.id && $root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Close_Ac==1" href="" ng-click="showCloseAcc(node)" ng-if="node.lgnusrCloseAc==0 && sessionusetype!=3?acStatus='Open Acc.':acStatus='Close Acc.'">{{acStatus}}</a>
              </li>
            </ul>
          </span>
        </div>
      </section>
      <table class="table chips-table" ng-show="tblNodeName || treeAcc==1">
        <tr>
          <th style="font-weight:bold;">Name</th>
          <th style="font-weight:bold;">{{tblNodeName}}</th>
        </tr>
        <tr>
          <td>Chips</td>
          <td>
            <span style="{{getValColor(ChipInOut)}}"> {{ChipInOut==null?0:ChipInOut}}</span>
          </td>
        </tr>
        <tr>
          <td>Free Chips</td>
          <td>
            <span style="{{getValColor(FreeChips)}}">{{FreeChips==null?0:FreeChips}}</span>
          </td>
        </tr>
        <tr>
          <td>Liability</td>
          <td>
            <span style="{{getValColor(Liability)}}">{{Liability==null?0:Liability}}</span>
          </td>
        </tr>
        <tr>
          <td>Wallet</td>
          <td>
            <span style="{{getValColor(Balance)}}">
              <b style="color:BLUE;">{{Balance | number}}</b>
            </span>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>