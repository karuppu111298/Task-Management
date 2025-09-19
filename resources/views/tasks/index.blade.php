
@extends('layouts.app')

@section('content')
<div class="container">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <h3>Task List</h3>

   <div class="d-flex justify-content-end align-items-center mb-3">
    <div class="col-md-2 me-2">
        <input type="text" id="search" class="form-control" placeholder="Search">
    </div>

    <div class="col-md-2 me-2">
        <select id="task_status" class="form-control">
            <option value="">All Status</option>
            <option value="1">Completed</option>
            <option value="0">In Completed</option>
        </select>
    </div>

    <!-- <div class="col-md-2 me-2">
        <select id="sort_price" class="form-control">
            <option value="">Sort by title</option>
            <option value="asc">Asc</option>
            <option value="desc">Desc</option>
        </select>
    </div> -->

    <span>
        <button type="button" class="btn btn-warning" onclick="searchTask()">Search</button>
        <button type="button" class="btn btn-secondary" onclick="resetSearch()">Reset</button>
    </span>
    <span class="ms-2">
        <a href="{{ route('task_add') }}">
            <button type="button" class="btn btn-success">Add Task</button>
        </a>
    </span>
</div>

    <div id="task_table">
      @include('tasks/task_list')
    </div>
    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<script>
    $.ajaxSetup({
       headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function deleteTask(id) {
        if (confirm('Are you sure you want to delete this task?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('task_delete') }}",
                data: { id: id },
                success: function (data) {
                    alert('Task deleted successfully');
                    window.location.href = '/task_list';
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        }
    }

    function searchTask() {
        var search = $('#search').val();
        var status = $('#task_status').val();
        // var sort_price = $('#sort_price').val();

        getData(1, search, status);
    }

    function resetSearch() {
        window.location.href = '/task_list';
    }
    function getData(pagination,search,status){
        var url = "{{ route('task_list') }}" + '?term=' + search + '&status=' + status ;

        window.history.pushState({ path: url }, '', url);

        $.ajax({
          url: url,
          type: "GET",
          data:{pagination:pagination},
          success: function(response) {
              $("#task_table").empty().append(response);
          },
          error: function(xhr) {
              console.error("Error:", xhr);
          }
      });

    }
    function toggleCompletion(id, is_complete) {
       if (confirm('Are you sure you want to Complete this task?')) {
         $.ajax({
                type: 'POST',
                url: "{{ route('task_completion') }}",
                data: { id:id,  is_complete: is_complete ? 1 : 0,  },
                success: function (data) {
                  //  window.location.href = '/task_list';
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        }

    }
    $(function () {
    $("#sortable").sortable({
        placeholder: "ui-state-highlight",
        update: function (event, ui) {
            var order = [];
            $('#sortable tr').each(function (index, element) {
                order.push($(element).data("id"));
            });

            // Ajax call to save order
            $.ajax({
                url: "{{ route('task_reorder') }}",
                method: "POST",
                data: {
                    order: order,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("Order updated successfully");
                },
                error: function (xhr) {
                    console.log("Error:", xhr.responseText);
                }
            });
        }
    });
});
</script>
@endsection
