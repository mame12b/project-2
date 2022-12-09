
@if (Auth::user()->information)

<form action="{{ route('user.profile.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-ban"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-check"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(Auth::user()->information->application_letter_file_path || Auth::user()->information->application_acceptance_file_path || Auth::user()->information->student_id_file_path)
    <div class="callout callout-warning">
        <h5>Warning!</h5>

        <p>Uploading new files will permanently delete the previous files.</p>
      </div>
    @endif
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Application Latter</label>
                <input type="file" class="form-control" id="application_letter_file_path" name="application_letter_file_path"
                    placeholder="Application Letter">
                @error('application_letter_file_path')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Application Acceptance Form</label>
                <input type="file" class="form-control" id="application_acceptance_file_path" name="application_acceptance_file_path"
                    placeholder="Application Letter">
                @error('application_acceptance_file_path')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Student ID</label>
                <input type="file" class="form-control" id="student_id_file_path" name="student_id_file_path"
                    placeholder="Application Letter">
                @error('student_id_file_path')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3"></div>
        <hr>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary float-right">Update</button>
        </div>
    </div>
</form>
@else
<p>Please first fill required informaions in <span class="text-primary">settings</span> tab</p>
@endif
