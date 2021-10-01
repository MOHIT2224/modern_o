<style>
  html, body{ height:auto !important;}
</style>


<div class="login-cont">
  <div class="login-logo" ng-init="user={username1:'',password1:''}">
    <img src="<?php base_url();?>app/images/logo_user.png" />
  </div>
  <!-- FORM -->
  <form name="userForm" method="post">
    <div class="login-form">
      <span class="login-error" >{{message}}</span>
      <div class="inp-box">
        <label>User Name</label>
        <input type="text" name="username" id="username" class="login-input" ng-model="user.username1" required="required" autofocus/>
      </div>
      <div class="inp-box">
        <label>Password</label>
        <input type="password" name="password" class="login-input" ng-model="user.password1" required="required"/>
      </div>
      <div>
        <button type="submit" class="btn blue-btn" ng-click="submitForm(user)">Sign In</button>
        <a href="<?= base_url()?>app/apk/PlaceBet365_1.apk" class="download-apk-btn" style="padding:5px 10px; float:right; font-size:14px; margin-top:4px;">
          <span class="glyphicon glyphicon-download-alt"></span> APK
        </a>
        <a href="<?= base_url()?>app/apk/PlaceBet365_nougat.apk" class="download-apk-btn" style="padding:5px 10px; float:right; font-size:14px; margin-top:4px; margin-right:6px;">
          <span class="glyphicon glyphicon-download-alt"></span> APK(Nougat)
        </a>
      </div>
    </div>
  </form>
</div>
