'use strict';

// Create an instance
var wavesurfer;

// Init & load
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        container: '#waveform',
        waveColor: 'violet',
        progressColor: 'purple',
        loaderColor: 'purple',
        cursorColor: 'navy',
        selectionColor: '#d0e9c6',
        loopSelection: false,
        plugins: [
            WaveSurfer.elan.create({
                url: '001z.xml',
                container: '#annotations',
                tiers: {
                    Text: true,
                    Comments: true
                }
            }),
            WaveSurfer.regions.create()
        ]
    };

    if (location.search.match('scroll')) {
        options.minPxPerSec = 100;
        options.scrollParent = true;
    }

    if (location.search.match('normalize')) {
        options.normalize = true;
    }

    // Init wavesurfer
    wavesurfer = WaveSurfer.create(options);

    /* Progress bar */
    (function() {
        var progressDiv = document.querySelector('#progress-bar');
        var progressBar = progressDiv.querySelector('.progress-bar');

        var showProgress = function(percent) {
            progressDiv.style.display = 'block';
            progressBar.style.width = percent + '%';
        };

        var hideProgress = function() {
            progressDiv.style.display = 'none';
            SILENCES.forEach(function(x){
                console.log(JSON.stringify(x));                    
                wavesurfer.addRegion({                            
                    start: Math.round(x.startTime/1000.0),
                    end: Math.round(x.endTime/1000.0),
                    resize: false,
                    color: 'rgba(102, 0, 102, 0.6)'
                });                                                    
            }); 

            UTTERANCES.forEach(function(u){
                if (u.categories)
                u.categories.forEach(function(c){                    
                    var wordText = '';
                    for (var i=c.startWordIdx;i<=c.endWordIdx;i++) wordText+=u.words[i].wordText+' ';
                    var startTime = u.words[c.startWordIdx].absoluteStartTime, endTime = u.words[c.endWordIdx].absoluteStartTime + u.words[c.endWordIdx].duration;    
                    var idx = u.words[c.startWordIdx].idx+'_'+u.words[c.endWordIdx].idx;
                    console.log(startTime+' '+endTime);
                    wavesurfer.addRegion({                   
                        id:idx,         
                        start: Math.round(startTime/1000.0),
                        end: Math.round(endTime/1000.0),
                        resize: false,
                        color: 'rgba(102, 0, 102, 0.0)'
                    });            
                    $('region[data-id='+idx+']').html('<b>'+wordText+'</b>');
                });
            }); 

        };

        wavesurfer.on('loading', showProgress);
        wavesurfer.on('ready', hideProgress);
        wavesurfer.on('destroy', hideProgress);
        wavesurfer.on('error', hideProgress);
    })();

    wavesurfer.elan.on('ready', function(data) {        
        alert("ws rdy");
        //wavesurfer.load('data/mono'+id+'.wav');                
        //wavesurfer.load('data/15d9c4fa4dbcf8.mp3');        
    });

    wavesurfer.elan.on('select', function(start, end) {
        wavesurfer.backend.play(start, end);
    });

    wavesurfer.elan.on('ready', function() {
        var classList = wavesurfer.elan.container.querySelector('table')
            .classList;
        ['table', 'table-striped', 'table-hover'].forEach(function(cl) {
            classList.add(cl);
        });
         
    });

    var prevAnnotation, prevRow, region;
    var onProgress = function(time) {
        onAudioProgress(time);
        
        var annotation = wavesurfer.elan.getRenderedAnnotation(time);
        if (prevAnnotation != annotation) {
            prevAnnotation = annotation;

            region && region.remove();
            region = null;

            if (annotation) {
                // Highlight annotation table row
                var row = wavesurfer.elan.getAnnotationNode(annotation);
                prevRow && prevRow.classList.remove('success');
                prevRow = row;
                row.classList.add('success');
                var before = row.previousSibling;
                if (before) {
                    wavesurfer.elan.container.scrollTop = before.offsetTop;
                }

                // Region
                /*
                region = wavesurfer.addRegion({
                    start: annotation.start,
                    end: annotation.end,
                    resize: false,
                    color: 'rgba(223, 240, 216, 0.0)' //TEMP MEINE 0.6
                });
                */
            }
        } 
        
    };

    wavesurfer.on('audioprocess', onProgress);
});
