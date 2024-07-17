@extends('layout')


@section('content')
    <div class="container flex items-center justify-center h-[calc(100vh-56px)]">
        <div class="bg-white text-indigo-700 p-5 rounded-xl w-72 sm:w-96">
            <h1 class="text-4xl font-bold text-center mb-6 w-full">Register</h1>
            <form id="form-register" class="space-y-3">
                @csrf
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="name" class="text-xl font-semibold">Name</label>
                    <input id="name" type="name" name="name" id="name" class="custom-input"
                        placeholder="Enter your name">
                </div>
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="email" class="text-xl font-semibold">email</label>
                    <input id="email" type="email" name="email" id="email" class="custom-input"
                        placeholder="Enter your email">
                </div>
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="password" class="text-xl font-semibold">password</label>
                    <input id="password" type="password" name="password" id="password" class="custom-input"
                        placeholder="Enter your password">
                </div>
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="password_confirmation" class="text-xl font-semibold">confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        id="password_confirmation" class="custom-input" placeholder="Enter your password again">
                </div>
                <span class="text-red-700 text-lg hidden" id="errors"></span>
                <div class="flex items-center justify-between w-full">
                    <button type="submit"
                        class="bg-indigo-700 text-white font-semibold rounded-lg p-2 hover:bg-indigo-600">Register</button>
                    <a href="{{ route('login') }}" class="text-indigo-700 font-semibold hover:underline">Already Have
                        Account?</a>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('#form-register').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            ajaxPost("{{ route('postRegister') }}", formData, function(response) {
                console.log(response);
                if (response.success) {
                    window.location.href = "{{ route('home') }}";
                } else {
                    displayMessage($('#errors'), response.message);
                }
            }, function(xhr) {
                let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                console.log(error);
            });
        });
    </script>
@endsection
