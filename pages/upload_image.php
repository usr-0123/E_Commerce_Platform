<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        $target_dir = "../assets/images/"; // Ensure this directory exists and is writable
        $file_name = basename($_FILES["image"]["name"]); // Get original filename
        $target_file = $target_dir . $file_name; // Full path for the upload
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ✅ Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("<script>alert('File is not an image.'); window.history.back();</script>");
        }

        // ✅ Allow only certain file formats (JPG, JPEG, PNG, GIF)
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>");
        }

        // ✅ Prevent overwriting by renaming the file
        $new_file_name = time() . "_" . uniqid() . "." . $imageFileType;
        $final_path = $target_dir . $new_file_name;

        // ✅ Move file to `assets/images/`
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $final_path)) {
            echo "<script>alert('Image uploaded successfully!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Failed to upload image. Try again.');</script>";
        }
    } else {
        echo "<script>alert('No file uploaded.'); window.history.back();</script>";
    }
?>
