<div class="content-wrapper" style="min-height: 720px;">
    <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
<!-- Market table -->
<div class="table-responsive member_listing">
    <div class="widget-content login_table">
          
          <table class="table table-striped my-market">
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
                <td data-label="Match Name"><a href="" ui-sref="masterDashboard.Matchodds({MatchId: mtchRs.matchId,MarketId:mtchRs.marketId,matchName:mtchRs.matchName,date:mtchRs.date,sportId:mtchRs.SportID})">{{mtchRs.matchName}}</a></td>
 <td data-label="Market Name"><a href="" >{{mtchRs.marketName}}</a></td>
                <td data-label="Team A" class="text-center">
                  <span class="team-lft-txt">{{mtchRs.teamAt}}</span>
                  <strong class="text-right"> 
                      <span ng-if="mtchRs.TeamA>0" style="color:#50bd00;"> {{mtchRs.TeamA|number:2}}</span>
                      <span ng-if="mtchRs.TeamA<0" style="color:RED;">{{mtchRs.TeamA|number:2}}</span>
                      <span ng-if="mtchRs.TeamA==0" style="color:Black;">{{mtchRs.TeamA|number:2}}</span>
                  </strong>
                </td>
         
                <td data-label="Team B" class="text-center">
                      <span class="team-lft-txt">{{mtchRs.teamBt }} </span> 
                      <strong class="text-right">
                        <span ng-if="mtchRs.TeamB>0" style="color:#50bd00;">{{mtchRs.TeamB|number:2}}</span>
                        <span ng-if="mtchRs.TeamB<0" style="color:RED;">{{mtchRs.TeamB|number:2}}</span>
                        <span ng-if="mtchRs.TeamB==0" style="color:Black;">{{mtchRs.TeamB|number:2}}</span>
                      </strong>
                </td>
         
                <td data-label="Draw" class="text-center">
                   <span class="team-lft-txt">{{mtchRs.teamCt }} &emsp;</span>
                    <strong class="text-right"> 
                      <span ng-if="mtchRs.theDraw>0" style="color:#50bd00;">{{mtchRs.theDraw|number:2}}</span>
                      <span ng-if="mtchRs.theDraw<0" style="color:RED;">{{mtchRs.theDraw|number:2}}</span>
                      <span ng-if="mtchRs.theDraw==0" style="color:Black;">{{mtchRs.theDraw|number:2}}</span>
                    </strong>
                </td>
                  <td class="text-center">{{mtchRs.date | date : 'dd MMM,yyyy HH:mm'}}</td>
              </tr>
              </tbody>
            </table>
          
        </div> <!-- /widget-content -->
</div>  
</div>
