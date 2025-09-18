@extends('layouts.app')

@section('content')
<div class="container py-4">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            {{ @$task_edit_rec ? '✏️ Edit Task' : '➕ Add Task' }}
        </h3>
        <a href="{{ route('task_list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form id="taskform" name="taskform">
                <input type="hidden" id="pr_edit_id" value="{{ @$task_edit_rec->id }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title_name" class="form-label">Title Name</label>
                        <input type="text" class="form-control" name="title_name" id="title_name"
                               placeholder="Enter title name"
                               value="{{ @$task_edit_rec->title }}">
                        <div class="invalid-feedback" id="title_name_error"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="description"
                               placeholder="Enter description"
                               value="{{ @$task_edit_rec->description }}">
                        <div class="invalid-feedback" id="description_error"></div>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success px-4" id="save_task">
                    💾 Save
                </button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#save_task').on('click', function () {
        let pr_edit_id = $('#pr_edit_id').val();
        let title_name = $('#title_name').val().trim();
        let description = $('#description').val().trim();

        $('#title_name_error').text('');
        $('#description_error').text('');
        $('#title_name').removeClass('is-invalid');
        $('#description').removeClass('is-invalid');

        let isValid = true;

        if (!title_name) {
            $('#title_name').addClass('is-invalid');
            $('#title_name_error').text('⚠️ Task name is required.');
            isValid = false;
        }

        if (!description) {
            $('#description').addClass('is-invalid');
            $('#description_error').text('⚠️ Description is required.');
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        let url = pr_edit_id ? "{{ route('task_edited') }}" : "{{ route('task_added') }}";

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                id: pr_edit_id,
                title_name: title_name,
                description: description
            },
            success: function (data) {
                window.location.href = '/task_list';
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.title_name) {
                        $('#title_name').addClass('is-invalid');
                        $('#title_name_error').text(errors.title_name[0]);
                    }

                    if (errors.description) {
                        $('#description').addClass('is-invalid');
                        $('#description_error').text(errors.description[0]);
                    }
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
</script>
@endsection