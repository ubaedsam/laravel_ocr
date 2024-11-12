<!-- resources/views/upload.blade.php -->

<html>
<body>
    <form action="{{ route('extract-text') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" accept="image/*">
        <button type="submit">Extract Text</button>
    </form>
</body>
</html>
