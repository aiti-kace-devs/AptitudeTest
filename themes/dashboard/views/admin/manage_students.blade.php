@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <!-- /.content-header -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Manage Students</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Manage Exam</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Title</h3>

                                    <div class="card-tools">
                                        <a class="btn btn-info btn-sm" href="javascript:;" data-toggle="modal"
                                            data-target="#myModal">Add new student</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <select class="form-control filter-select" data-column="3"
                                                data-filter="ex_name">
                                                <option value="">All Exams</option>
                                                @foreach ($exams as $exam)
                                                    <option value="{{ $exam['title'] }}">{{ $exam['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control filter-select" data-filter="course">
                                                <option value="">All Courses</option>
                                                @foreach ($courses as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <select class="form-control filter-select" data-column="6" data-filter="status">
                                                <option value="">All Statuses</option>
                                                <option value="passed">Passed</option>
                                                <option value="failed">Failed</option>
                                                <option value="not_taken">Not Taken</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control filter-select" data-filter="age_range">
                                                <option value="">All Ages</option>
                                                <option value="15-19">15-19</option>
                                                <option value="20-24">20-24</option>
                                                <option value="25-35">25-35</option>
                                                <option value="36-45">36-45</option>
                                                <option value="45+">45+</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="studentSearch"
                                                placeholder="Search by name or email">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary" id="admit-selected">Admit Selected
                                                Students</button>
                                        </div>
                                    </div>
                                    <table id="studentsTable" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="20px"><input type="checkbox" id="select-all"></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Age</th>
                                                <th>Course</th>
                                                <th>Exam</th>
                                                <th>Score</th>
                                                <th>Result</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add new Student</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('admin/add_new_students') }}" class="database_operation">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Enter Name</label>
                                        {{ csrf_field() }}
                                        <input type="text" required="required" name="name" placeholder="Enter name"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Enter E-mail</label>
                                        {{ csrf_field() }}
                                        <input type="text" required="required" name="email" placeholder="Enter name"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Enter Mobile-no</label>
                                        {{ csrf_field() }}
                                        <input type="text" required="required" name="mobile_no"
                                            placeholder="Enter mobile-no" class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Select DOB</label>
                            <input type="date" required="required" name="dob"  class="form-control">
                        </div>
                    </div> --}}
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Select exam</label>
                                        <select class="form-control" required="required" name="exam">
                                            <option value="">Select</option>
                                            @foreach ($exams as $exam)
                                                <option value="{{ $exam['id'] }}">{{ $exam['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">password</label>
                                        <input type="password" required="required" name="password"
                                            placeholder="Enter password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>


            <script>
                $(document).ready(function() {
                    var allFilteredIds = [];
                    var manuallySelectedIds = [];
                    var isFilterApplied = false;
                    var debounceTimer;

                    var table = $('#studentsTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.manage_students') }}",
                            type: "GET",
                            data: function(d) {
                                d.draw = d.draw;
                                d.start = d.start;
                                d.length = d.length;

                                isFilterApplied = false;

                                if ($('select[data-filter="ex_name"]').val()) {
                                    d['filter[ex_name]'] = $('select[data-filter="ex_name"]').val();
                                    isFilterApplied = true;
                                }
                                if ($('select[data-filter="status"]').val()) {
                                    d['filter[status]'] = $('select[data-filter="status"]').val();
                                    isFilterApplied = true;
                                }
                                if ($('select[data-filter="age_range"]').val()) {
                                    d['filter[age_range]'] = $('select[data-filter="age_range"]').val();
                                    isFilterApplied = true;
                                }
                                if ($('select[data-filter="course"]').val()) {
                                    d['filter[course]'] = $('select[data-filter="course"]').val();
                                    isFilterApplied = true;
                                }
                                if ($('#studentSearch').val()) {
                                    d['filter[search_term]'] = $('#studentSearch').val();
                                    isFilterApplied = true;
                                }
                            },
                            dataSrc: function(json) {
                                allFilteredIds = json.all_filtered_ids || [];
                                return json.data;
                            },
                            error: function(xhr, error, thrown) {
                                console.log("AJAX Error:", xhr.responseText);
                                $('#studentsTable tbody').html(
                                    '<tr><td colspan="10" class="text-center text-danger">Error loading data. Please try again.</td></tr>'
                                );
                            }
                        },
                        columns: [{
                                data: 'checkbox',
                                name: 'checkbox',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return '<input type="checkbox" class="student-checkbox" value="' + row
                                        .id + '" ' +
                                        (isFilterApplied ? 'checked' : '') + '>';
                                }
                            },
                            {
                                data: 'name',
                                name: 'users.name'
                            },
                            {
                                data: 'email',
                                name: 'users.email'
                            },
                            {
                                data: 'age',
                                name: 'users.age'
                            },
                            {
                                data: 'course_name',
                                name: 'course_name'
                            },
                            {
                                data: 'ex_name',
                                name: 'oex_exam_masters.title'
                            },
                            {
                                data: 'score',
                                name: 'score',
                                orderable: false
                            },
                            {
                                data: 'result',
                                name: 'result',
                                orderable: false
                            },
                            {
                                data: 'status',
                                name: 'status',
                                orderable: false
                            },
                            {
                                data: 'actions',
                                name: 'actions',
                                orderable: false
                            }
                        ],
                        columnDefs: [{
                                targets: 0,
                                width: "5%"
                            },
                            {
                                targets: -1,
                                width: "15%"
                            }
                        ],
                        order: [
                            [1, 'asc']
                        ],
                        drawCallback: function(settings) {
                            if (isFilterApplied) {
                                $('.student-checkbox').prop('checked', true);
                                manuallySelectedIds = [...allFilteredIds];
                                manuallySelectedIds = [];
                            }

                            var allChecked = $('.student-checkbox:not(:checked)').length === 0;
                            $('#select-all').prop('checked', allChecked);
                        }
                    });

                    $('#studentSearch').on('keyup', function() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(function() {
                            table.ajax.reload();
                        }, 500);
                    });

                    $('select[data-filter="ex_name"], select[data-filter="status"], select[data-filter="course"]').on(
                        'change',
                        function() {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(function() {
                                table.ajax.reload();
                            }, 300);
                        });

                    $('select[data-filter="age_range"]').on('change', function() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(function() {
                            table.ajax.reload();
                        }, 300);
                    });

                    $('#clear-age-filter').click(function() {
                        $('input[name="age_range"]').prop('checked', false);
                        table.ajax.reload();
                    });

                    $(document).on('change', '.student-checkbox', function() {
                        var studentId = $(this).val();
                        if ($(this).is(':checked')) {
                            if (!manuallySelectedIds.includes(studentId)) {
                                manuallySelectedIds.push(studentId);
                            }
                        } else {
                            manuallySelectedIds = manuallySelectedIds.filter(id => id != studentId);
                        }

                        var allChecked = $('.student-checkbox:not(:checked)').length === 0;
                        $('#select-all').prop('checked', allChecked);
                    });

                    $('#select-all').change(function() {
                        var isChecked = $(this).prop('checked');
                        $('.student-checkbox').prop('checked', isChecked);

                        if (isChecked) {
                            manuallySelectedIds = [...allFilteredIds];
                        } else {
                            manuallySelectedIds = [];
                        }
                    });

                    $('#admit-selected').click(function() {
                        var selected = manuallySelectedIds.length > 0 ? manuallySelectedIds : allFilteredIds;

                        if (selected.length === 0) {
                            toastr.warning('No students selected or no students match your filters');
                            return;
                        }

                        var btn = $(this);
                        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                        Swal.fire({
                            title: 'Admit Students?',
                            text: 'You are about to admit ' + selected.length + ' students. Continue?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, admit them',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admit_student') }}",
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        student_ids: selected
                                    },
                                    success: function(response) {
                                        toastr.success(response.message);
                                        table.ajax.reload();
                                        manuallySelectedIds = [];
                                    },
                                    error: function(xhr) {
                                        toastr.error(xhr.responseJSON?.message ||
                                            'An error occurred');
                                    },
                                    complete: function() {
                                        btn.prop('disabled', false).html(
                                            'Admit Selected Students');
                                    }
                                });
                            } else {
                                btn.prop('disabled', false).html('Admit Selected Students');
                            }
                        });
                    });
                });
            </script>
        @endsection
