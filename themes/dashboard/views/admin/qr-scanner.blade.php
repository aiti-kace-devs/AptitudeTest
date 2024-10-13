@extends('layouts.app')
@section('title', 'Scan QR Code')
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
                            <li class="breadcrumb-item active">Scan QR Code</li>
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
                <form name="qr-form">
                    <div class="row">
                        <div class="mb-4 col-md-4">
                            <label for="location" class="form-label">Select Location</label>
                            <select name="location" class="form-control">
                                <option value="">Choose One</option>

                                @foreach ($locations as $location)
                                    <option value="{{ $location->location }}">{{ $location->location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="location" class="form-label">Select Course</label>
                            <select name="course" class="form-control">
                                <option value="">Choose One</option>

                                @foreach ($courses as $course)
                                    <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 col-md-4">
                            <label for="date" class="form-label">Select Date</label>
                            <input class="form-control" type="date" name="date" id=""
                                max="{{ now()->toDateString() }}">
                        </div>
                </form>
            </div>
            <div class="row g-3 flex justify-content-center align-tems-center mb-4">
                <button type="button" class="btn btn-primary col-auto" onclick="startScanner()">Start
                    Scanner</button>
                <button type="button" class="btn btn-danger ml-4" onclick="stopScanner()">Stop Scanner</button>
                <button type="button" class="btn btn-success ml-4" onclick="generateCode()">Genrate Code</button>
                <button type="button" class="btn btn-success ml-4" onclick="stopCodeGeneration()">Stop Code
                    Generation</button>

            </div>

            <div class="col-12">
                <video class="col-12" id="scanner"></video>
                <div class="col-12" id="qrcode"></div>
            </div>


            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/qr-scanner.min.js') }}"></script>
    <script src="{{ asset('assets/js/easy.qrcode.min.js') }}"></script>
    <script>
        let interval = null;

        let qrCode = null;

        function getFormValues() {
            // const values = $('').serializeArray();
            const values = {};
            let error = false;
            $.each($('[name="qr-form"]').serializeArray(), function(i, field) {
                values[field.name] = field.value;
                if (field.value === '') {
                    error = true;
                }
            });

            if (error) {
                Swal.fire({
                    text: "You need to select all options",
                    timer: 4000,
                    toast: true,
                    icon: 'error',
                    showConfirmButton: false,
                    position: 'top-center'
                });
                return null;
            }
            return values;
        }

        const qrScanner = new QrScanner(
            document.getElementById('scanner'),
            result => {
                console.log('decoded qr code:', result)
                if (result.data) {
                    Swal.fire({
                        text: 'Confirming Attendance. Please wait...',
                        icon: 'info',
                    })
                    qrScanner.stop();
                }
            }, {
                /* your options or returnDetailedScanResult: true if you're not specifying any other options */
                preferredCamera: 'environment',
                highlightScanRegion: true,
                highlightCodeOutline: true,
            },
        );

        function startScanner() {
            const values = getFormValues();
            if (values !== null) {
                qrScanner.start();
            }
        }

        function stopScanner() {
            qrScanner.stop();
        }

        function generateCode() {
            // const values = getFormValues();
            qrCode = new QRCode(document.getElementById("qrcode"), {
                text: (new Date()).toString(),
                width: 800,
                height: 800,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H

            });
            // if (values !== null) {
            interval = setInterval(() => {
                console.log(new Date())
                // qrCode.clear();
                qrCode.makeCode((new Date()).toString());
                // qrCode.makeCode(new Date());
            }, 1000);
            // }
        }

        function stopCodeGeneration() {
            clearInterval(interval);
            qrCode.clear();
        }
    </script>
@endpush
