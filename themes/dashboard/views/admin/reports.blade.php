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
                            <form class="row d-flex align-items-center mb-4" method="POST"
                                action="{{ route('admin.generateReport') }}">
                                @csrf
                                <div class=" col-md-3">
                                    <label for="report_type" class="form-label">Report Type </label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <option value="course_summary" @if ($report_type == 'course_summary') selected @endif>
                                            Course Attendance Summary</option>
                                        <option value="student_summary" @if ($report_type == 'student_summary') selected @endif>
                                            Student Attendance Summary</option>

                                    </select>
                                </div>
                                <div class="col-1">
                                    <label for="daily" class="form-label">Daily?</label>
                                    <select name="daily" id="daily" class="form-control" aria-hidden="true">
                                        <option value="no" @if ('no' == ($selectedDailyOption ?? null)) selected @endif>No
                                        </option>
                                        <option value="yes" @if ('yes' == ($selectedDailyOption ?? null)) selected @endif>Yes
                                        </option>
                                    </select>
                                </div>
                                <div id="course_dropdown" class="col-md-3" style="display: none;">
                                    <label for="course" class="form-label">Select Course</label>
                                    <select name="course_id" id="course_id" class="form-control" aria-hidden="true">
                                        <option value="">Select Course</option>
                                        <option value="all" @if ('all' == $selectedCourse) selected @endif>All Courses
                                        </option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}"
                                                @if ($course->id == ($selectedCourse['id'] ?? null)) selected @endif>
                                                {{ $course->location }} -
                                                {{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="dates">Select Date</label>
                                    <input type="text" name="dates" id="selected_date" class="form-control"
                                        value="{{ $dates }}" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" class="btn btn-success mt-2" value="Generate Report" />
                                </div>
                            </form>

                            <div class="card-body">
                                @if ($report_type)
                                    <h4 class="text-uppercase mb-2 text-primary" id="reportHeading">
                                        {{ $selectedCourse->location ?? '' }}
                                        {{ $selectedCourse['course_name'] ?? '' }}
                                        {{ str_replace('_', ' ', $report_type) }}
                                        Report For
                                        {{ $dates }}</h4>
                                @endif
                                <table class="table table-striped table-bordered table-hover datatable">
                                    <thead>
                                        {{-- <tr>
                                            <th colspan="1"></th>
                                            <th colspan="{{ count($dates_array) }}">Dates</th>
                                            <th colspan="2">Statistics</th>

                                        </tr> --}}
                                        <tr>
                                            @if ($report_type == 'course_summary')
                                                <th>Course Name</th>
                                                <th>Average</th>
                                                <th>Total</th>
                                            @else
                                                <th>Student Name</th>
                                                <th>Course Name</th>
                                                <th>Total</th>
                                                <th>Gender</th>
                                                <th>Network Type</th>
                                                <th>Phone Number</th>
                                            @endif
                                            @if ($selectedDailyOption == 'yes')
                                                @foreach ($dates_array as $date)
                                                    <th>{{ $date }}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($report_type == 'course_summary')
                                            @foreach ($attendanceData as $course => $record)
                                                <tr>
                                                    <td>{{ $course }}</td>
                                                    <td>{{ floor($record->first()->values()[0]->average ?? 0) }}</td>
                                                    <td>{{ $record->first()->values()[0]->attendance_total }}</td>
                                                    @if ($selectedDailyOption == 'yes')
                                                        @foreach ($dates_array as $date)
                                                            <th>{{ $record->get($date)?->values()[0]->total ?? 0 }}</th>
                                                        @endforeach
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @endif

                                        @if ($report_type == 'student_summary')
                                            @foreach ($studentAttendanceData as $student => $record)
                                                <tr class="text-uppercase">
                                                    <td>{{ $student }}</td>
                                                    <td>{{ $record->first()[0]->course_name }}
                                                        ({{ $record->first()[0]->course_location }})
                                                    </td>
                                                    <td>{{ $record->first()->values()[0]->attendance_total ?? 0 }}
                                                    </td>
                                                    <td>{{ $record->first()[0]->user_gender ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $record->first()[0]->user_network_type ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $record->first()[0]->user_contact ?? 'N/A' }}
                                                    </td>
                                                    @if ($selectedDailyOption == 'yes')
                                                        @foreach ($dates_array as $date)
                                                            <th>{{ $record->get($date)?->values()[0]->attendance_date ? '✅' : '❌' }}
                                                            </th>
                                                            {{-- <th>{{ dump($record) }}</th> --}}
                                                        @endforeach
                                                    @endif

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
                    'Start to Date': [moment('2024-10-14'), moment()],
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

        function toggleCourseDropdown() {
            const reportType = document.getElementById('report_type').value;
            const courseDropdown = document.getElementById('course_dropdown');
            if (reportType === 'student_summary') {
                courseDropdown.style.display = 'block';
            } else {
                courseDropdown.style.display = 'none';
            }
        }
        document.getElementById('report_type').addEventListener('change', toggleCourseDropdown);
        document.querySelector('form').addEventListener('submit', function(event) {
            toggleCourseDropdown();
        });
        toggleCourseDropdown();

        $(document).prop('title', $('#reportHeading').text());
    </script>
@endpush
