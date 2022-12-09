@extends('pages.user.inc.app')

@section('header')
@include('layout.header', ['title' => 'User | Internship | View'])
@endsection

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Internship Detail</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Internship</li>
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

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">{{ $internship->department->name }}</h3>
                        <div class="card-tools mr-5">
                            <a href="{{ route('user.home') }}"><button type="button" class="btn btn-tool"><i
                                class="fas fa-arrow-left"></i>
                                Back
                            </button></a>
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
                                @if($internship->status == 0)
                                <span class="badge badge-danger">Ended</span>
                                @elseif($internship->status == 1)
                                <span class="badge badge-success">Accepting Applicants</span>
                                @else
                                <span class="badge badge-warning">Ongoing</span>
                                @endif
                            </dd>
                        </dl>

                        @if($internship->prerequisites && count($internship->prerequisites) > 0)
                        <hr>
                        <p><b>You will be asked:</b></p>

                        <ul>
                            @foreach ($internship->prerequisites as $prerequisites)
                                <li>{{ $prerequisites->pre_key }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @if (!auth()->user()->haveInternship() && !$internship->isDeadlinePassed() && !auth()->user()->alreadyApplied($internship))
                    <div class="card-footer">
                        <a href="{{ route('user.internship.apply', $internship->id) }}" class="btn btn-success float-right">
                            Apply <i class="fas fa-paper-plane"></i>
                        </a>
                    </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
    <!-- /.container-fluid -->
</section>
@endsection
