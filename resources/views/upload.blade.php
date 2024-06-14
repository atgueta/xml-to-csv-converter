<!DOCTYPE html>
<html>
<head>
    <title>Upload .xml File</title>
</head>
<body>
    <h1>Upload .xml File</h1>
    <form action="/upload" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".xml" required>
        <button type="submit">Upload and Convert</button>
    </form>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
