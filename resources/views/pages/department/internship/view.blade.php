@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Internship | View'])
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
                        <li class="breadcrumb-item"><a href="{{ route('department.internship.list') }}">List</a></li>
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

                <div class="col-md-12">

                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Internship Detail</h3>
                            <div class="card-tools mr-5">
                                <a href="{{ route('department.internship.list') }}"><button type="button" class="btn btn-tool"><i
                                            class="fas fa-arrow-left"></i>
                                            Back
                                    </button></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-3">Internship Id:</dt>
                                        <dd class="col-sm-9">{{ $internship->id }}</dd>
                                        <dt class="col-sm-3">Department</dt>
                                        <dd class="col-sm-9">{{ $internship->department->name }}</dd>
                                        <dt class="col-sm-3">Title</dt>
                                        <dd class="col-sm-9">{{ $internship->title }}</dd>
                                        <dt class="col-sm-3">Min CGPA</dt>
                                        <dd class="col-sm-9">{{ $internship->minimum_cgpa }}</dd>
                                        <dt class="col-sm-3">Quota</dt>
                                        <dd class="col-sm-9">{{ $internship->quota }}</dd>
                                        <dt class="col-sm-3">Deadline</dt>
                                        <dd class="col-sm-9">
                                            {{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-3">Start Date</dt>
                                        <dd class="col-sm-9">
                                            {{ \Carbon\Carbon::parse($internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-3">End Date</dt>
                                        <dd class="col-sm-9">
                                            {{ \Carbon\Carbon::parse($internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-3">Status</dt>
                                        <dd class="col-sm-9">
                                            @if($internship->status == 0)
                                                <span class="badge badge-danger">Ended</span>
                                            @elseif($internship->status == 1)
                                                <span class="badge badge-success">Accepting Applicants</span>
                                            @elseif ($internship->status == 2)
                                                <span class="badge badge-warning">Ongoing</span>
                                            @elseif ($internship->status == 3)
                                                <span class="badge badge-info">Waiting</span>
                                            @elseif ($internship->status == 4)
                                                <span class="badge badge-danger">Aborted</span>
                                            @endif
                                        </dd>
                                    </dl>
                                </div>

                                <div class="col-md-6">
                                    <p> Internship Prerequisite List: </p>
                                    <ul>
                                        @foreach ($internship->prerequisites as $prerequisites)
                                            <li>{{ $prerequisites->pre_key }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <p> Internship Description: </p>
                                    <p>{{ $internship->description }}</p>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
