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
                                    <table class="table table-striped table-bordered table-hover datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                {{-- <th>DOB</th> --}}
                                                <th>Exam</th>
                                                {{-- <th>Exam Date</th> --}}
                                                <th>Result</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $key => $std)
                                                <tr>
                                                    <td>{{ $std['id'] }}</td>
                                                    <td>{{ $std['name'] }}</td>
                                                    <td>{{ $std['email'] }}</td>
                                                    <td>{{ $std['title'] }}</td>
                                                    <td>

                                                        @if ($std['submitted'] == null)
                                                            <span class="badge badge-secondary">N/A</span>
                                                        @else
                                                            <span
                                                                class="badge badge-{{ ($std->result->yes_ans <= 20 ? 'danger' : $std->result->yes_ans <= 25) ? 'primary' : 'success' }}">{{ round(($std->result->yes_ans / 30) * 100) }}%</span>
                                                            {{-- <span class="badge badge-warning">{{ $std->result->yes_ans}}</span> --}}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($std['submitted'] == null)
                                                            <span class="badge badge-secondary">Not Taken</span>
                                                        @elseif($std->result->yes_ans >= $std['passmark'])
                                                            <span class="badge badge-success">PASS</span>
                                                        @else
                                                            <span class="badge badge-danger">FAIL</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- <a href="{{url('admin/edit_students/'.$std['id'])}}" class="btn btn-primary">Edit</a> --}}
                                                        <a href="{{ url('admin/delete_students/' . $std['id']) }}"
                                                            class="btn btn-danger btn-sm">Delete</a>

                                                        @if ($std['exam_joined'] == 1)
                                                            <a href="{{ url('admin/admin_view_result/' . $std['user_id']) }}"
                                                                class="btn btn-success btn-sm">View Result</a>
                                                        @endif
                                                        <a href="{{ route('admin.reset-exam', [$std['exam_id'], $std['user_id']]) }}"
                                                            class="btn btn-info btn-sm">Reset Result</a>
                                                    </td>
                                                </tr>
                                            @endforeach
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



        @endsection
