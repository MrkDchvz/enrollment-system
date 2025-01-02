@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            margin: 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }
        .details td {
            padding: 5px;
        }
        .schedule, .fees, .table-border-hidden {
            width: 100%;
            border: 1px solid green;
            border-collapse: collapse;
        }

        .table-border-hidden {
            border-style: hidden;
        }
        .schedule th, .schedule td, .fees th, .fees td, .table-border-hidden td, .table-border-hidden th {
            border: 1px solid green;
            text-align: center;
        }
        .fees th, .fees td, .schedule td {
            border-bottom-style: hidden ;
        }


        .footer {
            text-align: left;
            margin-top: 20px;
        }
        .footer p {
            margin: 5px 0;
        }

        .table-border-hidden td, .table-border-hidden th {
            border: none;
        }
        .note {
            font-size: 11px;
            margin-top: 10px;
            color: green;
        }

        .text-green {
            color: green;
        }
        .font-bold {
            font-weight: 700;
        }

        .italic  {
            font-style: italic;
        }

    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <img src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/images/cvsulogo.png'))); ?>" width="50">
        <h1 class="text-green">Cavite State University</h1>
        <p class="text-green">Bacoor City Campus</p>
        <p class="text-green font-bold">REGISTRATION FORM</p>
    </div>

    <!-- Student Details -->
    <div class="details">
        <table style="font-size: 10px;">
            <tr>
                <td colspan="2"><span class="text-green font-bold">Student Number: </span>{{$record->student->student_number}}</td>
                <td><span class="text-green font-bold">Semester: </span>{{$record->semester}}</td>
                <td><span class="text-green font-bold">School Year: </span>{{$record->school_year}}</td>
            </tr>
            <tr>
                <td colspan="2"><span class="text-green font-bold">Student Name: </span>{{$record->student->fullNameCOR}}</td>
                <td><span class="text-green font-bold">Date: </span>{{ Carbon::parse($record->enrollment_date)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 30%;"><span class="text-green font-bold">Course: </span>{{$record->department->department_code}}</td>
                <td style="width: 30%;"><span class="text-green font-bold">Year: </span>{{$record->year_level}}</td>
                <td><span class="text-green font-bold">Encoder: </span>{{$record->user->name}}</td>
                <td><span class="text-green font-bold">Major: </span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="text-green font-bold">Address: </span> {{$record->student->address}}</td>
                <td><span class="text-green font-bold">Section: </span>{{$record->section->sectionName}}</td>
            </tr>
        </table>
    </div>

    <!-- Schedule Table -->
    <table class="schedule">
        <thead>
        <tr>
            <th class="text-green">Sched Code</th>
            <th class="text-green">Course Code</th>
            <th class="text-green">Course Description</th>
            <th class="text-green">Units</th>
            <th class="text-green">Time</th>
            <th class="text-green">Day</th>
            <th class="text-green">Room</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($record->courseEnrollments as $courseEnrollment)
            <tr>
                <td>TBA</td>
                <td>{{ $courseEnrollment->course->course_code }}</td>
                <td>{{ $courseEnrollment->course_name}}</td>
                <td>
                    <table class="table-border-hidden" style="border: none; width: 100%;">
                        <tr>
                            <td style="width: 50%;">{!! $courseEnrollment->lecture_units == 0 ? '&nbsp;' : $courseEnrollment->lecture_units !!}</td>
                            <td style="width: 50%;">{!! $courseEnrollment->lab_units == 0 ? '&nbsp;' : $courseEnrollment->lab_units !!}</td>
                        </tr>
                    </table>
                </td>
                <td>TBA</td>
                <td>TBA</td>
                <td>TBA</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td>Lec</td>
                        <td>Lab</td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        </tbody>
    </table>

    <!-- Fees Table -->
    <table class="fees">
        <thead>
        <tr>
            <th style="width: 100%;">{!! '&nbsp;' !!}</th>
            <th style="width: 100%;">{!! '&nbsp;' !!}</th>
            <th style="width: 100%;">{!! '&nbsp;' !!}</th>
            <th style="width: 100%;">{!! '&nbsp;' !!}</th>
        </tr>
        </thead>
        <tbody>
        {{-- ROW 1--}}
        <tr>
            <td class="text-green font-bold" style="text-align: left;">Laboratory Fees</td>
            <td class="text-green font-bold" style="text-align: left;">Other Fees</td>
            <td class="text-green font-bold" style="text-align: left;">Assessment</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
        </tr>
        {{-- ROW 2--}}
        <tr>
            <td style="width: 100%; border-bottom-style: solid;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid;">{!! '&nbsp;' !!}</td>
            <td style="border-bottom-style: solid;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green font-bold" style="text-align: left ">Total UNITS:</td>
                        <td style="text-align: right;">{{$record->courseEnrollments->sum('lecture_units') + $record->courseEnrollments->sum('lab_units')}}</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 3--}}
        <tr>
            {{-- Com. Lab --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Com. Lab</td>
                        <td style="width: 20%; text-align: center "> <span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Com. Lab');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{-- NSTP --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">NSTP</td>
                        <td style="width: 20%; text-align: center "> <span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'NSTP');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{-- Tuition Fee --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Tuition Fee</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Tuition Fee');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--  Total Hours --}}
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green font-bold" style="text-align: left ">Total HOURS:</td>
                        <td style="text-align: right;">{{$record->courseEnrollments->sum('lecture_hours') + $record->courseEnrollments->sum('lab_hours')}}</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 4--}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{--Reg. Fee--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Reg. Fee</td>
                        <td style="width: 20%; text-align: center "> <span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Reg. Fee');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{-- SFDF --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">SFDF</td>
                        <td style="width: 20%; text-align: center "> <span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'SFDF');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>




        </tr>
        {{-- ROW 5--}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{--  ID --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">ID</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'ID');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
        </td>
            {{--  SRF --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">SRF</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'SRF');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--TOTAL AMOUNT --}}
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green font-bold" style="width: 60%; text-align: left;">TOTAL AMOUNT</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td class="font-bold" style="width: 20%; text-align: right;">{{number_format($record->enrollmentFees->sum('amount'))}}</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 6 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{-- Late Reg. --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Late Reg.</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Late Reg.');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{-- Misc --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Misc.</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Misc.');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="text-green" style="width: 100%; text-align: left">Scholarship</td>
        </tr>
        {{-- ROW 7 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{-- Insurance--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Insurance</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Insurance');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--   Athletics--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Athletics</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Athletics');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--   Scholarship --}}
            <td class="font-bold" style="font-size:10px; width: 100%;">CHED Free Tution and Misc. Fee</td>
        </tr>
        {{-- ROW 8 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{-- SCUAA --}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">SCUAA</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'SCUAA');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>

        </tr>
        {{-- ROW 9 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{--Library Fee--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Library Fee</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Library Fee');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--Tuition Fee--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 40%; text-align: left ">Tuition</td>
                        <td style="width: 30%; text-align: left "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Tuition Fee');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 10 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{--Lab Fees--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Lab Fees</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Lab Fees');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--SFDF--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 40%; text-align: left ">SFDF</td>
                        <td style="width: 30%; text-align: left "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'SFDF');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        {{-- ROW 11 --}}
        <tr>

            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            {{-- Other Fees--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: right ">Other Fees</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'Other Fees');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
            {{--SRF--}}
            <td style="width: 100%;">
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 40%; text-align: left ">SRF</td>
                        <td style="width: 30%; text-align: left "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td style="width: 30%; text-align: right;">
                            @php
                                $comLabFee = $record->enrollmentFees()
                                    ->whereHas('fee', function ($query) {
                                        $query->where('name', 'SRF');
                                    })
                                    ->first();
                            @endphp
                            {{ $comLabFee ? number_format($comLabFee->amount, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 12 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>
        </tr>
        {{-- ROW 13 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td class="text-green font-bold" style="width: 100%; border-bottom-style: solid; text-align: center">Terms of Payment</td>
        </tr>
        {{-- ROW 14 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 60%; text-align: left;">First</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td class="font-bold" style="width: 20%; text-align: right;">{{number_format($record->enrollmentFees->sum('amount'))}}</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 15 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 60%; text-align: left;">Second</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td class="font-bold" style="width: 20%; text-align: center;">-</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 16 --}}
        <tr>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td style="width: 100%;">{!! '&nbsp;' !!}</td>
            <td>
                <table class="table-border-hidden" style="border: none; width: 100%;">
                    <tr>
                        <td class="text-green" style="width: 60%; text-align: left;">Third</td>
                        <td style="width: 20%; text-align: center "><span style="font-family: DejaVu Sans, sans-serif;">&#8369;</span></td>
                        <td class="font-bold" style="width: 20%; text-align: center;">-</td>

                    </tr>
                </table>
            </td>
        </tr>
        {{-- ROW 17 --}}
        <tr>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>
            <td style="width: 100%; border-bottom-style: solid">{!! '&nbsp;' !!}</td>
        </tr>





        </tbody>
    </table>

    <p class="text-green font-bold">NOTE: Your slots on the above subjects will be confirmed only upon payment.</p>
    <!-- Footer Section -->
    <div class="footer">
        <table style="font-size:10px; width: 60%; border-style: hidden;" class="table-border-hidden">
            {{--OLD NEW STUDENT--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Old/New Student:</td>
                            <td style="text-align: left; width: 50%;">{{$record->old_new_student}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{--Registration Status--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Registration Status:</td>
                            <td style="text-align: left; width: 50%;">{{$record->registration_status}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{--Date of Birth--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Date of Birth:</td>
                            <td style="text-align: left; width: 50%;">{{Carbon::parse($record->student->date_of_birth)->format('F j, Y')}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{--Gender--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Gender:</td>
                            <td style="text-align: left; width: 50%;">{{$record->student->gender}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{--Gender--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Contact Number:</td>
                            <td style="text-align: left; width: 50%;">0{{$record->student->contact_number}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{--EMail--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">E-mail Address:</td>
                            <td style="text-align: left; width: 50%;">{{$record->student->email}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>{!! '&nbsp;' !!}</td>
                <td>{!! '&nbsp;' !!}</td>
            </tr>
            {{--Student's Sign--}}
            <tr>
                <td>
                    <table class="table-border-hidden">
                        <tr>
                            <td class="font-bold italic" style="text-align: left; width: 20%;">Student's signature</td>
                            <td style="text-align: left; width: 50%;">_________________________</td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </div>


</div>
</body>
</html>
