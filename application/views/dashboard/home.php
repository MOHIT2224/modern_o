<div class="content-wrapper">
   <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
<div ng-if="false" class="subhead">
     <div  class="status">
            <ul class="status-list">
                <li class="bal">Balance Down: <span class="positive">0.00</span></li>
                <li class="bal">Balance Up: <span class="positive">0.00</span></li>
                <li class="bal">Net Exposure: <span class="positive">0.00</span></li>
                <li class="bal">Available Credit: <span class="positive">0.00</span></li>             
            </ul>
            </div>
            
       <div class="agency-settings">
       <div class="switch_box"><small class="clickbet"> Position Taking</small> <label class="switch"><input type="checkbox" id="formButton"><span class="slider round"></span></label>
	    
	  </div>
            
            
            </div>
            
            
    </div>


<!-- Market table -->
<div class="table-responsive member_listing mymarket">
 
    <div class="widget-content  login_table">
         <!--  {{matchResult}} -->

         <div class="binding"> <h1>My Market</h1> </div>
          <table class="table table-striped my-market" >
            <thead>
              <tr>
                <th>Match Name</th>
                  <th>Market Name</th>
                <th class="text-right">Team A</th>
                <th class="text-right">Team B</th>
                <th class="text-right">Draw</th>
                <th class="text-right">Date</th>
              </tr>
            </thead>
            <tbody>
             <tr ng-repeat="mtchRs in matchResult">
                <td class="match_name" data-label="Match Name">
                  <a href="" ui-sref="dashboard.Matchodds({MatchId: mtchRs.matchId,MarketId:mtchRs.marketId,matchName:mtchRs.matchName,date:mtchRs.date,sportId:mtchRs.SportID})" >{{mtchRs.matchName}}</a>
                  <span ui-sref="dashboard.MatchBetList({MatchId: mtchRs.matchId,MarketId:mtchRs.marketId})" class="btn btn-info btn-sm" style="float: right;margin-top:0%">BetList</span>
                </td>
 <td data-label="Market Name"><a href="" >{{mtchRs.marketName}}</a></td>
                <td data-label="Team A" class="text-left">
                  <span class="team-lft-txt">{{mtchRs.teamAt  }}</span>
                  <strong class="text-right"> 
                      <span ng-if="mtchRs.TeamA>0" style="color:#50bd00;"> {{mtchRs.TeamA|number:2}}</span>
                      <span ng-if="mtchRs.TeamA<0" style="color:RED;">{{mtchRs.TeamA|number:2}}</span>
                      <span ng-if="mtchRs.TeamA==0" style="color:Black;">{{mtchRs.TeamA|number:2}}</span>
                  </strong>
                </td>
         
                <td data-label="Team B" class="text-left">
                      <span class="team-lft-txt">{{mtchRs.teamBt }} </span> 
                      <strong class="text-right">
                        <span ng-if="mtchRs.TeamB>0" style="color:#50bd00;">{{mtchRs.TeamB|number:2}}</span>
                        <span ng-if="mtchRs.TeamB<0" style="color:RED;">{{mtchRs.TeamB|number:2}}</span>
                        <span ng-if="mtchRs.TeamB==0" style="color:Black;">{{mtchRs.TeamB|number:2}}</span>
                      </strong>
                </td>
         
                <td data-label="Draw" class="text-left">
                   <span class="team-lft-txt">{{mtchRs.teamCt }} &emsp;</span>
                    <strong class="text-right"> 
                      <span ng-if="mtchRs.theDraw>0" style="color:#50bd00;">{{mtchRs.theDraw|number:2}}</span>
                      <span ng-if="mtchRs.theDraw<0" style="color:RED;">{{mtchRs.theDraw|number:2}}</span>
                      <span ng-if="mtchRs.theDraw==0" style="color:Black;">{{mtchRs.theDraw|number:2}}</span>
                    </strong>
                </td>
                <td>{{mtchRs.date | date : 'dd MMM,yyyy HH:mm'}}</td>
              </tr>
              </tbody>
            </table>
          
        </div> <!-- /widget-content -->
</div>  
</div>
