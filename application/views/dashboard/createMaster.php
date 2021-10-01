
  <div class="col-sm-9 col-md-10 master-cont">
      <h2 style="text-align: center; font-size: 28px; margin-bottom: 15px;">Create Master</h2>
      <div class="row">
          <div class="clearfix creat_form">
            <p ng-show="message" style="text-align: center; color:#FE5454; font-size: 18px; margin-bottom: 15px;">{{message}}</p>
               <!--  <span ng-show="message" class="error"></span> -->
              <form name="userForm" ng-submit="submitForm()">

                <div class="form_row col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                  <div class="form_box  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Master Name</label>
                   <input type="text" name="master_name" class="input-box" ng-model="master_name" required="required" >
                </div>
                  <div class="form_box  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label >Registration Date</label>
                    <input type="text" name="master_date" ng-model="FromDate" date-format="yyyy-MM-dd" >
                    <!-- <input id="date" name="date" placeholder="YYYY-MM-DD" type="text"/> -->
                    

                  </div>
                </div>

                        
                <div class="form_row col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                  <div class="form_box  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>User ID</label>
                     <input type="text" name="username" class="input-box" ng-model="username" ng-change="checkUserName()" >
                     <!-- {{errorMsg}} -->
                     <p ng-show="return checkUserName();" style="text-align: center; color:#FE5454; font-size: 18px; margin-bottom: 15px;">{{errorMsg}}</p>
                  </div>
                  <div class="form_box  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label >Password</label>
                    <input type="password" name="password" class="input-box" ng-model="password" >
                  </div>
                </div>
                                     
                <div class="form_row col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                
                <div class="form_box  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label >Remark</label>
                  <textarea name="remarks" ng-model="remarks"  class="input-box"></textarea>
                </div>
              </div>
                        
              <div class="form_row col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                <div class="form_box  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              
              
          </form>
                    
                      
            </div>
      </div>
  </div>




  