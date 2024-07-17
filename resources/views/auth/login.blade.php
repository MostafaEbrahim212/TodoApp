@extends('layout')


@section('content')
    <div class="container flex items-center justify-center h-[calc(100vh-56px)]">
        <div class="bg-white text-indigo-700 p-5 rounded-xl w-72 sm:w-96">
            <h1 class="text-4xl font-bold text-center mb-6 w-full">Login</h1>
            <form id="login-form" class="space-y-3">
                @csrf
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="email" class="text-xl font-semibold">Email</label>
                    <input id="email" type="email" name="email" id="email" class="custom-input"
                        placeholder="Enter your Email" required>
                </div>
                <div class="flex flex-col items-start space-y-1 w-full">
                    <label for="Password" class="text-xl font-semibold">Password</label>
                    <input id="password" type="Password" name="password" id="Password" class="custom-input"
                        placeholder="Enter your Password" required>
                </div>
                <span class="text-red-700 text-lg hidden" id="errors"></span>
                <div class="flex items-center justify-between w-full">
                    <button id="btn-login" type="submit"
                        class="bg-indigo-700 text-white font-semibold rounded-lg p-2 hover:bg-indigo-600">Login</button>
                    <div class="flex items-center">
                        <a href="{{ route('register') }}" class="text-indigo-700 font-semibold hover:underline">Register</a>
                        <a href="#" class="text-indigo-700 font-semibold hover:underline ml-2">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('#login-form').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            ajaxPost("{{ route('postLogin') }}", formData, function(response) {
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
