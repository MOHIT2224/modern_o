 
  <div class="main-header">
    
     <style>
.md-virtual-repeat-container.md-autocomplete-suggestions-container{
z-index:99999 !important;
}
</style>
    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
      <div class="container-fluid">
        <a class="navbar-brand js-scroll-trigger" ui-sref="userDashboard.Home"><img src="{{$root.logo}}" alt="Logo"></a>
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
			 <input ng-if="false" type="search" client-auto-complete ng-model="client.name" class="form-control" placeholder="Search" />
                    <input  ng-if="false"  type="text" class="form-control autocomplete" placeholder="Search"  ng-model="selected" typeahead="state as state.matchName for state in getMatchList($viewValue) | filter:$viewValue">  
            <md-autocomplete  md-selected-item="selectedItem" md-min-length="1" md-selected-item-change="CallSelectedItem(selectedItem)" md-search-text="searchText" md-items="item in getMatches(searchText)" md-item-text="item.matchName" placeholder="Search match" md-no-cache="true">
                <md-item-template>
                    <span>{{item.matchName}}</span>
                    <span md-highlight-text="searchText" md-highlight-flags="^i">{{item.matchName}}</span>
                </md-item-template>
                <md-not-found>
                    No matches found.
                </md-not-found>
            </md-autocomplete>
       

			<i class="fa fa-search"></i>
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
          </div>
        </li>
         <li class="nav-item  ">
          <a class="nav-link" ng-click="logout();" href="#">  
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
      <!--<div class="marqueeHead" >
          <div class="moving-div">
          <marquee hspace="0" scrollamount="5" BEHAVIOR="SCROLL" style="font-size:14px;color:#fff;" onmouseover="this.stop();" onmouseout="this.start();">{{diplayMsg}}</marquee>
        </div>
      </div>-->
<aside class="main-sidebar" style="top:95px">
    <section class="sidebar" style="height: auto;">
      <usersidebar></usersidebar>

    </section>
</aside>



    </div>



