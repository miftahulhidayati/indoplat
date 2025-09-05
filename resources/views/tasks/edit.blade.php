@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Task
                </h4>
            </div>
            <div class="card-body">
                <form id="edit-task-form" method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $task->title) }}"
                               required maxlength="150" placeholder="Enter task title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4"
                                  placeholder="Enter task description (optional)">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                            <option value="">Select status</option>
                            <option value="To-Do" {{ old('status', $task->status) == 'To-Do' ? 'selected' : '' }}>To-Do</option>
                            <option value="In Progress" {{ old('status', $task->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Done" {{ old('status', $task->status) == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="due_at" class="form-label">Due Date</label>
                        <input type="datetime-local" class="form-control @error('due_at') is-invalid @enderror"
                               id="due_at" name="due_at"
                               value="{{ old('due_at', $task->due_at ? $task->due_at->format('Y-m-d\TH:i') : '') }}">
                        <div class="form-text">Leave empty if no due date is required.</div>
                        @error('due_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Tasks
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // AJAX form submission
    $('#edit-task-form').submit(function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', response.message);

                // Redirect to tasks index after a short delay
                setTimeout(function() {
                    window.location.href = '{{ route("tasks.index") }}';
                }, 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(form, errors);
                } else {
                    showToast('error', 'An error occurred while updating the task.');
                }
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Helper functions
    function showToast(type, message) {
        const toast = $('#toast');
        const toastBody = $('#toast-body');

        toast.removeClass('bg-success bg-danger');
        toast.addClass(`bg-${type === 'success' ? 'success' : 'danger'}`);

        toastBody.html(`<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`);

        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
    }

    function displayValidationErrors(form, errors) {
        // Clear previous validation errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Display new validation errors
        $.each(errors, function(field, messages) {
            const input = form.find(`[name="${field}"]`);
            input.addClass('is-invalid');

            // Find or create invalid feedback element
            let feedback = input.siblings('.invalid-feedback');
            if (feedback.length === 0) {
                feedback = $('<div class="invalid-feedback"></div>');
                input.after(feedback);
            }

            feedback.text(messages[0]);
        });
    }
});
</script>
@endpush
