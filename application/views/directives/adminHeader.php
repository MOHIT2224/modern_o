<div class="main-header">
<!--<nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
     
        <div class="container-fluid">
       <a class="navbar-brand js-scroll-trigger" href="index.html"><img src="{{$root.logo}}" alt="Logo"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#accordion" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
       <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto pull-right">
          <li>
                    <p class="last-login">Logged in as dixit1234</p>
                    <p class="last-login">Last logged in: 
                        <time class="">14/08/2018 10:53</time>
                    </p>
                </li>
                <li>  <a class="nav-link" href="#">   <i class="fa fa-question-circle" aria-hidden="true"></i> Help  </a> </li> 
                
        <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-cog" aria-hidden="true"></i> Settings  </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
         
            <a class="dropdown-item " href="#">Change Password</a>
          </div>
        </li>
        
        <li class="nav-item  ">
          <a class="nav-link" href="#">  
            <i class="fa fa-sign-out"></i> Logout
          </a>
          
        </li>
      </ul>
        </div>
       </div>
    
    </nav>-->
    
    
 
<nav class="navbar navbar-expand-lg navbar-dark">



      <div class="container-fluid">
      <a class="navbar-brand js-scroll-trigger" ui-sref="dashboard.Home"><img src="{{$root.logo}}" alt="Logo"></a>
     <!--   <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#accordion" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
       <i class="fa fa-bars"></i>
        </button>-->
       <div class="collapse navbar-collapse" id="navbarResponsive">
       <div class="col-sm-3 col-xs-6">
                <div class="navbar-header ">
                 <a id="mobileDemo" onclick="myfun()" class="button-collapse"><i class="fa fa-bars"></i></a>
                 <!-- <a class="navbar-brand visible-xs"><img src="app/images/CricExchange.png" alt="logo"></a>
                  <a class="navbar-brand hidden-xs"><img src="app/images/CricExchange.png" alt="logo"></a> -->
                  
                 
            <!-- <a id="mobileDemo" onClick="myfun()" class="button-collapse"><i class="fa fa-bars"></i></a>-->
              <div class="status-icon new_topdowm" ng-if="usertype == 0">
                <span>
                  <a ng-if="false" class="status-btn" href="javascript:void()" ng-click="displayFancyTest1();FancyListDisplay()">
                    <img src="app/images/sportsicon/Cricket.png"/></a>

                  <ul class="dropdown-stat" ng-show="showvalue==true">

                    <button type="button" class="ref-stat" ng-click="FancyListDisplay()">
                      <span class="glyphicon glyphicon-retweet"></span>
                    </button>

                    <div class="close-stat">
                      <span class="close glyphicon glyphicon-remove" ng-click="HideFancyDiv();" data-dismiss="dropdown-stat"></span>
                    </div>
                    <li>
                      <div class="resp_table2">
                        <table class="table">
                          <thead>
                            <tr>
                              <td>Sno</td>
                              <td>MatchName</td>
                              <td>FancyName</td>
                              <td>
                                Status :-

                                <span class="stat-head">
                                  <img src="app/images/active.png"/>Active
                                  <img src="app/images/inactive.png"/>Inactive
                                  <img src="app/images/suspend.png"/>Suspend
                                </span>
                              </td>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- {{GetfancyList1}} -->
                            <tr ng-repeat="fancy in GetfancyList1">
                              <td data-label="Sno">{{$index+1}}</td>
                              <td data-label="MatchName">{{fancy.matchName}}</td>
                              <td data-label="FancyName">{{fancy.HeadName}}</td>
                              <td data-label="Status" class="status-cont">
                                <input type="radio" name="{{fancy.ID}}" value="1" ng-checked="fancy.active == 1" ng-click="getFancyStatus(fancy.ID,1)"  />
                                <img src="app/images/active.png"/>
                                <input type="radio" name="{{fancy.ID}}" value="0" ng-checked="fancy.active == 0" ng-click="getFancyStatus(fancy.ID,0)" />
                                <img src="app/images/inactive.png"/>
                                <input type="radio" name="{{fancy.ID}}" value="2" ng-checked="fancy.active == 2" ng-click="getFancyStatus(fancy.ID,2)" />
                                <img src="app/images/suspend.png"/>
                                <input type="text" id="result1_{{fancy.ID}}" value="{{fancy.result}}" name="result" style="width:90px;"  ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1" />

                                <div ng-if="fancy.TypeID==1" class="even_oddpopup" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.EditFancy==1">
                                  <a href="" class="result-btn" ng-click="getFancyUpDown(fancy.SportID,fancy.MatchID,fancy.ID,fancy.TypeID)" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1">Result</a>
                                  <a href="" class="edit-btn" ng-click="editOddEvenFancy(fancy.ID,fancy.TypeID,fancy.matchName,fancy.SportID,fancy.HeadName);HideFancyDiv();">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                  </a>
                                </div>
                                <span ng-if="fancy.TypeID==2" ui-sref-active="active" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.EditFancy==1">
                                  <a href="" class="result-btn" ng-click="getFancyResult(fancy.SportID,fancy.MatchID,fancy.ID)" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1">Result</a>
                                   <a href="" class="edit-btn" ui-sref="dashboard.Editfancycntr({FancyID:fancy.ID,TypeID:fancy.TypeID,MatchName:fancy.matchName,SportID:fancy.SportID,MatchID:fancy.MatchID,is_indian_fancy:fancy.is_indian_fancy,fancy_mode:fancy.fancy_mode,MarketId:fancy.market_id,mFancyId:fancy.mFancyId})" ng-click="HideFancyDiv();">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                  </a>
                                </span>
                                <span ng-if="fancy.TypeID==3 || fancy.TypeID==4" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.EditFancy==1">
                                  <a href="" class="result-btn" ng-click="getFancyUpDown(fancy.SportID,fancy.MatchID,fancy.ID,fancy.TypeID)" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1">Result</a>
                                  <a href="" class="edit-btn" ng-click="editFancy(fancy.ID,fancy.TypeID,fancy.matchName,fancy.SportID,fancy.HeadName)">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                  </a>
                                </span>
                                <span ng-if="fancy.TypeID==5" ui-sref-active="active" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.EditFancy==1">
                                  <a href="" class="result-btn" ng-click="getFancyUpDown(fancy.SportID,fancy.MatchID,fancy.ID,fancy.TypeID)" ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1">Result</a>
                                  <a href="" class="edit-btn" ui-sref="dashboard.EditUpdown({FancyID:fancy.ID,TypeID:fancy.TypeID,MatchName:fancy.matchName,SportID:fancy.SportID})" ng-click="HideFancyDiv();">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                  </a>
                                </span>
                              </td>
                            </tr>
                          </tbody>

                        </table>
                      </div>

                    </li>

                  </ul>


                </span>
              </div>
              <div class="status-icon" ng-if="usertype != 0 && displayFicon==true">
                <span>
                  <button type="button" class="fancy-blink-btn blink" name="changerate" ng-click="displayFancyTest1();">F</button>
                  <ul class="dropdown-stat" ng-show="showvalue==true">
                    <div class="close-stat">
                      <span class="glyphicon glyphicon-remove" ng-click="closeBlink(FancyIcon[0].ID);"></span>
                    </div>
                    <li class="fancy-blink-li">
                      <div class="resp_table2">
                          <span class="fancy-head">{{FancyIcon[0].HeadName}} >></span> {{FancyIcon[0].matchName}}
                          <button type="button" class="goto-fancy" ng-click="RedirectToFancy(FancyIcon[0].ID,FancyIcon[0].TypeID,FancyIcon[0].MatchID,FancyIcon[0].SportID,FancyIcon[0].matchName);">Go To Fancy</button>                  
                      </div>
                    </li>
                  </ul>
                </span>
              </div>

                 <!--  <a class="navbar-brand visible-xs" ui-sref="dashboard.Home"><img src="app/images/CricExchangeSmall.png" alt="logo"></a>
                  <a class="navbar-brand hidden-xs" ui-sref="dashboard.Home"><img src="app/images/CricExchange.png" alt="logo"></a> -->
          
      
                  
                  
                  
              </div>
            </div>
         <div class="navbar-custom-menu">
         <header-notification></header-notification>
         
         
        </div>
        
          
           
            
     
       
    </div>
    
    
    
    
    
  </div>
</nav>

<aside class="main-sidebar" style="top:100px">
    <section class="sidebar" style="height: auto;">
    <sidebar></sidebar>
    </section>
</aside>
  </div>
  


  

  



