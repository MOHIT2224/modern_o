
 <div class="sidebar" id="navmob" ng-init="accordionLv2=0;GetSoprtName()">
  
  
  <nav id="menu2">
<ul>
<li><a ui-sref="userDashboard.OnePageRprt({typeId:1})"><i style="float: left;position: relative;" class="fa fa-file" aria-hidden="true"></i>  One Page Report</a> </li>
 <li><a href="javascript:void(0)" ui-sref="userDashboard.Favorite"> <img src="app/assets/newscreen/images/star.png"> Favourites</a></li> 
<li id="si{{displyData.id}}" class="submenu" ng-repeat="displyData in FsportData | filter : {active:1} | orderBy : 'name'" ng-if="displyData.id != 7">

  <a href="javascript:void(0)" ui-sref="userDashboard.Home({'SportType': displyData.id,'SeriesId':0})" ng-click="$root.selectedRow='';resetCall(displyData.id);ShowHideAng1(displyData.id,0);"> 
    <span> <img src="app/assets/newscreen/images/{{displyData.name}}.png"> {{displyData.name}} </span></a>
    
    
  <ul class="lav{{displyData.id}}" ng-show="displyData.id==selectedSport">
  <li class="prv lv{{displyData.id}}" ng-click="previous(displyData.id)" ng-show="GetSeriesData.length>0"> <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
   <li id="s2{{displyData.id}}" class="lv{{displyData.id}}" class="submenu" ng-repeat="series in GetSeriesData">
  
<a ng-click="resetCall2(displyData.id);getSeriesMatch(displyData.id,series.seriesId)" ui-sref="userDashboard.Home({'SportType': displyData.id,'SeriesId':series.seriesId})" href="javascript:void(0)">{{series.Name}}  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
   <ul class="lav2{{displyData.id}}" ng-show="accordionLv2==0 || accordionLv2==series.seriesId">
      <li class="prv lv2{{displyData.id}}" ng-click="previous2(displyData.id);" ng-show="GetMatchData.length>0">  <a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous</a></li>
     <li class="lav2{{displyData.id}}" ng-repeat="series in GetMatchData"><a ng-click="$root.selectedRow=$index+'main';accordionLv13=series.MstCode;setClickedRow($index+'sub',series)" href="javascript:void(0)">{{series.matchName}}</a></li>
     </ul>
</li>
  </ul>
  </li>
    
  
  <!--
    <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/teenpatti.png">  Live Teen Patti </a><li>
    <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/pro.png">  Pro Kabbadi  </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/horse.png">  Horse Racing  </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/grey.png">  Greyhound Racing </a><li>
  <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/poker.png">   Live Poker  </a><li>
 <li><a href="javascript:void(0)"> <img src="app/assets/newscreen/images/anderbhar.png">    Andar Bahar  </a><li> -->
 
 
</ul>

			</nav>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            


  </div>
