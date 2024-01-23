<script >
    $(document).ready(function(){
        $(document).on('click', '#createProject',function(){
            $("#projectModalLabel").text("Create Project");
            $("#projectModalSave").text("Create");

            $("#projectModalSave").attr('data-projectId', null);
            $("#name").val('');
            
            $("#projectModal").modal('show');
        });

        $(document).on('click', '#projectModalClose',function(){
            $("#projectModal").modal('hide');
        });

        $(document).on("submit", "#projectForm", function(e){
            e.preventDefault();

            $("#err_project").hide();
            var name = $("#name").val();
            if(name==""){
                $("#err_project").text("Please enter project name");
                $("#err_project").show();
                return false;
            }
            else if(name.length < 4){
                $("#err_project").text("Please enter valid project name");
                $("#err_project").show();
                return false;
            }

            var form = $(this);
            var actionUrl = form.attr('action');

            var formData = $("#projectForm").serializeArray();

            var project_id = $("#projectModalSave").attr('data-projectId');
            if(project_id!=undefined || project_id!=null){
                formData.push({'name':"project_id", 'value':project_id});
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
                            // $("#projectModal").modal('hide');
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });

        $(document).on('click', '.projectEdit',function(){
            var projectId = $(this).attr('data-project');
            var projectName = $(this).attr('data-name');

            $("#projectModalSave").attr('data-projectId', projectId);
            $("#name").val(projectName);
            
            $("#projectModalLabel").text("Update Project");
            $("#projectModalSave").text("Update");
            
            $("#projectModal").modal('show');
        });

        $(document).on('click', '.projectDelete',function(){
            var projectId = $(this).attr('data-project');

            var confirmation = confirm("Do you really want to remove this record?");
            if (confirmation) {
                var actionUrl = "{{ URL::to('project/delete') }}/"+projectId
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
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            }
        });
        
        $(document).on('click', '.showTask',function(){
            var projectId = $(this).attr('data-project');
            window.location.href = "{{ URL::to('project-tasks/') }}/"+projectId;
        });

    });
</script>