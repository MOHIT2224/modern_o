<div style="padding-left:0px;">
  <script>
jQuery(window).scroll(function() {

  var uheight = jQuery( ".main-sidebar").innerHeight();
  alert(uheight);
jQuery('.main-sidebar').css("min-height", uheight);

});
jQuery(window).resize(function() {

	  var uheight = jQuery( ".main-sidebar").innerHeight();
jQuery('.main-sidebar').css("min-height", uheight);

});
</script>
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
      <!--<label for="tab2" class="tab-label" ng-click="treeAcc=1" ng-hide="sessionusetype==3">
       <button type="button" class="user-ref-btn" ng-click="refresh_tree()">
          <i class="glyphicon glyphicon-refresh"></i>
        </button>
        <span>User </span>
      </label>-->
      <input type="hidden" name='hdnVal' id='hdnVal' value='P'/>
      <input type="hidden" name='hdnVal1' id='hdnVal1' value='P'/>

      <section id="content1"  class="tab-content clearfix" style="display:block !important">
      <!--ng-show="treeAcc==0"-->
        <!-- ng-init="ShowHideAng(0)"-->
       <ul id="mainId" class="sidebar-menu" ng-show="sessionusetype==0">
       <li ng-click="ShowHideAng(-1)" ng-show="IsShowMenu('subAdmin') || IsShowMenu('role')"><a href=""><i class="fa fa-cog" aria-hidden="true"></i> Manage Subadmin <i class="fa fa-angle-left pull-right"></i></a>
                   <ul class="treeview-menu">
                     <li ng-show="IsShowMenu('role')" ui-sref-active="treeview active"> <a ui-sref="dashboard.RoleSubAdmin">Manage Role</a> </li>
                    <li ng-show="IsShowMenu('subAdmin')"  ui-sref-active="treeview active" class="active treeview"> <a  ui-sref="dashboard.AddSubAdmin"> <i class="fa fa-dashboard"></i><span>Sub Admin</span> </a> </li>

       	        </ul>
       	        </li>

      <li class="userlisting" ng-click="refresh_tree();IsShowTreeDiv=true" ng-show="IsShowMenu('user')"> <a ng-click="treeAcc=1;IsPopupShow=true;" href="JavaScript:void(0)"> <img src="app/dist/img/dealer_white.png" />              Users</a>  </li>
      <li ui-sref-active="active" ng-show="IsShowMenu('MasterList')" class="active treeview"> <a  ui-sref="dashboard.MasterList"> <i class="fa fa-list"></i><span>Master List</span> </a> </li>
 	<li ui-sref-active="active" ng-show="IsShowMenu('MarketWatch')" ng-click="ShowHideAng(0)" class="active treeview"> <a  ui-sref="{{getDashboardurl()}}"> <i class="fa fa-dashboard"></i><span>Market Watch</span> </a> </li>
<li ng-show="IsShowMenu('fancy')"><a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.ManageFancy"><i class="fa fa-file" aria-hidden="true"></i>Manage Fancy</a> </li>
<li ng-show="IsShowMenu('onePageReportSetting')"><a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.OnePageRprt"><i class="fa fa-file" aria-hidden="true"></i>  One Page Report</a> </li>
<li ng-show="IsShowMenu('scheduleMatch')"><a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.Matches"><i class="fa fa-file" aria-hidden="true"></i>  Schedule Match</a> </li>
<li ng-show="false"><a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.CollectionReport"><i class="fa fa-file" aria-hidden="true"></i>  Collection Report</a> </li>
<li ng-show="IsShowMenu('seriesMatch')"><a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ui-sref="dashboard.SeriesMatches"><i class="fa fa-list" aria-hidden="true"></i>Manage Series/Matches</a> </li>
            <li ng-click="ShowHideAng(-1)"><a href=""><i class="fa fa-cog" aria-hidden="true"></i> Application Settings <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
            <li ng-show="IsShowMenu('fancy')"><a href="" ui-sref="dashboard.ApkVersion"><i class="fa fa-cog" aria-hidden="true"></i>Apk Setting</a> </li>
            <li ng-show="IsShowMenu('term')"><a href="" ui-sref="dashboard.TermCondition"><i class="fa fa-cog" aria-hidden="true"></i>Term & Condition</a> </li>
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('settlementEntryList')"> <a ui-sref="dashboard.DelChipLst">Settlement Entry List</a> </li>
               <li ng-show="IsShowMenu('sportSetting')"  ui-sref-active="treeview active"> <a ui-sref="dashboard.sportSetting">Sports Setting</a> </li>
	        <li ui-sref-active="treeview active" ng-show="IsShowMenu('settledMatches')"> <a ui-sref="dashboard.SettlementBet">Settled Matches</a> </li>
		 <li ui-sref-active="treeview active" ng-show="IsShowMenu('trashBets')"> <a ui-sref="dashboard.TrashBet">Trash Bets</a> </li>
              <li ui-sref-active="treeview active"  ng-show="IsShowMenu('seriesSetting')"> <a ui-sref="dashboard.SeriesActDact">Series Setting</a> </li>
              <li ui-sref-active="treeview active"  ng-show="IsShowMenu('matchSetting')"> <a ui-sref="dashboard.Createfancy">Match Setting</a> </li>
              <li ui-sref-active="treeview active"  ng-show="IsShowMenu('marketSetting')" > <a ui-sref="dashboard.ListOfMarket">Market Setting</a> </li>

            <!--  <li ui-sref-active="treeview active"> <a ui-sref="dashboard.Listmarkettype">Market Type</a> </li> -->
              <li ui-sref-active="treeview active" ng-show="($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1) && IsShowMenu('setMatchResult')" > <a ui-sref="dashboard.match_result">Set Match Result</a> </li>
           <!--   <li ui-sref-active="active"> <a ui-sref="dashboard.userRightsCntr">User permissions</a> </li> -->
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('settings')"> <a ui-sref="dashboard.SetAdminLimitCntr">Settings</a> </li>
          <!--    <li ui-sref-active="treeview active"> <a ui-sref="dashboard.CrtSubAdminCntr">Create Sub Admin</a> </li>  -->
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('closedUsersAccount')"> <a ui-sref="dashboard.closeUserListCntr">Closed Users Account</a> </li>
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('RemoveOldGameAndUser')"> <a ui-sref="dashboard.Delete_old_data">Remove Old Game + Users Data</a> </li>
              <li ui-sref-active="treeview active" ng-show="IsShowMenu('RemoveOldBetData')"> <a ui-sref="dashboard.Delete_old_bet_data">Remove Old Bet Data</a> </li>
            </ul>
          </li>


        </ul>
      </section>
      <section id="content2" class="tab-content clearfix" ng-show="treeAcc==1">


    <div class="search-container">
        <form class="form-search form-inline">
<input type="text"  id="search12" name="usearch" placeholder="Search User"    ng-change="searchTree()" ng-model="txtSearch.params"/>

                 <i class="fa fa-search" ng-click="searchTree()"></i>
                 <h5 ng-show="treeNodes.length==0">No user found.</h5>
        </form>
    </div>

     <div class="closebox"> <i ng-click="treeAcc=0" class="fa fa-times"></i></div>


        <div class="col-sm-12 col-md-12">
          <div slim-scroll="">
            <div data-angular-treeview="true" data-tree-id="tree01" data-tree-model="treeNodes" data-node-id="id" data-node-label="name" data-node-children="children" data-node-image="image" ng-click="printParent($event);" ></div>
          </div>
          <span class="dropdownLayout dropdown123" style=" position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;"  ng-show="IsPopupShow">
          <!-- style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;"-->
          <ul>
            <li> <a ng-click="setNodeToTable(node)" class="close-lft-clk"> <span class="cls-txt">Close</span> <span class="glyphicon glyphicon-remove cls-ico"></span> </a> </li>
            <li> <a ng-click="setNodeToTable(node)"> Select {{node.name}}<span style="color:RED;">  ({{node.children.length?node.children.length:0}})</span> </a> </li>
            <li ng-show="IsShowMenu('AddUser')"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.AddUser==1" href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2" ng-click="showAddSetting(node,currentScope1);">Add Acc.</a> </li>
            <li  ng-show="IsShowMenu('ViewUser') || IsShowMenu('UpdateUser')" ng-hide="node.usetype == 0"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ng-click="showViewSetting(node,currentScope1);">View Acc.</a></li>
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

      <div id="" class="tablebox" ng-show="treeAcc==1">
      <div ng-if="IsShowTreeDiv" class="table-responsive" >
<!--ng-show="tblNodeName || treeAcc==1" -->
        <table class="table chips-table">
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

</div>


