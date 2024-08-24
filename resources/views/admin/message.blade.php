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
