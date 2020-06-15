(function ($, Drupal) {

  Drupal.behaviors.statistics = {
    attach() {
      $('#years li').click(function () {
        let data = {'2019': [0, 42944], '2018': [9082707, 288235], '2017': [8477331, 289325], '2016': [8766151, 217056]}
        $('#year').text(this.innerText);
        $('li').css('color', '#666');
        $(this).css('color', $('#year').css('color'));
        $('#passengers').text(0);
        $('#gargo').text(0);
        showStatistics(data[this.innerText][0], '#passengers');
        showStatistics(data[this.innerText][1], '#gargo');
      });

      function showStatistics(max_number, id_element) {
        let currCount = parseInt($(id_element).html());
        $(id_element).text(currCount + 50000);
        if (currCount + 1 <= max_number) {
          setTimeout(function () {
            showStatistics(max_number, id_element);
          }, 10);
        }
        else {
          $(id_element).text(max_number);
        }
      }

      // Start drawing graphs and simulating clicking on the first li in the list.
      $(function () {
        // Simulating clicking on the first li in the list.
        $('#block-keyfactsstatistics li:first').click();
        // Data for graphs.
        let years = [2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019];
        let data = [[3930585, 3991623, 4147105, 4296979, 5144290, 5581503, 6696025, 7320039, 8758068, 9053631, 8898197, 7793527, 8479266, 7371651, 8102502, 8586645, 8766151, 8477331, 9082707, 0],
          [150498, 156821, 180682, 237673, 301906, 334832, 357277, 385278, 369822, 342734, 329937, 279240, 262386, 245146, 219333, 207936, 217056, 289325, 288235, 42944],
          [60072, 60490, 61965, 69493, 72530, 73891, 80538, 87417, 101203, 103727, 106356, 103419, 105931, 90837, 96193, 100625, 101345, 95966, 96030, 0]];
        let titles = [[Drupal.t('Total Number of PAX'), Drupal.t('Total pax')], [Drupal.t('Cargo Tonnes of Cargo'),
          Drupal.t('Total cargo')], [Drupal.t('Total Aircraft Movements'), Drupal.t('Total Aircraft Movements')]];

        // Start drawing graphs.
        drawGraph(years, data[0], 'graphPassenger', titles[0][0], titles[0][1]);
        drawGraph(years, data[1], 'graphCargo', titles[1][0], titles[1][1]);
        drawGraph(years, data[2], 'graphAirCrafts', titles[2][0], titles[2][1]);
      });

      // Draw graph.
      function drawGraph(years, data, targetID, yAxisText, seriesName) {
        Highcharts.setOptions({
          chart: {
            backgroundColor: {
              linearGradient: [0, 0, 500, 500],
              stops: [
                [0, 'rgba(255, 255, 255, 0)'],
                [1, 'rgba(255, 255, 255, 0)']
              ]
            },
            height: 350,
          },
          lang: {
            decimalPoint: '.',
            thousandsSep: ','
          }
        });

        $(('#' + targetID)).highcharts({
          chart: {
            type: 'column'
          },
          credits: {
            enabled: false
          },
          title: {
            text: ''
          },
          colors: [
            '#FF6600'
          ],
          xAxis: {
            categories: years
          },
          yAxis: {
            title: {
              text: yAxisText
            }
          },
          series: [{
            name: seriesName,
            data: data
          }],
        });
      }
    },
  };
})(jQuery, Drupal);
