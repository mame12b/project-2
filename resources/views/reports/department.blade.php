<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Department | Report</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body style="font-size: 10px;">
    <div class="wrapper">
        <section class="invoice" style="border: none !important;">

            <div class="row">
                <div class="col-12">
                    <h2 class="page-header" style="padding: 10px;">
                        Jimma University
                        <small class="float-right">Date: {{ \Carbon\Carbon::parse($ats_report->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y')  }}</small>
                    </h2>
                </div>

            </div>

            <div class="row">

                <div class="col-6">
                  <p class="lead">Informations</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Report Reference:</th>
                        <td>ATS-R-{{ $ats_report->id }}</td>
                      </tr>
                      <tr>
                        <th>Issue Date:</th>
                        <td>{{ \Carbon\Carbon::parse($ats_report->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>

                    </tr>
                    <tr>
                        <th>Start Date:</th>
                        <td>{{ \Carbon\Carbon::parse($ats_report->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date:</th>
                        <td>{{ \Carbon\Carbon::parse($ats_report->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                      </tr>
                      <tr>
                        <th>Report For:</th>
                        <td>@if ($ats_report->getOwner->name) {{ $ats_report->getOwner->name }} @else {{ 'System' }} @endif - {{ ucwords($ats_report->owner_type) }}</td>
                      </tr>
                    </table>
                  </div>
                </div>

                <div class="col-6">
                    <br><br>
                    <b>Name:</b> ____________________________ <br><br>
                    <b>Signature:</b> __________________________
                </div>

            </div>

            <div style="page-break-after: always;"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <h3 style="padding: 10px;">Internship List:</h3>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>DeadLine</th>
                                        <th>Qouta</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($internships) > 0)
                                    @foreach ($internships as $internship)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($internship->title) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ $internship->quota }}</td>
                                        <td>
                                            @if ($internship->status == 0)
                                                <span class="text-danger">Ended</span>
                                            @elseif ($internship->status == 1)
                                                <span class="text-success">Accepting Applicants</span>
                                            @elseif ($internship->status == 2)
                                                <span class="text-warning">Ongoing</span>
                                            @elseif ($internship->status == 3)
                                                <span class="text-info">Waiting</span>
                                            @elseif ($internship->status == 4)
                                                <span class="text-danger">Aborted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Data Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <hr>
                        <br>
                        <h3 style="padding: 10px;" >Application List:</h3>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Applicant Name</th>
                                        <th>Internship</th>
                                        <th>Internship Status</th>
                                        <th>Applied Date</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Application Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($user_applications) > 0)
                                    @foreach ($user_applications as $user_application)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($user_application->user->getName()) }}</td>
                                        <td>{{ ucwords($user_application->internship->title) }}</td>
                                        <td>
                                            @if ($user_application->internship->status == 0)
                                                <span class="text-danger">Ended</span>
                                            @elseif ($user_application->internship->status == 1)
                                                <span class="text-success">Accepting Applicants</span>
                                            @elseif ($user_application->internship->status == 2)
                                                <span class="text-warning">Ongoing</span>
                                            @elseif ($user_application->internship->status == 3)
                                                <span class="text-info">Waiting</span>
                                            @elseif ($user_application->internship->status == 4)
                                                <span class="text-danger">Aborted</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($user_application->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user_application->internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user_application->internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>
                                            @if ($user_application->status == 0)
                                            <span class="text-warning">Pending</span>
                                            @elseif ($user_application->status == 1)
                                            <span class="text-success">Accepted</span>
                                            @elseif ($user_application->status == 2)
                                            <span class="text-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8" class="text-center">No Data Found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>
</body>

</html>
