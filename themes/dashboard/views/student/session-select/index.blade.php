@extends('layouts.student')
@section('title', 'Select Session')
@php
    $noSide = true;
@endphp
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

        <!-- /.content-header -->
        {{-- "name" => $user->name,
        "sessions" => $sessions,
        "course" => $courseDetails, --}}
        {{--
        @dump($sessions) --}}
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                @if ($confirmed)
                    <div class="text-center text-lg mb-4">
                        You have already selected {{ $session->name }} - {{ $session->course_time }}
                    </div>
                @else
                    <form action="{{ route('student.select-session', $user->userId) }}" method="POST">
                        <div class="text-center text-lg mb-4">
                            Congratulations, {{ $user->name }}. Please select a session for {{ $course->course_name }}
                        </div>
                        <div class="text-center mb-4">
                            Availabel Options are:
                            <ol>
                                @foreach ($sessions as $session)
                                    <li>{{ $session->session }} ( {{ $session->slotLeft() }} slots left ) -
                                        {{ $session->course_time }}
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        <div class="col-8 text-center m-auto mb-4">

                            <select name="session_id" id="" class="form-control">
                                @foreach ($sessions as $session)
                                    @if ($session->slotLeft() > 0)
                                        <option value="{{ $session->id }}">{{ $session->name }}
                                            ({{ $session->course_time }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-lg btn-primary mt-4">
                                CONFIRM
                            </button>
                        </div>
                        @csrf
                    </form>

                @endif
                {{-- <div class="text-center mb-4">
                    Slots left: 100
                </div> --}}

                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



@endsection
