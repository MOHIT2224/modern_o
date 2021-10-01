
 <div class="sidebar" id="navmob" ng-init="accordionLv2=0">
  
  
  <nav id="menu2">
<ul ng-if="false">
    <li><a ui-sref="userDashboard.OnePageRprt({typeId:1})">One page report</li> 
    <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/star.png"> Favourites</a></li> 
      
      <li class="submenu"><a href="javascript:void(0)"> <span> <img src="app/assets/newscreen/images/cricket.png"> Cricket </span></a>
 <ul class="lav1">
 <li class="prv lv1"> <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
   <li class="lv1"><a href="favourites.html">Caribbean Premier League</a></li>
   <li class="submenu"><a href="javascript:void(0)"> <span>T20 Blast</span> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
   
     <ul class="lav2">
      <li class="prv lv2">  <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
     <li class="lav2"><a href="javascript:void(0)">Birmingham Bears v Lancashire</a></li>
     <li class="lav2"><a href="javascript:void(0)">Gloucestershire v Sussex</a></li>
     <li class="lav2	"><a href="javascript:void(0)">Kent v Somerset</a></li>
   
     </ul>
     
                     </li>
   
   <li class="lv1"><a href="#/about/team/development">Development</a></li>
 </ul>
</li>
             
               <li> <a href="javascript:void(0)"> <span><img src="app/assets/newscreen/images/football.png"> Football</span></a>
 <ul>
   <li><a href="#/about/team/management">Management</a></li>
   <li><a href="#/about/team/sales">Sales</a></li>
   <li><a href="#/about/team/development">Development</a></li>
 </ul>
</li>                           
             

<li><a href="javascript:void(0)"> <span><img src="app/assets/newscreen/images/tennis.png"> Tennis</span></a>


<ul>
<li><a href="#/about/address">Our address</a></li>
</ul>
</li>

</ul>

<ul>
<li><a ui-sref="userDashboard.OnePageRprt({typeId:1})"><i style="float: left;position: relative;" class="fa fa-file" aria-hidden="true"></i>  One Page Report</a> </li>
 <li><a href="javascript:void(0)" ui-sref="userDashboard.Favorite"> <img src="app/assets/newscreen/images/star.png"> Favourites</a></li>
<li id="si{{displyData.id}}" class="submenu" ng-repeat="displyData in sprtData | filter : {active:1} | orderBy : 'name'" ng-if="displyData.id !=7">

  <a href="javascript:void(0)" ui-sref="userDashboard.Menu({'SportType': displyData.id,'SeriesId':0})" ng-click="$root.selectedRow='';resetCall(displyData.id);ShowHideAng1(displyData.id,0);"> 
    <span> <img src="app/assets/newscreen/images/{{displyData.name}}.png"> {{displyData.name}} </span></a>
    
    
  <ul class="lav{{displyData.id}}" ng-show="displyData.id==selectedSport" ng-if="false">
  <li class="prv lv{{displyData.id}}" ng-click="previous(displyData.id)" ng-show="GetSeriesData.length>0"> <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
   <li id="s2{{displyData.id}}" class="lv{{displyData.id}}" class="submenu" ng-repeat="series in GetSeriesData">
  
<a ng-click="resetCall2(displyData.id);getSeriesMatch(displyData.id,series.seriesId)" href="javascript:void(0)">{{series.Name}}  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
   <ul class="lav2{{displyData.id}}" ng-show="accordionLv2==0 || accordionLv2==series.seriesId">
      <li class="prv lv2{{displyData.id}}" ng-click="previous2(displyData.id);" ng-show="GetMatchData.length>0">  <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
     <li class="lav2{{displyData.id}}" ng-repeat="series in GetMatchData"><a onclick="myfun()" ng-click="$root.selectedRow=$index+'main';accordionLv13=series.MstCode;setClickedRow($index+'sub',series)" href="javascript:void(0)">{{series.matchName}}</a></li>
     </ul>
</li>
  </ul>
  </li> 
 
   <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/teenpatti.png">  Live Teen Patti </a><li>
    <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/pro.png">  Pro Kabbadi  </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/horse.png">  Horse Racing  </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/grey.png">  Greyhound Racing </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/poker.png">   Live Poker  </a><li>
 <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/anderbhar.png">    Andar Bahar  </a><li>  
</ul>

			</nav>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
  <section class="middle_blk_user" ng-if="false">
    <div class="container-fluid">
      <div class="middle_left_user">
        <div class="panel-group" id="accordion">
          <div class="panel panel-default"   ng-repeat="displyData in sprtData | orderBy : 'name'" ng-if="displyData.id != 7 || displayData.active!=0">
            <div class="panel-heading" >
              <h4 class="panel-title">
<a id="#sp{{displyData.id}}" class="accordion-toggle" ng-click="$root.selectedRow='';resetCall();getSeriesMatch(displyData.id, 0);"  data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);#{{displyData.id}}"><i class="lnavicon"><img src="app/images/sportsicon/{{displyData.name}}.png"></i>&emsp; {{displyData.name}} </a> </h4>
            </div>
            <div id="{{displyData.id}}" class="panel-collapse collapse">
              <div class="panel-body">
               <ul class="treeview-menu">
<li class="not" ng-repeat="series in GetMatchData" ng-show="accordionLv1==0 || accordionLv1==series.MstCode">
              <a href="javascript:;" id="MId{{series.MstCode}}" ng-class="{'m_active':$index+'main' == $root.selectedRow || SelectedMId==series.MstCode}" ng-click="$root.selectedRow=$index+'main';accordionLv13=series.MstCode;setClickedRow($index+'sub',series)">
                 {{series.matchName}}<!-- || {{series.active}}  --><i class="fa fa fa-chevron-right pull-right" id="arow">

</i>
              </a>

            </li>
		</ul>
         
              </div>
            </div>
          </div>
          
         
        </div>
      </div>
</div>
</section>








      <section id="nav-1" ng-if="false">
        <nav id="main-nav">
          <h2 class="title" ui-sref="userDashboard.Home">Home <a href="javascript:;">
               <span class="home-icon pull-right" id="menu-home"></span></a></h2>
          <ul class="ul" id="main-menu" >
            <li ng-repeat="displyData in sprtData" id="{{displyData.id}}" ng-if="displyData.id !=7">
              <a href="javascript:;" ui-sref-active="active" ui-sref="userDashboard.{{displyData.name}}MatchLst" ng-click="displaysubmenu(displyData.id);getSeriesMatch(displyData.id, 0);">
                <img src="app/images/sportsicon/{{displyData.name}}.png">{{displyData.name}}
		<input type="hidden" value="{{displyData.id}}" id="SportType"/>
              </a>
            </li>
            <!-- <li ng-repeat="displyData in sprtData" id="{{displyData.id}}" ng-if="displyData.id ==7">
              <a href="javascript:;" ui-sref-active="active" ui-sref="userDashboard.HorseMatchLst" ng-click="displaysubmenu(displyData.id);getSeriesMatch(displyData.id, 0);">
                <img src="app/images/sportsicon/{{displyData.name}}.png">{{displyData.name}}
              </a>
            </li>  -->           
          </ul>
        </nav>
      </section>
      
      <!--=======SLID-NAV 3============-->            
      <section ng-repeat="displyData in sprtData"  class="nav-2" id="nav-2" ng-if="false">
        <nav class="main-nav" id="{{displyData.id}}-sub-nav">
          <h2 class="title" ui-sref="userDashboard.Home">{{displyData.name}}<!-- ||{{displyData.id}} --> <a href="javascript:;">
           <span class="home-icon pull-right" id="menu-home"></span></a></h2>
          <ul class="ul">
            <li class="back">
              <a href="javascript:;" ng-click="backButton()">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i>Back    
              </a>
            </li>
            <li class="not" ng-repeat="series in GetMatchData">
              <a href="javascript:;" ng-click="oddsdisplay(series.matchName);getMatchMarket(displyData.id,series.MstCode,series.matchName)">
                 {{series.matchName}} <!-- || {{series.active}}  --><i class="fa fa fa-chevron-right pull-right" id="arow"></i>
              </a>
            </li>
          </ul>
        </nav>
      </section>    
      <!--=======SLID-NAV 4============-->
      <section class="nav-4" id="nav-4" ng-if="false">
        <nav class="main-nav" id="not-sub-nav">
          <h2 class="title" ui-sref="userDashboard.Home">{{MatchName}} <a href="javascript:;">
               <span class="home-icon pull-right" id="menu-home"></span></a></h2>
          <ul class="ul">
            <li class="sub-back">
              <a href="javascript:void(0)">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i>Back                
              </a>
            </li>
            <li onclick="mobilefun()"  ng-repeat="market in MatchMarket">
              <a href="javascript:;" ui-sref="userDashboard.Matchodds({MatchId: market.matchId,MarketId:market.Id,matchName:MatchName,date:market.createdOn,sportId:market.sportsId})" >
                 {{market.Name}} <!-- || {{market}} -->
              </a>
            </li>
            <li ng-repeat="fancyArr in getMatchFancy">
              <div  ng-if="fancyArr.TypeID==1 && fancyArr.IsPlay==0" ui-sref-active="active" >
                     <!-- <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)" ng-show="sessionusetype!=3">
                     </a>
                     <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-show="sessionusetype==3">
                     </a>
                    <a class="match-od-link" ui-sref="dashboard.Evenoddfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> -->
                    <a href="">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==2 && fancyArr.IsPlay==0" ui-sref-active="active">
                      <!-- <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)" ng-show="sessionusetype!=3">
                      </a>
                     
                    <a class="match-od-link" ui-sref="dashboard.Sessionfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> -->
                     <a href="">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==3 && fancyArr.IsPlay==0" ui-sref-active="active">
                    <!-- <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)" ng-show="sessionusetype!=3">
                    </a>
                    <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-show="sessionusetype==3">
                    </a> 
                    <a class="match-od-link" ui-sref="dashboard.Khaddalfancy({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> -->
                    <a href="">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==4 && fancyArr.IsPlay==0" ui-sref-active="active">
                     <!-- <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)" ng-show="sessionusetype!=3">
                     </a>
                     <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-show="sessionusetype==3">
                     </a> 
                    <a class="match-od-link" ui-sref="dashboard.Lastdigit({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> -->
                    <a href="">{{fancyArr.HeadName}}</a>
                  </div>
                  <div ng-if="fancyArr.TypeID==5 && fancyArr.IsPlay==0" ui-sref-active="active">
                      <!-- <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-click="sdMarketPP(displyData.id,series.MstCode,0,fancyArr.ID,fancyArr.IsPlayIcon)" ng-show="sessionusetype!=3">
                      </a>
                      <a ng-init="fancyArr.IsPlayIcon==1?chkMarketPPF=false:chkMarketPPF=true;" href="" ng-class="{true:'play comm-play',false:'pause comm-play'}[chkMarketPPF]" ng-show="sessionusetype==3">
                      </a>
                    <a class="match-od-link" ui-sref="dashboard.Updown({matchId: series.MstCode,FancyID:fancyArr.ID,TypeID:fancyArr.TypeID,matchName:series.matchName,sportId: displyData.id})">{{fancyArr.HeadName}}</a> -->
                    <a href="">{{fancyArr.HeadName}}</a>
                  </div>
            </li>      

			
          </ul>
        </nav>
      </section>

  </div>
