<div class="content-wrapper ng-scope" style="min-height: 720px;" ng-init="getSportDetail(0)">
    <div class="load-box" ng-show="loading">
        <img id="mySpinner" src="app/images/loading1.gif" />
    </div>
	
    <div class="padding">
        <div class="col-md-12 inplay">
            <div class="sport_blk newdesign">
                <div ng-if="SportType==0 || SportType==4">

                    <h1 class="binding" ng-if="FbindCricket.length>0">Cricket<span class="starts-in-label ng-binding ng-scope" ng-if="globalStartsInLabel">Betting from 30mins before start</span></h1>
                    <div class="bet_table  table-bordered">
                        <table class="table table-responsive">
                            <tr class="blank_blk hidden-xs top_xtow" ng-if="FbindCricket.length>0">
                                <td>Match </td>
                                <td>&nbsp;</td>


                                <td class="betboxtd">
                                    <div class="betright">
                                        <div class="betbox">1</div>  <div class="betbox">x</div> <div class="betbox">2</div>
                                    </div>
                                </td>
                                <!--  <td>&nbsp;</td>-->
                            </tr>

                            <tr ng-repeat="match in FbindCricket | orderBy:'Sort' track by $index" ng-click="goTomarketPage(match)">

                                <td class="team_name"><a href=""><i  ng-if="match.is_favourite=='N'" class="fa fa-star-o" aria-hidden="true" ng-click="setfavourite(match,$index)"></i> <i  ng-if="match.is_favourite=='Y'" ng-click="setUnfavourite(match,$index)" class="fa fa-star" aria-hidden="true"></i></a>  <img src="app/dist/img/greendot.png" alt="img"> <a ng-click="ClearAllTimeOut()" ui-sref="userDashboard.Matchodds({'MatchId': match.matchid,'matchName':match.matchName,'date':match.matchdate,'sportId':match.SportId})">{{match.matchName}}
                                        <span ng-if="match.day>1" class="commonbtn">{{match.day}} Day </span>               <!--<span  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}">{{match.status=='OPEN'?match.inplay?"In-Play":"Going In-Play":match.status}}</span>--></td>

                                <td><!--{{match.matchdate | date : 'EEE'}} -->

                                    <span ng-if="match.status!='SUSPENDED'" ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}"><i ng-if="match.inplay && match.status=='OPEN'" class="fa fa-play-circle" style="color: green;"></i>{{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>

                                    <span ng-if="!match.inplay || match.status!='OPEN'">{{match.matchdate | date : 'dd MMM,yyyy HH:mm'}} </span>
                                    <a ng-if="match.isfancy==1" ui-sref="userDashboard.Matchodds({'MatchId': match.matchid,'matchName':match.matchName,'date':match.matchdate,'sportId':match.SportId})">
                                        <span class="commonbtn">Fancy</span></a>

                                </td>


                                <td class="betboxtd">
                                    <div class="betright">

                                        <div class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-init="SetCommonProperty(match.runners[0].id,match)" ng-class="{'callYlCss':match.runners[0].back[0].selected}" ng-click="CallBackLay(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck1'+$index,0,match.runners[0])"  class="td_btn blue_btn"  >{{getOddCalcVal(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[0].back[0].size}}</span> </a> <a ng-init="SetCommonProperty(match.runners[0].id,match)" ng-class="{'callCYanCss':match.runners[0].lay[0].selected}" ng-click="CallBackLay(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay1'+$index,1,match.runners[0])"  class="td_btn pink_btn" href="">{{getOddCalcVal(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[0].lay[0].size}}</span> </a></div>



                                        <div  class="odds_blk betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}" class="odds_blk"><a ng-class="{'callYlCss':match.runners[2].back[0].selected}" ng-init="SetCommonProperty(match.runners[2].id,match)" ng-click="CallBackLay(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck2'+$index,0,match.runners[2])" class="td_btn blue_btn" data-toggle="collapse" data-target="#demo{{$index}}"
                                                                                                                                                                                                                                                                                                                                                     ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[2].back[0].size}}</span> </a> <a  ng-class="{'callCYanCss':match.runners[2].lay[0].selected}" ng-init="SetCommonProperty(match.runners[2].id,match)" ng-click="CallBackLay(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay2'+$index,1,match.runners[2])"  class="td_btn pink_btn" data-toggle="collapse" data-target="#demo{{$index}}" href="">{{getOddCalcVal(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[2].lay[0].size}}</span> </a></div>


                                        <div  class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-init="SetCommonProperty(match.runners[1].id,match)"  ng-class="{'callYlCss':match.runners[1].back[0].selected}" ng-click="CallBackLay(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck3'+$index,0,match.runners[1])" class="td_btn blue_btn" ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[1].back[0].size}}</span> </a> <a  ng-class="{'callCYanCss':match.runners[1].lay[0].selected}" ng-init="SetCommonProperty(match.runners[1].id,match)" ng-click="CallBackLay(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay3'+$index,1,match.runners[1])" class="td_btn pink_btn"  href="">{{getOddCalcVal(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[1].lay[0].size}}</span> </a></div>
                                        <span ng-if="match.status=='SUSPENDED' || match.status=='CLOSED'"  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN'),'css-suspend1':(match.status=='SUSPENDED' || match.status=='CLOSED')}">
<i ng-if="match.inplay && match.status=='OPEN'" class="fa fa-play-circle" style="color: green;"></i>{{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>
                                    </div>





                                </td>



                            </tr>
                            <tr ng-if="FbindCricket.length>0" ng-show="false">
                                <td colspan="7">No Record Found.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div ng-if="SportType==0 || SportType==1">

                    <h1 class="binding" ng-if="FbindSoccer.length>0">Soccer <span class="starts-in-label ng-binding ng-scope" ng-if="globalStartsInLabel">Betting from 30 mins before start</span></h1>

                    <div class="bet_table  table-bordered">
                        <table class="table table-responsive">




                            <tr class="blank_blk hidden-xs top_xtow" ng-if="FbindSoccer.length>0">
                                <td>Match </td>
                                <td>&nbsp;</td>


                                <td class="betboxtd">
                                    <div class="betright">
                                        <div class="betbox">1</div>  <div class="betbox">x</div> <div class="betbox">2</div>
                                    </div>
                                </td>
                                <!--  <td>&nbsp;</td>-->
                            </tr>

                            <tr  ng-repeat="match in FbindSoccer | orderBy:'Sort'" ng-click="goTomarketPage(match)">




                                <td class="team_name"><a href=""><i  ng-if="match.is_favourite=='N'" class="fa fa-star-o" aria-hidden="true" ng-click="setfavourite(match,$index)"></i> <i  ng-if="match.is_favourite=='Y'" ng-click="setUnfavourite(match,$index)" class="fa fa-star" aria-hidden="true"></i></a>  <img src="app/dist/img/greendot.png" alt="img"> <a ng-click="ClearAllTimeOut()" ui-sref="userDashboard.Matchodds({'MatchId': match.matchid,'matchName':match.matchName,'date':match.matchdate,'sportId':match.SportId})">{{match.matchName}}
                                        <!--<span  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}">{{match.status=='OPEN'?match.inplay?"In-Play":"Going In-Play":match.status}}</span>--></td>

                                <td><!--{{match.matchdate | date : 'EEE'}} -->


                                    <span ng-if="match.status!='SUSPENDED'"  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}"><i ng-if="match.inplay && match.status=='OPEN'" class="fa fa-play-circle" style="color: green;"></i>{{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>


                                    <span  ng-if="!match.inplay || match.status!='OPEN'">{{match.matchdate | date : 'dd MMM,yyyy HH:mm'}}</span>
                                    <a ng-if="match.isfancy==1" ui-sref="userDashboard.Matchodds({'MatchId': match.matchid,'matchName':match.matchName,'date':match.matchdate,'sportId':match.SportId})">
                                        <span class="commonbtn">Fancy</span></a>
                                </td>


                                <td class="betboxtd">
                                    <div class="betright">

                                        <div class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-click="CallBackLay(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck1'+$index,0,match.runners[0])" ng-class="{'callYlCss':match.runners[0].back[0].selected}"  class="td_btn blue_btn"  >{{getOddCalcVal(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[0].back[0].size}}</span> </a> <a ng-click="CallBackLay(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay1'+$index,1,match.runners[0])" ng-class="{'callYlCss':match.runners[0].lay[0].selected}"  class="td_btn pink_btn" href="">{{getOddCalcVal(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[0].lay[0].size}}</span> </a></div>



                                        <div class="odds_blk betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}" class="odds_blk"><a ng-click="CallBackLay(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck2'+$index,0,match.runners[2])" ng-class="{'callYlCss':match.runners[2].back[0].selected}" class="td_btn blue_btn" data-toggle="collapse" data-target="#demo{{$index}}"
                                                                                                                                                                                                                                                                                                                                                    ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[2].back[0].size}}</span> </a> <a ng-click="CallBackLay(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay2'+$index,1,match.runners[2])" ng-class="{'callYlCss':match.runners[2].lay[0].selected}" class="td_btn pink_btn" data-toggle="collapse" data-target="#demo{{$index}}" href="">{{getOddCalcVal(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[2].lay[0].size}}</span> </a></div>


                                        <div class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-click="CallBackLay(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck3'+$index,0,match.runners[1])" ng-class="{'callYlCss':match.runners[1].back[0].selected}" class="td_btn blue_btn" ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[1].back[0].size}}</span> </a> <a ng-click="CallBackLay(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay3'+$index,1,match.runners[1])" ng-class="{'callYlCss':match.runners[1].lay[0].selected}" class="td_btn pink_btn"  href="">{{getOddCalcVal(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[1].lay[0].size}}</span> </a></div>

                                        <span ng-if="match.status=='SUSPENDED' || match.status=='CLOSED'"  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN'),'css-suspend1':(match.status=='SUSPENDED' || match.status=='CLOSED')}">
            {{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>
                                    </div></td>



                            </tr>
                            <tr ng-if="FbindCricket.length>0" ng-show="false">
                                <td colspan="7">No Record Found.</td>
                            </tr>
                        </table>

                    </div>

                </div>

                <div ng-if="SportType==0 || SportType==2">

                    <h1 class="binding" ng-if="FbindTennis.length>0">Tennis <span class="starts-in-label ng-binding ng-scope" ng-if="globalStartsInLabel">Betting from 30mins before start</span></h1>

                    <div class="bet_table  table-bordered">
                        <table class="table table-responsive">




                            <tr class="blank_blk hidden-xs top_xtow" ng-if="FbindTennis.length>0">
                                <td>Match </td>
                                <td>&nbsp;</td>


                                <td class="betboxtd">
                                    <div class="betright">
                                        <div class="betbox">1</div>  <div class="betbox">x</div> <div class="betbox">2</div>
                                    </div>
                                </td>
                                <!--  <td>&nbsp;</td>-->
                            </tr>

                            <tr  ng-repeat="match in FbindTennis | orderBy:'Sort'" ng-click="goTomarketPage(match)">




                                <td class="team_name"><a href=""><i  ng-if="match.is_favourite=='N'" class="fa fa-star-o" aria-hidden="true" ng-click="setfavourite(match,$index)"></i> <i  ng-if="match.is_favourite=='Y'" ng-click="setUnfavourite(match,$index)" class="fa fa-star" aria-hidden="true"></i></a>  <img src="app/dist/img/greendot.png" alt="img"> <a ng-click="ClearAllTimeOut()" ui-sref="userDashboard.Matchodds({'MatchId': match.matchid,'matchName':match.matchName,'date':match.matchdate,'sportId':match.SportId})">{{match.matchName}}
                                        <!--<span  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}">{{match.status=='OPEN'?match.inplay?"In-Play":"Going In-Play":match.status}}</span>--></td>

                                <td><!--{{match.matchdate | date : 'EEE'}} -->
                                    <span ng-if="match.status!='SUSPENDED'"  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN')}"><i ng-if="match.inplay && match.status=='OPEN'" class="fa fa-play-circle" style="color: green;"></i>{{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>

                                    <span  ng-if="!match.inplay || match.status!='OPEN'">{{match.matchdate | date : 'dd MMM,yyyy HH:mm'}} </span></td>


                                <td class="betboxtd">
                                    <div class="betright">

                                        <div class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-click="CallBackLay(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck1'+$index,0,match.runners[0])" ng-class="{'callYlCss':match.runners[0].back[0].selected}" class="td_btn blue_btn"  >{{getOddCalcVal(match.runners[0].back[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[0].back[0].size}}</span> </a> <a ng-class="{'callYlCss':match.runners[0].lay[0].selected}" ng-click="CallBackLay(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay1'+$index,1,match.runners[0])"  class="td_btn pink_btn" href="">{{getOddCalcVal(match.runners[0].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[0].lay[0].size}}</span> </a></div>



                                        <div class="odds_blk betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}" class="odds_blk"><a ng-click="CallBackLay(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck2'+$index,0,match.runners[2])" ng-class="{'callYlCss':match.runners[2].back[0].selected}" class="td_btn blue_btn" data-toggle="collapse" data-target="#demo{{$index}}"
                                                                                                                                                                                                                                                                                                                                                    ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[2].back[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[2].back[0].size}}</span> </a> <a ng-click="CallBackLay(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay2'+$index,1,match.runners[2])" ng-class="{'callYlCss':match.runners[2].lay[0].selected}"  class="td_btn pink_btn" data-toggle="collapse" data-target="#demo{{$index}}" href="">{{getOddCalcVal(match.runners[2].lay[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[2].lay[0].size}}</span> </a></div>


                                        <div class="betbox" ng-class="{'betting-disableds':(((match.IsMatchDisable || match.status=='SUSPENDED' || match.status=='CLOSED' || match.visibility==0) && match.is_manual!=1) || (match.is_manual==1 && (match.isBetAllowedOnManualMatchOdds==0 || match.visibility==0)))}"><a ng-click="CallBackLay(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit,match,'bck3'+$index,0,match.runners[1])" ng-class="{'callYlCss':match.runners[1].back[0].selected}" class="td_btn blue_btn" ng-click="match.IsShow=true"  href="">{{getOddCalcVal(match.runners[1].back[0].price,match.volumeLimit[0].oddsLimit)}} <span>{{match.runners[1].back[0].size}}</span> </a> <a ng-click="CallBackLay(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit,match,'lay3'+$index,1,match.runners[1])" ng-class="{'callYlCss':match.runners[1].lay[0].selected}" class="td_btn pink_btn"  href="">{{getOddCalcVal(match.runners[1].lay[0].price,match.volumeLimit[0].oddsLimit)}}<span>{{match.runners[1].lay[0].size}}</span> </a></div>


                                        <span ng-if="match.status=='SUSPENDED' || match.status=='CLOSED'"  ng-class="{'mat-stat':(match.inplay && match.status=='OPEN'),'css-suspend1':(match.status=='SUSPENDED' || match.status=='CLOSED')}">
            {{match.status=='OPEN'?match.inplay?"In-Play":"":match.status}}</span>
                                    </div></td>



                            </tr>
                            <tr ng-if="FbindCricket.length>0" ng-show="false">
                                <td colspan="7">No Record Found.</td>
                            </tr>
                        </table>

                    </div>
                </div>


            </div>

        </div>
        <!--<userrightbar></userrightbar>-->
    </div>







</div>
</div>