@extends('layout')

@section('content')
    <div class="container flex flex-col lg:flex-row gap-3 py-10">
        {{-- start left --}}
        <div class="w-full lg:w-1/5 p-5 bg-indigo-500 rounded-lg grow-0 h-full">
            <h1 id="form-title" class="text-center text-4xl font-bold text-indigo-950">Add Todo</h1>
            <form id="form-todo" class="space-y-3">
                <input type="hidden" name="todo_id" id="todo_id">
                <div class="flex flex-col space-y-1">
                    <label for="title" class="text-xl font-semibold text-indigo-950">Title</label>
                    <input type="text" name="title" id="title" class="custom-input text-indigo-950"
                        placeholder="Title" required>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="description" class="text-xl font-semibold text-indigo-950">Description</label>
                    <textarea name="description" id="description" class="custom-input text-indigo-950 resize-none min-h-48"
                        placeholder="Description" required></textarea>
                </div>
                <div id="errors" class="bg-red-500 bg-opacity-50 text-white font-semi text-xl rounded-xl p-3 hidden">
                </div>
                <div class="flex flex-col space-y-1">
                    <button id="btn-add" type="submit" class="w-full p-2 bg-indigo-900 text-white rounded-lg">Add
                        Todo</button>
                </div>
                <div class="flex flex-col space-y-1">
                    <button id="btn-update" class="w-full p-2 bg-green-500 text-white rounded-lg hidden">Update
                        Todo</button>
                </div>
            </form>
        </div>

        {{-- end left --}}

        {{-- start right --}}
        <div class="w-full lg:w-4/5 min-h-full p-5 bg-indigo-500 rounded-lg space-y-7 relative">
            <h1 class="text-center text-4xl font-bold text-indigo-950">Todo List</h1>
            <div id="success"
                class="bg-green-500 bg-opacity-50 text-white font-semi text-xl rounded-xl p-3 hidden text-center">
            </div>
            <div class="w-full">
                <input type="search" id="search" class="custom-input text-indigo-950"
                    placeholder="Search by title or description">
            </div>
            <div id="loading" class="text-6xl text-center text-white">
                <i class="fa-solid fa-spinner animate-spin"></i>
            </div>
            <div id="todos" class="grid grid-cols-12 gap-2 bg-indigo-700 p-2 rounded-lg">
                {{-- todo --}}
            </div>
            <div id="completed-todos" class="grid grid-cols-12 gap-2  bg-indigo-700 p-2 rounded-lg">

            </div>

        </div>
        {{-- end right --}}
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            loadTodos();

            $('#form-todo').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = "{{ route('todos.create') }}";
                ajaxPost(url, formData, function(response) {
                    loadTodos();
                    loadCompletedTodos();
                    $('#title').val('');
                    $('#description').val('');
                    displayMessage($('#success'), response.message);
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.log(error);
                    displayMessage($('#errors'), error);
                });
            });

            function createTodoHtml(todo, isCompleted) {
                const bgClass = isCompleted ? 'bg-green-900' : 'bg-indigo-900';
                const iconClass = isCompleted ? 'fa-x' : 'fa-check';
                const btnBgClass = isCompleted ? 'bg-red-500' : 'bg-green-500';
                const btnHoverClass = isCompleted ? 'hover:bg-red-700' : 'hover:bg-green-700';
                const btnId = isCompleted ? 'btn-inCompleted' : 'btn-completed';
                const date = isCompleted ? todo.completed_at : todo.created_at;
                const buttons = isCompleted ? '' : `
                    <a id="btn-edit" class="edit-todo block text-center p-2 grow bg-green-500 text-white rounded-lg hover:bg-green-400 hover:cursor-pointer" data-id="${todo.id}">Edit</a>
                    <a id="btn-delete" class="delete-todo block text-center p-2 grow bg-red-500 text-white rounded-lg hover:bg-red-400 hover:cursor-pointer" data-id="${todo.id}">Delete</a>
                `;
                return `
                <div class="todo col-span-12 sm:col-span-6 lg:col-span-4 flex flex-col p-3 ${bgClass} rounded-lg relative" data-id="${todo.id}">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-xl font-semibold text-white mb-3">${todo.title}</h1>
                        <div>
                            <a id="${btnId}" data-id="${todo.id}" class="${btnBgClass} text-white px-2 py-1 rounded-lg text-xl font-bold ${btnHoverClass} hover:cursor-pointer">
                                <i class="fa-solid ${iconClass}"></i>
                            </a>
                        </div>
                    </div>
                    <p class="w-full bg-indigo-50 text-indigo-950 p-3 rounded-lg min-h-24 font-bold text-lg mb-3 break-words">${todo.description}</p>
                    <div class="flex justify-between gap-2 mt-auto mb-7">
                        ${buttons}
                    </div>
                    <span class="absolute bottom-0 right-0 p-2 text-white rounded-bl-lg rounded-tr-lg">${date}</span>
                </div>`;
            }

            function displayTodos(todos, containerId, title) {
                const container = $(`#${containerId}`);
                container.empty();
                if (todos.length === 0) {
                    container.append(
                        '<h1 class="text-2xl font-bold text-center text-white col-span-12">No todos found</h1>');
                } else {
                    container.append(
                        `<h1 class="text-3xl font-bold text-center text-white col-span-12 mb-8">${title}</h1>`);
                    todos.forEach(function(todo) {
                        container.append(createTodoHtml(todo, containerId === 'completed-todos'));
                    });
                }
            }

            function loadTodos(searchTerm = '') {
                var url = "{{ route('todos.index') }}";
                var params = searchTerm ? '?search=' + encodeURIComponent(searchTerm) : '';
                url += params;
                ajaxGet(url, function(response) {
                    $('#loading').fadeOut();
                    displayTodos(response.inCompletedTodos, 'todos', 'Uncompleted Todos');
                    displayTodos(response.completedTodos, 'completed-todos', 'Completed Todos');
                }, function(xhr) {
                    console.log(xhr.responseText);
                    $('#loading').fadeOut();
                });
            }

            $('#search').on('input', function() {
                var searchTerm = $(this).val();
                loadTodos(searchTerm);
                loadCompletedTodos();
            });

            $('#search').keyup(function(e) {
                searchTimeout = setTimeout(function() {
                    let searchTerm = $(this).val();
                    loadTodos(searchTerm);
                    loadCompletedTodos();
                }, 2000);
            });

            async function getTodo(todoId) {
                var url = "{{ route('todos.show', '') }}" + '/' + todoId;
                try {
                    let response = await fetch(url);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    let data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error fetching todo:', error);
                    throw error;
                }
            }

            function toggleForm(response = null) {
                if (response != null) {
                    $('#todo_id').val(response.id);
                    $('#title').val(response.title);
                    $('#description').val(response.description);
                    $('#form-title').text('Update Todo');
                    $('#submit-btn').text('Update Todo');
                    $('#btn-add').addClass('hidden');
                    $('#btn-update').removeClass('hidden');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else {
                    $('#title').val('');
                    $('#description').val('');
                    $('#todo_id').val('');
                    $('#form-title').text('Add Todo');
                    $('#btn-add').removeClass('hidden');
                    $('#btn-update').addClass('hidden');
                }

            }

            $(document).on('click', '#btn-edit', async function() {
                var todoId = $(this).data('id');
                try {
                    let response = await getTodo(todoId);
                    toggleForm(response);
                } catch (error) {
                    console.error(error);
                }
            });

            $('#btn-update').click(function(e) {
                e.preventDefault();
                let formData = new FormData($('#form-todo')[0]);
                let todoId = formData.get('todo_id');
                let url = "{{ route('todos.update', '') }}" + '/' + todoId;
                ajaxPut(url, formData, function(response) {
                    toggleForm();
                    loadTodos();
                    loadCompletedTodos();
                    displayMessage($('#success'), response.message);
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.log(error);
                    displayMessage($('#errors'), error);
                });

            });

            $(document).on('click', '.delete-todo', function() {
                var todoId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure you want to delete this task?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('todos.destroy', '') }}" + '/' + todoId;
                        ajaxDelete(url, function(response) {
                            console.log(response.message);
                            loadTodos();
                            loadCompletedTodos();
                            displayMessage($('#success'), response.message);
                        }, function(xhr) {
                            let error = xhr.responseJSON ? xhr.responseJSON.message :
                                "An error occurred";
                            console.log(error);
                            displayMessage($('#errors'), error);
                        });

                        Swal.fire(
                            'Deleted!',
                            'Your task has been deleted.',
                            'success'
                        );
                    }
                });
            });

            function handleTodoCompletion(action, todoId) {
                let url = `{{ route('todos.index') }}/${action}/${todoId}`;
                ajaxPost(url, {}, function(response) {
                    loadTodos();
                    displayMessage($('#success'), response.message);
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.log(error);
                    displayMessage($('#errors'), error);
                });
            }
            $(document).on('click', '#btn-completed, #btn-inCompleted', function() {
                var todoId = $(this).data('id');
                var action = $(this).attr('id') === 'btn-completed' ? 'complete' : 'incomplete';
                handleTodoCompletion(action, todoId);
            });
        });
    </script>
@endsection
