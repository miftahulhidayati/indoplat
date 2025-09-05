@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-eye text-primary me-2"></i>Task Details
                </h4>
                <div>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-muted mb-3">Basic Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <p class="form-control-plaintext">{{ $task->title }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>
                                <span class="badge bg-{{ $task->getStatusBadgeClass() }} fs-6">
                                    {{ $task->status }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Created</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-plus text-muted me-1"></i>
                                {{ $task->created_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-edit text-muted me-1"></i>
                                {{ $task->updated_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-muted mb-3">Additional Details</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            @if($task->description)
                                <div class="border rounded p-3 bg-light">
                                    {{ $task->description }}
                                </div>
                            @else
                                <p class="form-control-plaintext text-muted">
                                    <i class="fas fa-info-circle me-1"></i>No description provided
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Due Date</label>
                            @if($task->due_at)
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar-alt text-muted me-1"></i>
                                    <span class="text-{{ $task->due_at->isPast() ? 'danger' : 'dark' }}">
                                        {{ $task->due_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                    @if($task->due_at->isPast())
                                        <small class="text-danger d-block">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                        </small>
                                    @elseif($task->due_at->isToday())
                                        <small class="text-warning d-block">
                                            <i class="fas fa-clock me-1"></i>Due today
                                        </small>
                                    @elseif($task->due_at->isTomorrow())
                                        <small class="text-info d-block">
                                            <i class="fas fa-clock me-1"></i>Due tomorrow
                                        </small>
                                    @endif
                                </p>
                            @else
                                <p class="form-control-plaintext text-muted">
                                    <i class="fas fa-calendar-times me-1"></i>No due date set
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Task
                        </a>
                        <button type="button" class="btn btn-outline-warning" onclick="toggleStatus({{ $task->id }})">
                            <i class="fas fa-sync-alt me-1"></i>Toggle Status
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteTask({{ $task->id }}, '{{ $task->title }}')">
                            <i class="fas fa-trash me-1"></i>Delete Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong id="delete-task-title"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash me-1"></i>Delete Task
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(taskId) {
    $.ajax({
        url: `/tasks/${taskId}/toggle-status`,
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showToast('success', response.message);
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function() {
            showToast('error', 'An error occurred while updating the task status.');
        }
    });
}

function deleteTask(taskId, taskTitle) {
    $('#delete-task-title').text(taskTitle);
    $('#confirm-delete').data('task-id', taskId);
    $('#deleteTaskModal').modal('show');
}

$(document).ready(function() {
    // Confirm delete
    $('#confirm-delete').click(function() {
        const taskId = $(this).data('task-id');

        $.ajax({
            url: `/tasks/${taskId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#deleteTaskModal').modal('hide');
                showToast('success', response.message);
                setTimeout(function() {
                    window.location.href = '{{ route("tasks.index") }}';
                }, 1500);
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the task.');
            }
        });
    });

    // Helper function
    function showToast(type, message) {
        const toast = $('#toast');
        const toastBody = $('#toast-body');

        toast.removeClass('bg-success bg-danger');
        toast.addClass(`bg-${type === 'success' ? 'success' : 'danger'}`);

        toastBody.html(`<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`);

        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
    }
});
</script>
@endpush
