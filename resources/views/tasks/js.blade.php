<script >
    $(document).ready(function(){
        $(document).on('click', '#createTask',function(){
            $("#taskModalLabel").text("Create Task");
            $("#taskModalSave").text("Create");

            $("#custom-alert-modal").hide();

            $("#name").val('');
            $("#priority").val(1);
            $("#task_start").val('');
            $("#task_end").val('');
            
            $("#taskModal").modal('show');
        });

        $(document).on('click', '#taskModalClose',function(){
            $("#taskModal").modal('hide');
        });

        $(document).on("submit", "#taskForm", function(e){
            e.preventDefault();

            $("#err_task").hide();
            $("#err_task_start").hide();
            $("#err_task_end").hide();

            var name = $("#name").val();
            if(name==""){
                $("#err_task").text("Please enter task name");
                $("#err_task").show();
                return false;
            }
            else if(name.length < 4){
                $("#err_task").text("Please enter valid task name");
                $("#err_task").show();
                return false;
            }

            var task_start = $("#task_start").val();
            if(task_start==""){
                $("#err_task_start").text("Please enter task start time");
                $("#err_task_start").show();
                return false;
            }

            var task_end = $("#task_end").val();
            if(task_end==""){
                $("#err_task_end").text("Please enter task end name");
                $("#err_task_end").show();
                return false;
            }

            var form = $(this);
            var actionUrl = form.attr('action');

            var formData = $("#taskForm").serializeArray();
            formData.push({'name':"project_id", 'value':"{{ encrypt($project->id) }}"});

            var task_id = $("#taskModalSave").attr('data-taskId');
            if(task_id!=undefined || task_id!=null){
                formData.push({'name':"task_id", 'value':task_id});
            }

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: formData, // serializes the form's elements.
                success: function(response){
                    console.log(response);

                    $("#custom-alert-modal").text(response.message);
                    if (response.status==true){
                        $("#custom-alert-modal").addClass("alert-success");
                    }
                    else{
                        $("#custom-alert-modal").addClass("alert-danger");
                    }
                    $("#custom-alert-modal").show();

                    if (response.status==true){
                        setTimeout(() => {
                            $("#taskModal").modal('hide');
                            // location.reload();
                            loadTasks();
                        }, 1500);
                    }
                }
            });
        });

        $(document).on('click', '.taskEdit',function(){
            var taskId = $(this).attr('data-task');
            var taskName = $(this).attr('data-name');
            var priority = $(this).attr('data-priority');
            var start = $(this).attr('data-start');
            var end = $(this).attr('data-end');

            $("#taskModalSave").attr('data-taskId', taskId);
            $("#name").val(taskName);
            $("#priority").val(priority);
            $("#task_start").val(start);
            $("#task_end").val(end);
            
            $("#custom-alert-modal").hide();

            $("#taskModalLabel").text("Update task");
            $("#taskModalSave").text("Update");
            
            $("#taskModal").modal('show');
        });

        $(document).on('click', '.taskDelete',function(){
            var taskId = $(this).attr('data-task');

            var confirmation = confirm("Do you really want to remove this record?");
            if (confirmation) {
                var actionUrl = "{{ URL::to('task/delete') }}/"+taskId
                $.ajax({
                    type: "GET",
                    url: actionUrl,
                    success: function(response){
                        $("#custom-alert").text(response.message);
                        if (response.status==true){
                            $("#custom-alert").addClass("alert-success");
                        }
                        else{
                            $("#custom-alert").addClass("alert-danger");
                        }
                        $("#custom-alert").show();
                        if (response.status==true){
                            setTimeout(() => {
                                // location.reload();
                                loadTasks();
                            }, 1000);
                        }
                    }
                });
            }
        });

        loadTasks();

    });

    function loadTasks(){
        loadTask(1);
        loadTask(2);
        loadTask(3);
    }

    function loadTask(priority){
        var actionUrl = "{{ URL::to('task/list') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: {
                priority: priority,
                id: "{{ encrypt($project->id) }}"
            },
            success: function(response){
                console.log(response);
                $("#task-list_"+priority).html(response.html);
            }
        });
    }


    /* Drag and drop */
    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        event.dataTransfer.setData('text/plain', event.target.dataset.taskId);
    }

    function drop(event, projectId, priority) {
        event.preventDefault();

        const taskId = event.dataTransfer.getData('text/plain');
        const card = document.querySelector(`[data-task-id="${taskId}"]`);

        // Remove the card from its current column
        // card.parentNode().removeChild();

        // Append the card to the target column
        const targetColumnElement = document.getElementById(`project_${projectId}_${priority}`);
        targetColumnElement.appendChild(card);

        // Update the server-side record using AJAX
        updateRecord(taskId, projectId, priority);
    }

    function updateRecord(taskId, projectId, priority) {
        // Use AJAX to send the taskId and status to the server for updating the record
        var actionUrl = "{{ URL::to('task/update-dragdrop') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: {
                taskId: taskId,
                projectId: projectId,
                priority: priority
            },
            success: function(response){
                console.log(response);
                $("#custom-alert").text(response.message);
                if (response.status==true){
                    loadTask(priority);
                    $("#custom-alert").addClass("alert-success");
                }
                else{
                    $("#custom-alert").addClass("alert-danger");
                }
                $("#custom-alert").show();
            }
        });
    }



</script>