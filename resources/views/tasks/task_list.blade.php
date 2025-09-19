<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>S No</th>
            <th>Task Name</th>
            <th>Description</th>
            <th>Completed</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="sortable">
        @foreach($task_list as $key => $value)
            <tr data-id="{{ $value->id }}">
                <td class="text-center"> <span class="drag-handle me-2"> <i class="fas fa-bars"></i> </span> <span>{{ $key + 1 }}</span> </td>
                <td>{{ $value->title }}</td>
                <td>{{ $value->description }}</td>
                
                <!-- Checkbox for Completion -->
                <td class="text-center">
                    <input type="checkbox" 
                           {{ $value->is_completed ? 'checked' : '' }} 
                           onchange="toggleCompletion({{ $value->id }}, this.checked)">
                </td>

                <!-- Actions -->
                <td>
                    <a href="{{ route('task_edit', $value->id) }}" class="btn btn-sm btn-outline-primary me-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTask({{ $value->id }})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
