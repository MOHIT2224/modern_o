   <div>
  <div class="msg-alerts alert alert-success" role="alert" ng-show="msgShowHide==true" ng-click="msg_show();"> <strong>{{alertMessage}}.</strong> </div>
  
  
     <div class="headernew">
  
  
  
  <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
      <button  onclick="myfun()" class="navbar-toggler navbar-toggler-left" type="button">          
          <i class="fa fa-bars"></i>
        </button>
        
        
<a class="navbar-brand js-scroll-trigger" ui-sref="dealerDashboard.Home"><img src="app/images/logo.png"><!--<img ng-if="false" src="{{$root.logo}}" alt="Logo">Demo_New--></a>


<button  onclick="myfunright()" class="navbar-toggler navbar-toggler-right" type="button">          
         <i class="fa fa-user" aria-hidden="true"></i>
        </button>
  </nav>
  
  </div>
  
  
  
  
  
  <div class="right_sidebar">
 <div class="innercontent">
 
<div class="account-overview">
    <p class="login-name">{{$root.user}}</p>

    
        <div class="menu-item">
            <p class="label">
                <i class="apl-icon-university"></i>
                <span>Balance Information</span>
            </p>
          <div class="content">
                <div class="group">
                    <div>Available Credit:</div>
                    <span ng-class="{'red':$root.Balance<0,'green':$root.Balance>0}"><strong>{{$root.Balance}}</strong></span>
                </div>
                <div class="group">
                    <div>Credit Limit:</div>
                    <span ng-class="{'red':$root.Balance<0,'green':$root.Balance>0}">{{$root.Balance}}</span>
                </div>
 
                <div class="group">
                    <div>Net Exposure:</div>
                    <span ng-class="{'red':$root.Liability<0,'green':$root.Liability>0}">{{$root.Liability}}</span>
                </div>
            </div>
        </div>
        
        
        
        
        
 
 <div class="othermenu">
 
   <a class="dropdown-item" href="#" ui-sref-active="active" ui-sref="dealerDashboard.changePasswordUser">Change Password</a>
            <a class="dropdown-item"  ui-sref-active="active" href="#" ui-sref="dealerDashboard.OnePageRprt({typeId:1})">Bet History</a>
            <a class="dropdown-item" ui-sref-active="active" href="#" ui-sref="dealerDashboard.Profitlosscntr">Profit & Loss</a>
             <a class="dropdown-item " href="#" ui-sref="dealerDashboard.ShowTermCondition">Term & Condition</a>
    
 
 </div>
 
 

 
 
 
</div>

 
 </div>
 
 
   <a class="logout" ng-click="logout();" href="javascript:void(0)">  
            <i class="fa fa-sign-out"></i> Logout
          </a>
 
 </div>
  
  
  
  
   <div class="main-header">
 
     
      <aside class="main-sidebar">
<div ng-init="treeAcc=0" class="main-nav-admin">
    <div class="tab_container">
 <input id="tab1" class="tab-inp" type="radio" name="tabs"  ng-model="treeAcc" ng-value="false" ng-checked="!treeAcc"></input>
      <label for="tab1" class="tab-label" ng-click="treeAcc=0" ng-hide="sessionusetype==3">
        <span>Menu </span>
      </label>
      <input id="tab2" class="tab-inp" type="radio" name="tabs" ng-model="treeAcc" ng-value="true" ng-checked="treeAcc"/>
      <label for="tab2" class="tab-label" ng-click="treeAcc=1" ng-hide="sessionusetype==3">
       <button type="button" class="user-ref-btn" ng-click="refresh_tree()">
          <i class="glyphicon glyphicon-refresh"></i>
        </button>
        <span>User </span>
      </label>
      <input type="hidden" name='hdnVal' id='hdnVal' value='P'/>
      <input type="hidden" name='hdnVal1' id='hdnVal1' value='P'/>
    <section id="content1" ng-show="treeAcc==0" class="sidebar" style="height: auto;" class="tab-content clearfix">
<ul class="sidebar-menu">
         <li ui-sref-active="active" class="active treeview" onclick="myfun()"><a ui-sref="dealerDashboard.Home"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
<li  ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.OnePageRprt({typeId:1})"><i style="float: left;position: relative;" class="fa fa-file" aria-hidden="true"></i>  One Page Report</a> </li>
<li ng-if="false"  ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.CollectionReport"><i style="float: left;position: relative;" class="fa fa-file" aria-hidden="true"></i>  Collection Report</a> </li>
         <li ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.ClientList"><i class="fa fa-list" aria-hidden="true"></i><span>Client List</span></a></li>
         <li ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.Chiphistorycntr"><i class="fa fa-history" aria-hidden="true"></i><span>Chips History</span></a></li>
         <li ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.ChipSummaryCntr"><i class="fa fa-microchip"></i><span>Chips Summary</span></a></li>
 <li ui-sref-active="active" onclick="myfun()"><a ui-sref="dealerDashboard.Createfancy"><i class="fa fa-cog"></i><span>Match Setting</span></a></li>
       </ul>
 </section>
   <section id="content2" class="tab-content clearfix" ng-show="treeAcc==1">
        <div class="col-sm-12 col-md-12">
          <div slim-scroll="">
                <input type="text"  id="search12" name="usearch" placeholder="Search User"    ng-change="searchTree()" ng-model="txtSearch.params"/>
                                                                     <i class="fa fa-search" ng-click="searchTree()"></i>
                                                                     <h5 ng-show="treeNodes.length==0">No user found.</h5>
            <div data-angular-treeview="true" data-tree-id="tree01" data-tree-model="treeNodes" data-node-id="id" data-node-label="name" data-node-children="children" data-node-image="image" ng-click="printParent($event);" ></div>
          </div>
          <span class="dropdownLayout dropdown123"  style="position:absolute;top:{{yPosi-110}}px;left:{{xPosi}}px;" >
          <ul>
            <li> <a ng-click="setNodeToTable(node)" class="close-lft-clk"> <span class="cls-txt">Close</span> <span class="glyphicon glyphicon-remove cls-ico"></span> </a> </li>
            <li> <a ng-click="setNodeToTable(node)"> Select {{node.name}}<span style="color:RED;">  ({{node.children.length?node.children.length:0}})</span> </a> </li>
            <li> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.AddUser==1" href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2" ng-click="showAddSetting(node,currentScope1);">Add Acc.</a> </li>
            <li ng-hide="node.usetype == 0 || node.usetype==1 || node.usetype==2"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.UpdateUser==1" href="" ng-click="showViewSetting(node,currentScope1);">View Acc.</a> </li>
            <li ng-hide="node.usetype==2"> <a ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChangePwd==1" href="" ng-click="showChangePwd(node,currentScope1);">Change Pwd.</a> </li>
           <hr ng-show="sessionuser_id!=node.id || node.usetype==0"/>
            <li ng-show="node.usetype!=2"> <a href="" ng-click="showFreeChips(node,currentScope1);">A/c Chips In/Out</a> </li>
            <!--  <li ng-show="sessionuser_id!=node.id || node.usetype==0">
                <a href="" ng-click="showSettlement(node,currentScope1);">Settlement</a>
              </li> -->
            <hr ng-show="sessionuser_id!=node.id && node.usetype!=2"/>
                    <li ng-show="sessionuser_id!=node.id && (!(node.lgnusrCloseAc==0 && sessionusetype!=3)) && ($root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.BettingLock==1) && node.usetype!=2"> <a href="" ng-click="showLockBetting(node)" ng-if="node.lgnusrlckbtng==0 && sessionusetype!=3?lckbting='Unlock Betting':lckbting='Lock Betting'">{{lckbting}}</a> </li>
          </ul>
          </span> </div>
      </section>
  </aside>

    </div>



  </div>





