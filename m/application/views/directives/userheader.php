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
		 <a class="navbar-brand js-scroll-trigger" ui-sref="userDashboard.Home"><i  class="fa fa-home" aria-hidden="true"></i></a>
   <a class="navbar-brand js-scroll-trigger" ui-sref="userDashboard.Home"><img src="app/images/logo.png"></a>



  <button  onclick="myfunright()" class="navbar-toggler navbar-toggler-right" type="button">
         <i class="fa fa-user" aria-hidden="true"></i>
         <span  ng-class="{'red':$root.Balance<0,'green':$root.Balance>0}"><strong>{{$root.Balance}}</strong></span>
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
                    <span ng-class="{'red':$root.Balance<0,'green':$root.Balance>0}"><strong>{{$root.Balance}}</strong></span>
                </div>
                <div class="group">
                    <div>Credit Limit:</div>
                    <span ng-class="{'red':$root.FreeChips<0,'green':$root.FreeChips>0}">{{$root.FreeChips}}</span>
                </div>
                  <div class="group">
                                    <div>Winning Amount:</div>
                                    <span ng-class="{'red':$root.P_L<0,'green':$root.P_L>0}">{{$root.P_L}}</span>
                                </div>

                <div class="group">
                    <div>Net Exposure:</div>
                    <span ng-class="{'red':$root.Liability<0,'green':$root.Liability>0}">{{$root.Liability}}</span>
                </div>
            </div>
        </div>






 <div class="othermenu">

   <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:1})">My Bets</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:2})">Betting Profit and Loss</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:3})">Account Statement</a>
            <a class="dropdown-item" href="#"  ui-sref="userDashboard.Chiphistorycntr">Transfer Statement</a>
            <a class="dropdown-item " href="#" ui-sref="userDashboard.ShowTermCondition">Term & Condition</a>
            <a class="dropdown-item " href="#" ui-sref="userDashboard.changePasswordUser">Change Password</a>
     <!--<a class="dropdown-item " ng-if="apkDownloadUrl=='N'" href="/UtilityController/downloadApk">Download Apk</a>-->
	 <a class="dropdown-item " href="http://skaigold.co/apk/skaigold.apk" >Download Apk</a>

 </div>






</div>


 </div>
   <a class="logout" ng-click="logout();" href="javascript:void(0)">
            <i class="fa fa-sign-out"></i> Logout
          </a>
 </div>


 <div class="marqueeHead" >
          <div class="moving-div">
          <marquee hspace="0" scrollamount="5" BEHAVIOR="SCROLL" style="font-size:14px;color:#000;" onmouseover="this.stop();" onmouseout="this.start();">{{diplayMsg}}</marquee>
        </div>
      </div>
	  
	  
 <div class="sportsbook">	
	<ul>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Cricket.png"> </span> Cricket </a> </li>
	<li><a  ui-sref="userDashboard.Home"><span><img src="app/assets/img/Soccer.png"> </span> Soccer </a></li>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Tennis.png"> </span>Tennis </a></li>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Pro-Kabbadi.png"> </span> Pro Kabbadi  </a></li>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Soccer.png"> </span> Horse Racing  </a></li>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Soccer.png"> </span> Exchange Games  </a></li>
	<li><a  ui-sref="userDashboard.Home"> <span><img src="app/assets/img/Soccer.png"> </span> Others </a></li>
	
	</ul>
 </div> 
	  
	  
	  
	  
 <aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
      <usersidebar></usersidebar>

    </section>
</aside>


 </div>
