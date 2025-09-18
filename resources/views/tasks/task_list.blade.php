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
    <tbody>
        @foreach($task_list as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
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
