<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form data is present
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $files = isset($_FILES['file']) ? $_FILES['file'] : null;

    // If any required field is empty, show an error
    if (empty($title) || empty($description) || empty($category) || empty($price) || empty($files)) {
        $error_message = "All fields are required.";
    } else {
        include '../includes/db.php';

        // Destination directory
        $target_dir = "../uploads/";
        $image_urls = [];

        // Allowed file types
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/mov'];

        // Max file size (20MB)
        $max_file_size = 20 * 1024 * 1024; // 20MB

        // Check if the number of files is less than or equal to 5
        if (count($files['name']) > 5) {
            $error_message = "You can upload a maximum of 5 files.";
        } else {
            // Check and upload each file
            for ($i = 0; $i < count($files['name']); $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'type' => $files['type'][$i],
                    'size' => $files['size'][$i]
                ];

                // Check file size
                if ($file['size'] > $max_file_size) {
                    $error_message = "The file " . $file['name'] . " exceeds the maximum allowed size (20MB).";
                    break;
                }

                // If it's a video, it must be smaller than the 20MB size limit
                if (in_array($file['type'], ['video/mp4', 'video/webm', 'video/mov'])) {
                    if ($file['size'] > $max_file_size) {
                        $error_message = "The video " . $file['name'] . " exceeds the maximum allowed size of 20MB.";
                        break;
                    }
                }

                // Check file type
                $target_file = $target_dir . basename($file['name']);
                $file_type = mime_content_type($file['tmp_name']);

                if (in_array($file_type, $allowed_types)) {
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        $image_urls[] = $target_file; // Save the file path
                    } else {
                        $error_message = "An error occurred while uploading the file " . $file['name'];
                        break;
                    }
                } else {
                    $error_message = "The file type is not allowed: " . $file['name'];
                    break;
                }
            }
        }

        // If no errors, insert data into the database
        if (empty($error_message)) {
            $image_urls_str = implode(',', $image_urls); // Join file paths with a comma

            // Insert data into the database
            $sql = "INSERT INTO projects (title, description, image_urls, category, price) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title, $description, $image_urls_str, $category, $price]);

            // Set the success message with the title
            $success_message = $title . " has been added successfully !";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="icon" type="image/png" href="../../assets/logo.png"/>
    <link rel="stylesheet" href="./addpro.css">
   
</head>
<body>
    <div class="form-container">
        <h2>Add Project</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($success_message)) : ?>
            <p class="success-message"><?php echo $success_message; ?></p>
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
                    <option value="woman">Woman</option>
                    <option value="man">Man</option>
                    <option value="child">Child</option>
                    <option value="shoes">Shoes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (DZD):</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="file">Files (Images and Videos only, you can upload multiple files up to 20MB each):</label>
                <input type="file" id="file" name="file[]" class="form-control" accept="image/*, video/*" multiple required>
            </div>
            <div class="button-container">
                <button type="submit">Add</button>
                <button type="button" onclick="window.location.href='dashboard.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
