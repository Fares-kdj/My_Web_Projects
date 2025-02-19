<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db.php';

    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $file = $_FILES['file'];

    // Destination folder
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($file['name']);

    // Check file type
    $file_type = mime_content_type($file['tmp_name']);
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/mov'];

    if (in_array($file_type, $allowed_types)) {
        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Insert data into the database
            $sql = "INSERT INTO projects (title, description, image_url, category, file_type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title, $description, $target_file, $category, $file_type]);

            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Error uploading the file.";
        }
    } else {
        $error_message = "File type not allowed. Only images and videos are accepted.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" href="./add.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="form-container">
        <h2>Add Project</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="add-project-form">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="html">Design</option>
                    <option value="photoshop">Web Development</option>
                    <option value="wordpress">Filmmaking</option>
                    <option value="mobile">Video Editing</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">File (images or videos only):</label>
                <input type="file" id="file" name="file" class="form-control" accept="image/*, video/*" required>
            </div>
            <button class="smoothScroll btn btn-default btn-lg" type="submit">
                <span>ADD</span>
            </button>
        </form>
    </div>
</body>
</html>