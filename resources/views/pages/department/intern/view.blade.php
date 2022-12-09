@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Intern | Detail'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Intern Detail</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Intern</li>
                        <li class="breadcrumb-item"><a href="{{ route('department.intern.list') }}">List</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                @if (session('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-ban"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-check"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif


                <div class="col-md-12">
                    <div class="card card-default collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Internship Detail</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"> <b>{{ $internship->title }}</b> </h6>
                            <p class="card-text">{{ $internship->description }}</p>

                            <hr>
                            <p><b>Detail:</b></p>

                            <dl class="row">
                                <dt class="col-sm-4">Minimum CGPA required: </dt>
                                <dd class="col-sm-6">{{ $internship->minimum_cgpa }}</dd>
                                <dt class="col-sm-4">Available Quota</dt>
                                <dd class="col-sm-6">{{ $internship->quota }}</dd>
                                <dt class="col-sm-4">Deadline</dt>
                                <dd class="col-sm-6">
                                    {{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-4">Start Date</dt>
                                <dd class="col-sm-6">
                                    {{ \Carbon\Carbon::parse($internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-4">End Date</dt>
                                <dd class="col-sm-6">
                                    {{ \Carbon\Carbon::parse($internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-6">
                                    @if ($internship->status == 0)
                                        <span class="badge badge-danger">Ended</span>
                                    @elseif($internship->status == 1)
                                        <span class="badge badge-success">Accepting Applicants</span>
                                    @else
                                        <span class="badge badge-warning">Ongoing</span>
                                    @endif
                                </dd>
                            </dl>

                            @if ($internship->prerequisites)
                                <hr>
                                <p><b>You will be asked:</b></p>

                                <ul>
                                    @foreach ($internship->prerequisites as $prerequisites)
                                        <li>{{ $prerequisites->pre_key }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Intern Detail</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($user->information)
                                <p><b> User Information: </b></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">Full Name: </dt>
                                            <dd class="col-sm-8">
                                                {{ $user->information->first_name ? ucwords($user->information->first_name) : '' }}
                                                {{ $user->information->middle_name ? ucwords($user->information->middle_name) : '' }}
                                                {{ $user->information->last_name ? ucwords($user->information->last_name) : '' }}
                                            </dd>
                                            <dt class="col-sm-4">Student ID: </dt>
                                            <dd class="col-sm-8">
                                                {{ $user->information->student_id ? $user->information->student_id : '-' }}
                                            </dd>
                                            <dt class="col-sm-5">Comulative GPA: </dt>
                                            <dd class="col-sm-7">
                                                {{ $user->information->cgpa ? $user->information->cgpa : '-' }}
                                            </dd>
                                            @if ($user->information->year_of_study)
                                                <dt class="col-sm-6">Year of Study: </dt>
                                                <dd class="col-sm-6">
                                                    {{ $user->information->year_of_study }}
                                                </dd>
                                            @endif
                                            @if ($user->information->phone_number)
                                                <dt class="col-sm-4">Phone: </dt>
                                                <dd class="col-sm-8">
                                                    {{ $user->information->phone_number }}
                                                </dd>
                                            @endif
                                            @if ($user->information->city)
                                                <dt class="col-sm-4">City: </dt>
                                                <dd class="col-sm-8">
                                                    {{ $user->information->city }}
                                                </dd>
                                            @endif
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">University: </dt>
                                            <dd class="col-sm-8">
                                                {{ $user->information->university ? $user->information->university : '-' }}
                                            </dd>
                                            @if ($user->information->degree)
                                                <dt class="col-sm-4">department: </dt>
                                                <dd class="col-sm-8">
                                                    {{ $user->information->degree }}
                                                </dd>
                                            @endif
                                            @if ($user->information->department)
                                                <dt class="col-sm-4">Department: </dt>
                                                <dd class="col-sm-8">
                                                    {{ $user->information->department }}
                                                </dd>
                                            @endif
                                        </dl>
                                    </div>
                                </div>

                                @if ($user->information->application_letter_file_path ||
                                    $user->information->application_acceptance_file_path ||
                                    $user->information->student_id_file_path)
                                    <hr>
                                    <p><b>User Files:</b></p>
                                    <div id="accordion">
                                        @if ($user->information->application_letter_file_path)
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <a data-toggle="collapse" href="#applicationLetter"
                                                            aria-expanded="true">
                                                            #1) Application Letter
                                                        </a>
                                                    </h4>
                                                    <div class="card-tools">
                                                        <a href="{{ asset('/uploads/application_letter/' . $user->information->application_letter_file_path) }}"
                                                            target="_blank" class="btn btn-tool">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="applicationLetter" class="collapse" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <iframe
                                                            src="{{ asset('/uploads/application_letter/' . $user->information->application_letter_file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                            frameBorder="0" scrolling="auto" width="100%"
                                                            height="600px"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($user->information->application_acceptance_file_path)
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <a data-toggle="collapse" href="#applicationAcceptance"
                                                            aria-expanded="true">
                                                            #2) Application Acceptance Form
                                                        </a>
                                                    </h4>
                                                    <div class="card-tools">
                                                        <a href="{{ asset('/uploads/application_acceptance/' . $user->information->application_acceptance_file_path) }}"
                                                            target="_blank" class="btn btn-tool">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="applicationAcceptance" class="collapse"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <iframe
                                                            src="{{ asset('/uploads/application_acceptance/' . $user->information->application_acceptance_file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                            frameBorder="0" scrolling="auto" width="100%"
                                                            height="600px"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($user->information->student_id_file_path)
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <a data-toggle="collapse" href="#applicationStudentId"
                                                            aria-expanded="true">
                                                            #3) Student ID
                                                        </a>
                                                    </h4>
                                                    <div class="card-tools">
                                                        <a href="{{ asset('/uploads/student_id/' . $user->information->student_id_file_path) }}"
                                                            target="_blank" class="btn btn-tool">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="applicationStudentId" class="collapse" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <iframe
                                                            src="{{ asset('/uploads/student_id/' . $user->information->student_id_file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                            frameBorder="0" scrolling="auto" width="100%"
                                                            height="600px"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endif
                            @else
                                <p>No user information found</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($prerequisite_responses)
                    <div class="col-md-12">
                        <div class="card card-default collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Internship Prerequisite Answers</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    @foreach ($prerequisite_responses as $prerequisite_respose)
                                        <dt class="col-sm-6">#{{ $loop->iteration }} )
                                            {{ strpos('?', $prerequisite_respose->internshipPrerequisite->pre_key) ? ucfirst($prerequisite_respose->internshipPrerequisite->pre_key) : ucfirst($prerequisite_respose->internshipPrerequisite->pre_key) . '?' }}
                                        </dt>
                                        <dd class="col-sm-6">{{ $prerequisite_respose->response }}</dd>
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
