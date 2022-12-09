<p><b> Account Informations: </b></p>
<dl class="row">
    <dt class="col-sm-3">Account Id: </dt>
    <dd class="col-sm-9">{{ Auth::user()->id }}</dd>
    <dt class="col-sm-3">Email Address: </dt>
    <dd class="col-sm-9">{{ Auth::user()->email }}</dd>
    <dt class="col-sm-3">Last Login: </dt>
    <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse(Auth::user()->last_login)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
    </dd>
    <dt class="col-sm-3">Email Verfication Date: </dt>
    <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse(Auth::user()->email_verified_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
    </dd>
    <dt class="col-sm-3">Registered Date: </dt>
    <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse(Auth::user()->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
    </dd>
    <dt class="col-sm-3">Last update: </dt>
    <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse(Auth::user()->updated_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}
    </dd>
</dl>

@if (Auth::user()->information)


<hr>

<p><b> User Informations: </b></p>
<div class="row">
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-4">Full Name: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->first_name)?ucwords(Auth::user()->information->first_name):'' }} {{ (Auth::user()->information->middle_name)?ucwords(Auth::user()->information->middle_name):'' }} {{ (Auth::user()->information->last_name)?ucwords(Auth::user()->information->last_name):'' }}</dd>
            <dt class="col-sm-4">Student ID: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->student_id)?Auth::user()->information->student_id:'-' }}</dd>
            <dt class="col-sm-4">Comulative GPA: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->cgpa)?Auth::user()->information->cgpa:'-' }}</dd>
            <dt class="col-sm-4">Year of Study: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->year_of_study)?Auth::user()->information->year_of_study:'-' }}</dd>
            <dt class="col-sm-4">Phone Number: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->phone_number)?Auth::user()->information->phone_number:'-' }}</dd>
            <dt class="col-sm-4">City: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->city)?Auth::user()->information->city:'-' }}</dd>
        </dl>
    </div>
    <div class="col-md-6">
        <dl  class="row">
            <dt class="col-sm-4">University: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->university)?Auth::user()->information->university:'-' }}</dd>
            <dt class="col-sm-4">School: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->degree)?Auth::user()->information->degree:'-' }}</dd>
            <dt class="col-sm-4">Department: </dt>
            <dd class="col-sm-8">{{ (Auth::user()->information->department)?Auth::user()->information->department:'-' }}</dd>
            <dt class="col-sm-4">Registered Date: </dt>
            <dd class="col-sm-8">{{ \Carbon\Carbon::parse(Auth::user()->information->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}</dd>
            <dt class="col-sm-4">Last update: </dt>
            <dd class="col-sm-8">{{ \Carbon\Carbon::parse(Auth::user()->information->updated_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y \a\t H:i a') }}</dd>
        </dl>
    </div>
</div>

@if(Auth::user()->information->application_letter_file_path || Auth::user()->information->application_acceptance_file_path || Auth::user()->information->student_id_file_path)
<hr>
<p><b>Uploaded file list:</b></p>
<ul>
    @if(Auth::user()->information->application_letter_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->application_letter_file_path) }}"> Application Letter</a></li> @endif
    @if(Auth::user()->information->application_acceptance_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->application_acceptance_file_path) }}"> Application Acceptance Form </a> </li> @endif
    @if(Auth::user()->information->student_id_file_path)<li><a target="_blank" href="{{ asset('/uploads/application_acceptance/'.Auth::user()->information->student_id_file_path) }}">Student ID</a></li> @endif
</ul>
@endif

<hr>

<p><b>About Me:</b></p>
<p>{{ (Auth::user()->information->about_me)?Auth::user()->information->about_me:'No data...'}}</p>

@else

<p>No user information found</p>

@endif
