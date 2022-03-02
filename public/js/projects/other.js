$.JST.loadDecorator("RESOURCE_ROW", function(resTr, res){
    resTr.find(".delRes").click(function(){
      $(this).closest("tr").fadeOut(200, function(){$(this).remove()});
    });

    resTr.find(".selRes").change(function(){
      var name = $(this).parent().find("select option:selected").text().trim();
      var code = $(this).parent().find("select option:selected").val();
      $(this).parent().parent().find("input[name='name']").val(name);
      $(this).parent().parent().find("input[name='code']").val(code);
    });
});

$.JST.loadDecorator("ASSIGNMENT_ROW", function(assigTr, taskAssig){
    var resEl = assigTr.find("[name=resourceId]");
    var opt = $("<option>");
    resEl.append(opt);
    for(var i=0; i< taskAssig.task.master.resources.length;i++){
      var res = taskAssig.task.master.resources[i];
      opt = $("<option>");
      opt.val(res.id).html(res.name);
      if(taskAssig.assig.resourceId == res.id)
        opt.attr("selected", "true");
      resEl.append(opt);
    }
    var roleEl = assigTr.find("[name=roleId]");
    for(var i=0; i< taskAssig.task.master.roles.length;i++){
      var role = taskAssig.task.master.roles[i];
      var optr = $("<option>");
      optr.val(role.id).html(role.name);
      if(taskAssig.assig.roleId == role.id)
        optr.attr("selected", "true");
      roleEl.append(optr);
    }

    if(taskAssig.task.master.permissions.canWrite && taskAssig.task.canWrite){
      assigTr.find(".delAssig").click(function(){
        var tr = $(this).closest("[assId]").fadeOut(200, function(){$(this).remove()});
      });
    }
});

function loadI18n(){
    GanttMaster.messages = {
      "CANNOT_WRITE":"No tienes permiso para cambiar la siguiente tarea:",
      "CHANGE_OUT_OF_SCOPE":"No es posible actualizar el proyecto debido a que carece de derechos para actualizar un proyecto principal.",
      "START_IS_MILESTONE":"La fecha de inicio es un hito.",
      "END_IS_MILESTONE":"La fecha de fin es un hito.",
      "TASK_HAS_CONSTRAINTS":"La tarea tiene limitaciones.",
      "GANTT_ERROR_DEPENDS_ON_OPEN_TASK":"Error: existe una dependencia en una tarea abierta.",
      "GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK":"Error: descendiente de una tarea cerrada.",
      "TASK_HAS_EXTERNAL_DEPS":"Esta tarea tiene dependencias externas.",
      "GANNT_ERROR_LOADING_DATA_TASK_REMOVED":"GANNT_ERROR_LOADING_DATA_TASK_REMOVED",
      "CIRCULAR_REFERENCE":"Referencia circular.",
      "CANNOT_DEPENDS_ON_ANCESTORS":"La tarea no puede depender de sus ancestros.",
      "INVALID_DATE_FORMAT":"Los datos insertados no poseen un formato válido.",
      "GANTT_ERROR_LOADING_DATA_TASK_REMOVED":"Un error ha ocurrido mientras se intentaba cargar los datos.",
      "CANNOT_CLOSE_TASK_IF_OPEN_ISSUE":"No se puede cerrar una tarea con problemas abiertos.",
      "TASK_MOVE_INCONSISTENT_LEVEL":"No puedes intercambiar tareas de diferente profundidad.",
      "CANNOT_MOVE_TASK":"CANNOT_MOVE_TASK",
      "PLEASE_SAVE_PROJECT":"PLEASE_SAVE_PROJECT",
      "GANTT_SEMESTER":"Semestre",
      "GANTT_SEMESTER_SHORT":"s.",
      "GANTT_QUARTER":"Trimestre",
      "GANTT_QUARTER_SHORT":"t.",
      "GANTT_WEEK":"Semana",
      "GANTT_WEEK_SHORT":"s."
    };
}

function createNewResource(el) {
    var row = el.closest("tr[taskid]");
    var name = row.find("[name=resourceId_txt]").val();
    var url = contextPath + "/applications/teamwork/resource/resourceNew.jsp?CM=ADD&name=" + encodeURI(name);

    openBlackPopup(url, 700, 320, function (response) {
      //fillare lo smart combo
      if (response && response.resId && response.resName) {
        //fillare lo smart combo e chiudere l'editor
        row.find("[name=resourceId]").val(response.resId);
        row.find("[name=resourceId_txt]").val(response.resName).focus().blur();
      }
    });
}

$(document).on("change", "#load-file", function() {
  var uploadedFile = $("#load-file").prop("files")[0];
  upload(uploadedFile);
});