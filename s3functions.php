<?php
require 'vendor/autoload.php';
// require($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
// require '/aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

function upload_avatar($file) {
    $credentials = parse_ini_file('config.ini');
    $s3 = new S3Client([
        'version' => 'latest',
        'region' => $credentials['aws_region'],
        'credentials' => [
            'key' => $credentials['aws_access_key'],
            'secret' => $credentials['aws_secret_key'],
        ],
    ]);

    if (isset($file['image']) && $file['image']['error'] === UPLOAD_ERR_OK) {
        $file_path = $file['image']['tmp_name'];
        $key = "uploads/{$_SESSION['USER']->userid}_{$_SESSION['USER']->username}_" . (time() - (60*60*7)) . ".png";

        try {
            // Upload the file.
            $result = $s3->putObject([
                'Bucket' => $credentials['s3_bucket_name'],
                'Key' => $key,
                'SourceFile' => $file_path,
                'ACL' => 'public-read',
                // 'ContentDisposition' => 'inline',
            ]);

            // Display the uploaded image.
            echo "<h2>File uploaded successfully!</h2>";
            echo "<img src='" . $result['ObjectURL'] . "' alt='Uploaded Image'>";
            
            $values['userid'] = $_SESSION['USER']->userid;
            $values['avatar'] = $result['ObjectURL'];
            $query = "UPDATE user_t SET avatar = :avatar WHERE userid = :userid";
            run_database($query, $values);

        } catch (S3Exception $e) {
            echo "There was an error uploading the file: " . $e->getMessage();
        }
    } else {
        echo "Error: Please select a valid image file.";
    }
}

?>

