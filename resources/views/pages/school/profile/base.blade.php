@extends('pages.school.inc.app')

@section('header')
@include('layout.header', ['title' => 'School | Profile'])
@endsection

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Profile</li>
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
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link @if (!session('setting_page') && !session('upload_page') && !session('password_page')) active @endif" href="#detail" data-toggle="tab">Detail</a></li>
                            <li class="nav-item"><a class="nav-link @if (session('setting_page')) active @endif" href="#setting" data-toggle="tab">Settings</a></li>
                            <li class="nav-item"><a class="nav-link @if (session('password_page')) active @endif" href="#password" data-toggle="tab">password</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane @if (!session('setting_page') && !session('upload_page') && !session('password_page')) active @endif" id="detail">
                                @include('pages.school.profile.detail')
                            </div>

                            <div class="tab-pane @if (session('setting_page')) active @endif" id="setting">
                                @include('pages.school.profile.setting')
                            </div>

                            <div class="tab-pane @if (session('password_page')) active @endif" id="password">
                                @include('pages.school.profile.password')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@endsection
