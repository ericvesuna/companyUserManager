<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.meta_header')
        @include('layouts.scripts')
        @include('layouts.css')
    </head>

    <body>
        <div class="body-class">
            @include('layouts.header')
            <div id="content" class="container-fluid">
                @yield('content')
                <div id="footer">
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        toastr.options = {
            "closeButton": false,
            "preventDuplicates": true,
            "progressBar": false,
            "showDuration": "4000",
            "hideDuration": "100",
            "timeOut": "3000",
            "extendedTimeOut": "1",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
        };
        
        @if(Session::has('success'))
            var alertMessage = {!! json_encode(Session::get('success')) !!};
            toastr.success(alertMessage);
        @endif
        @if(Session::has('error'))
            var alertMessage = {!! json_encode(Session::get('error')) !!};
            toastr.error(alertMessage);
        @endif
        
    </script>
    @yield('script')
</html>
