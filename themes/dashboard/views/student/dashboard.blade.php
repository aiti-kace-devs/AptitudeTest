@extends('layouts.student')
@section('title', 'Portal dashboard')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">My Exams</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->

                <div class="row">
                    @foreach ($portal_exams as $key => $exam)
                        <?php

                        if (strtotime(date('Y-m-d')) > strtotime($exam['exam_date'])) {
                            $cls = 'bg-danger';
                        } else {
                            $val = $key + 1;
                            if ($val % 2 == 0) {
                                $cls = 'bg-info';
                            } else {
                                $cls = 'bg-success';
                            }
                        }

                        ?>
                        <div class="col-lg-4 col-6">
                            <div class="small-box {{ $cls }}">
                                <div class="inner">
                                    <h3>{{ $exam['title'] }}</h3>
                                    <p>{{ $exam['category_name'] }}</p>
                                    <p>Exam date : {{ $exam['exam_date'] }}</p>
                                    <p>Duration : {{ $exam['exam_duration'] }} mins</p>
                                    <p>Pass Mark : {{ $exam['exam_duration'] }} mins</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                @if (strtotime(date('Y-m-d')) <= strtotime($exam['exam_date']))
                                    <a href="{{ url('student/join_exam/' . $exam['exam_id']) }}"
                                        class="small-box-footer">Take Test &nbsp;<i
                                            class="fas fa-arrow-circle-right"></i></a>
                                @endif

                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



@endsection
