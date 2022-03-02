$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
    }
});

var ge;

$(function() {
  //this is the default for test purposes
  var canWrite=true;
  
  // here starts gantt initialization
  ge = new GanttMaster();
  ge.set100OnClose=true;

  ge.shrinkParent=true;

  ge.init($("#workSpace"));
  loadI18n(); //overwrite with localized ones

  //in order to force compute the best-fitting zoom level
  delete ge.gantt.zoom;

  var project=loadFromLocalStorage();

  if (!project.canWrite)
    $(".ganttButtonBar button.requireWrite").attr("disabled","true");

  ge.loadProject(project);
  ge.checkpoint(); //empty the undo stack

  initializeHistoryManagement(ge.tasks[0].id);
});

/*function getDemoProject(){
    //console.debug("getDemoProject")
    var ret = {"tasks": [
        {"id": -1, "name": "Nombre del proyecto", "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 0, "status": "STATUS_UNDEFINED", "depends": "",  "canWrite": true, "start": 1396994400000, "duration": 15, "end": "", "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
        {"id": -2, "name": "Inicio",              "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 1, "status": "STATUS_UNDEFINED", "depends": "",  "canWrite": true, "start": 1396994400000, "duration": 3,  "end": "", "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
        {"id": -3, "name": "Planificación",       "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 1, "status": "STATUS_UNDEFINED", "depends": "2", "canWrite": true, "start": 1397494400000, "duration": 5,  "end": "", "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
        {"id": -4, "name": "Ejecución",           "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 1, "status": "STATUS_UNDEFINED", "depends": "3", "canWrite": true, "start": 1398094400000, "duration": 5,  "end": "", "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
        {"id": -5, "name": "Cierre",              "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 1, "status": "STATUS_UNDEFINED", "depends": "4", "canWrite": true, "start": 1398694400000, "duration": 2,  "end": "", "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
    ], 
    "selectedRow": 0, "deletedTaskIds": [], "resources": [],
    "roles": [
        {"id": "tmp_1", "name": "Resp. Proyecto"},
        {"id": "tmp_2", "name": "Colaborador"},
        {"id": "tmp_3", "name": "Stakeholder"},
        {"id": "tmp_4", "name": "Cliente"}
    ], 
    "canWrite": true, "canDelete": true, "canWriteOnParent": true, canAdd: true}

    //actualize data
    var offset = new Date().getTime() - ret.tasks[0].start;
    for (var i=0; i<ret.tasks.length; i++) {
        ret.tasks[i].start = ret.tasks[i].start + offset;
    }
    return ret;
}*/

function loadGanttFromServer(taskId, callback) {
  //this is a simulation: load data from the local storage if you have already played with the demo or a textarea with starting demo data
  var ret=loadFromLocalStorage();

  //this is the real implementation
  /*
  //var taskId = $("#taskSelector").val();
  var prof = new Profiler("loadServerSide");
  prof.reset();

  $.getJSON("ganttAjaxController.jsp", {CM:"LOADPROJECT",taskId:taskId}, function(response) {
    //console.debug(response);
    if (response.ok) {
      prof.stop();

      ge.loadProject(response.project);
      ge.checkpoint(); //empty the undo stack

      if (typeof(callback)=="function") {
        callback(response);
      }
    } else {
      jsonErrorHandling(response);
    }
  });
  */
  return ret;
}

function upload(uploadedFile) {
  var fileread = new FileReader();
  
  fileread.onload = function(e) {
    var content = e.target.result;
    var intern = JSON.parse(content); // Array of Objects.
    //console.log(intern); // You can index every object
    
    ge.loadProject(intern);
    ge.checkpoint(); //empty the undo stack

  };

  fileread.readAsText(uploadedFile);
}

function saveGanttOnServer() {

  //this is a simulation: save data to the local storage or to the textarea
  //saveInLocalStorage();

  var prj = ge.saveProject();

  download(JSON.stringify(prj, null, '\t'), "MyProject.json", "application/json");

  /*

  delete prj.resources;
  delete prj.roles;

  var prof = new Profiler("saveServerSide");
  prof.reset();

  if (ge.deletedTaskIds.length>0) {
    if (!confirm("TASK_THAT_WILL_BE_REMOVED\n"+ge.deletedTaskIds.length)) {
      return;
    }
  }

  $.ajax("ganttAjaxController.jsp", {
    dataType:"json",
    data: {CM:"SVPROJECT",prj:JSON.stringify(prj)},
    type:"POST",

    success: function(response) {
      if (response.ok) {
        prof.stop();
        if (response.project) {
          ge.loadProject(response.project); //must reload as "tmp_" ids are now the good ones
        } else {
          ge.reset();
        }
      } else {
        var errMsg="Errors saving project\n";
        if (response.message) {
          errMsg=errMsg+response.message+"\n";
        }

        if (response.errorMessages.length) {
          errMsg += response.errorMessages.join("\n");
        }

        alert(errMsg);
      }
    }

  });
  */
}

// Function to download data to a file
function download(data, filename, type) {
  var file = new Blob([data], {type: type});
  if (window.navigator.msSaveOrOpenBlob) // IE10+
    window.navigator.msSaveOrOpenBlob(file, filename);
  else { // Others
    var a = document.createElement("a"),
      url = URL.createObjectURL(file);
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    setTimeout(function() {
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);  
    }, 0); 
  }
}

function newProject(){
  clearGantt();
}

function clearGantt() {
  ge.reset();
}

//-------------------------------------------  Get project file as JSON (used for migrate project from gantt to Teamwork) ------------------------------------------------------
function getFile() {
  $("#gimBaPrj").val(JSON.stringify(ge.saveProject()));
  $("#gimmeBack").submit();
  $("#gimBaPrj").val("");

  /*var uriContent = "data:text/html;charset=utf-8," + encodeURIComponent(JSON.stringify(prj));
  neww=window.open(uriContent,"dl");*/
}

function loadFromLocalStorage() {
  var ret;
  if (localStorage) {
    if (localStorage.getObject("teamworkGantDemo")) {
      ret = localStorage.getObject("teamworkGantDemo");
    }
  }

  //if not found create a new example task
  if (!ret || !ret.tasks || ret.tasks.length == 0){
    ret=getDemoProject();
  }
  return ret;
}

function saveInLocalStorage() {
  var prj = ge.saveProject();

  if (localStorage) {
    localStorage.setObject("teamworkGantDemo", prj);
  }
}

//-------------------------------------------  Open a black popup for managing resources. This is only an axample of implementation (usually resources come from server) ------------------------------------------------------
function editResources() {
  var pre = $('#_method').val() ? '../' : '';
  //make resource editor
  var resourceEditor = $.JST.createFromTemplate({}, "RESOURCE_EDITOR");
  var resTbl = resourceEditor.find("#resourcesTable");

  for (var i=0; i<ge.resources.length; i++){
    var res = ge.resources[i];
    resTbl.append($.JST.createFromTemplate(res, "RESOURCE_ROW"))
  }

  //bind add resource
  resourceEditor.find("#addResource").click(function() {
    resTbl.append($.JST.createFromTemplate({id:"new", name:"Selecciona un nuevo recurso"}, "RESOURCE_ROW"))
  });

  //bind save event
  resourceEditor.find("#resSaveButton").click(function() {
    var newRes = [];
    var error = false;
    var message = '<b>¡Atención!</b><ul>';
    for (var i=0; i<ge.resources.length; i++) { //find for deleted res
      var res = ge.resources[i];
      var row = resourceEditor.find("[resId=" + res.id + "]");
      if (row.length > 0) { //if still there save it
        var name = row.find("input[name='name']").val();
        var code = row.find("input[name='code']").val();
        if (code) {
          $.ajax({
            type: 'post',
            url: pre + '../resources/' + i,
            data: {'_method' : 'PATCH', 'rsc_id' : code},
            async: false,
            success: function() {
              res.name = name;
            },
            error: function(msg) {
              error = true;
              $.each(msg.responseJSON['errors'], function() {
                  message += addItem(this);
              });
              $('#rsc_fail_msg').html(message + '</ul>');
              $('#rsc_fail_div').css('display','block');
            }
          });
        }
        newRes.push(res);
      }
      else { //remove assignments
        $.ajax({
          type: 'delete',
          url: pre + '../resources/' + i,
          async: false,
          success: function() {
            ge.resources.splice(i--, 1);
            for (var j=0; j<ge.tasks.length; j++) {
              var task = ge.tasks[j];
              var newAss = [];
              for (var k=0; k<task.assigs.length; k++) {
                var ass = task.assigs[k];
                if (ass.resourceId != res.id)
                  newAss.push(ass);
              }
              task.assigs = newAss;
            }
          },
          error: function(msg) {
            error = true;
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            $('#rsc_fail_msg').html(message + '</ul>');
            $('#rsc_fail_div').css('display','block');
          }
        });
      }
    }
    //loop on new rows
    var cnt = 0;
    resourceEditor.find("[resId=new]").each(function() {
      var name = $(this).find("select option:selected").text().trim();
      var code = $(this).find("select option:selected").val();
      if (code) {
        $.ajax({
          type: 'post',
          url: pre + '../resources',
          data: {'rsc_id' : code},
          async: false,
          success: function() {
            newRes.push(new Resource("tmp_" + new Date().getTime() + "_" + ++cnt, name));
            ge.resources = newRes;
          },
          error: function(msg) {
            error = true;
            $.each(msg.responseJSON['errors'], function() {
                message += addItem(this);
            });
            $('#rsc_fail_msg').html(message + '</ul>');
            $('#rsc_fail_div').css('display','block');
          }
        });
      }
    });
    if (!error) {
      ge.redraw();
      closeBlackPopup();
    }
  });
  createModalPopup(400, 500).append(resourceEditor);
}

function initializeHistoryManagement(taskId){

  //retrieve from server the list of history points in millisecond that represent the instant when the data has been recorded
  //response: {ok:true, historyPoints: [1498168800000, 1498600800000, 1498687200000, 1501538400000, …]}
  $.getJSON(contextPath+"/applications/teamwork/task/taskAjaxController.jsp", {CM: "GETGANTTHISTPOINTS", OBJID:taskId}, function (response) {

    //if there are history points
    if (response.ok == true && response.historyPoints && response.historyPoints.length>0) {

      //add show slider button on button bar
      var histBtn = $("<button>").addClass("button textual icon lreq30 lreqLabel").attr("title", "SHOW_HISTORY").append("<span class=\"teamworkIcon\">&#x60;</span>");

      //clicking it
      histBtn .click(function () {
        var el = $(this);
        var ganttButtons = $(".ganttButtonBar .buttons");

        //is it already on?
        if (!ge.element.is(".historyOn")) {
          ge.element.addClass("historyOn");
          ganttButtons.find(".requireCanWrite").hide();

          //load the history points from server again
          showSavingMessage();
          $.getJSON(contextPath + "/applications/teamwork/task/taskAjaxController.jsp", {CM: "GETGANTTHISTPOINTS", OBJID: ge.tasks[0].id}, function (response) {
            jsonResponseHandling(response);
            hideSavingMessage();
            if (response.ok == true) {
              var dh = response.historyPoints;
              if (dh && dh.length > 0) {
                //si crea il div per lo slider
                var sliderDiv = $("<div>").prop("id", "slider").addClass("lreq30 lreqHide").css({"display":"inline-block","width":"500px"});
                ganttButtons.append(sliderDiv);

                var minVal = 0;
                var maxVal = dh.length-1 ;

                $("#slider").show().mbSlider({
                  rangeColor : '#2f97c6',
                  minVal     : minVal,
                  maxVal     : maxVal,
                  startAt    : maxVal,
                  showVal    : false,
                  grid       :1,
                  formatValue: function (val) {
                    return new Date(dh[val]).format();
                  },
                  onSlideLoad: function (obj) {
                    this.onStop(obj);

                  },
                  onStart    : function (obj) {},
                  onStop     : function (obj) {
                    var val = $(obj).mbgetVal();
                    showSavingMessage();
                    /**
                     * load the data history for that milliseconf from server
                     * response in this format {ok: true, baselines: {...}}
                     *
                     * baselines: {61707: {duration:1,endDate:1550271599998,id:61707,progress:40,startDate:1550185200000,status:"STATUS_WAITING",taskId:"3055"},
                     *            {taskId:{duration:in days,endDate:in millis,id:history record id,progress:in percent,startDate:in millis,status:task status,taskId:"3055"}....}}                     */

                    $.getJSON(contextPath + "/applications/teamwork/task/taskAjaxController.jsp", {CM: "GETGANTTHISTORYAT", OBJID: ge.tasks[0].id, millis:dh[val]}, function (response) {
                      jsonResponseHandling(response);
                      hideSavingMessage();
                      if (response.ok ) {
                        ge.baselines=response.baselines;
                        ge.showBaselines=true;
                        ge.baselineMillis=dh[val];
                        ge.redraw();
                      }
                    })

                  },
                  onSlide    : function (obj) {
                    clearTimeout(obj.renderHistory);
                    var self = this;
                    obj.renderHistory = setTimeout(function(){
                      self.onStop(obj);
                    }, 200)

                  }
                });
              }
            }
          });

          // closing the history
        } else {
          //remove the slider
          $("#slider").remove();
          ge.element.removeClass("historyOn");
          if (ge.permissions.canWrite)
            ganttButtons.find(".requireCanWrite").show();

          ge.showBaselines=false;
          ge.baselineMillis=undefined;
          ge.redraw();
        }

      });
      $("#saveGanttButton").before(histBtn);
    }
  })
}

function showBaselineInfo (event,element){
  //alert(element.attr("data-label"));
  $(element).showBalloon(event, $(element).attr("data-label"));
  ge.splitter.secondBox.one("scroll",function(){
    $(element).hideBalloon();
  })
}

$('#name').change(function() {
  ge.tasks[0].name = $(this).val();
  ge.redraw();
});