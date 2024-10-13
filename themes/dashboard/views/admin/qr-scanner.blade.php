@extends('layouts.app')
@section('title', 'Scan QR Code')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Scan/Generate QR Code</h1>
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
                        <div class="mb-4 col-md-6">
                            <label for="course_id" class="form-label">Select Course</label>
                            <select name="course_id" class="form-control">
                                <option value="">Choose One</option>

                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"> {{ $course->location }} -
                                        {{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="mb-4 col-md-4">
                            <label for="location" class="form-label">Select Course</label>
                            <select name="course" class="form-control">
                                <option value="">Choose One</option>

                                @foreach ($courses as $course)
                                    <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="mb-4 col-md-6">
                            <label for="date" class="form-label">Select Date</label>
                            <input class="form-control" type="date" name="date" id=""
                                max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
                        </div>
                </form>
            </div>
            <div class="row g-3 flex justify-content-center align-tems-center mb-4">
                <button type="button" class="btn btn-primary col-auto" onclick="startScanner()">Start
                    Scanner</button>
                <button type="button" class="btn btn-danger ml-4" onclick="stopScanner()">Stop Scanner</button>
                <button type="button" class="btn btn-success ml-4" onclick="generateCode()">Genrate Code</button>
                <button type="button" class="btn btn-danger ml-4" onclick="stopCodeGeneration()">Stop Code
                    Generation</button>

            </div>

            <div class="col-12 flex justify-content-center align-tems-center">
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

        const scannerElem = $('#scanner');
        const qrcodeElem = $('#qrcode');


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
            async result => {
                qrScanner.stop();
                const values = getFormValues();

                if (result.data && result.data.length > 5) {
                    Swal.fire({
                        text: 'Confirming Attendance. Please wait...',
                        icon: 'info',
                        toast: true,
                        timer: 3000
                    })
                    try {
                        const url = `/admin/confirm_attendance`;
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                course_id: values['course_id'],
                                date: values['date'],
                                user_id: result.data
                            })
                        });
                        if (response.ok) {
                            const result = await response.json();
                            Swal.fire({
                                text: result.message,
                                icon: result.success ? 'info' : 'error',
                                toast: true,
                                preConfirm: () => {
                                    qrScanner.start();
                                }
                            })
                        }
                    } catch (error) {
                        Swal.fire({
                            text: 'Unable to confirm.',
                            icon: 'error',
                            toast: true,
                            timer: 5000
                        });
                        qrScanner.start();
                    }
                } else {
                    qrScanner.start();
                }
            }, {
                /* your options or returnDetailedScanResult: true if you're not specifying any other options */
                preferredCamera: 'environment',
                highlightScanRegion: true,
                highlightCodeOutline: true,
                maxScansPerSecond: 1
            },
        );

        function startScanner() {
            qrcodeElem.hide();
            scannerElem.show();
            this.stopCodeGeneration();

            const values = getFormValues();
            if (values !== null) {
                qrScanner.start();
            }
        }

        function stopScanner() {
            try {
                scannerElem.hide();
                qrScanner.stop();
            } catch (e) {}
        }

        async function generateCode() {
            this.stopScanner();
            const values = getFormValues();
            qrcodeElem.show();
            scannerElem.hide();

            if (values == null) {
                return;
            }
            // interval = setInterval(generateCode, 1000 * 60 * 10);
            const data = await getQRCodeData(values);

            if (data) {
                new QRCode(document.getElementById("qrcode"), {
                    text: "{{ route('student.mark-attendance') }}?scanned_data=" + data,
                    width: 450,
                    height: 450,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        }

        function stopCodeGeneration() {
            try {
                qrcodeElem.hide();
                clearInterval(interval);
                qrCode.clear();
            } catch (e) {}
        }


        async function getQRCodeData(values) {
            try {
                const url = `/admin/generate_qrcode`;
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        course_id: values['course_id'],
                        date: values['date']
                    })
                });
                if (response.ok) {
                    const token = await response.json();
                    return token['data'];
                }
                return null;
            } catch (error) {
                console.log(error);
                return null;
            }
        }
    </script>
@endpush
