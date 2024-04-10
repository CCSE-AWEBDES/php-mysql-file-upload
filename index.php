<!DOCTYPE html>
<html>
<head>
    <title>File Upload to MySQL Database</title>
</head>
<body>
    <h2>Upload File to Database</h2>
    <form action="index.php" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>
</body>
</html>

<?php
// Check if the form was submitted
if(isset($_POST["submit"])) {
    // Database connection
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "database_name";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if(!empty($_FILES['fileToUpload']))
    {
        //folder that will contain the uploaded files
        $folder = "files/"; 
        
        //get the original filename
        $originalFileName = basename( $_FILES['fileToUpload']['name']); 

        //renaming the filename to avoid duplication
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION); 
        $newFileName = uniqid() . '.' . $fileExtension;

        //final location of the uploaded file
        $filepath = $folder . $newFileName;


        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $filepath)) {
            
            // Insert file path into database
            $sql = "INSERT INTO tableName (fileName) VALUES ('$filepath')";

            if (mysqli_query($conn, $sql)) {
                 echo "File uploaded successfully."; 
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else{
            echo "There was an error uploading the file, please try again!";
        }
    }
    mysqli_close($conn);
}
?>
