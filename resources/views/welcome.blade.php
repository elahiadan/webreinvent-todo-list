<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEBREINVENT TO-DO LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .hide {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1>TO-DO LIST</h1>

        <form id="task-form" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="title" id="task-title" required>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>

        <button id="show-all" class="btn btn-secondary mb-3">Show All Tasks</button>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Task</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="task-list">
                @foreach($tasks as $task)
                <tr class="{{ $task->is_completed ? 'completed hide' : '' }}">
                    <th scope="row">{{$task->id}}</th>
                    <td>{{ $task->title }}</td>
                    <td class="status">{{$task->is_completed ? 'Done' : ''}}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <input type="checkbox" class="complete-task" data-id="{{ $task->id }}" {{ $task->is_completed ? 'checked' : '' }}>
                            <button class="btn btn-danger btn-sm ms-2 delete-task" data-id="{{ $task->id }}" aria-label="Delete Task">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            // Add new task
            $('#task-form').submit(function(e) {
                e.preventDefault();
                let title = $("#task-title").val();
                $.ajax({
                    url: "{{route('task.store')}}",
                    type: 'POST',
                    data: {
                        title
                    },
                    success: function(res) {
                        if (res.success) {
                            $("#task-title").val('');
                            let html = `<tr class="${ res.task.is_completed ? 'completed hide' : '' }">
                                    <th scope="row">${ res.task.id }</th>
                                    <td>${ res.task.title }</td>
                                    <td class="status">${res.task.is_completed ? 'Done' : ''}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="complete-task" data-id="${ res.task.id }" ${ res.task.is_completed ? 'checked' : '' }>
                                            <button class="btn btn-danger btn-sm ms-2 delete-task" data-id="${ res.task.id }" aria-label="Delete Task">Delete</button>
                                        </div>
                                    </td>
                                </tr>`;
                            $('#task-list').append(html);
                        } else {
                            alert(res.errors.title);
                        }
                    }
                });
            });

            // Toggle visibility of completed tasks
            let status = false;
            $('#show-all').click(function() {
                status = !status;
                if (status) {
                    $('#show-all').text('Hide Completed Tasks');
                    $('.hide').show();
                } else {
                    $('#show-all').text('Show All Tasks');
                    $('.hide').hide();
                }
            });

            // Mark task as complete/incomplete
            $('#task-list').on('change', '.complete-task', function() {
                let $tr = $(this).closest('tr');
                let taskId = $(this).data('id');
                $.ajax({
                    url: `{{route('task.update')}}`,
                    type: 'POST',
                    data: {
                        id: taskId
                    },
                    success: function() {
                        $tr.toggleClass('hide');
                        $tr.find('.status').text($tr.hasClass('hide') ? 'Done' : '');
                    }
                });
            });

            // Delete task
            $('#task-list').on('click', '.delete-task', function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    let $tr = $(this).closest('tr');
                    let taskId = $(this).data('id');
                    $.ajax({
                        url: `{{route('task.delete')}}`,
                        type: 'POST',
                        data: {
                            id: taskId
                        },
                        success: function() {
                            $tr.remove();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>