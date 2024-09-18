@if (session('success'))
    <script>
        toaster('{{ session('success') }}', 'success');
    </script>
@endif
@if (session('error'))
    <script>
        toaster('{{ session('error') }}', 'danger');
    </script>
@endif

@if(session('password'))
    <script>
        toaster('This is your new password: {{ session('password') }}', 'success');
    </script>
@endif

