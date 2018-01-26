<!DOCTYPE html>
<html>
<head>
	<title>chart created with HighCharts | HighCharts</title>
	<meta name="description" content="chart created using HighCharts" />
		
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
		
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

	<link href="http://bit.ly/2elb0Hw" rel="shortcut icon">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 
	<!-- Include Date Range Picker -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		
	<link rel="stylesheet" type="text/css" href="../css/tmCommonFormatting.css">
	<script type="text/javascript" src="../js/cgCommonScripts.js"></script>
	<style>
		.container 
		{
			min-width: 310px;
			max-width: 800px;
			height: 400px;
			margin: 0 auto
		}
			
		.highcharts-yaxis-grid .highcharts-grid-line {
			display: none;
		}
	</style>
</head>
	
<body onload="datepick()" width="1250">
<div>
<table border=0; width="1420px">
<tr><td>
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="../cgBuildCampaign.php">Generate Campaign</a></li>
	<li><a class="active" href="../cgTemplateManager.php">Template Manager</a></li>
	<li><a class="active" href="../cgScheduled.php">Manage Scheduled Campaigns</a></li>
	<li><a class="active" href="../cgEmailTracer.php">Email Tracer</a></li>
	<li><a href="../helpdocs/cgHelp.php">Help</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
	<li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
	<li><a class="active" href="../cgKey.php">Logoff</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  	</li>
</ul>
</td></tr>
<tr><td align="left">
<iframe src="http://free.timeanddate.com/clock/i5ze60a7/n5446/fs12/tt0/tw0/tm1/ta1/tb2" frameborder="0" width="201" height="16"></iframe>
<td></tr></table>
</div>

<table border=0; width="1420px"><tr><td>
<center><h1>Summary Reports</h1></center>
</td><tr></table>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Range:<br>
<table>
	<tr><td style="width:25px"></td>
	<td><div id="reportrange" onchange="datepick()" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 320px">
	<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
	<span></span> <b class="caret"></b></div>
	<br><br><br></td></tr>
<tr><td style="width:25px"></td>
<td style="width:1200px">
<div style="width: 1000px; height: 200px; margin: 0 auto; background-color: lightgrey">
    <div style="width: 250px; height: 175px; float: left">
    	<center>
    		<h4>Number of Messages Sent</h4>
    		<textarea id="messages_sent" name="messages_sent" readonly rows=1 style="background-color: lightgrey; border:none; width: 225px; resize: none; font-size: 200%; text-align: center"></textarea>
    	</center>
    </div>
    <div id="unique_opens" name="unique_opens" style="width: 250px; height: 175px; float: left"></div>
    <div id="unique_clicks" name="unique_clicks" style="width: 250px; height: 175px; float: left"></div>
    <div id="count_delayed" name="count_delayed" style="width: 250px; height: 175px; float: left"></div>
</div>

<div id="efficiency" class="container"></div>
<div id="bounces" class="container"></div>
<div id="bounce_details" class="container"></div>
<div id="rejects" class="container"></div>
<!--<div id="chartdiv" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>-->
</td></tr>
</table>
<script>
		function efficiency (from, to)
		{
			if (from == null) from = "2017-12-30T00:00"; else from = moment(from).format('YYYY-MM-DD');
			if (to == null)  to = moment().format('YYYY-MM-DD'); else to = moment(to).format('YYYY-MM-DD');
			$.ajax({
      		url:'efficiency.php',
 	     	type: "POST",
 	     	dataType : 'json',
    	  	data: {"from" : from, "to" : to},
      		success: function (data) 
      		{
      			var chartdata = [{
        			name: 'accepted',
        			data: data.accepted
    			}, {
        			name: 'clicked',
        			data: data.clicked
    			}, {
        			name: 'opened',
        			data: data.opened
    			},];
				Highcharts.chart('efficiency', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			setOptions: {lang:{thousandSep: ','}},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata, 
					title: {text: "Efficiency Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0,
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},

    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}})
			},
			error: function (data) {
        		alert("Problem getting Efficiency graph data from SparkPost Server!");
        		$('#servererror').html(data);
    		}	
			})
		}
		
		function allLineGraphs (from, to)
		{
			if (from == null) from = "2017-12-30T00:00"; else from = moment(from).format('YYYY-MM-DD');
			if (to == null)  to = moment().format('YYYY-MM-DD'); else to = moment(to).format('YYYY-MM-DD');
			$.ajax({
      		url:'allLineGraphData.php',
 	     	type: "POST",
 	     	dataType : 'json',
    	  	data: {"from" : from, "to" : to},
      		success: function (data) 
      		{
      			var chartdata_efficiency = [{
        			name: 'accepted',
        			data: data.accepted
    			}, {
        			name: 'clicked',
        			data: data.clicked
    			}, {
        			name: 'opened',
        			data: data.opened
    			}];
    			var chartdata_bounce = [{
        			name: 'Bounced',
        			data: data.bounced
    			}, {
        			name: 'In Band',
        			data: data.inband
    			}, {
        			name: 'Out of Band',
        			data: data.outofband
    			}];
    			var chartdata_reject = [{
        			name: 'Rejected',
        			data: data.rejected
    			},{
        			name: 'Policy Rejection',
        			data: data.policy
    			},{
        			name: 'Generation Failed',
        			data: data.generation_failed
    			},{
        			name: 'Generation Rejection',
        			data: data.generation_rejection
    			}];
				Highcharts.chart('efficiency', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			setOptions: {lang:{thousandSep: ','}},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata_efficiency, 
					title: {text: "Efficiency Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0,
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},

    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}}),
				Highcharts.chart('bounces', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata_bounce, 
					title: {text: "Bounce Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},

    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}}),
				Highcharts.chart('rejects', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata_reject, 
					title: {text: "Reject Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},
    				
    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}})
			},

			error: function (data) {
        		alert("Problem getting Efficiency/Bounce/Reject data from SparkPost Server!");
        		$('#servererror').html(data);
    		}	
			})
		}
		
		function bounces (from, to)
		{
			if (from == null) from = "2017-12-30T00:00"; else from = moment(from).format('YYYY-MM-DD');
			if (to == null)  to = moment().format('YYYY-MM-DD'); else to = moment(to).format('YYYY-MM-DD');
			$.ajax({
      		url:'bounce.php',
 	     	type: "POST",
 	     	dataType : 'json',
    	  	data: {"from" : from, "to" : to},
      		success: function (data) 
      		{
      			var chartdata = [{
        			name: 'Bounced',
        			data: data.bounced
    			}, {
        			name: 'In Band',
        			data: data.inband
    			}, {
        			name: 'Out of Band',
        			data: data.outofband
    			}];
				Highcharts.chart('bounces', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata, 
					title: {text: "Bounce Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},

    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}})
			},
			error: function (data) {
        		alert("Problem getting Bounce data from SparkPost Server!");
        		$('#servererror').html(data);
    		}	
			})
		}
		
				
		function bounce_details (from, to)
		{
			if (from == null) from = "2017-12-30T00:00"; else from = moment(from).format('YYYY-MM-DD');
			if (to == null) to = moment().format('YYYY-MM-DD'); else to = moment(to).format('YYYY-MM-DD');
			$.ajax({
      		url:'bounceDetail.php',
 	     	type: "POST",
 	     	dataType : 'json',
    	  	data: {"from" : from, "to" : to},
      		success: function (data) 
      		{
      			/* Available Data from bounceDetail.php
      			bounce_class_name 
				bounce_class_description
				bounce_category_name
				classification_id
				count_bounce
				count_inband_bounce
				count_outofband_bounce */
      			
      			var chartdata = [{
        			name: 'Count',
        			data: data.count_bounce
    			}, {
        			name: 'In Band',
        			data: data.count_inband_bounce
    			}, {
        			name: 'Out of Band',
        			data: data.count_outofband_bounce
    			}];
    			
				Highcharts.chart('bounce_details', 
				{
					chart: 
					{
           				type : 'column'
        			},
        			subtitle: 
        			{
            			text: 'Hover over detail bars to see full error descriptions'
       				 },
					series : chartdata, 
					title: {text: "Bounce Details"}, 
					xAxis: 
					{
        				categories: data.reason,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},

    				plotOptions: 
    				{
        				area: {
                			marker: {
                    			radius: 2
                			},
                			lineWidth: 1,
                			states: {
                    			hover: {
                        			lineWidth: 1
                    			}
                			},
                			threshold: null
    					},
					}})
				},
				error: function (data) {
        			alert("Problem getting Bounce Detail data from SparkPost Server!");
        			$('#servererror').html(data);
    			}	
			})
		}
		
		
		function rejects (from, to)
		{
			if (from == null) from = "2017-12-30T00:00"; else from = moment(from).format('YYYY-MM-DD');
			if (to == null)  to = moment().format('YYYY-MM-DD'); else to = moment(to).format('YYYY-MM-DD');
			$.ajax({
      		url:'rejections.php',
 	     	type: "POST",
 	     	dataType : 'json',
    	  	data: {"from" : from, "to" : to},
      		success: function (data) 
      		{
      			var chartdata = [
      			{
        			name: 'Rejected',
        			data: data.rejected
    			}, 
    			{
        			name: 'Policy Rejection',
        			data: data.policy
    			}, 
    			{
        			name: 'Generation Failed',
        			data: data.generation_failed
    			},{
        			name: 'Generation Rejection',
        			data: data.generation_rejection
    			}];
				Highcharts.chart('rejects', 
				{
					chart: 
					{
           				zoomType: 'x'
        			},
        			subtitle: 
        			{
            			text: document.ontouchstart === undefined ?
                    		'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
       				 },
					series : chartdata, 
					title: {text: "Reject Tracking"}, 
					xAxis: 
					{
        				categories: data.ts,
        				title: 
        				{
            				text: 'Date'
        				}
    				},
    				yAxis: 
    				{
        				title: 
        				{
            				text: 'Emails'
        				},
        				min: 0
    				},
    				tooltip: 
    				{
        				headerFormat: 'On {point.x}, there were',
        				pointFormat: ' {point.y:,.0f}',
    				},
    				
    				plotOptions: 
    				{
        				area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
    				},
				}})
			},
			error: function (data) {
        		alert("Problem getting Reject data from SparkPost Server!");
        		$('#servererror').html(data);
    		}	
			})
		}
		
		
function guages(from, to) {
    //setInterval(function(){
    /*$fullArray = array(
    	'total_msg_volume' => $results['total_msg_volume'],
    	'count_unique_clicked' => $results['count_unique_clicked'], 
    	'count_unique_rendered' => $results['count_unique_rendered'],
    	'count_delayed' => $results['count_delayed'],
    			'count_sent' => $results['count_sent'],
    			'count_spam_complaint' => $results['count_spam_complaint'],
    		);*/
    if (from == null) from = "2017-12-30T00:00";
    else from = moment(from).format('YYYY-MM-DD');
    if (to == null) to = moment().format('YYYY-MM-DD');
    else to = moment(to).format('YYYY-MM-DD');
    var color = "lightgrey";
    $.ajax({
        url: 'deliverabilityCounts.php',
        type: "POST",
        dataType: 'json',
        data: {
            "from": from,
            "to": to
        },
        beforeSend:function() {
        	document.getElementById("messages_sent").value = "Loading...";
        },
        success: function(data) {
            var n = data.count_sent;
            if (n != null) a = n.toLocaleString();
            else a = "No Data";
            document.getElementById("messages_sent").value = a;
            Highcharts.chart('unique_opens', {
                    chart: {
                        type: 'solidgauge',
                        backgroundColor: color
                    },
                    exporting: {
                        enabled: false
                    },
                    title: null,
                    tooltip: {
                        enabled: false
                    },
                    pane: {
                        center: ['50%', '85%'],
                        size: '140%',
                        startAngle: -90,
                        endAngle: 90,
                        background: {
                            //backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                            innerRadius: '60%',
                            outerRadius: '100%',
                            shape: 'arc'
                        }
                    },

                    series: [{
                        name: "Unique Opens",
                        data: [data.count_unique_rendered],
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:10px;color:' +
                                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                                '<span style="font-size:12px;color:silver">Opened</span></div>'
                        },

                        tooltip: {
                            valueSuffix: 'This Month'
                        }
                    }],

                    yAxis: {
                        min: 0,
                        max: data.count_sent,
                        stops: [
                            [0.1, '#DF5353'], // red
                            [0.2, '#DDDF0D'], // yellow
                            [0.3, '#55BF3B'] // green
                        ],
                        lineWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 2,
                        title: {
                            y: -70,
                            text: 'Unique Opens'
                        },
                        labels: {
                            y: 16
                        }
                    },

                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                y: 5,
                                borderWidth: 0,
                                useHTML: true
                            }
                        }
                    }
                }),
                Highcharts.chart('unique_clicks', {
                    chart: {
                        type: 'solidgauge',
                        backgroundColor: color
                    },
                    exporting: {
                        enabled: false
                    },
                    title: null,
                    tooltip: {
                        enabled: false
                    },
                    pane: {
                        center: ['50%', '85%'],
                        size: '140%',
                        startAngle: -90,
                        endAngle: 90,
                        background: {
                            //backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                            innerRadius: '60%',
                            outerRadius: '100%',
                            shape: 'arc'
                        }
                    },

                    series: [{
                        name: "Unique Clicks",
                        data: [data.count_unique_clicked],
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:10px;color:' +
                                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                                '<span style="font-size:12px;color:silver">Clicked</span></div>'
                        },

                        tooltip: {
                            valueSuffix: 'This Month'
                        }
                    }],

                    yAxis: {
                        min: 0,
                        max: data.count_sent,
                        stops: [
                            [0.0, '#DF5353'], // red
                            [0.05, '#DDDF0D'], // yellow
                            [0.1, '#55BF3B'] // green
                        ],
                        lineWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 2,
                        title: {
                            y: -70,
                            text: 'Unique Clicks'
                        },
                        labels: {
                            y: 16
                        }
                    },

                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                y: 5,
                                borderWidth: 0,
                                useHTML: true
                            }
                        }
                    }
                }),
                Highcharts.chart('count_delayed', {
                    chart: {
                        type: 'solidgauge',
                        backgroundColor: color
                    },
                    exporting: {
                        enabled: false
                    },
                    title: null,
                    tooltip: {
                        enabled: false
                    },
                    pane: {
                        center: ['50%', '85%'],
                        size: '140%',
                        startAngle: -90,
                        endAngle: 90,
                        background: {
                            //backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                            innerRadius: '60%',
                            outerRadius: '100%',
                            shape: 'arc'
                        }
                    },

                    series: [{
                        name: "Unique Clicks",
                        data: [data.count_delayed],
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:10px;color:' +
                                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                                '<span style="font-size:12px;color:silver">Delayed</span></div>'
                        },

                        tooltip: {
                            valueSuffix: 'This Month'
                        }
                    }],

                    yAxis: {
                        min: 0,
                        max: data.count_sent,
                        stops: [
                            [0.05, '#55BF3B'], // green
                            [0.1, '#DDDF0D'], // yellow
                            [0.2, '#DF5353'] // red
                        ],
                        lineWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 2,
                        title: {
                            y: -70,
                            text: 'Delayed Messages'
                        },
                        labels: {
                            y: 16
                        }
                    },

                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                y: 5,
                                borderWidth: 0,
                                useHTML: true
                            }
                        }
                    }
                })
        },
        error: function(data) {
            alert("Problem getting Top Dial data from SparkPost Server!");
            $('#servererror').html(data);
        }
    })
    // },10000);
}


function datepick() {

    var start = moment().subtract(180, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment().add(1, 'days')],
           'Yesterday': [moment().subtract(1, 'days'), moment()],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    allLineGraphs (start, end)
    //efficiency (start, end);
    //bounces (start, end);
    //rejects (start, end);
    guages (start, end);
    bounce_details (start, end);
    
};

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
	allLineGraphs (picker.startDate, picker.endDate)
    //efficiency (picker.startDate, picker.endDate);
    //bounces (picker.startDate, picker.endDate);
    //rejects (picker.startDate, picker.endDate);
    guages (picker.startDate, picker.endDate);
    bounce_details (picker.startDate, picker.endDate);
});

/*setTimeout(function(){
       location.reload();
   },10000);
*/
</script>
	</body>
</html>