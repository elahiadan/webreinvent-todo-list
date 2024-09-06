<!DOCTYPE html>
<html>

<head>
    <title>WEBREINVENT TO-DO LIST</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .completed {
            text-decoration: line-through;
            color: gray;
        }

        .hide {
            display: none;
        }
    </style>
</head>

<body>
    <h1>TO-DO LIST</h1>

    <form id="task-form">
        <input type="text" name="title" id="task-title" required>
        <button type="submit">Add Task</button>
    </form>

    <button id="show-all">Show All Tasks</button>

    <ul id="task-list">
        @foreach($tasks as $task)
        <li data-id="{{ $task->id }}" class="{{ $task->is_completed ? 'completed hide' : '' }}">
            <input type="checkbox" class="complete-task" {{ $task->is_completed ? 'checked' : '' }}>
            {{ $task->title }}
            <button class="delete-task">Delete</button>
        </li>
        @endforeach
    </ul>

    <script>
        $(document).ready(function() {
            $('#task-form').submit(function(e) {
                e.preventDefault();
                let title = $("#task-title").val();
                $.ajax({
                    url: "{{route('task.store')}}",
                    type: 'POST',
                    data: {
                        title,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            $("#task-title").val('');
                            let html = `<li data-id="${ res.task.id }"><input type="checkbox" class="complete-task" ${ res.task.is_completed ? 'checked' : '' }>
                                            ${ res.task.title }
                                            <button class="delete-task">Delete</button>
                                        </li>`;
                            $('#task-list').append(html);
                        } else {
                            alert(res.errors.title);
                        }
                    }
                });
            });

            let status = false;
            $('#show-all').click(function() {
                status = !status;
                if (status) {
                    $('#show-all').text('Hide completed Tasks');
                    $('.hide').show();
                } else {
                    $('#show-all').text('Show All Tasks');
                    $('.hide').hide();
                }
            });

            $('#task-list').on('change', '.complete-task', function() {
                var $li = $(this).closest('li');
                var taskId = $li.data('id');
                $.ajax({
                    url: `{{route('task.update')}}`,
                    type: 'POST',
                    data: {
                        id: taskId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $li.toggleClass('completed hide');
                        $li.find('.complete-task').prop('checked', $li.hasClass('completed'));
                    }
                });
            });

            $('#task-list').on('click', '.delete-task', function() {
                if (confirm('Are you sure to delete this task?')) {
                    var $li = $(this).closest('li');
                    var taskId = $li.data('id');
                    $.ajax({
                        url: `{{route('task.delete')}}`,
                        type: 'POST',
                        data: {
                            id: taskId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            $li.remove();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>