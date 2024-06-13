<!-- upload_form.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload CSV</title>
</head>
<body>
    <h1>Upload Email List</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="email_csv" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
