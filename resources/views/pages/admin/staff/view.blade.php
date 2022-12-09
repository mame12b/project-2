@extends('pages.admin.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Admin | Staff | View'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Staff Detail</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Staff</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staff.list') }}">List</a></li>
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
                            <h3 class="card-title">Staff Detail</h3>
                            <div class="card-tools mr-5">
                                <a href="{{ route('admin.staff.list') }}"><button type="button" class="btn btn-tool"><i
                                            class="fas fa-arrow-left"></i>
                                            Back
                                    </button></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5>Account Information: </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Staff Id:</dt>
                                        <dd class="col-sm-8">{{ $staff->id }}</dd>
                                        <dt class="col-sm-4">Email:</dt>
                                        <dd class="col-sm-8">{{ $staff->email }}</dd>
                                        <dt class="col-sm-4">Last Login:</dt>
                                        <dd class="col-sm-8">
                                            @if ($staff->last_login)
                                                {{ \Carbon\Carbon::parse($staff->last_login)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                            @else
                                                Never Loged in
                                            @endif
                                        </dd>
                                        <dt class="col-sm-4">Status:</dt>
                                        <dd class="col-sm-8">{{ $staff->type }}</dd>
                                        <dt class="col-sm-4">Register Date:</dt>
                                        <dd class="col-sm-8">
                                            {{ \Carbon\Carbon::parse($staff->created_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-4">Last Update:</dt>
                                        <dd class="col-sm-8">
                                            {{ \Carbon\Carbon::parse($staff->updated_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <h5>User Information: </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Full Name:</dt>
                                        <dd class="col-sm-8">{{ $staff->information->first_name }} {{ $staff->information->middle_name }} {{ $staff->information->last_name }}</dd>
                                        <dt class="col-sm-4">Phone Number:</dt>
                                        <dd class="col-sm-8">{{ ($staff->information->phone_number) ? $staff->information->phone_number : '-' }}</dd>
                                        <dt class="col-sm-4">Register Date:</dt>
                                        <dd class="col-sm-8">
                                            {{ \Carbon\Carbon::parse($staff->information->created_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
                                        </dd>
                                        <dt class="col-sm-4">Last Update:</dt>
                                        <dd class="col-sm-8">
                                            {{ \Carbon\Carbon::parse($staff->information->updated_at)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}
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
