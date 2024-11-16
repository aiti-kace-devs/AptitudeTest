@extends('layouts.app')
@section('title', 'View Attendance')
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- /.content-header -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">View Attendance Report</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View Attendance Report</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <form class="row d-flex align-items-center" method="POST"
                                action="{{ route('admin.generateReport') }}">
                                @csrf
                                <div class="mb-4 col-md-4">
                                    <label for="report_type" class="form-label">Report Type </label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <option value="course_summary" @if ($report_type == 'course_summary') selected @endif>
                                            Course Attendance Summary</option>
                                        <option value="student_summary" @if ($report_type == 'student_summary') selected @endif>
                                            Student Attendance Summary</option>

                                        {{-- @foreach ($courses as $course)
                                            <option value="{{ $course->id }}"
                                                @if ($course->id == $selectedCourse?->id) selected @endif>
                                                {{ $course->location }} -
                                                {{ $course->course_name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="mb-4 col-md-4">
                                    <label for="dates">Select Date</label>
                                    <input type="text" name="dates" id="selected_date" class="form-control"
                                        value="{{ $dates }}" required>
                                </div>
                                <div class="mb-1 col-md-4">
                                    <input type="submit" class="btn btn-success mt-2" value="Generate Report" />
                                </div>
                                <div class="mb-1 col-md-4">
                                    <button type="submit" name="export" value="1" class="btn btn-primary mt-2">Export
                                        Report</button>
                                </div>
                            </form>

                            <div class="card-body">
                                @if ($report_type)
                                    <h1 class="text-uppercase mb-2 text-primary">{{ str_replace('_', ' ', $report_type) }}
                                        Report For
                                        {{ $dates }}</h1>
                                @endif
                                <table class="table table-striped table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th colspan="1"></th>
                                            <th colspan="{{ count($dates_array) }}">Dates</th>
                                            <th colspan="2">Statistics</th>

                                        </tr>
                                        <tr>
                                            <th>
                                                @if ($report_type == 'course_summary')
                                                    Course Name
                                                @else
                                                    Student Name
                                                @endif
                                            </th>
                                            @foreach ($dates_array as $date)
                                                <th>{{ $date }}</th>
                                            @endforeach
                                            <th>Total</th>
                                            <th>Average</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($report_type == 'course_summary')
                                            @foreach ($attendanceData as $course => $record)
                                                <tr>
                                                    <td>{{ $course }}</td>
                                                    @foreach ($dates_array as $date)
                                                        <th>{{ $record->get($date)?->values()[0]->total ?? 0 }}</th>
                                                        {{-- <th>{{ dump($record) }}</th> --}}
                                                    @endforeach
                                                    <td>{{ $record->first()->values()[0]->attendance_total }}</td>
                                                    <td>{{ $record->first()->values()[0]->average }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @if ($report_type == 'student_summary')
                                            @foreach ($studentAttendanceData as $student => $record)
                                                <tr>
                                                    <td>{{ $student }}</td>
                                                    @foreach ($dates_array as $date)
                                                        <th>{{ $record->get($date)?->values()[0]->total ?? 0 }}</th>
                                                        {{-- <th>{{ dump($record) }}</th> --}}
                                                    @endforeach
                                                    <td>{{ $record->first()->values()[0]->attendance_total }}</td>
                                                    <td>{{ $record->first()->values()[0]->average }}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
        </div>
        </section>
    </div>
    <!-- /.content-header -->
@endsection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input[name="dates"]').daterangepicker({
                showWeekNumbers: true,
                locale: {
                    format: 'MMMM D, YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            });
        });
    </script>
@endpush
