$(function(){

  let filter_id = 0;
  let optionL = {
    title: {text: '',left: 'center', textStyle: {fontSize: '15', color: '#666'}, padding: 0},
    color: ['#3398DB'],
    tooltip : {trigger: 'axis', axisPointer: {type : 'shadow'}},
    grid: {left: '3%', right: '4%', bottom: '3%', containLabel: true},
    xAxis: [{
      type : 'category',
      data : [],
      axisTick: {alignWithLabel: true},
      axisLabel : {
        formatter: (function(value){
          return value.substring(0, 6) + '...';
        })
      }
    }],
    yAxis: [{type : 'value'}],
    series: [{name: '', type: 'bar', barWidth: '60%', data: []}]
  };

  let optionR = {
    title: {text: '',left: 'center', textStyle: {fontSize: '15', color: '#666'}, padding: 0},
    color: ['#3398DB'],
    tooltip : {trigger: 'axis', axisPointer: {type : 'shadow'}},
    grid: {left: '3%', right: '4%', bottom: '3%', containLabel: true},
    xAxis: [{
      type : 'category',
      data : [],
      axisTick: {alignWithLabel: true},
      axisLabel : {
        formatter: (function(value){
          return value.substring(0, 6) + '...';
        })
      }
    }],
    yAxis: [{type : 'value'}],
    series: [{name: '', type: 'bar', barWidth: '60%', data: []}]
  };


  $('.filter').on('change', function() {
    filter_id = $(this).val();
    setFilter();
    let dates = $('.date_min_max').val().replace(/\s/g, '').split('-');
    getDataLeft('type=' + $(this).val() + '&date_left=' + dates[0] + '&date_right=' + dates[1]);
    getDataRight('type=' + $(this).val() + '&date_left=' + dates[0] + '&date_right=' + dates[1]);
  })

  let chartLeftElem = null;
  let chartLeft = null;
  let chartLeftLabels = [];
  let chartLeftDatas = [];
  let chartLeftTitle = '';
  let chartLeftChartTitle = '';
  let chartLeftColor = 'rgba(54, 162, 235, 1)';
  let chartLeftLabel = '';

  let chartRightElem = null;
  let chartRight = null;
  let chartRightLabels = [];
  let chartRightDatas = [];
  let chartRightTitle = '';
  let chartRightChartTitle = '';
  let chartRightColor = 'rgba(54, 162, 235, 1)';
  let chartRightLabel = '';

  let categories = [];
  let table = null;
  let categories_sel = 0;

  function createLeftCharts() {
    $('.chartBarLeft').html('<div id="chartBarLeft" class="w-full"></div>');
    chartLeftElem = echarts.init(document.getElementById('chartBarLeft'));
    if (filter_id == 2) {
      optionL.color = ['#388e21', '#3398DB', '#8c2020'];
      optionL.title.text = chartLeftTitle;
      optionL.xAxis[0].data = chartLeftLabels;
      optionL.series = chartLeftDatas.map(function(item, i){
        let new_item = {
          name: chartLeftLabel[i],
          type: 'bar',
          barWidth: '20%',
          data: item.map(function(item2) {
            return Math.round(item2);
          })
        };
        if (i < chartLeftDatas.length - 1) {
          return new_item;
        }
      })
    } else {
      optionL.color = ['#3398DB'];
      optionL.title.text = chartLeftTitle;
      optionL.xAxis[0].data = chartLeftLabels;
      optionL.series = [{name: chartLeftLabel, type: 'bar', barWidth: '60%', data: chartLeftDatas}];
    }
    chartLeftElem.setOption(optionL);
    chartLeftElem.on('click', function (params) {
      getTable(params, 0);
    });
    $('.chartTitleLeft').html(chartLeftChartTitle);
  }

  function createRightCharts(){
    $('.chartBarRight').html('<div id="chartBarRight" class="w-full"></div>');
    var chartRight = echarts.init(document.getElementById('chartBarRight'));
    if (filter_id == 0) {
      optionR.xAxis[0].data = chartRightLabels;
      optionR.series = [{name: chartRightLabel, type: 'line', data: chartRightDatas}];
      optionR.dataZoom = [{startValue: '2014-06-01'}, {type: 'inside'}];
    } else {
      delete optionR.dataZoom;
      optionR.color = ['#3398DB'];
      optionR.title.text = chartRightTitle;
      optionR.xAxis[0].data = chartRightLabels;
      optionR.series = [{name: chartRightLabel, type: 'bar', barWidth: '60%', data: chartRightDatas}];
    }
    chartRight.setOption(optionR);
    chartRight.on('click', function (params) {
      getTable(params, 1);
    });
    $('.chartTitleRight').html(chartRightChartTitle);
  }

  function getDataLeft (params = '') {
    categories_sel = 0;
    $.ajax({
      type: "POST",
      url: "/function.php",
      data: params,
      success: function(data){
        data = JSON.parse(data);
        chartLeftLabels = data.left.items;
        chartLeftDatas = data.left.datas;
        chartLeftTitle = data.left.title;
        chartLeftChartTitle = data.left.chartTitle;
        chartLeftColor = data.left.color;
        chartLeftLabel = data.left.label;
        createLeftCharts();
      }
    });
  }

  function getDataRight (params = '') {
    $.ajax({
      type: "POST",
      url: "/function.php",
      data: params,
      success: function(data) {
        data = JSON.parse(data);
        chartRightLabels = data.right.items;
        chartRightDatas = data.right.datas;
        chartRightTitle = data.right.title;
        chartRightChartTitle = data.right.chartTitle;
        chartRightColor = data.right.color;
        chartRightLabel = data.right.label;
        categories = data.right.categories ? data.right.categories : [];
        $('#select_cat').html('');
        categories.forEach(function(item){
          let elem = document.createElement('option')
              elem.value = item.id;
              elem.innerHTML = item.name;
          if (categories_sel == item.id) {
            elem.setAttribute('selected', 'selected');
          }
          $('#select_cat').append(elem);
        });
        createRightCharts();
      }
    });
  }

  function setFilter () {
    if (filter_id == 0) {
      $('#select_day').css('display', 'none');
      $('#select_cat').css('display', 'block');
    } else {
      $('#select_day').css('display', 'block');
      $('#select_cat').css('display', 'none');
    }
  }

  $('#select_cat').change(function(){
    categories_sel = $(this).val();
    let dates = $('.date_min_max').val().replace(/\s/g, '').split('-');  
    let params = 'type=' + $('.filter').val() + '&category=' + $(this).val() + '&date_left=' + dates[0] + '&date_right=' + dates[1];
    getDataRight(params);
  });

  $('#example').on("click", "tr", function(event){
      var id = $(this).find('td:first').text();
      window.open('interaction.php?scroll&id='+id, '_blank');
  });

  $('.date_min_max').on('apply.daterangepicker', function(ev, picker) {
    let params = 'type=' + $('.filter').val() + '&date_left=' + picker.startDate.format('DD.MM.YYYY') + '&date_right=' + picker.endDate.format('DD.MM.YYYY');
    getDataLeft(params);
    getDataRight(params);
  });

  $('.date_min_max').daterangepicker({
    timePicker: false,
    minDate: moment().startOf('hour').add(-7, 'day'),
    maxDate: moment().startOf('hour'),
    startDate: moment().startOf('hour').add(-7, 'day'),
    endDate: moment().startOf('hour'),
    locale: {
      format: 'DD.MM.YYYY'
    }
  });

  function getTable (params = '' ,chart = 0) {
    let filter = '';
    if (params != '') {
      if (chart == 0) {
        if (filter_id == 0) filter = '&filter=category&name=' + params.name;
        if (filter_id == 1) filter = '&filter=user&name=' + params.name + '&param=emotional';
        if (filter_id == 2) filter = '&filter=user&name=' + params.name + '&param=pauses';
        if (filter_id == 3) filter = '&filter=user&name=' + params.name + '&param=duration';
      } else {
        if (filter_id == 1) filter = '&filter=category&name=' + params.name + '&param=emotional';
        if (filter_id == 2) filter = '&filter=category&name=' + params.name + '&param=pauses';
        if (filter_id == 3) filter = '&filter=category&name=' + params.name + '&param=duration';
      }
    }
    posts = 'type=6' + filter;
    console.log("posts", posts);
    $.ajax({
      type: "POST",
      url: "/function.php",
      data: posts,
      success: function(data) { //console.log("getTable", data);
        data = JSON.parse(data);
        if (table != null) {
          table.destroy();
        }
        table = $('#example').DataTable({
            data: data,
            columns: [
              { title: "#" },
              { title: "Файл" },
              { title: "Сотрудник" },
              { title: "Время загрузки" },
              { title: "Продолжительность" },
              { title: "Эмоции" },
              { title: "Категории" },
              { title: "Ключевые слова" }
            ]
        });
      }, error:function(err){
        console.log("getTableErr", err);
      }
    });
  }

  setFilter();
  let dates = $('.date_min_max').val().replace(/\s/g, '').split('-');
  getDataLeft('type=' + $('.filter').val() + '&date_left=' + dates[0] + '&date_right=' + dates[1]);
  getDataRight('type=' + $('.filter').val() + '&date_left=' + dates[1] + '&date_right=' + dates[1]);

  getTable();
});
