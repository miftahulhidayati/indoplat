@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tasks text-primary me-2"></i>Tasks
            </h1>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Task
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('tasks.index') }}" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Tasks</option>
                                <option value="To-Do" {{ request('status') == 'To-Do' ? 'selected' : '' }}>To-Do</option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary me-2">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="card">
            <div class="card-body">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Created</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tasks-table-body">
                                @foreach($tasks as $task)
                                    <tr data-task-id="{{ $task->id }}">
                                        <td>
                                            <div class="fw-bold">{{ $task->title }}</div>
                                            @if($task->description)
                                                <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                                {{ $task->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->due_at)
                                                <span class="text-{{ $task->due_at->isPast() ? 'danger' : 'dark' }}">
                                                    {{ $task->due_at->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $task->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-toggle-status"
                                                        data-task-id="{{ $task->id }}"
                                                        title="Toggle Status">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-edit"
                                                        data-task-id="{{ $task->id }}"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-delete"
                                                        data-task-id="{{ $task->id }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $tasks->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No tasks found</h4>
                        <p class="text-muted">Get started by creating your first task!</p>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Task
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-task-form">
                <div class="modal-body">
                    <input type="hidden" id="edit-task-id" name="id">
                    <div class="mb-3">
                        <label for="edit-title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit-title" name="title" required maxlength="150">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit-status" name="status" required>
                            <option value="To-Do">To-Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-due-at" class="form-label">Due Date</label>
                        <input type="datetime-local" class="form-control" id="edit-due-at" name="due_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Task
                    </button>
                </div>
            </form>
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
$(document).ready(function() {
    // Auto-submit filter form when status changes
    $('#status').change(function() {
        $('#filter-form').submit();
    });

    // Edit task functionality
    $('.btn-edit').click(function() {
        const taskId = $(this).data('task-id');
        const button = $(this);

        // Show loading state
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        // Fetch task data via AJAX
        $.ajax({
            url: `/tasks/${taskId}`,
            type: 'GET',
            success: function(response) {
                // Populate modal with task data
                $('#edit-task-id').val(response.id);
                $('#edit-title').val(response.title);
                $('#edit-description').val(response.description || '');
                $('#edit-status').val(response.status);

                // Format due date for datetime-local input
                if (response.due_at) {
                    const dueDate = new Date(response.due_at);
                    const formattedDate = dueDate.toISOString().slice(0, 16);
                    $('#edit-due-at').val(formattedDate);
                } else {
                    $('#edit-due-at').val('');
                }

                // Show modal
                $('#editTaskModal').modal('show');
            },
            error: function() {
                showToast('error', 'Failed to load task data.');
            },
            complete: function() {
                // Reset button state
                button.prop('disabled', false).html('<i class="fas fa-edit"></i>');
            }
        });
    });

    // Update task
    $('#edit-task-form').submit(function(e) {
        e.preventDefault();

        const taskId = $('#edit-task-id').val();
        const formData = $(this).serialize();

        $.ajax({
            url: `/tasks/${taskId}`,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#editTaskModal').modal('hide');
                showToast('success', response.message);
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors('#edit-task-form', errors);
                } else {
                    showToast('error', 'An error occurred while updating the task.');
                }
            }
        });
    });

    // Delete task functionality
    $('.btn-delete').click(function() {
        const taskId = $(this).data('task-id');
        const row = $(this).closest('tr');
        const taskTitle = row.find('td:first .fw-bold').text();

        $('#delete-task-title').text(taskTitle);
        $('#confirm-delete').data('task-id', taskId);
        $('#deleteTaskModal').modal('show');
    });

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
                $(`tr[data-task-id="${taskId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the task.');
            }
        });
    });

    // Toggle status functionality
    $('.btn-toggle-status').click(function() {
        const taskId = $(this).data('task-id');
        const button = $(this);

        button.prop('disabled', true);

        $.ajax({
            url: `/tasks/${taskId}/toggle-status`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', response.message);
                location.reload();
            },
            error: function() {
                showToast('error', 'An error occurred while updating the task status.');
                button.prop('disabled', false);
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

    function displayValidationErrors(formSelector, errors) {
        $(formSelector).find('.is-invalid').removeClass('is-invalid');
        $(formSelector).find('.invalid-feedback').text('');

        $.each(errors, function(field, messages) {
            const input = $(formSelector).find(`[name="${field}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(messages[0]);
        });
    }
});
</script>
@endpush
