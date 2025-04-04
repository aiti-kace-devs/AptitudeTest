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
                        {{-- <h1 class="m-0">My Exams</h1> --}}
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Test Details</li>
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
                                $cls = 'bg-info';
                            }
                        }

                        ?>
                        {{-- <div class="col-lg-8 col-12 mx-auto">
                            <div class="small-box {{ $cls }} text-center">
                                <div class="inner">
                                    <h3>{{ $exam['title'] }}</h3>
                                    <p>{{ $exam['category_name'] }}</p>
                                    <p>Exam date : {{ $exam['exam_date'] }}</p>
                                    <p>Duration : {{ $exam['exam_duration'] }} mins</p>
                                    <p>Pass Mark : {{ $exam['passmark'] }}</p>
                                    <p>Total Questions : {{ $exam['question_count'] }}</p>

                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                @if (strtotime(date('Y-m-d')) <= strtotime($exam['exam_date']))
                                    <a href="{{ url('student/join_exam/' . $exam['exam_id']) }}"
                                    class="btn btn-warning mt-3 mb-3">Take Test &nbsp;<i
                                            class="fas fa-arrow-circle-right"></i></a>
                                @endif

                            </div>
                        </div> --}}

                        <div class="col-12 col-md-8 mx-auto px-0 px-md-2">
                            <div class="small-box text-center custom-card p-4 mobile-full-card">
                                <div class="inner">
                                    <!-- Exam Title -->
                                    <h2 class="exam-title">{{ $exam['title'] }}</h2>

                                    <!-- Exam Category -->
                                    <p class="exam-category">Category: {{ $exam['category_name'] }}</p>

                                    <!-- Exam Details -->
                                    <div class="exam-details py-3">
                                        <p class="exam-detail"><strong>Exam Deadline:</strong><x-exam-deadline
                                                :date="$exam['exam_date']"></x-exam-deadline></p>
                                        <p class="exam-detail"><strong>Duration:</strong> {{ $exam['exam_duration'] }} mins
                                        </p>
                                        {{-- <p class="exam-detail"><strong>Pass Mark:</strong> {{ $exam['passmark'] }}</p> --}}
                                        <p class="exam-detail"><strong>Total Questions:</strong>
                                            {{ $exam['question_count'] }}</p>
                                    </div>
                                </div>

                                <!-- Icon -->
                                <div class="icon my-3">
                                    <i class="ion ion-document-text exam-icon"></i>
                                </div>

                                <!-- Call to Action Button -->
                                <x-can-take-exam :date="$exam['exam_date']">
                                    @if ($exam['submitted'] == null)
                                        <a href="{{ url('student/join_exam/' . $exam['exam_id']) }}"
                                            class="btn custom-btn mt-3">
                                            Take Test &nbsp;<i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    @endif
                                </x-can-take-exam>
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

    <style>

        @media (max-width: 767.98px) {
            .content-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }

            .content {
                padding: 0 !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .row {
                margin: 0 !important;
            }

            .col-12 {
                padding: 0 !important;
            }

            .custom-card, .mobile-full-card {
                border-radius: 0 !important;
                margin-bottom: 10px !important;
                width: 100% !important;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }


            .mobile-heading {
                font-size: 3.5rem !important;
                margin-bottom: 1rem !important;
            }

            .mobile-text {
                font-size: 1.9rem !important;
                line-height: 2rem !important;
                margin-bottom: 1rem !important;
            }

            .mobile-text strong {
                font-size: 1.4rem !important;
                font-weight: 600 !important;
            }

            .exam-icon {
                font-size: 2.5rem !important;
            }

            .mobile-btn {
                font-size: 1.4rem !important;
                padding: 0.75rem 1.5rem !important;
                width: 80% !important;
                margin: 0 auto !important;
            }

            .content-header {
                display: none;
            }
        }
    </style>


@endsection
