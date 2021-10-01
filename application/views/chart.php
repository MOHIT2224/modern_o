<div>
<!--<c3chart bindto-id="chart3">
    <chart-column column-id="line 1"
                  column-values="30,200,100,400,150,250"
                  column-type="line"/>

    <chart-grid show-x="false" show-y="true">
        <chart-grid-optional axis-id="y" grid-value="20" grid-text="Minimum" position="start"/>
        <chart-grid-optional axis-id="y" grid-value="200" grid-text="Maximum" grid-class="top-item"/>
    </chart-grid>
</c3chart>-->
    <c3chart bindto-id="pie-plot1-chart" sort-data="desc">
        <chart-column column-id="ODD EVEN" column-values="20" column-type="pie"/>
        <chart-column column-id="SESSION" column-values="20" column-type="pie"/>
        <chart-column column-id="LAST DIGIT" column-values="20" column-type="pie"/>
        <chart-column column-id="KHADDAL" column-values="15" column-type="pie"/>
        <chart-column column-id="UPDOWN" column-values="25" column-type="pie"/>
        <chart-pie expand="true" show-label="true" threshold-label="0.1"/>
    </c3chart>
    <c3chart bindto-id="chart2" show-labels="true">
        <chart-column column-id="x" column-values="period 1,period 2,period 3,period 4,period 5,period 6"/>
        <chart-column column-id="bar1" column-name="Profit" column-color="green" column-values="30,200,100,400,150,250" column-type="bar"/>
        <chart-column column-id="bar2" column-name="Loss" column-color="red" column-values="50,100,200,300,350,450" column-type="bar"/>
        <chart-bar ratio="0.8"/><chart-axes values-x="x"/>
        <chart-axis><chart-axis-x axis-position="outer-center" axis-label="The periods" axis-type="category"/></chart-axis>
    </c3chart>


    <c3chart bindto-id="chartGroupDataPoints" chart-data="datapoints" chart-columns="datacolumns" chart-x="datax" show-labels="true">
        <chart-bar ratio="0.5"/><chart-axes values-x="x"/>
        <chart-axis ><chart-axis-x axis-position="outer-center" axis-label="Profit&Loss" axis-type="category"/></chart-axis>

    </c3chart>
    <c3chart bindto-id="gauge-plot2-chart" chart-data="gaugePoint" chart-columns="gaugeColumn">
        <chart-gauge units=" hours" width="39" show-label="true" expand="true" />
    </c3chart>
</div>