 
  <div class="main-header">
    
     <style>
.md-virtual-repeat-container.md-autocomplete-suggestions-container{
z-index:99999 !important;
}
.container{
  width: 100%; 
  height: 300px;
}

#searchResult{
  list-style: none;
  padding: 1px;
  width: 250px;
  position: absolute;
  margin: 0;
margin-left:-5px;
}

#searchResult li{
  background: lavender;
  padding: 4px;
  margin-bottom: 1px;
}

#searchResult li:nth-child(even){
  background: cadetblue;
  color: white;
}

#searchResult li:hover{
  cursor: pointer;
}

.searchbar input[type=text]{
  padding: 5px;
  width: 250px;
  letter-spacing: 1px;
}
</style>

    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
      <div class="container-fluid">
    <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
        <a class="navbar-brand js-scroll-trigger" ui-sref="userDashboard.Home({'SportType': 0})"><img src="{{$root.logo}}" alt="Logo"></a>
        <button  onclick="myfun()" class="navbar-toggler navbar-toggler-right" type="button">          
          <i class="fa fa-bars"></i>
        </button>
        
        <div class="cdate"> 
        <div class="point_hed datetime"> 
			<div class="dropdown time_drop">
	<strong>{{Currentdate | date:'d MMM, y h:mm:ss a'}} <span class="time_zone dropdown-toggle" data-toggle="dropdown">(+05:30)</span>
			
    <div class="dropdown-menu">
      <a ng-if="false" class="dropdown-item" href="javascript:void(0)">System time - (GMT +00:00)</a>
      <a class="dropdown-item" href="javascript:void(0)">Your computer time - (GMT +05:30)</a>
      <a class="dropdown-item" href="javascript:void(0)">India Standard time - (GMT +05:30)</a>
    </div>
			
			</strong>
			</div>
			</div>
         <div class="point_hed searchbar"> 
    
<input type='text'  class="form-control"
          ng-keyup='fetchUsers()' 
          ng-click='searchboxClicked($event);' 
          ng-model='searchText' 
          placeholder="Enter match name."><i class="fa fa-search"></i><br>
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
 
        <div class=" navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto pull-right">
           
          <li>
                    <p class="last-login">Logged in as {{$root.user}}</p>
                    <p class="last-login">Last logged in: 
                        <time class="">{{LastLoginDate | date:'medium'}}</time>
                    </p>
                </li>
                
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="javascript:void();" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell fa-fw"></i> Account
          <!--  <span class="badge badge-danger">9+</span>-->
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:1})">My Bets</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:2})">Betting Profit and Loss</a>
            <a class="dropdown-item" href="#" ui-sref="userDashboard.OnePageRprt({typeId:3})">Account Statement</a>
            <a class="dropdown-item" href="#"  ui-sref="userDashboard.Chiphistorycntr">Transfer Statement</a>
            <a class="dropdown-item " href="#" ui-sref="userDashboard.changePasswordUser">Change Password</a>
              <a class="dropdown-item " ng-if="apkDownloadUrl=='Y'" href="/UtilityController/downloadApk">Download Apk</a>

          </div>
        </li>
         <li class="nav-item  ">
          <a class="nav-link" ng-click="logout();" href="javascript:void(0)">  
            <i class="fa fa-sign-out"></i> Logout
          </a>
          
        </li>
      </ul>
        </div>
      </div>
    </nav>
    
    
 
      <nav class="navbar navbar-static-to">
        <div class="container-fluid">
           <div class="row">
            <div class="col-sm-3 col-xs-6">
              <div class="navbar-header ">
                 <a id="mobileDemo" onClick="myfun()" class="button-collapse"><i class="fa fa-bars"></i></a>
               
              </div>
            </div>
        <!--  <div class="navbar-custom-menu">
            <header-notificationuser></header-notificationuser>
          </div>-->
          <!--/.nav-collapse -->
        </div>
      </div>
        <!--/.container-fluid -->

      </nav>
      <div class="marqueeHead" >
          <div class="moving-div">
          <marquee hspace="0" scrollamount="5" BEHAVIOR="SCROLL" style="font-size:14px;color:#000;" onmouseover="this.stop();" onmouseout="this.start();">{{diplayMsg}}</marquee>
        </div>
      </div>
	  
	  
 
<aside class="main-sidebar" style="top:95px">
    <section class="sidebar" style="height: auto;">
      <usersidebar></usersidebar>

    </section>
</aside>



    </div>



