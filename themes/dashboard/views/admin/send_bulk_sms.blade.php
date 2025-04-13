<x-modal id="bulk-sms-modal" title="Send Bulk SMS" size="modal-lg">

    <label for="sms_template">Select Template To Use</label>
    <select name="sms_template" id="sms_template" class="form-control">
        <option value="" selected disabled>Loading templates...</option>
    </select>

    <br>

    <label for="sms_message">Or Write Message</label>
    <textarea class="form-control mb-3" name="sms_message" id="sms_message"
              placeholder="Type your SMS message here..."></textarea>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="modal-submit" type="button" class="btn btn-primary">Submit</button>
    </x-slot>
</x-modal>









<script>
    $(document).ready(function () {
        const modal = $('#bulk-sms-modal');
        const templateSelect = $('#sms_template');
        const messageBox = $('#sms_message');

        // Load templates when the modal opens
        modal.on('show.bs.modal', function () {
            templateSelect.empty().append('<option selected disabled>Loading templates...</option>');

            $.get("{{ route('admin.fetch.sms.template') }}", function (templates) {
                templateSelect.empty().append('<option value="" disabled selected>Select a template</option>');

                $.each(templates, function (index, template) {
                    const option = $('<option></option>')
                        .val(template.id)
                        .text(template.name)
                        .data('content', template.content); // store SMS content
                    templateSelect.append(option);
                });
            }).fail(function () {
                toastr.error('Failed to load SMS templates.');
                templateSelect.empty().append('<option value="" disabled selected>Unable to load templates</option>');
            });
        });

        // When a template is selected, auto-fill the message box
        templateSelect.on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const content = selectedOption.data('content');
            if (content) {
                messageBox.val(content);
            }
        });

        // Submit button handler
        $(document).on('click', '#modal-submit', function () {
            const message = messageBox.val();
            //const subject = $('#sms_subject').val();
            const template = templateSelect.val();

            const modalActionEvent = new CustomEvent('modalAction', {
                detail: {
                    message,
                    // subject,
                    template,
                    modalId: 'bulk-sms-modal',
                },
                bubbles: true,
                cancelable: true,
            });

            document.getElementById('bulk-sms-modal').dispatchEvent(modalActionEvent);
        });

        // Handle actual AJAX submission
        modal.on('modalAction', function (event) {
            const { message, subject, template } = event.detail;

            if ((!message && !template)) {
                toastr.error('You need a message/template and a subject');
                return;
            }

            const selectedIds = typeof manuallySelectedIds !== 'undefined' && manuallySelectedIds.length > 0
                ? manuallySelectedIds
                : allFilteredIds;

            $.ajax({
                url: "{{ route('admin.send_bulk_sms') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    student_ids: selectedIds,
                    //subject,
                    message,
                    //template
                },
                success: function (response) {
                    toastr.success(response.message || 'SMS transfer initiated successfully!');
                    modal.modal('hide');
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to send SMS to students.');
                }
            });
        });
    });
</script>
