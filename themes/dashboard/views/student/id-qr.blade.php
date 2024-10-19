@extends('layouts.student')
@section('title', 'Portal dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">My Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">My Details</li>
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
                <div class="row g-3 flex mb-2 align-items-center">
                    <div class="col-12 mb-3">
                        <label class="form-label col-md-3">Fullname</label>
                        <input type="text" value=" {{ $user->student_name }}" class="form-control col-md-7 mr-2">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label col-md-3">Ghana Card Number</label>
                        <input id="ghcard" type="text" value=" {{ $user->ghcard }}"
                            class="form-control col-md-7 mr-2">
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-primary">Update</button>
                        <p class="text-sm text-danger">You can only update your details once, make sure you verify all
                            details before submitting</p>
                    </div>
                </div>

                <div class="text-md">Location : {{ $user->location }} </div>
                <div class="text-md">Course : {{ $user->course_name }}</div>
                <div class="text-md">Session : {{ $user->selected_session }}</div>
                <div class="text-lg font-bold mt-2">Student ID for Attendance</div>
                <div id="qrcode"></div>
                <button type="button" class="btn btn-primary" onclick="downloadQRCode()">Download</button>
                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection


@push('scripts')
    <script src=”https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js”></script>
    <script src="{{ asset('assets/js/easy.qrcode.min.js') }}"></script>
    <script>
        const innerWidth = Math.floor(window.innerWidth * (7 / 9));
        const width = innerWidth > 400 ? 400 : innerWidth
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ Auth::user()->userId }}",
            width: width,
            height: width,
            colorDark: "blue",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
        });

        function downloadQRCode() {
            qrcode.download("StudentID-{{ Auth::user()->userId }}")
        }


        $("#ghcard").inputmask("GHA-d{9}-d{1}");
    </script>
@endpush
