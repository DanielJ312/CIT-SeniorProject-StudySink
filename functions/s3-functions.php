<?php
# Functions - Contains functions relating to AWS S3
require ($_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php");

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

function upload_avatar($file) {
    $credentials = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/config.ini");
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
        $key = "avatars/{$_SESSION['USER']->UserID}_{$_SESSION['USER']->Username}_" . (time() - (60*60*7)) . ".png";

        try {
            $result = $s3->putObject([
                'Bucket' => $credentials['s3_bucket_name'],
                'Key' => $key,
                'SourceFile' => $file_path,
                'ACL' => 'public-read',
            ]);
            
            $values['UserID'] = $_SESSION['USER']->UserID;
            $values['Avatar'] = $result['ObjectURL'];
            $query = "UPDATE USER_T SET Avatar = :Avatar WHERE UserID = :UserID";
            run_database($query, $values);
            update_session();

        } catch (S3Exception $e) {
            echo "There was an error uploading the file: " . $e->getMessage();
        }
    } else {
        echo "Error: Please select a valid image file.";
    }
}
?>