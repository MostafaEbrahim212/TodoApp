@extends('layout')

@section('content')
    <div class="container flex flex-col lg:flex-row gap-3 py-10">
        {{-- start left --}}
        <div class="w-full lg:w-2/5 p-5 bg-indigo-500 rounded-lg grow-0 h-full space-y-2">
            <div id="cover-img" class="w-full relative mb-24 min-h-64 rounded-t-lg bg-center bg-cover "
                style="background-image: url('https://plus.unsplash.com/premium_photo-1664112065830-5819554d0ec2?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
                <div class="w-8 h-8 bg-cover bg-center absolute bottom-0 right-0 bg-white "
                    style="background-image: url('{{ asset('assets/images/upload-icon.svg') }}');">
                    <label for="avatar" class="block w-full h-full cursor-pointer">
                        <input type="file" name="cover" id="cover" class="opacity-0 w-full">
                    </label>
                </div>
                <div id="avatar-img"
                    class="w-40 h-40 bg-cover rounded-full bg-center border border-indigo-950 absolute left-1/2 top-full transform -translate-x-1/2 -translate-y-1/2"
                    style="background-image: url('https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png');">
                    <div class="w-8 h-8 bg-cover bg-center absolute bottom-0 right-4 bg-white"
                        style="background-image: url('{{ asset('assets/images/upload-icon.svg') }}');">
                        <label for="avatar" class="block w-full h-full cursor-pointer">
                            <input type="file" name="avatar" id="avatar" class="opacity-0">
                        </label>
                    </div>
                </div>
            </div>
            <h1 class="text-center text-2xl font-bold text-indigo-950 " id="profile-name"></h1>
            <span id="profile-job" class="text-indigo-900 text-center block text-lg font-semibold"></span>
            <p class="bg-indigo-950 text-white p-3 rounded-lg text-lg text-start min-h-24" id="profile-bio"></p>
        </div>
        {{-- end left --}}

        {{-- start right --}}
        <div class="w-full lg:w-4/5 min-h-full p-5 bg-indigo-500 rounded-lg space-y-7 relative">
            <h1 class="text-center text-4xl font-bold text-indigo-950">Edit Profile</h1>
            <div id="success"
                class="bg-green-500 bg-opacity-50 text-white font-semi text-xl rounded-xl p-3 hidden text-center">
            </div>
            <div id="loading" class="text-6xl text-center text-white">
                <i class="fa-solid fa-spinner animate-spin"></i>
            </div>
            <form id="updateProfileForm" class="space-y-4">
                @csrf
                <div class="flex flex-col gap-4">
                    <label for="name" class="text-white">Name</label>
                    <input type="text" name="name" id="name" class="p-2 rounded-lg bg-indigo-950 text-white"
                        required>
                </div>
                <div class="flex flex-col gap-4">
                    <label for="email" class="text-white">Email</label>
                    <input type="email" name="email" id="email" class="p-2 rounded-lg bg-indigo-950 text-white"
                        required>
                </div>
                <div class="flex flex-col gap-4">
                    <label for="phone" class="text-white">Phone</label>
                    <input type="text" name="phone" id="phone" class="p-2 rounded-lg bg-indigo-950 text-white"
                        required>
                </div>
                <div class="flex flex-col gap-4">
                    <label for="text" class="text-white">Job</label>
                    <input type="job" name="job" id="job" class="p-2 rounded-lg bg-indigo-950 text-white"
                        required>
                </div>
                <div class="flex flex-col gap-4">
                    <label for="bio" class="text-white">Bio</label>
                    <textarea required id="bio" name="bio" id="bio"
                        class="p-2 rounded-lg bg-indigo-950 text-white resize-none min-h-40"></textarea>
                </div>
                <div class="flex flex-col gap-4">
                    <label for="country" class="text-white">Country</label>
                    <select name="country" id="country" class="p-2 rounded-lg bg-indigo-950 text-white h-10" required>

                    </select>
                </div>

                <button type="submit"
                    class="bg-indigo-950 text-white p-2 rounded-lg hover:bg-indigo-900 transition duration-300 w-full">Update
                    Profile</button>
            </form>
        </div>
        {{-- end right --}}
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            initialize()
            $('#loading').hide();
            $('#success').hide();
            const token = document.head.querySelector('meta[name="csrf-token"]');

            function initialize() {
                url = '{{ route('profile') }}';
                ajaxGet(url, function(response) {
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#phone').val(response.phone);
                    $('#job').val(response.job);
                    $('#bio').val(response.bio);
                    $('#profile-name').text(response.name);
                    $('#profile-bio').text(response.bio);
                    $('#profile-job').text(response.job);
                    $('#avatar-img').css('background-image', 'url(' + response.avatar + ')');
                    $('#cover-img').css('background-image', 'url(' + response.cover + ')');
                }, function(xhr) {
                    console.log(xhr);
                });
            }
            // change avatar
            $('#avatar').change(function() {
                $('#loading').show();
                var formData = new FormData();
                formData.append('avatar', $(this)[0].files[0]);
                url = '{{ route('profile.avatar') }}';

                ajaxPost(url, formData, function(response) {
                    $('#loading').hide();
                    initialize();
                    displayMessage($('#success'), 'Avatar uploaded successfully');
                }, function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                    console.error('Error uploading avatar:', error);
                });
            });
            // change cover
            $('#cover').change(function() {
                $('#loading').show();
                var formData = new FormData();
                formData.append('cover', $(this)[0].files[0]);
                url = '{{ route('profile.cover') }}';
                ajaxPost(url, formData,
                    function(response) {
                        $('#loading').hide();
                        initialize();
                        displayMessage($('#success'), 'Cover uploaded successfully');
                    },
                    function(xhr) {
                        let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                        console.error('Error uploading cover:', error);
                    }
                );
            });




            // get countries From App.js
            for (let i = 0; i < countries.length; i++) {
                $('#country').append(`<option value="${countries[i]}">${countries[i]}</option>`);
            }
            if ('{{ auth()->user()->profile->country }}') {
                $('#country').val('{{ auth()->user()->profile->country }}');
            }


            $('#updateProfileForm').submit(function(e) {
                e.preventDefault();
                $('#loading').show();
                var formData = new FormData(this);
                console.log(formData['bio']);

                url = '{{ route('profile.update') }}';
                ajaxPut(url, formData,
                    function(response) {
                        $('#loading').hide();
                        initialize();
                        displayMessage($('#success'), 'Profile updated successfully');
                    },
                    function(xhr) {
                        let error = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred";
                        console.error('Error updating profile:', error);
                        console.log(xhr);
                    }
                );
            });
        });
    </script>
@endsection
