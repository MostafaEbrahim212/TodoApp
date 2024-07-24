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
            <div id="complteded-todos" class="grid grid-cols-12 gap-2  bg-indigo-700 p-2 rounded-lg">

            </div>

        </div>
        {{-- end right --}}
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            // استدعاء الدالة لتحميل المهام عند تحميل الصفحة
            loadTodos();
            loadCompletedTodos();

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

            function loadTodos(searchTerm = '') {
                var url = "{{ route('todos.index') }}";
                var params = searchTerm ? '?search=' + encodeURIComponent(searchTerm) : '';
                url += params;
                ajaxGet(url, function(response) {
                    $('#todos').empty();
                    if (response.length === 0) {
                        $('#todos').append(
                            '<h1 class="text-2xl font-bold text-center text-white col-span-12">No todos found</h1>'
                        );
                    }
                    const uncompletedTodosTitle =
                        '<h1 class="text-3xl font-bold text-center text-white col-span-12 mb-8">Uncompleted Todos</h1>';
                    $('#todos').append(uncompletedTodosTitle);
                    response.forEach(function(todo) {
                        var todoHtml = `
                        <div class="todo col-span-12 sm:col-span-6  lg:col-span-4 flex flex-col p-3 bg-indigo-900 rounded-lg relative"
                    data-id="${todo.id}">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-xl font-semibold text-white mb-3 ">${todo.title}</h1>
                        <div>
                            <a id="btn-completed" data-id="${todo.id}"
                                class=" bg-green-500 text-white px-2 py-1 rounded-lg text-xl font-bold hover:bg-green-700 hover:cursor-pointer"><i
                                    class="fa-solid fa-check"></i></a>
                        </div>
                    </div>
                    <p
                        class="w-full bg-indigo-50 text-indigo-950 p-3 rounded-lg min-h-24 font-bold text-lg mb-3 break-words">
                        ${todo.description}</p>
                    <div class="flex justify-between gap-2 mt-auto mb-7">
                        <a id="btn-edit"
                            class="edit-todo block text-center p-2 grow bg-green-500 text-white rounded-lg hover:bg-green-400 hover:cursor-pointer"
                            data-id="${todo.id}">Edit</a>
                        <a id="btn-delete"
                            class="delete-todo block text-center p-2 grow bg-red-500 text-white rounded-lg hover:bg-red-400 hover:cursor-pointer"
                            data-id="${todo.id}">Delete</a>
                    </div>
                    <span
                        class="absolute bottom-0 right-0 p-2  text-white rounded-bl-lg rounded-tr-lg">${todo.created_at}</span>
                </div>
                    `;
                        $('#todos').append(todoHtml);
                    });
                    $('#loading').fadeOut();
                }, function(xhr) {
                    console.log(xhr.responseText);
                    $('#loading').fadeOut();
                });
            }

            function loadCompletedTodos() {
                ajaxGet("{{ route('todos.completed') }}", function(response) {
                    $('#complteded-todos').empty();
                    if (response.length === 0) {
                        $('#complteded-todos').append(
                            '<h1 class="text-2xl font-bold text-center text-white col-span-12">No completed todos found</h1>'
                        );
                    }
                    const completedTodosTitle =
                        '<h1 class="text-3xl font-bold text-center text-white col-span-12 mb-8">Completed Todos</h1>';
                    $('#complteded-todos').append(completedTodosTitle);
                    response.forEach(function(todo) {
                        var todoHtml = `
                        <div class="todo col-span-12 sm:col-span-6  lg:col-span-4 flex flex-col p-3 bg-green-900 rounded-lg relative"
                    data-id="${todo.id}">
                    <div class="flex items center justify-between mb-2">
                        <h1 class="text-xl font-semibold text-white mb-3 ">${todo.title}</h1>
                        <div>
                            <a id="btn-inCompleted" data-id="${todo.id}"
                                class=" bg-red-500 text-white px-2 py-1 rounded-lg text-xl font-bold hover:bg-red-700 hover:cursor-pointer"><i class="fa-solid fa-x"></i></a>
                        </div>
                    </div>
                    <p
                        class="w-full bg-green-50 text-green-950 p-3 rounded-lg min-h-24 font-bold text-lg mb-8 break-words">
                        ${todo.description}</p>
                    <span
                        class="absolute bottom-0 right-0 p-2  text-white rounded-bl-lg rounded-tr-lg">${todo.completed_at}</span>
                </div>
                    `;
                        $('#complteded-todos').append(todoHtml);
                    });
                    $('#loading').fadeOut();

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

            // حدث النقر على زر حذف المهمة
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

            $(document).on('click', '#btn-completed', function() {
                var todoId = $(this).data('id');
                let url = "{{ route('todos.complete', '') }}" + '/' + todoId;
                console.log(url);
                ajaxPost(url, {}, function(response) {
                    loadTodos();
                    loadCompletedTodos();
                    displayMessage($('#success'), response.message);
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.log(error);
                    displayMessage($('#errors'), error);
                });
            });
            $(document).on('click', '#btn-inCompleted', function() {
                var todoId = $(this).data('id');
                let url = "{{ route('todos.incomplete', '') }}" + '/' + todoId;
                console
                ajaxPost(url, {}, function(response) {
                    loadTodos();
                    loadCompletedTodos();
                    displayMessage($('#success'), response.message);
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.log(error);
                    displayMessage($('#errors'), error);
                });
            });
        });
    </script>
@endsection
