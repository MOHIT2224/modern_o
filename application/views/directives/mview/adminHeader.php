
 
<div class="main-header">
    
    
        <div class="headernew">
   

  
  <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
      <button  onclick="myfun()" class="navbar-toggler navbar-toggler-left" type="button">          
          <i class="fa fa-bars"></i>
        </button>
        
        
<a class="navbar-brand js-scroll-trigger" ui-sref="masterDashboard.Home"><img src="app/images/logo.png"><!--<img ng-if="false" src="{{$root.logo}}" alt="Logo">Demo_New--></a>


   <div class="status-icon new_topdowm" ng-if="usertype == 0">
                <span>
                  <a class="status-btn" href="javascript:void()" ng-click="displayFancyTest1();FancyListDisplay()">
                    <img src="app/images/sportsicon/Cricket.png"/></a>

                  <ul class="dropdown-stat" ng-show="showvalue==true">

                    <button type="button" class="ref-stat" ng-click="FancyListDisplay()">
                     <i class="fa fa-retweet" aria-hidden="true"></i>
                    </button>

                    <div class="close-stat">
                    <i class="fa fa-times" ng-click="HideFancyDiv();" data-dismiss="dropdown-stat" aria-hidden="true"></i>
                  <!--    <span class="close glyphicon glyphicon-remove" ng-click="HideFancyDiv();" data-dismiss="dropdown-stat"></span>-->
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
                                <input type="text" id="result_{{fancy.ID}}" value="{{fancy.result}}" name="result" style="width:90px;"  ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.Result==1" />

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
 
  <md-menu md-position-mode="target-right target" md-offset="0 58">
            
            
                    <md-button onClick="addClass(this)" ui-sref="dashboard.anctStatementCntr"><span class="drp-txt">Account Statement</span></md-button>	 
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Home"><span class="drp-txt">My Market</span></md-button>
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Profitlosscntr"><span class="drp-txt">Profit & Loss</span></md-button>
                    <md-button onClick="addClass(this)" ui-sref="dashboard.changePasswordUser"><span class="drp-txt">Change Pwd</span></md-button>
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Onlineusersctrl"><span class="drp-txt">Online Users</span></md-button>
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Get_bethistryCntr"><span class="drp-txt">Bet History</span></md-button>
													                           
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Userlist"><span class="drp-txt">User List</span></md-button>                           
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Get_actMtchUsersCntr"><span class="drp-txt">Active Users</span></md-button>  
                    <span ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChipHistory==1">
                    <md-button onClick="addClass(this)" ui-sref="dashboard.Chiphistorycntr"><span class="drp-txt">Balance History</span></md-button>
              

                    <span ng-show="$root.HelperAllRights==angular.isUndefinedOrNull || $root.HelperAllRights.ChipHistory==1">
                    <md-button onClick="addClass(this)" ui-sref="dashboard.newChipHistory" ><span class="drp-txt">Balance Summary</span></md-button>
                    
        </md-menu>
    
 
 </div>
 
 

 
 
 
</div>

 
 </div>
 
   <a class="logout" ng-click="logout();" href="javascript:void(0)">  
            <i class="fa fa-sign-out"></i> Logout
          </a>
 </div>
 
 
 
<aside class="main-sidebar" >
    <section class="sidebar" style="height: auto;">
    <sidebar></sidebar>
    </section>
</aside>
  </div>
  


  

  



