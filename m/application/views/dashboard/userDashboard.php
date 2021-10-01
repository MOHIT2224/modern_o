<div class="content-wrapper ng-scope" ng-init="getSportDetail(0)"> 
<style>.usercontent, .content, .content-wrapper h1{ padding-top:8px}  </style>
    <div class="load-box" ng-show="loading">
            <img id="mySpinner" src="app/images/loading1.gif" />
        </div>
		
		
		
		
		<div id="my-welcome-message"> <a id="fvpp-close">✖</a>
 <img src="app/assets/img/popup21white.jpg">
</div>
		
		
		
    <div class="row padding">
<div class="col-md-12 inplay">
 <div class="sport_blk newdesign">
 

 <div class="inpaygames"> 
 
 

  
  <div  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}">
  
  
  <div class="in-play page-title">
           <i class="fa fa-play-circle" aria-hidden="true"></i>
            <span class="label">In Play</span>
        </div>
  
  </div>
 
 </div>
 

 
 
 
 
 
 
 
 
  <div class="market_tr" id='sportids'  ng-repeat="marketitems in FBindArrayItems track by $index">
    <h1 class="binding" ng-if="marketitems.sportData.length>0">{{marketitems.id}}<span class="starts-in-label ng-binding ng-scope" ng-if="globalStartsInLabel">Betting from 30mins before start</span> </h1>
            <div class="clearfix"></div>
<div ng-if="marketitems.sportData.length==0 && false">
                        No record found.
                    </div>
        <div ng-repeat="marketitem in marketitems.sportData | orderBy: 'Sort'">

             <div ng-if="$last" ng-init="$last?CollectMarketMatchId():null"></div>
                    <div>
                        <div>

                            <div>
                                <div class="matchodd-header" ng-init="marketitem.status='OPEN';DisMarketId=marketitem">
                                    <div class="row">
                                        <div class="col-sm-8">

                                          <!--   <div class="matchinfo hidden-xs">{{PLAYPAUSE}}_Matched : <b>GLC {{marketitem.totalMatched|number:0}}</b></div>  MarketWinLoss(marketitem.marketid,marketitem.matchid)-->
                                        </div>
                                        <div class="col-sm-4 text-right" ng-if="!marketitem.IsOpen">
                                            <span class="goin-play"><!--{{marketitem.status=='OPEN'?marketitem.inplay?"In-Play":"Going In-Play":marketitem.status}}--> </span>
                                            <button class="btn btn-warning btn-sm hidden-xs" ng-if="false" ng-click="GetMarketListId()">Refresh</button>
<span class="rigt-ref" ng-if="marketitem.IsOpen==false" ng-click="marketitem.IsOpen=true;ChangeMarketId(marketitem.id)">
                                                                                <i class="glyphicon glyphicon-triangle-top"></i>
                
                                                                            </span>
<span class="rigt-ref" ng-if="marketitem.IsOpen==true" ng-click="marketitem.IsOpen=false;ChangeMarketId(marketitem.id)">
                                                                                <i class="glyphicon glyphicon-triangle-bottom"></i>
                
                                                                            </span>


                                        </div>
                                    </div>
                                </div>
                    
                                
                                <div class="market-wrapper nowodds" ng-if="!marketitem.IsOpen">
        
                                    <div class="matchodd_dis" ng-if="PLAYPAUSE==1"></div>
                                    <div class="table-overflow bet_table">
                                        <table class="table market-listing-table">
                                            <thead ng-init="selectedRow=-1">
                                                <tr class="top_xtow">
                                                    <th colspan="3" class="title" ng-click="ClearAllTimeOut();selectedRow=$index" href="javascript:void(0)" ng-class='{active: $index==selectedRow}' ui-sref="userDashboard.Matchodds({'MatchId': marketitem.matchid,'matchName':marketitem.matchName,'date':marketitem.matchdate,'sportId':marketitem.SportId})"><i  ng-if="marketitem.is_favourite=='N'" class="fa fa-star-o" aria-hidden="true" ng-click="setfavourite(marketitem,$index)"></i>
                                                    
                                                    <i  ng-if="marketitem.is_favourite=='Y'" ng-click="setUnfavourite(marketitem,$index)" class="fa fa-star" aria-hidden="true"></i></a> 
                                                    
                                                    <a ng-click="ClearAllTimeOut()" href="javascript:void(0)" ui-sref-active-eq="active" ui-sref="userDashboard.Matchodds({'MatchId': marketitem.matchid,'matchName':marketitem.matchName,'date':marketitem.matchdate,'sportId':marketitem.SportId})">
													
													<img class="sportsicons" src="app/images/sportsicon/{{marketitems.id}}.png">  {{marketitem.matchName}}
                            <span ng-if="marketitem.day>1" class="commonbtn">{{marketitem.day}} Day </span>
                                                   
                                                    <br />
                                                    
                                                     <span class="matchdate" ng-if="!marketitem.inplay || marketitem.status!='OPEN'">{{marketitem.matchdate | date : 'dd MMM,yyyy HH:mm'}} </span>
                                                     
                                                     
                                                     
                                                     </a> 
                                                       <a ng-if="marketitem.isfancy==1"  ui-sref="userDashboard.Matchodds({'MatchId': marketitem.matchid,'matchName':marketitem.matchName,'date':marketitem.matchdate,'sportId':marketitem.SportId})">
                                                                                                                                                                        <span class="matchdate commonbtn">Fancy</span></a>
                                                      <span ng-if="marketitem.status=='OPEN' && marketitem.inplay" class="playbutton fa fa-play-circle"></span></th>
                                                    
                                                    
                                                    

                                               
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-if="marketitem.status=='OPEN' && marketitem.inplay" ng-init="marketInfo.uniqueId=getRandomSpan();oddsLimit=marketitem.volumeLimit[0].oddsLimit;volumeLimit=marketitem.volumeLimit[0].volumeLimit" ng-repeat-start="marketInfo in marketitem.runners">
                
                                                    <td class="running-cell team_name">
                                                        <strong class="odds">{{marketInfo.name}}
                
                                                        <input type="hidden" id="NewLiability_{{$index}}" name="NewLiability_" value="{{testValue1}}">
                
                                                         </strong>
                                                        <div class="size" style='{{getValColor(getSumValPnL((marketitem.win_loss.runners|filter:{"selectionId":marketInfo.id})[0].winValue,(marketitem.win_loss.runners|filter:{"selectionId":marketInfo.id})[0].lossValue)+($root.stake2["field_" + marketInfo.id +"_"+ marketitem.matchid+"_"+marketitem.marketid]))}}'>
                                                            <span ng-init="$root.stake2['field_' + marketitem.matchid +'_' + marketInfo.marketid]=0">

{{(getSumValPnL((marketitem.win_loss.runners|filter:{"selectionId":marketInfo.id})[0].winValue,(marketitem.win_loss.runners|filter:{"selectionId":marketInfo.id})[0].lossValue)+$root.stake2['field_' + marketInfo.id +'_'+ marketitem.matchid+"_"+marketitem.marketid])|number:2}}


</span>
                                                        
                                                        </div>
                                                    </td>

                                                
                                                    <td ng-class="{'betting-disableds':(((marketitem.IsMatchDisable || marketitem.status=='SUSPENDED' || marketitem.status=='CLOSED' || marketitem.visibility==0) && marketitem.is_manual!=1) || (marketitem.is_manual==1 && (marketitem.isBetAllowedOnManualMatchOdds==0 || marketitem.visibility==0))),'callYlCss':marketInfo.back[0].selected}" ng-init="SetCommonProperty(marketInfo.id,marketitem)" class="back td_btn blue_btn"  ng-click="marketInfo.IsShow=true;CallBackLay(marketInfo.back[0].price,marketitem.volumeLimit[0].oddsLimit,marketitem,'bck1'+$index,0,marketInfo)">
                            
                                                        <strong class="odds">{{getOddCalcVal(marketInfo.back[0].price,1,marketitem.volumeLimit[0].oddsLimit,marketitem.volumeLimit[0].volumeLimit)}}</strong>
                                                        <div class="size">
                                                            <span>{{getOddCalcVal(marketInfo.back[0].size,2,marketitem.volumeLimit[0].oddsLimit,marketitem.volumeLimit[0].volumeLimit)}}</span>
                                                        </div>
                                                    </td>
                                                    <td ng-class="{'betting-disableds':(((marketitem.IsMatchDisable || marketitem.status=='SUSPENDED' || marketitem.status=='CLOSED' || marketitem.visibility==0) && marketitem.is_manual!=1) || (marketitem.is_manual==1 && (marketitem.isBetAllowedOnManualMatchOdds==0 || marketitem.visibility==0))),'callCYanCss':marketInfo.lay[0].selected }" class="lay td_btn pink_btn"  ng-click="marketInfo.IsShow=true;CallBackLay(marketInfo.lay[0].price,marketitem.volumeLimit[0].oddsLimit,marketitem,'lay1'+$index,1,marketInfo)">
                  
                                                        <strong class="odds" ng-model="testModel">{{getOddCalcVal(marketInfo.lay[0].price,1,marketitem.volumeLimit[0].oddsLimit,marketitem.volumeLimit[0].volumeLimit)}}</strong>
                                                        <div class="size">
                                                            <span>{{getOddCalcVal(marketInfo.lay[0].size,2,marketitem.volumeLimit[0].oddsLimit,marketitem.volumeLimit[0].volumeLimit)}}</span>
                                                        </div>
                                                    </td>
                                                   
                
                                                </tr>
                                                <tr ng-repeat-end ng-show="marketInfo.IsShow">
                                                    <td colspan="7">
                                                        
                                                    </td>
                
                                                </tr>
                                            </tbody>
                                        </table>
                  <!--  <div ng-if="marketitem.runners.length==0 || marketitem==undefined">
                        No record found.
                    </div>-->
                                    </div>
                                </div>
                                <div class="betOverlay" ng-if="PLAYPAUSE==1">
                                    stakeVal
                                </div>
                            </div>
                    
                        </div>
               
          
          
          
</div>

  </div>

      </div>





      </div>  
 <h1 class="binding ng-binding ng-scope" >LIVE CASINO<!-- ngIf: globalStartsInLabel --> </h1>     

 
  
  <div class="upcominggames">
  <ul>
  <li> <img src="/uploads/Diamondexch-One-Day-Teen-Patti-Betting-Id-Account.jpg"> <br> <span>  5Five Cricket </span> </li>  
  <li> <img src="/uploads/Diamondexch-One-Day-Teen-Patti-Betting-Id-Account.jpg"> <br><span>  Andar-Bahar-2  </span> </li>
   <li> <img src="/uploads/Diamondexch-20-20-Dragon-Tiger-Betting-Id-Account.jpg"> <br> <span> Dragon Tiger   </span> </li>
    <li> <img src="/uploads/Diamondexch-Andar-Bahar-Betting-Id-Account-1.jpg"> <br> <span> Baccarat 2  </span> </li>
	 <li> <img src="/uploads/Diamondexch-Worli-Matka-Betting-Id-Account.jpg"> <br><span>  Baccarat  </span> </li>
	  <li> <img src="/uploads/Diamondexch-32-Cards-B-Betting-Id-Account.jpg"> <br> <span> Lucky 7 - B </span> </li>
	   <li> <img src="/uploads/Diamondexch-20-20-Dragon-Tiger-Betting-Id-Account.jpg"> <br> <span> Teenpatti 2.0  </span>  </li>
	    <li> <img src="/uploads/Diamondexch-Lottery-Betting-Id-Account.jpg"> <br> <span> Casino Meter  </span> </li>
		 <li> <img src="/uploads/Diamondexch-Baccarat-Betting-Id-Account.png"> <br> <span> Casino War   </span> </li>
 <li> <img src="/uploads/Diamondexch-Baccarat-Betting-Id-Account.png"> <br> <span>  20-20 DTL   </span> </li>		 
<li> <img src="/uploads/Diamondexch-One-Day-Teen-Patti-Betting-Id-Account.jpg"> <br><span> Open Teenpatti   </span> </li>		 
<li> <img src="/uploads/Diamondexch-One-Day-Teen-Patti-Betting-Id-Account.jpg"> <br><span>   Test Teenpatti  </span> </li>		 		 
<li> <img src="/uploads/Diamondexch-One-Day-Teen-Patti-Betting-Id-Account.jpg"> <br><span>  32 Cards B </span> </li>	
<li> <img src="/uploads/Diamondexch-32-Cards-B-Betting-Id-Account.jpg"> <br> <span> 32 Cards A </span> </li>
<li> <img src="/uploads/Diamondexch-Andar-Bahar-Betting-Id-Account-1.jpg"> <br> <span>  Andar Bahar  </span> </li>
 <li> <img src="/uploads/Diamondexch-Worli-Matka-Betting-Id-Account.jpg"> <br><span>   Instant Worli </span> </li>
 <li> <img src="/uploads/Diamondexch-Worli-Matka-Betting-Id-Account.jpg"> <br><span>   Worli Matka </span> </li>	 
	<li> <img src="/uploads/Diamondexch-Lottery-Betting-Id-Account.jpg"> <br> <span>  Lottery  </span> </li>	 
  </ul>
  
  </div>
</div>
<div id="fvpp-blackout" ></div>

 <script>
$(document).ready(function() {
    if(localStorage.getItem('popState2')!= 'shown'){	
        localStorage.setItem('popState2','shown');
	 
		
		$("#fvpp-blackout").fadeIn();	
        $("#my-welcome-message").fadeIn();
    }

    $(document).on("click", "#fvpp-close, #my-welcome-message", function() // You are clicking the close button
    {
    $('#my-welcome-message').fadeOut(); // Now the pop up is hiden.
	$("#fvpp-blackout").fadeOut();
    });
    $('#my-welcome-message').click(function(e) 
    {
    $('#my-welcome-message').fadeOut(); 
	$("#fvpp-blackout").fadeOut();
    });
});


 </script>