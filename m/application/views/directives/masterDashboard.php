<div class="content-wrapper">

  <div class="bann-sport">
    <!--<img src="app/images/sport-banner.jpg" />-->
      <ul class="bxslider" >

          <?php  foreach($sliderLst as $row) {?>
              <li><img src="app/images/slider/<?php echo $row['img_name'];?>" /></li>
          <?php }?>

      </ul>
  </div>
  
  <div class="sport-cont tab_container">
    <div class="box-heading">Sport Highlights</div>
          
    <md-content>
      <md-tabs md-dynamic-height="" md-border-bottom="">
        <md-tab label="In Play" ng-click="getMatchDetail(0)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix head-li">
                <div class="spor-rgt">
                  <ul class="odd-cat-head">
                    <li class="sec-mar-rgt">1</li>
                    <li class="sec-mar-rgt">x</li>
                    <li>2</li>
                  </ul>
                </div>
              </li>
              <!--sourabh 161231-->              
              <li class="clearfix" ng-repeat="match in sportDetail" ng-show="sportid==0 && od.inplay==true">
                <div class="spor-lft">
                  <span class="glyphicon glyphicon-check" aria-hidden="true" ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}"></span>
                  <a ui-sref="dashboard.{{getUrl(match.TypeID,'match.matchid','match.marketid',match.matchName,match.MstDate,match.SportId)}}">
                    {{match.sportname}} > {{match.matchName}} {{match.HeadName}}<span>{{match.MstDate | date : 'EEEE HH:mm'}}</span>
                    <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span>
                  </a>
                </div>
                <div class="spor-rgt" ng-if='od=(oddsDetail|filter:{"marketId":match.marketid})[0]' ng-show='od.status=="OPEN"'>
                  <ul class="odds-detail">
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a class="cell lay-cell">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </md-content>
        </md-tab>

        <md-tab label="Cricket" ng-click="getMatchDetail(4)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix head-li">
                <div class="spor-rgt">
                  <ul class="odd-cat-head">
                    <li class="sec-mar-rgt">1</li>
                    <li class="sec-mar-rgt">x</li>
                    <li>2</li>
                  </ul>
                </div>
              </li>
              <li class="clearfix" ng-repeat="match in sportDetail" ng-show="sportid!=0">
                <div class="spor-lft">
                  <span class='glyphicon glyphicon-check ' aria-hidden="true" ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}"></span>
                  <a href="" ui-sref="dashboard.{{getUrl(match.TypeID,'match.matchid','match.marketid',match.matchName,match.MstDate,match.SportId)}}">
                    {{match.matchName}}&emsp;<<&emsp;{{match.HeadName}}<span>{{match.MstDate | date : 'EEEE HH:mm'}}</span>
                    <!-- {{match}} -->
                    <!--{{(=="OPEN"?od.inplay?false:true:false)}}-->
                    <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span>
                  </a>
                </div>
                <div class="spor-rgt" ng-if='od=(oddsDetail|filter:{"marketId":match.marketid})[0]' ng-show='od.status=="OPEN"'>
                  <ul class="odds-detail">
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a class="cell lay-cell">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                  </ul>
                </div>
              </li>              
            </ul>
          </md-content>
        </md-tab>

        <md-tab label="Soccer" ng-click="getMatchDetail(1)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix head-li">
                <div class="spor-rgt">
                  <ul class="odd-cat-head">
                    <li class="sec-mar-rgt">1</li>
                    <li class="sec-mar-rgt">x</li>
                    <li>2</li>
                  </ul>
                </div>
              </li>
              <li class="clearfix" ng-repeat="match in sportDetail" ng-show="sportid!=0">
                <div class="spor-lft">
                  <span class='glyphicon glyphicon-check ' aria-hidden="true" ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}"></span>
                  <a href="" ui-sref="dashboard.{{getUrl(match.TypeID,'match.matchid','match.marketid',match.matchName,match.MstDate,match.SportId)}}">
                    {{match.matchName}}&emsp;<<&emsp;{{match.HeadName}}<span>{{match.MstDate | date : 'EEEE HH:mm'}}</span>
                    <!-- {{match}} -->
                    <!--{{(=="OPEN"?od.inplay?false:true:false)}}-->
                    <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span>
                  </a>
                </div>
                <div class="spor-rgt" ng-if='od=(oddsDetail|filter:{"marketId":match.marketid})[0]' ng-show='od.status=="OPEN"'>
                  <ul class="odds-detail">
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a class="cell lay-cell">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </md-content>
        </md-tab>

        <md-tab label="Tennis" ng-click="getMatchDetail(2)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix head-li">
                <div class="spor-rgt">
                  <ul class="odd-cat-head">
                    <li class="sec-mar-rgt">1</li>
                    <li class="sec-mar-rgt">x</li>
                    <li>2</li>
                  </ul>
                </div>
              </li>
              <li class="clearfix" ng-repeat="match in sportDetail" ng-show="sportid!=0">
                <div class="spor-lft">
                  <span class='glyphicon glyphicon-check ' aria-hidden="true" ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}"></span>
                  <a href="" ui-sref="dashboard.{{getUrl(match.TypeID,'match.matchid','match.marketid',match.matchName,match.MstDate,match.SportId)}}">
                    {{match.matchName}}&emsp;<<&emsp;{{match.HeadName}}<span>{{match.MstDate | date : 'EEEE HH:mm'}}</span>
                    <!-- {{match}} -->
                    <!--{{(=="OPEN"?od.inplay?false:true:false)}}-->
                    <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span>
                  </a>
                </div>
                <div class="spor-rgt" ng-if='od=(oddsDetail|filter:{"marketId":match.marketid})[0]' ng-show='od.status=="OPEN"'>
                  <ul class="odds-detail">
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a class="cell lay-cell">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </md-content>
        </md-tab>

        <md-tab label="Fancy" ng-click="getMatchDetail(-1)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix head-li">
                <div class="spor-rgt">
                  <ul class="odd-cat-head">
                    <li class="sec-mar-rgt">1</li>
                    <li class="sec-mar-rgt">x</li>
                    <li>2</li>
                  </ul>
                </div>
              </li>
              <li class="clearfix" ng-repeat="match in sportDetail" ng-show="sportid!=0">
                <div class="spor-lft">
                  <span class='glyphicon glyphicon-check ' aria-hidden="true" ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}"></span>
                  <a href="" ui-sref="dashboard.{{getUrl(match.TypeID,'match.matchid','match.marketid',match.matchName,match.MstDate,match.SportId)}}">
                    {{match.matchName}}&emsp;<<&emsp;{{match.HeadName}}<span>{{match.MstDate | date : 'EEEE HH:mm'}}</span>
                    <!-- {{match}} -->
                    <!--{{(=="OPEN"?od.inplay?false:true:false)}}-->
                    <span  ng-class="{'mat-stat':(od.inplay && od.status=='OPEN')}">{{od.status=='OPEN'?od.inplay?"In-Play":"Going In-Play":od.status}}</span>
                  </a>
                </div>
                <div class="spor-rgt" ng-if='od=(oddsDetail|filter:{"marketId":match.marketid})[0]' ng-show='od.status=="OPEN"'>
                  <ul class="odds-detail">
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[0].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a class="cell lay-cell">{{getOddCalcVal(od.runners[0].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[2].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li class="sec-mar-rgt">
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[2].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell back-cell">{{getOddCalcVal(od.runners[1].ex.availableToBack[0].price,match.oddsLimit)}}</a>
                    </li>
                    <li>
                      <a  class="cell lay-cell">{{getOddCalcVal(od.runners[1].ex.availableToLay[0].price,match.oddsLimit)}}</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </md-content>
        </md-tab>

        <md-tab label="Horse riding" ng-click="getMatchDetail(7)">
          <md-content class="md-padding">
            <ul class="sport-high">
              <li class="clearfix under-cons-box">
                <img src="app/images/under_construction.gif" />
              </li>
            </ul>
          </md-content>
        </md-tab>

      </md-tabs>
    </md-content>
    
    </div>



  <div class="member_listing">
  <div class="widget-content  login_table">
  
 <div class="resp_table clearfix ">
    <!-- {{GetSeriesData}} -->
    <table class="table table-condensed table-responsive">
      <thead>
        <tr >
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Serial No
          </th>
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Match Name
          </th>
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Market
          </th>
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Team A
          </th>
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Team B
          </th>
          <th>
            <a>
              <i class="glyphicon glyphicon-sort"></i>
            </a>Draw
          </th>
        </tr>
      </thead>
      <tr ng-repeat="mtchRs in matchResult">
        <td data-label="S.No">{{$index+1}}</td>
        <td data-label="Match Name">{{mtchRs.matchName}}</td>
        <td data-label="Market">{{mtchRs.marketName}}</td>
        <td data-label="Team A">{{mtchRs.TeamA}}</td>
        <td data-label="Team B">{{mtchRs.TeamB}}</td>
        <td data-label="Draw">{{mtchRs.theDraw}}</td>
      </tr>
    </table>
  </div>
  
  </div>
  </div>
  
  
  </div>
