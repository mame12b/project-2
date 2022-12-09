@extends('pages.user.inc.app')

@section('header')
    @include('layout.header', ['title' => 'User | Home'])
@endsection

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <h2 class="text-center display-4">Search</h2>
                <div class="col-md-8 offset-md-2 mb-3">
                    <form>
                        <div class="input-group">
                            <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here" id="searchQuery">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default" id="searchQuerySubmitBtn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" id="searchResultDiv">
                    <div class="col-md-12 mt-5">
                        <center>
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">

                        <h3 class="profile-username text-center">{{ ucwords(auth()->user()->getName()) }}</h3>

                        <p class="text-muted text-center">{{ __("Member since ") . \Carbon\Carbon::parse(auth()->user()->created_at)->setTimezone('Africa/Addis_Ababa')->format('M, Y') }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Pending</b> <a class="float-right">{{ number_format($stats[0]) }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Accepted</b> <a class="float-right">{{ number_format($stats[1]) }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Rejected</b> <a class="float-right">{{ number_format($stats[2]) }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
