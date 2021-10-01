   
  <div class="limiter">
  <style>html, body{ padding-top:0}
  .results {
    padding: 3px;
    display: inline-block;
    height: 10vh;
    min-width: 40vw;
    border-style: solid;
    border-width: 1px;
    border-radius : 3px;
    border-color: grey;
    font-weight: bold;
  }

  .final {
    color: black;
    font-weight: normal;
  }

  .interim {
    color: darkgrey;
  }
  	.downloadbtn{    display: block;
    margin-top: 15px;
    text-align: center;
    color: #fff;
    text-decoration: none;
    padding: 4px 0;}
  </style>

				 <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
        <div class="container-login100">
			
<div class="wrap-login100">
			 <span class="login100-form-logo" style="background:none;"> 
						<i class="zmdi "><img src="app/images/logo.png"></i>
                       
					</span>
                <form class="navbar-form login100-form validate-form" name="userForm" method="post" autocomplete>
					
					<span class="login100-form-title p-b-34 p-t-27">
						Log in 
					</span>
				<div class="login-error" ng-show="showGreeting"><img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/OOjs_UI_icon_alert_destructive.svg">  
                {{message}}                
</div>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						
                          <input type="text" name="username" id="username" class="input100" ng-model="user.username1" required="required" placeholder="Username" autofocus/>
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
                     
						<input class="input100" type="password" name="password" ng-model="user.password1" placeholder="Password" required/>
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>
					<div ng-if="false">
                    					 <div  class="form-group has-feedback" ng-bind-html="image" >    </div><i class="fa fa-refresh" ng-click="RefressCaptcha()"></i>
                                        <div class="wrap-input100 validate-input" ng-class="{ 'has-error' : userForm.captcha.$invalid && !userForm.captcha.$pristine }">
                                         <input type="text" name="captcha" class="input100" ng-model="user.captcha" placeholder="please enter captcha" required/>

                                                   </div>
                                                   </div>

 
	 <input type="hidden" name="device" class="form-control" ng-model="user.device_info" />  
	 <input type="hidden" name="browser" class="form-control" ng-model="user.browser_info" />  
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" ng-click="submitForm(user)" ng-disabled="userForm.$invalid">
							Login  &nbsp; <i class=" ml-2 fa fa-sign-in"></i>
						</button>
					</div>
					
					
					

					<!--<div class="text-center p-t-90">
						<a class="txt1" href="#">
							Forgot Password?
						</a>
					</div>-->
					
					<a href="http://modernexch9.com/app.apk" class="btn btn-lg btn-success downloadbtn btn-block">  Download Apk  <i _ngcontent-rqs-c41="" class="fa fa-android"></i></a>
					
				</form>
				 
				     <button ng-if="false" class="login100-form-btn" ng-speech-recognition-start ng-speech-recognition-end="speech.displayTranscript()">
                                                                                        Press & Talk
                                                                                    </button>
                                                                                     <div ng-controller="speechController as spc" class="results" ng-if="false">
                                                                                                                                                              <span  class="final">
                                                                                                                                                                {{ spc.final }}
                                                                                                                                                              </span>
                                                                                                                                                              <span class="interim">
                                                                                                                                                                {{ spc.interim }}
                                                                                                                                                              </span>
                                                                                                                                                            </div>
                                                                                                                                                            <button ng-controller="speechController as spc" ng-click="spc.start()" ng-if="false">
                                                                                                                                                            Start
                                                                                                                                                            </button>
                  <div class="powerd" style="display: none;"> <img style=" max-width: 130px;margin: 0px auto 0;display: block;position: absolute;left: 0;right: 0;bottom: 15px;" src="app/assets/newscreen/images/powered_by_betfair_light.svg" /></div>
			</div>
		</div>
	</div>
  
  

 
