@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">App Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">App Settings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Application Settings</h3>
                            </div>
                            <form role="form" action="{{ route('admin.app_settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exam_deadline_after_registration">Exam Deadline After Registration (in
                                            days)</label>
                                        <input type="number" class="form-control" id="exam_deadline_after_registration"
                                            name="exam_deadline_after_registration"
                                            value="{{ old('exam_deadline_after_registration', config('app_settings.exam_deadline_after_registration')) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Allow Changing of Courses</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="allow_course_change"
                                                name="allow_course_change"
                                                {{ config('app_settings.allow_course_change') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="allow_course_change">{{ config('app_settings.allow_course_change') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Allow Changing of Sessions</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="allow_session_change"
                                                name="allow_session_change"
                                                {{ config('app_settings.allow_session_change') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="allow_session_change">{{ config('app_settings.allow_session_change') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send Email After Registration</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="send_email_registration"
                                                name="send_email_registration"
                                                {{ config('app_settings.send_email_registration') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_email_registration">{{ config('app_settings.send_email_registration') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send SMS After Registration</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="send_sms_registration"
                                                name="send_sms_registration"
                                                {{ config('app_settings.send_sms_registration') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_sms_registration">{{ config('app_settings.send_sms_registration') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send Email After Exam Submission</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_email_exam_submission" name="send_email_exam_submission"
                                                {{ config('app_settings.send_email_exam_submission') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_email_exam_submission">{{ config('app_settings.send_email_exam_submission') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send SMS After Exam Submission</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_sms_exam_submission" name="send_sms_exam_submission"
                                                {{ config('app_settings.send_sms_exam_submission') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_sms_exam_submission">{{ config('app_settings.send_sms_exam_submission') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send Email After Admission Creation</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_email_admission_creation" name="send_email_admission_creation"
                                                {{ config('app_settings.send_email_admission_creation') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_email_admission_creation">{{ config('app_settings.send_email_admission_creation') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send SMS After Admission Creation</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_sms_admission_creation" name="send_sms_admission_creation"
                                                {{ config('app_settings.send_sms_admission_creation') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_sms_admission_creation">{{ config('app_settings.send_sms_admission_creation') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send Email After Admission Confirmation</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_email_admission_confirmation"
                                                name="send_email_admission_confirmation"
                                                {{ config('app_settings.send_email_admission_confirmation') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_email_admission_confirmation">{{ config('app_settings.send_email_admission_confirmation') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Send SMS After Admission Confirmation</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input"
                                                id="send_sms_admission_confirmation"
                                                name="send_sms_admission_confirmation"
                                                {{ config('app_settings.send_sms_admission_confirmation') ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="send_sms_admission_confirmation">{{ config('app_settings.send_sms_admission_confirmation') ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
        <script>
            $(function() {
                $('input[data-bootstrap-switch]').each(function() {
                    $(this).bootstrapSwitch();
                });
            })
        </script>
    @endpush
@endsection
