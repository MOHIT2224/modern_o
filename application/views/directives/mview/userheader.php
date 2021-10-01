 <div class="headernew">
 <div class="notifications"> <a onclick="closnotif()" class="close-btn">x</a>

 </div>
 
 
 
   <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
       <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
 <button  onclick="myfun()" class="navbar-toggler navbar-toggler-left" type="button">          
          <i class="fa fa-bars"></i>
        </button>
   <a class="navbar-brand js-scroll-trigger" ui-sref="userDashboard.Home"><img src="app/images/logo.png"></a>
 
 
 
  <button  onclick="myfunright()" class="navbar-toggler navbar-toggler-right" type="button">          
         <i class="fa fa-user" aria-hidden="true"></i>
        </button>
<div class="searchbox"  onclick="opensearch()" > 
<i class="fa fa-search" aria-hidden="true"></i>
</div>



<div class="point_hed searchbar"> 

<div class="backbtn" onclick="opensearch()"><i  class="fa fa-chevron-left" aria-hidden="true"></i></div>



		<div class="searchinput">	
        
     <input type='text'  class="form-control"
          ng-keyup='fetchUsers()' 
          ng-click='searchboxClicked($event);' 
          ng-model='searchText' 
          placeholder="Enter match name.">
    <ul id='searchResult' ng-if="searchText_len>0" >
      <li ng-click='setValue($index,$event)' 
          ng-repeat="result in searchResult" >
          {{ result.matchName }}
      </li>
    </ul>
  <ul id='searchResult' ng-if="searchResult.length==0 && searchText!=''" >
      <li>
            No matches found <span ng-if="searchText!=''"> for {{searchText}}</span>.
      </li>
    </ul>
        
        
        </div>
             
             
             
                     
            
       

			</div>

 
 
 </nav>
 
 
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
                    <span><strong>{{$root.Balance}}</strong></span>
                </div>
                <div class="group">
                    <div>Credit Limit:</div>
                    <span>{{$root.Balance}}</span>
                </div>
 
                <div class="group">
                    <div>Net Exposure:</div>
                    <span>{{$root.Liability}}</span>
                </div>
            </div>
        </div>
        
        
        
        
        
 
 <div class="othermenu">
 
   <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:1})">My Bets</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:2})">Betting Profit and Loss</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:3})">Account Statement</a>
            <a class="dropdown-item" href="#"  ui-sref="userDashboard.Chiphistorycntr">Transfer Statement</a>
            <a class="dropdown-item " href="#" ui-sref="userDashboard.changePasswordUser">Change Password</a>
 
 </div>
 
 

 
 
 
</div>

 
 </div>
   <a class="logout" ng-click="logout();" href="javascript:void(0)">  
            <i class="fa fa-sign-out"></i> Logout
          </a>
 </div> 
 
 
 
 <aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
      <usersidebar></usersidebar>

    </section>
</aside>
 
 
 </div>
