<div class="login-box">
  <div class="login-logo" ng-init="user={username1:'',password1:''}">
   <!--  <img src="app/images/CricExchange.png" style="width:100%!important;"> -->
                Betdip        
  </div>                                                                                            
  <!-- FORM -->
<div class="login-box-body">
 <span class="login-error" ng-hide="myquestion(myAnswer) || !myAnswer" >{{message}}</span>
                                <p class="login-box-msg" ng-show="myquestion(myAnswer) && myAnswer">Please Login to Continue</p>
  <form name="userForm" method="post" autocomplete>
<div class="form-group has-feedback" ng-class="{ 'has-error' : userForm.username.$invalid && !userForm.username.$pristine }">
                    <input type="text" name="username" id="username" class="form-control" ng-model="user.username1" required="required" placeholder="Username" autofocus/>       
                          <!--  <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
                <center> <span style="color:red; text-align:left; font-weight:bold;" ng-show="userForm.username.$error.required && !userForm.username.$pristine"> Username is required.</span></center>
                </div>
<div class="form-group has-feedback" ng-class="{ 'has-error' : userForm.password.$invalid && !userForm.password.$pristine }">
                    <input type="password" name="password" class="form-control" ng-model="user.password1" placeholder="Password" required/>  
                            <!--   <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                  <center><span style="color:red;text-align:left; font-weight:bold;"  ng-show="userForm.password.$error.required && !userForm.password.$pristine"> Password is required.</span></center>
                </div>
<div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary" ng-click="submitForm(user)" ng-disabled="userForm.$invalid">
          <span class=" glyphicon glyphicon-log-in"></span>&emsp;Login</button>
               </div>
                </div>
  </form>
</div>
</div>
<div class="container-fluid margn_tp clearfix">

<div class="col-lg-6">
<div class="sport_box">
            <i><center><img alt="icon" src="app/dist/img/cricket_icon.png"></center></i>
            <h3>Cricket</h3>
            <div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                <tbody>
		<tr ng-if="(sportDetail | filter:{ SportId: 4 }).length>0" ng-show="isShow4">
		<td></td>
		<td style="text-align:center"> 
		1
		</td>
		<td style="text-align:center"> 
		X
		 </td>
               <td style="text-align:center"> 
		2
		</td>
		</tr>
               <tr ng-repeat="match in sportDetail | filter:{ SportId: 4 }" ng-show='isShow4=od.status=="OPEN"'>
                <td><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}} --{{isShow4}}
                  <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span> </a>  <span style="color: rgb(221,44,0);font-weight: 600;">{{match.MstDate | date : 'EEEE HH:mm'}}</span> </td>
		<td  ng-init="checkStaus(4,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'> 
		<a class="td_btn blue_btn">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
                <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
		<td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
		<a class="td_btn pink_btn">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a> 
		 </td>
                <td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow4=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
		 <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
		</tr>
                   </tbody>
                </table>
		<div class="nofound" ng-show="!isShow4">
			No record found
		</div>
             </div>
          </div>
</div>

<div class="col-lg-6">
<div class="sport_box">
            <i><center><img alt="icon" src="app/dist/img/soccer_icon.png"></center></i>
            <h3>Soccer</h3>
            <div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                <tbody>
		<tr ng-if="(sportDetail | filter:{ SportId: 1 }).length>0" ng-show="isShow1">
		<td></td>
		<td style="text-align:center"> 
		1
		</td>
		<td style="text-align:center"> 
		X
		 </td>
               <td style="text-align:center"> 
		2
		</td>
		</tr>
               <tr ng-repeat="match in sportDetail | filter:{ SportId: 1 }"  ng-show='isShow1=od.status=="OPEN"'>
                <td><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}} 
                  <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span> </a>  <span style="color: rgb(221,44,0);font-weight: 600;">{{match.MstDate | date : 'EEEE HH:mm'}}</span> </td>
		<td ng-init="checkStaus(1,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'> 
		<a class="td_btn blue_btn">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
                <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
		<td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
		<a class="td_btn pink_btn">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a> 
		 </td>
                <td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow1=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
		 <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
</tr>
      	          </tbody>
                </table>
<div class="nofound" ng-show="!isShow1">
			No record found
		</div>
             </div>
          </div>
</div>


<div class="col-lg-6">
<div class="sport_box">
            <i><center><img alt="icon" src="app/dist/img/tennis_icon.png"></center></i>
            <h3>Tennis</h3>
            <div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                <tbody>
		<tr ng-if="(sportDetail | filter:{ SportId: 2 }).length>0" ng-show="isShow2">
		<td></td>
		<td style="text-align:center"> 
		1 
		</td>
               <td style="text-align:center"> 
		2
		</td>
		</tr>
                     <tr ng-repeat="match in sportDetail | filter:{ SportId: 2 }">
                <td ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}} 
                  <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span> </a>  <span style="color: rgb(221,44,0);font-weight: 600;">{{match.MstDate | date : 'EEEE HH:mm'}}</span> </td>
                <td ng-init="checkStaus(2,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'> 
		<a class="td_btn blue_btn">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
                <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
		<td ng-show="false"> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
		<a class="td_btn pink_btn">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a> 
		 </td>
                <td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow2=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
		 <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
                </tr>

                </tbody>
                </table>
<div class="nofound" ng-show="!isShow2">
			No record found
		</div>
             </div>
          </div>
</div>

<div class="col-lg-6">
    <div class="sport_box">
            <i><center><img alt="icon" src="app/dist/img/horse_icon.png"></center></i>
            <h3>Horse Racing </h3>
        <div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                <tbody>
	<tr ng-if="(sportDetail | filter:{ SportId: 7 }).length>0" ng-show="isShow7">
		<td></td>
		<td style="text-align:center"> 
		1
		</td>
		<td style="text-align:center"> 
		X
		 </td>
               <td style="text-align:center"> 
		2
		</td>
		</tr>
                      <tr ng-repeat="match in sportDetail | filter:{ SportId: 7 }"  ng-show='isShow7=od.status=="OPEN"'>
                <td><img src="app/dist/img/greendot.png" alt="img"> <a href="">{{match.matchName}}  {{match.HeadName}} 
                  <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span> </a>  <span style="color: rgb(221,44,0);font-weight: 600;">{{match.MstDate | date : 'EEEE HH:mm'}}</span> </td>
		<td ng-init="checkStaus(7,od.status)"  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow7=od.status=="OPEN"'> 
		<a class="td_btn blue_btn">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
                <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
		<td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow7=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a> 
		<a class="td_btn pink_btn">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a> 
		 </td>
                <td  ng-if="od = (oddsDetail|filter:{'marketId':match.marketid})[0]" ng-show='isShow7=od.status=="OPEN"'> <a class="td_btn blue_btn">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
		 <a class="td_btn pink_btn">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>  
		</td>
</tr>
		
                </tbody>
            </table>
<div class="nofound" ng-show="!isShow7">
			Coming Soon
		</div>
        </div>
    </div>
</div>
</div>
