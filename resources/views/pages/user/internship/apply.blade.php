@extends('pages.user.inc.app')

@section('header')
    @include('layout.header', ['title' => 'User | Internship | Apply'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Apply For Internship</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Internship</li>
                        <li class="breadcrumb-item active">Apply</li>
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
                        <button type="button" class="close" data-dismiss="alert"
                            aria-hidden="true">×</button>
                        <i class="icon fas fa-ban"></i>
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                @if (session('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"
                            aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if (auth()->user()->haveInternship())
                <div class="col-md-12">
                    <div class="callout callout-danger">
                        <p>You have Internship in progress, you cannot apply for another internship.</p>
                    </div>
                </div>
                @endif

                @if ($internship->isDeadlinePassed())
                <div class="col-md-12">
                    <div class="callout callout-danger">
                        <p>This internship's deadline is passed, you cannot apply.</p>
                    </div>
                </div>
                @endif

                @if (auth()->user()->alreadyApplied($internship))
                <div class="col-md-12">
                    <div class="callout callout-success">
                        <p>You have applied to this internship before</p>
                    </div>
                </div>
                @endif

                <div class="col-md-12">
                    <div class="card card-default collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $internship->department->name }}</h3>
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
                                <dt class="col-sm-3">Minimum CGPA required: </dt>
                                <dd class="col-sm-7">{{ $internship->minimum_cgpa }}</dd>
                                <dt class="col-sm-3">Available Quota</dt>
                                <dd class="col-sm-7">{{ $internship->quota }}</dd>
                                <dt class="col-sm-3">Deadline</dt>
                                <dd class="col-sm-7">
                                    {{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-3">Start Date</dt>
                                <dd class="col-sm-7">
                                    {{ \Carbon\Carbon::parse($internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-3">End Date</dt>
                                <dd class="col-sm-7">
                                    {{ \Carbon\Carbon::parse($internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
                                </dd>
                                <dt class="col-sm-3">Status</dt>
                                <dd class="col-sm-7">
                                    @if ($internship->status == 0)
                                        <span class="badge badge-danger">Ended</span>
                                    @elseif($internship->status == 1)
                                        <span class="badge badge-success">Accepting Applicants</span>
                                    @else
                                        <span class="badge badge-warning">Ongoing</span>
                                    @endif
                                </dd>
                            </dl>

                            @if ($internship->prerequisites && count($internship->prerequisites) > 0)
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

                @if (!auth()->user()->haveInternship() && !$internship->isDeadlinePassed() && !auth()->user()->alreadyApplied($internship))
                <div class="col-md-12">
                    @if (auth()->user()->isEligibleToApply($internship))
                        <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">Caution</h3>
                                <div class="card-tools">
                                    <a href="{{ route('user.home') }}"><button type="button" class="btn btn-tool"><i
                                                class="fas fa-arrow-left"></i>
                                            Back
                                        </button></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-danger">
                                    <h5>You cannot apply for this internship!</h5>

                                    <b>Detail:</b>
                                    <ul>
                                        @foreach (auth()->user()->isEligibleToApply($internship) as $err)
                                            <li>{{ $err[0] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                    <form action="{{ route('user.internship.store', $internship->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="internship_id" value="{{ $internship->id }}">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Application Form</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-6">
                                        <p><b>Personal Informations:</b></p>
                                        <dl class="row">
                                            <dt class="col-sm-4">Full Name: </dt>
                                            <dd class="col-sm-8">{{ (Auth::user()->information->first_name)?ucwords(Auth::user()->information->first_name):'' }} {{ (Auth::user()->information->middle_name)?ucwords(Auth::user()->information->middle_name):'' }} {{ (Auth::user()->information->last_name)?ucwords(Auth::user()->information->last_name):'' }}</dd>
                                            <dt class="col-sm-4">Student ID: </dt>
                                            <dd class="col-sm-8">{{ (Auth::user()->information->student_id)?Auth::user()->information->student_id:'-' }}</dd>
                                            <dt class="col-sm-4">Comulative CGPA: </dt>
                                            <dd class="col-sm-8">{{ (Auth::user()->information->cgpa)?Auth::user()->information->cgpa:'-' }}</dd>
                                            <dt class="col-sm-4">University: </dt>
                                            <dd class="col-sm-8">{{ (Auth::user()->information->university)?Auth::user()->information->university:'-' }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-5">
                                        <p><b>This Files will be sent:</b></p>
                                        <ul>
                                            @if(Auth::user()->information->application_letter_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->application_letter_file_path) }}"> Application Letter</a></li> @endif
                                            @if(Auth::user()->information->application_acceptance_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->application_acceptance_file_path) }}"> Application Acceptance Form </a> </li> @endif
                                            @if(Auth::user()->information->student_id_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->student_id_file_path) }}">Student ID</a></li> @endif
                                        </ul>
                                    </div>
                                </div>
                                @if ($internship->prerequisites)
                                <hr>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-8">
                                        <p><b>Internship Prerequisit Questions:</b></p>
                                        @foreach ($internship->prerequisites as $prerequisites)
                                            <div class="form-group">
                                                <label>#{{ $loop->iteration}} ) {{ (strpos('?',$prerequisites->pre_key))?ucfirst($prerequisites->pre_key):ucfirst($prerequisites->pre_key).'?' }}</label>
                                                <textarea name="r_{{ $prerequisites->id }}" class="form-control" placeholder="Enter your answer..." required></textarea>
                                                @error('r_'.$prerequisites->id)
                                                    <span class="text-danger" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right"> <i class="fas fa-paper-plane mr-1"></i> Apply </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                @endif

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
