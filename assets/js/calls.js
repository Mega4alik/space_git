document.addEventListener("DOMContentLoaded", () => {
  var app = new Vue({
    el: '#app',
    data: {
      menuOpen: false,
      fullSizeOpen: false,
      filter: 0,
      lChart: null,
      lChartTitle: '',
      rChart: null,
      rChartTitle: '',
      categories: [],
      category: 0,
      counts: 10,
      tables: [],

      option: {
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
      }
    },
    methods: {
      fullSize: function() {
        let elem = document.documentElement;
        if (this.fullSizeOpen) {
          if (document.exitFullscreen) {document.exitFullscreen();} else if (document.mozCancelFullScreen) {document.mozCancelFullScreen();} else if (document.webkitExitFullscreen) {document.webkitExitFullscreen();} else if (document.msExitFullscreen) {document.msExitFullscreen();}
          this.fullSizeOpen = false;
        } else {
          if (elem.requestFullscreen) {elem.requestFullscreen();} else if (elem.mozRequestFullScreen) {elem.mozRequestFullScreen();} else if (elem.webkitRequestFullscreen) {elem.webkitRequestFullscreen();} else if (elem.msRequestFullscreen) {elem.msRequestFullscreen();}
          this.fullSizeOpen = true;
        }
      },

      lChartCreate: function (data) {
        if (this.lChart) {
          this.lChart.dispose();
        }
        this.lChart = document.getElementById('lChart');
        this.lChart = echarts.init(lChart);
        this.lChartTitle = data.chartTitle;
        this.option.title.text = data.title;
        this.option.color = ['#3398DB'];
        if (this.filter == 2) {
          this.option.xAxis[0].data = data.items;
          this.option.color = ['#388e21', '#3398DB', '#8c2020']
          this.option.series = data.datas.map(function(item, i){
            let new_item = {
              name: data.label[i],
              type: 'bar',
              barWidth: '20%',
              data: item.map(function(item2) {
                return Math.round(item2);
              })
            };
            if (i < data.datas.length - 1) {
              return new_item;
            }
          })
        } else {
          this.option.xAxis[0].data = data.items;
          this.option.series = [{name: data.label, type: 'bar', barWidth: '60%', data: data.datas}];
        }
        this.lChart.setOption(this.option);
      },

      rChartCreate: function (data) {
        if (this.rChart) {
          this.rChart.dispose();
        }
        this.rChartTitle = data.chartTitle;
        this.option.title.text = data.title;
        this.option.color = ['#3398DB'];
        delete this.option.dataZoom;
        if (this.filter == 0) {
          this.option.xAxis[0].data = data.items;
          this.option.series = [{name: data.label, type: 'line', data: data.datas}];
          this.option.dataZoom = [{startValue: '2014-06-01'}, {type: 'inside'}];
          this.categories = data.categories;
          if (this.category == 0) {
            this.category = this.categories[0].id;
          }
        } else {
          this.option.xAxis[0].data = data.items;
          this.option.series = [{name: data.label, type: 'bar', barWidth: '60%', data: data.datas}];
        }
        this.rChart = document.getElementById('lChart');
        this.rChart = echarts.init(rChart);
        this.rChart.setOption(this.option);
      },

      chartGetData: function () {
        let that = this;
        const params = new URLSearchParams();
        params.append('type', this.filter);
        params.append('date_left', '0');
        axios.post('/function.php', params)
        .then(response => {
          this.lChartCreate(response.data.left);
          this.rChartCreate(response.data.right);
        });
      },

      chartGetDataParams: function () {
        let that = this;
        const params = new URLSearchParams();
        params.append('type', this.filter);
        params.append('category', this.category);
        axios.post('/function.php', params)
        .then(response => {
          this.rChartCreate(response.data.right);
        });
      },

      filter_set: function () {
        this.chartGetData();
      },

      filter_set_1: function () {
        this.chartGetDataParams();
      },

      tableGetData: function () {
        const params = new URLSearchParams();
        params.append('type', 6);
        params.append('limit', this.counts);
        axios.post('/function.php', params)
        .then(response => {
          console.log(response.data)
          this.tables = response.data;
        });
      },
    },
    mounted () {
      axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
      this.chartGetData();
      this.tableGetData();
    }
  })
});
