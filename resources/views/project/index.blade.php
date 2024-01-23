<div class="card">
    <div class="card-header">
        {{ __('Projects') }}
        <a href="javascript:void(0)" id="createProject" class="btn btn-sm btn-primary float-end" >{{ __("Create Project") }}</a>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="alert" id="custom-alert" style="display: none" role="alert"></div>

        <div class="row">
            @foreach ($projects as $project)
                <div class="col-md-3 mt-1">
                    <div class="card">
                        <div class="card-header">
                            Proect Name: {{ $project->name }}
                            
                            <div class="mt-2 float-end">
                                <a href="javascript:void(0)" data-project="{{ encrypt($project->id) }}" data-name="{{ ($project->name) }}" class="btn btn-sm btn-primary projectEdit" ><i class="fa fa-edit"></i> Edit</a>

                                @if (count($project->tasks)==0)
                                    <a href="javascript:void(0)" data-project="{{ encrypt($project->id) }}" class="btn btn-sm btn-danger projectDelete"><i class="fa fa-trash"></i> Delete</a>                                
                                @endif
                            </div>
                        </div>
                        <div class="card-body showTask" data-project="{{ encrypt($project->id) }}" style="cursor: pointer" >
                            Total Tasks: {{ count($project->tasks) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

<!-- Modal -->
<form method="POST" id="projectForm" action="{{ route('storeProject') }}" autoComplete="off">
@csrf
    <div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel">Project</h5>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div class="alert" id="custom-alert-modal" style="display: none" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="project">Project Name <span class="error">*</span></label>
                    <input type="text" name="name" id="name" placeholder="Enter project name" class="form-control" />

                    <span id="err_project" style="display: none; color: red"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="projectModalClose">Close</button>
                <button type="submit" class="btn btn-primary" id="projectModalSave">Save</button>
            </div>
        </div>
        </div>
    </div>
</form>

@section('end_js')
    @include('project/js')
@endsection