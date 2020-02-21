<div class="container">
    @if(session('success'))
    <script src="{{ asset('js/sweetalert2@9.js') }}" type="text/javascript"></script>
    <script>
       Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 5500
        });
    </script>
    @endif
    @if(session('danger'))
        <script src="{{ asset('js/sweetalert2@9.js') }}" type="text/javascript"></script>
        <script>
           Swal.fire({
                position: 'top-center',
                icon: 'error',
                title: '{{ session("danger") }}',
                showConfirmButton: false,
                timer: 5500
            });
        </script>
    @endif
    @if(session('warning'))
        <script>
            Swal.fire({
                 position: 'top-center',
                 icon: 'warning',
                 title: '{{ session("warning") }}',
                 showConfirmButton: false,
                 timer: 5500
             });
         </script>
    @endif
</div>