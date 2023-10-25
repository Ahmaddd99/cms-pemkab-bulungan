<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Pemkab Bulungan</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}" />
    <link rel="icon" href="{{ asset('assets/images/logo/logo-pemkab-bulungan.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}" />
    <style>
        #auth {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #auth-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 300px;
            margin: auto;
        }

        #auth-left img {
            width: 8em!important;
            margin-left: 2em;
        }

        #auth-left .auth-title {
            font-size: 1.5rem!important;
            margin-top: 10px;
            white-space: nowrap;
            margin-left: 2em;
        }

        form {
            text-align: center;
        }
    </style>
</head>

<body>
    <script src="assets/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-6 col-12">
                <div id="auth-left">
                    <img src="{{ asset('assets/images/logo/logo-pemkab-bulungan.png') }}" alt=""
                        style="width: 10em">
                    <p class="auth-title mt-2" style="margin:0;font-size:1.5em!important;margin-left:2em">Selamat Datang di Sistem Manajemen Konten</p>
                    <p class="auth-title p">Pemerintah Kabupaten Bulungan</p>
                    <form action="#" method="post" class="mt-3">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="username"
                                class="form-control @error('username')
                            is-invalid
                            @enderror"
                                placeholder="Username" name="username" />
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password"
                                class="form-control @error('password')
                            is-invalid
                            @enderror"
                                placeholder="Password" name="password" />
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-block btn-lg shadow-lg mt-4" style="color: #00A757">
                            Login
                        </button>
                    </form>
                </div>
            </div>
            {{-- <div class="col-lg-6 d-none d-lg-block">
                <div id="auth-right">
                    <div class="col-12 d-flex justify-content-center">
                        <img class="image-login" width="500px" src="">
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script>
        @if (Session::has('error'))
            Toastify({
                text: "{{ Session::get('error') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#B4000D",
            }).showToast()
        @endif

        @if (Session::has('success'))
            Toastify({
                text: "{{ Session::get('success') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#60AF4B",
            }).showToast()
        @endif
    </script>
</body>

</html>
