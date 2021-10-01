<nav  role="navigation" >
  <div class="header-page clearfix" ng-class="usertype == 1 ? 'master-header-page' : (usertype == 2 ? 'dealer-header-page' :(usertype == 3 ? 'user-header-page' :'' ) )">
    <div class="header-top clearfix">
      <div class="box-padd col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
          <div class="head-lft-top">
            <div class="head-search">
              <input type="text" />
              <span class="glyphicon glyphicon-search"></span>
            </div>
            <div class="app-mob">
              <button>Mobile </button>
            </div>
          </div>
          <div class="head-lft-icon">
            <div class="wallet">
              <span class="dropdown" >
                <button type="button" class="btn wallet-btn">
                  <img src="images/wallet-icon.png"/>
                  <span class="glyphicon glyphicon-menu-down"></span>
                </button>
                <label>
                  <input type="checkbox"/>
                  <ul class="drop-lft">
                    <li ui-sref-active="active">
                      <a ui-sref="dashboard.Createmaster">Create Master{{usertype}}</a>
                    </li>
                    <li ui-sref-active="active">
                      <a ui-sref="dashboard.Crtdlercontroller">Create Dealer</a>
                    </li>
                    <li ui-sref-active="active">
                      <a ui-sref="dashboard.Createuser">Create User</a>
                    </li>
                    <li class="divider"></li>
                    <li ui-sref-active="active">
                      <a ui-sref="dashboard.Userlist">User List</a>
                    </li>
                  </ul>
                </label>
              </span>
            </div>
            <div class="home-btn">
              <a href="#/userDashboard">
                <img src="app/images/home-icon.png" />
              </a>
            </div>
          </div>
        </div>
        <div class="logo col-lg-4 col-md-4 col-sm-4 col-xs-6">
          <a href="index.html">
            <img src="app/images/logo_user.png" class="img-responsive"/>
          </a>
        </div>
        <header-notification></header-notification>
      </div>
    </div>
  </div>
  <sidebar></sidebar>
</nav>