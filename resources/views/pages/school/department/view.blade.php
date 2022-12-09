@extends('pages.school.inc.app')

@section('header')
    @include('layout.header', ['title' => 'School | Department | View'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Department Detail</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Department</li>
                        <li class="breadcrumb-item"><a href="{{ route('school.department.list') }}">List</a></li>
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
                            <h3 class="card-title">Department Detail</h3>
                            <div class="card-tools mr-5">
                                <a href="{{ route('school.department.list') }}"><button type="button" class="btn btn-tool"><i
                                            class="fas fa-arrow-left"></i>
                                            Back
                                    </button></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <dl class="row">
                                        <dt class="col-sm-3">Department Id:</dt>
                                        <dd class="col-sm-9">{{ $department->id }}</dd>
                                        <dt class="col-sm-3">School Name:</dt>
                                        <dd class="col-sm-9 @if ($department->school->trashed()) text-danger @endif">{{ $department->school->name }}</dd>
                                        <dt class="col-sm-3">Department Head:</dt>
                                        <dd class="col-sm-9">{{ $department->getHeadName() }}</dd>
                                        <dt class="col-sm-3">Department Name:</dt>
                                        <dd class="col-sm-9">{{ $department->name }}</dd>
                                        <dt class="col-sm-3">Description:</dt>
                                        <dd class="col-sm-9">{{ $department->description }}</dd>
                                        <dt class="col-sm-3">Register Date:</dt>
                                        <dd class="col-sm-9">
                                            {{ \Carbon\Carbon::parse($department->created_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-3">Last Update:</dt>
                                        <dd class="col-sm-9">
                                            {{ \Carbon\Carbon::parse($department->updated_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
