


<div class="col-sm-12 col-md-3 rightsidebar account_section">

<div class="load-box ng-hide" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif">
        </div>
  <div>
        <div class="account_overview mobilebox right_account"> 
		
		
	

        
       <div class="dropdown">
       <button class="dropdown-toggle credit" type="button" data-toggle="dropdown">Available Credit:
       <span class="caret">{{$root.Balance}}</span></button>
       <ul class="dropdown-menu">
       <li>Credit Limit: <span  ng-class="{'red':$root.FreeChips<0,'green':$root.FreeChips>0}">{{$root.FreeChips}}</span></li>
       <li>Winning Amount: <span  ng-class="{'red':$root.P_L<0,'green':$root.P_L>0}">{{$root.P_L}}</span></li>
       <li>Total Net Exposure: <span  ng-class="{'red':$root.Liability<0,'green':$root.Liability>0}">{{$root.Liability}}</span></li>
       </ul>
       </div>
      
      
      
      <div class="showpoupbox">
      
      <div class="mobileview banking">  <a onclick="myfunNew()" id="showonclick" > <span class="betshowbtn">Betslip</span> <span class="closebtn">Close</span>   </a>  </div>
      
       <div class="mobilebox1">
      <div class="switch_box">
		  
		  <label class="switch">
			  <input type="checkbox" class="switch-input" ng-model="IsToggle" ng-click="getStakesett(1)"  id="formButton1">
			  <span class="switch-label" data-on="On" data-off="Off"></span>
			  <span class="switch-handle"></span> 
		  </label>
		  <small class='clickbet'> 1 Click Betting </small>
		  
      <form id="form1" name="form1" ng-init="isEdit=true">
       <div class="iner_form">
       <ul>
       <li ng-show="!isEdit" ng-repeat="stack in one_click_stack track by $index" class="form_text">
 <input type="text"   class="form-control"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="" 
ng-model="one_click_stack[$index]" required></li>

<li ng-show="isEdit" ng-repeat="stack in one_click_stack track by $index" class="form_text">
 <button type="button" ng-class="{btn_active:$index==btnActive}" ng-click="setOneClickBetStake(stack,$index)" class="btn btn_default">{{one_click_stack[$index]}}</button></li>

       <li class="form_text" ng-show="isEdit" ng-click="isEdit=false"><div class="form_button">	      <a href="javascript:void(0)">Edit</a>	    </div></li>
      <li class="form_text" ng-show="!isEdit"><div class="form_button"><a class="savebtn" ng-style="{'pointer-evenet:none':form1.$invalid==true}" ng-click="SaveOneClick()" href="javascript:void(0)">Save</a>	    </div>
	<div class="form_button">	      <a ng-click="isEdit=true;CallOnclickSetting()" href="javascript:void(0)">Cancel</a>	    </div>
      </li>

       </ul>
      
      </div>
      
  </form> 
      </div>
      <div class="betsilip_sec">
      <h5>Betslip </h5>
      <div id="exTab1">	
  <ul  class="nav nav-pills">
  <li ng-init="isBetSlip=1">
   
  <a ng-class="{'active':isBetSlip==1}"  ng-click="betslipinfo=true;betinfo=false;isBetSlip=1">Betslip</a>
  </li>
  <li>
  <a  ng-class="{'active':isBetSlip==2}" ng-click="betinfo=true;betslipinfo=false;isBetSlip=2">Open Bets</a>
  </li>
	  <li class="edit_stakes">
		 <span class="form_button"> 
<a href="javascript:void(0)" title="Edit Stakes" data-toggle="modal" data-target="#edit_popup" ng-click="CallOnclickSetting()">Edit Stakes</a> 
	  </span>
	  </li>
  </ul> <div class="edit_stakes">
  <div class="modal" tabindex="-1" role="dialog" id="edit_popup">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"></div>
        <div class="modal-body">
	<form name="stakesettForm">
        <div class="row">
       <div class="col-sm-4" ng-repeat="stakeval in stakesettingData | limitTo :5 track by $index">
        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"  class="form-control" placeholder="" ng-model="stakesettingData[$index]" required>
       </div>
    
      </div>
</form>
        </div>
        <div class="modal-footer text-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="CallOnclickSetting()">Cancel</button>
          <button type="button" class="btn btn-primary" ng-disabled="stakesettForm.$invalid" ng-click="saveMatchoddstake()">Save</button>
        </div>
      </div>
    </div>
  </div>
   </div>
   
      <div class="tab_bg clearfix betslipnew" >                                              
                                                        
  <div class="smg_texfill" ng-repeat="back in BackLayArray" ng-show="betslipinfo == true">
  <div class="sigma_olomouc">
 <span class="error" ng-if="back.IsErrorShow">{{back.Message}}</span>
 <span class="success" ng-if="!back.IsErrorShow">{{back.Message}}</span>
  <label ng-if="back.isback==0">Back</label>
 <label ng-if="back.isback==1" class="before_lay">Lay</label>
  <a href="#">{{back.MatchName}}</a>
  </div>
  <div class="bet_back" ng-class="{'bet_lay':back.isback==1}">
  <span class="selection_name">{{back.placeName}} </span>
  <div class="bet_fields clearfix">
  
  <ul class="odds_tex">
  <li>
    <div class="form-group">
      <label ng-show="back.is_session_fancy=='N'">Odds</label>
      <label ng-show="back.is_session_fancy!='N'">{{back.isback==1 ? 'No':'Yes'}}</label>
      <input ng-if="back.step!=angular.isUndefindOrNull" type="number" ng-change="updateLiability(back)" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" min="0" step="{{back.step}}" ng-model="back.priceVal" class="form-control numclass" ng-disabled="back.is_session_fancy!='N'">
        <input ng-if="back.step==angular.isUndefindOrNull" type="number" ng-change="updateLiability(back)" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" min="0" step="0.01" ng-model="back.priceVal" class="form-control numclass" ng-disabled="back.is_session_fancy!='N'">
	  <span class="error" ng-show="back.isMaxOdds">Max odds is {{config_max_odd_limit}}.</span>
    </div>
  </li>
  <li>
    <div class="form-group" ng-init="back.stake=0">
      <label>Stake</label>
      <input type="number" min="0" class="form-control numclass" ng-change="updateLiability(back)"   placeholder="0" ng-focus="setbtn($index)" ng-model="back.stake" ng-blur="setstacksetng($index)">
      <span class="error" ng-show="back.isError">Please Enter stack.</span>
    </div>
  </li>
  <li>
    <div class="form-group" ng-show="back.is_session_fancy=='N'">
      <label ng-show="(back.isback==0) ">Profit</label>
      <label ng-show="(back.isback==1)">Liability</label>

	<input type="hidden" ng-init="back.p_l=((back.priceVal*back.stake)-back.stake)" ng-model="back.p_l">
	 <span ng-if="back.isManual" ng-init="back.p_l=((back.priceVal*back.stake)-back.stake)">{{((back.priceVal+1)*back.stake)-back.stake | number:2}}</span>
          <span ng-if="!back.isManual" ng-init="back.p_l=((back.priceVal*back.stake)-back.stake)">{{(back.priceVal*back.stake)-back.stake | number:2}}</span>

    </div>
  </li>
     <i ng-click="RemoveBackLay(back.unique_id,back.isback,((back.priceVal*back.stake)-back.stake),back.stake,back.isback,back)" class="fa fa-window-close"></i>
  </ul>
  
  <div class="first_row"  ng-if="setRef==$index">
  <ul>
  <li ng-repeat="betbutton in $root.MatchStack | limitTo :5 track by $index">
 <button  type="button" ng-click="back.isError=false;addStake(((back.priceVal*back.stake)-back.stake),betbutton,back.isback,back)" class="btn num-btns">{{betbutton}}</button></li>
<li><button type="button" ng-click="back.stake=0;back.p_l=0;updateLiability(back)"> Clear</button></li>
  </ul>
  </div>
  </div>
  </div>

  </div>
	<div class="tab_bg">	  
<p ng-show="BackLayArray.length == 0 && betslipinfo == true" style="    margin: 5px;
">Click on the odds to add selections to the betslip.

</p>
 </div>	  
 

  
  <div class="liability" ng-show="BackLayArray.length>0 && betslipinfo == true" >
  <span>Liability: {{$root.total_liability}}</span>
  
  <div class="remove_all text-right">
  <ul>
  <li><a href="javascript:void(0)" ng-click="layArray=[];backArray=[];BackLayArray=[];$root.total_liability=0;clearAll();">Remove All</a></li>
  <li><a href="javascript:void(0)" ng-class="{'clsActive':isActive}" ng-click="Place_bet()">Place bets</a></li>
  </ul>
  </div>
  
  
  <li><span>
  <input type="checkbox" ng-click="update_confirmation_setting(ischeckconfirmval)" ng-model="ischeckconfirmval" ng-checked="$root.ischeckconfirm == 'Y'?true:false" id="test2" >
  <label for="test2">Confirm bets before placing </label>
  </span></li>
  </div>
 </div>
 
  
  
  
  
  
  
  </div>
	  
  <div class="tab-pane tab_bg" id="2a">
  <p>
  <li ng-init="IsShowBetInfo=true" ng-show="betinfo == true"><span>
  <input checked="" name="checkbox-group" ng-model="IsShowBetInfo" id="test1" type="checkbox">
  <label for="test1">Show bet Info</label>
  </span></li>
  
  <div class="show_betinfo" ng-show="betinfo == true">
              <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default" ng-if="isconfirmbet=='Y'">
                      <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                              <a role="button" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                  Unmatched Bets ({{(UserData | filter : {"IsMatched":"0"}).length}})
                              </a>
                          </h4>
                      </div>
                      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
            <div class="fancy-collapse-panel">
                    <div class="panel panel-default match">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h3 class="panel-title">UnMatched Bets ({{(UserData | filter : {"IsMatched":"0"}).length}})</h3>
                        </div>
                        <div class="panel-body unmatchedScrollTable" ng-show="IsShowBetInfo" ng-if='(UserData | filter : {"IsMatched":"0"}).length>0'>
                            <table class="table betslip-table">

                                <tbody>
                                	<thead>
                                                                                        <tr>
                                                    					  <th></th>
                                                                                            <th>Runner</th>
                                                                                            <th ng-if="USERTYPE==0">Master</th>
                                                                                            <th ng-if="USERTYPE==0 || USERTYPE==1">Dealer</th>
                                                                                            <th ng-if="USERTYPE==0 || USERTYPE==1 ||USERTYPE==2">Client</th>
                                                                                            <th>odds </th>
                                                                                            <th>stack</th>
                                                                                            <th>P&l</th>
                                                                                        </tr>
                                                                                    </thead>
                                    <tr ng-repeat='unMatchedData in (UserData | filter : {"IsMatched":"0"})' ng-class="{'bet_lay': unMatchedData.isBack == 1 , 'bet_back': unMatchedData.isBack == 0}">
<td><span class="fa fa-trash" ng-click="deleteUser(unMatchedData.MstCode,unMatchedData.UserId)"></span></td>
					 <td>{{unMatchedData.selectionName}}<br>
                                                                                             {{unMatchedData.MstDate}}
                                                                                                      </td>
                                                            <td ng-if="USERTYPE==0">{{unMatchedData.MasterName}}</td>
                                                            <td ng-if="USERTYPE==0 || USERTYPE==1">{{unMatchedData.ParantName}}</td>
                                                            <td ng-if="USERTYPE==0 || USERTYPE==1 ||USERTYPE==2">{{unMatchedData.userName}}</td>
                                                            <td>{{OddReturn(unMatchedData.Odds)}}</td>
                                                            <td>{{unMatchedData.Stack}}</td>
                                                             <td>{{unMatchedData.P_L}}</td>


					</tr>
<tr>
                                         <td colspan="7">  {{unMatchedData.MstDate}}</td>
                                                                                                                        </tr>
					</tbody>
					</table>
					</td>

                                    </tr>
                                      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                             
                          </div>
                      </div>
                  </div>
                  <div class="panel panel-default">
                      <div class="panel-heading" role="tab" id="headingTwo">
                          <h4 class="panel-title">
                              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                  Matched Bets ({{(UserData | filter : {"IsMatched":"1"}).length}})
                              </a>
                          </h4>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <div class="fancy-collapse-panel">
                    <div class="panel panel-default match">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h3 class="panel-title">Matched Bets ({{(UserData | filter : {"IsMatched":"1"}).length}})</h3>
                        </div>
                        <div class="panel-body unmatchedScrollTable"  ng-show="IsShowBetInfo" ng-if='(UserData | filter : {"IsMatched":"1"}).length>0'>
                            <table class="table betslip-table">

                                <tbody>
                                  <thead>
                                                                                                               <tr>
                                                                                                                   <th>Runner</th>
                                                                                                                   <th ng-if="USERTYPE==0">Master</th>
                                                                                                                   <th ng-if="USERTYPE==0 || USERTYPE==1">Dealer</th>
                                                                                                                   <th ng-if="USERTYPE==0 || USERTYPE==1 ||USERTYPE==2">Client</th>
                                                                                                                   <th>odds </th>
                                                                                                                   <th>stack</th>
                                                                                                                   <th>P&l</th>
                                                                                                               </tr>
                                                                                                           </thead>
                                    <tr ng-repeat='unMatchedData in (UserData | filter : {"IsMatched":"1"})' ng-class="{'bet_lay': unMatchedData.isBack == 1 , 'bet_back': unMatchedData.isBack == 0}">
                                    <td>{{unMatchedData.selectionName}} <br>
                                     {{unMatchedData.MstDate}} <span class="red" ng-if="unMatchedData.void==1"> <br>-> Void</span>
                                    </td>
                                                    <td ng-if="USERTYPE==0">{{unMatchedData.MasterName}}</td>
                                          <td ng-if="USERTYPE==0 || USERTYPE==1">{{unMatchedData.ParantName}}</td>
                                              <td ng-if="USERTYPE==0 || USERTYPE==1 ||USERTYPE==2">{{unMatchedData.userName}}</td>
                                                                                                                     <td>{{OddReturn(unMatchedData.Odds)}}</td>
                                                                                                                     <td>{{unMatchedData.Stack}}</td>
                                                                                                                     <td>{{unMatchedData.P_L}}</td>
                                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              </div>
                      </div>
                  </div>
              </div>
  </div>
  
  </p>
  
  
  </div>
  </div>
  
	  	</div>
	  </div>
  
    </div>
 
          
      </div>
      </div>

