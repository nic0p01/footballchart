function GetBar(dat, selector, name){
    google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(function() {
    var br = Object.values(dat);
      var data = google.visualization.arrayToDataTable(br);

      var options = {
        title: name,
        
        chartArea: {width: '50%'},
        series: {
            0: { color: '#0468b4' },
            1: { color: '#03a762' }
        },
        legend:{
            textStyle: {
                color: '#5f6368',
                fontSize: 10
            }
        },
        titleTextStyle: {
            color: '#5f6368',
            fontSize: 14,
            bold: true
        },
        hAxis: {
          minValue: 0,
          textStyle: {
            bold: true,
            fontSize: 12,
            color: '#5f6368'
          },
          titleTextStyle: {
            bold: true,
            fontSize: 18,
            color: '#5f6368'
          }
        },
        vAxis: {
          textStyle: {
            fontSize: 14,
            bold: true,
            color: '#5f6368'
          },
          titleTextStyle: {
            fontSize: 14,
            bold: true,
            color: '#5f6368'
          }
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById(selector));
      chart.draw(data, options);
    });
}

function GetLine(dat, selector, title, min, max, color){
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
        var a = Object.values(dat);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(a);
        if (color === undefined) {
            color = '#0468b4';
        }
        var options = {
          height: 240,
          title: title,
          legend:{
            textStyle: {
                color: '#5f6368',
                fontSize: 10
            }
        },
        titleTextStyle: {
            color: '#5f6368',
            fontSize: 14,
            bold: true
        },
          series: {
            0: { color: color },
            1: { color: '#ee423e' }
        },
          hAxis: {textStyle: {fontSize:10,color: '#5f6368'}},
          vAxis: {minValue:min , maxValue:max, textStyle: {color: '#5f6368'}},
          pointSize: 5
        };

        var chart = new google.visualization.AreaChart(document.getElementById(selector));
        chart.draw(data, options);
      }
}

function GetLine1(dat, selector, title, min, max, color){
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
        var a = Object.values(dat);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(a);
        if (color === undefined) {
            color = '#0468b4';
        }
        var options = {
          height: 240,
          title: title,
          legend:{
            textStyle: {
                color: '#5f6368',
                fontSize: 10
            }
        },
        titleTextStyle: {
            color: '#5f6368',
            fontSize: 14,
            bold: true
        },
          series: {
            0: { color: '#f58726' },
            1: { color: '#ee423e' }
        },
          hAxis: {textStyle: {fontSize:10,color: '#5f6368'}},
          vAxis: {minValue:min , maxValue:max, textStyle: {color: '#5f6368'}},
          pointSize: 5
        };

        var chart = new google.visualization.AreaChart(document.getElementById(selector));
        chart.draw(data, options);
      }
}


function GetPie(dat, selector, title){
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(function() {
        var matches = Object.values(dat);
        var data = google.visualization.arrayToDataTable(matches);

        var options = {
          title: title,
          legend:{
            textStyle: {
                color: '#5f6368',
                fontSize: 10
            }
        },
        titleTextStyle: {
            color: '#5f6368',
            fontSize: 14,
            bold: true
        },
          is3D: true,
          slices: {
            0: { color: '#0468b4' },
            1: { color: '#03a762' },
            2: { color: '#f58726' }
        }
        };

        var chart = new google.visualization.PieChart(document.getElementById(selector));

        chart.draw(data, options);
      });
}
function monthf(pn,mon,year){
		if (pn == 'next'){
			mon++;
		}else if (pn == 'prev'){
			mon--;
		}else{
			alert('Неправильный параметр');
			return false;
		}
		if (mon > 12){
			year ++;
			mon = 1;
		}
		if (mon < 1){
			year --;
			mon = 12;
		}
		if ((mon < 10) && (mon >= 1)){
			mon = '0'+mon;
		}
		var nextDate = year+'-'+mon+'-00';
        $.ajax({
            url: '/matches/cal',
            data: {date: nextDate},
            type: 'GET',
            success: function(res){
                
              $('.calendar').html(res);      
                
                
            },
            error: function(){
                alert('Error');
            }
        });
            }
$(document).ready(function(){
    $('#reg').click(function(){
        $('#regi').show();
        $('html').addClass('html');
    });
    if($(document).width() < 500){
        $('.sidebar').css('margin-top', $('.logo').height()+15);
    }
    $('.close').click(function(){
        $('#regi').hide();
        $('html').removeClass('html');
    });
    $('#restor').click(function(){
        $('#rest').show();
        $('html').addClass('html');
    });
    $('.close').click(function(){
        $('#rest').hide();
        $('html').removeClass('html');
    });
    $('.login').on('click', function(e){
        e.preventDefault();
        $.ajax({
            url: '/login',
            success: function(res){
                showLogin(res); 
            },
            error: function(){
                alert('Error');
            }
        });
    });
});

function showLogin(res){
            $('#login .modal-body').html(res);
            $('#login').modal();
        }; 






