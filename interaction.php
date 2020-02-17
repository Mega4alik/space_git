<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from stacksthemes.com/space/theme/templates/admin1/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 11 Jan 2019 18:46:44 GMT -->
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>Запись разговора</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="./assets/plugins/icomoon/style.css" rel="stylesheet">
        <link href="./assets/plugins/uniform/css/default.css" rel="stylesheet"/>
        <link href="./assets/plugins/switchery/switchery.min.css" rel="stylesheet"/>
        <link href="./assets/plugins/nvd3/nv.d3.min.css" rel="stylesheet">  
        <link href="./assets/plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>   
        <link href="./assets/plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>   
        <link href="./assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
      
        <!-- Theme Styles -->
        <link href="./assets/css/space.min.css" rel="stylesheet">
        <link href="./assets/css/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <!-- Javascripts -->
        <script src="./assets/plugins/jquery/jquery-3.1.0.min.js"></script>
        <script src="./assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="./assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="./assets/plugins/uniform/js/jquery.uniform.standalone.js"></script>
        <script src="./assets/plugins/switchery/switchery.min.js"></script>
        <script src="./assets/plugins/d3/d3.min.js"></script>
        <script src="./assets/plugins/nvd3/nv.d3.min.js"></script>
        <script src="./assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="./assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="./assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="./assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="./assets/plugins/flot/jquery.flot.tooltip.min.js"></script>               
        <script src="./assets/js/space.min.js"></script>
        


        <script src="./assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>        
        <script src="./assets/js/pages/table-data.js"></script>        


        <style>
        #transcriptions span.active{background-color:lightgreen;}
        #transcriptions .keyphrases{background-color: purple;}
        </style>


		<!-- wavesurfer.js -->
        <script src="https://unpkg.com/wavesurfer.js/dist/wavesurfer.js"></script>

        <!-- regions plugin -->
        <script src="https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.regions.js"></script>

        <!-- ELAN format renderer https://unpkg.com/wavesurfer.js/dist/plugin/wavesurfer.elan.js -->
        <script src="./assets/js/elan.js"></script>
     	<!-- App -->
        <script src="./assets/js/app.js"></script>
        <script src="./assets/js/trivia.js"></script>

		<script>
        var SILENCES = [], UTTERANCES = [], cw = null, $ctr = null, rg;        
        var categories_list = ['','эмоции',"угороза подачи в суд","не работает","адреса и контакты","оставить заявку"];
        var id = <?php echo $_GET['id'];?>;
        
        function timeBeautify(milli){
              var milliseconds = milli % 1000;
              var seconds = Math.floor((milli / 1000) % 60);
              var minutes = Math.floor((milli / (60 * 1000)) % 60);

              return minutes + ":" + seconds; //milliseconds
        }


        function onAudioProgress(time){
            time = time * 1000;//from secs to millisecs            
            if (cw!=null && cw.absoluteStartTime<=time && cw.absoluteStartTime + cw.duration>=time) {//word same

            } else { //word changed
                UTTERANCES.forEach(function(u){                    
                    if (u.startTime<=time && u.startTime+u.duration>=time)         
                        u.words.forEach(function(w){
                            if (w.absoluteStartTime<=time && w.absoluteStartTime+w.duration>=time){
                                if (cw!=null) $('#w'+cw.idx).removeClass('active');
                                //scroll
                                var $tr = $('#w'+w.idx).closest('tr');
                                if ($ctr == null || $tr.attr('starttime')!=$ctr.attr('starttime')){//new utterance
                                    console.log($tr.attr('starttime'));
                                    // Region
                                    rg && rg.remove();
                                    rg = null;                                    
                                    rg = wavesurfer.addRegion({
                                        start: Math.round($tr.attr('starttime')/1000.0),
                                        end: Math.round($tr.attr('endtime')/1000.0),
                                        resize: false,
                                        color: 'rgba(223, 240, 216, 0.6)'
                                    });                                      
                                    var offset = $tr.offset().top;   
                                    //console.log(offset);
                                    //$('#transcriptions')[0].scrollTop = offset;                                                                
                                }
                                $ctr = $tr;   
                                //endOf scroll
                                $('#w'+w.idx).addClass('active');                                                                          
                                cw = w;                      
                                console.log(w.wordText);                                                                
                            }                             
                        });                    
                });
            }
        }



		$(document).ready(function(){            
			$.ajax({
                url:'func.php',
                data:{'action':'getInteraction','id':id},
                dataType:'json',
                success:function(j){ 
                    console.log(JSON.stringify(j));                    
                    //console.log(j['path']);
                    var html = '';                    
                    var d = [0,0,0,0,0,0,0,0,0];
                    var idx = 0;
                    j.utterances.forEach(function(u){
                        //console.log(u.utteranceKeyphrases," - ", u.utteranceText ? u.utteranceText : "");
                        var wordsHtml = '';
                        if (u.words) u.words.forEach(function(w){
                            idx+=1;
                            w.idx = idx; 
                            wordsHtml+='<span id="w'+w.idx+'">'+w.wordText+'</span> ';
                        });
                        if (u.utteranceKeyphrases && u.utteranceKeyphrases.length>0 && u.words) u.utteranceKeyphrases.forEach(function(phrase){
                                wordsHtml+='<span class="label label-info">'+phrase+'</span> ';
                        });
                        if (u.alternative) wordsHtml +='<br><i>'+u.alternative+'</i>'; 
                        var timeSt = timeBeautify(u.startTime)+'-'+ timeBeautify(u.startTime+u.duration);
                        html+='<tr starttime='+u.startTime+' endtime='+(u.startTime+u.duration)+'><td>'+u.speakerId+'</td>'+'<td>'+timeSt+'</td>'+
                        '<td>'+wordsHtml+'</td></tr>';
                        if (u.categories) u.categories.forEach(function(x){
                            d[x.categoryId]+=1;
                        });
                    });

                    UTTERANCES = j.utterances;
                    SILENCES = j.long_silences;
                    //console.log(JSON.stringify(SILENCES)) ;                                                            
                    $('#transcriptions tbody').html(html);

                    //categories 
                    html = '';
                    for (var i=0;i<6;i++)
                        if (d[i]>0) html+=categories_list[i]+' : <a href>'+d[i]+'</a><br><br>';
                    $('#categories').html(html);
                    
                    audioPath = j.path; 
                    wavesurfer.load(audioPath);
                }   
            })
		}); 
		
		</script>

       
		<style>
		#annotations {
    max-height: 300px;
    overflow: auto;
}

.wavesurfer-annotations tr.wavesurfer-active td {
    background-color: yellow;
}

.wavesurfer-time {
    width: 100px;
    color: #555;
}

.wavesurfer-tier-Text {
    width: 500px;
}

td.wavesurfer-tier-Comments {
    color: #999;
}

.wavesurfer-handle {
    background-color: #c9e2b3;
}
		</style>
 
    </head>

    
   <body style="overflow-x: hidden;">

        <!-- Page Container -->
        <div class="page-container">

          <!-- Page Sidebar -->
          <?php include './layouts/page_sidebar.inc.php'; ?>
          <!-- /Page Sidebar -->

          <!-- Page Content -->
          <div class="page-content">

            <!-- Page Header -->
            <?php include './layouts/page_header.inc.php'; ?>
            <!-- /Page Header -->

            <!-- Page Inner -->
            <div class="page-inner">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 page-title">
                  <h3 class="breadcrumb-header">Взаимодействия</h3>
                </div>
              </div>
              <div id="main-wrapper">
                <div class="header">
                    <noindex>
                      <ul class="nav nav-pills pull-right">
                          <li><a href="?fill">Fill</a></li>
                          <li><a href="?scroll">Scroll</a></li>
                      </ul>
                    </noindex>
                </div>
                <div id="demo">
                  <div id="waveform">
                      <div class="progress progress-striped active" id="progress-bar">
                          <div class="progress-bar progress-bar-info"></div>
                      </div>
                  </div>
                  <div class="controls">
                    <br>
                    <button class="btn btn-primary" data-action="play">
                        <i class="glyphicon glyphicon-play"></i>
                        Play
                        /
                        <i class="glyphicon glyphicon-pause"></i>
                        Pause
                    </button>
                  </div>
                </div>
                <div id="wave-timeline"></div>
                <div id="annotations" class="table-responsive hidden" ></div>
                <br><br>
                <div class="panel panel-white" style="height:400px;overflow:scroll;" id="transcriptions">
                  <div class="panel-heading clearfix">
                    <h4 class="panel-title">Транскрипция разговора</h4>
                  </div>
                  <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                          <tr>
                            <th>Speaker</th>
                            <th>TimeFrame</th>
                            <th>Text</th>
                          </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Добрый день</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="panel panel-white">
                  <div class="panel-heading clearfix">
                      <h4 class="panel-title">Категории</h4>
                  </div>
                  <div class="panel-body" id="categories"></div>
                </div>
              </div>
              <?php include './layouts/footer.inc.php'; ?>
            </div>
            <!-- /Page Inner -->

          </div>
          <!-- /Page Content -->

        </div>
        <!-- /Page Container -->
    </body>

<!-- Mirrored from stacksthemes.com/space/theme/templates/admin1/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 11 Jan 2019 18:48:23 GMT -->
</html>

