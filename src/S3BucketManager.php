<?php
/**
 * @package RocksCoder\S3BucketManager
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 RocksCoder
 * @license MIT
 * @link https://github.com/siddik-web/s3-bucket-manager
 * @version 1.0.0
 * @since 1.0.0
 * 
 * This class provides a simple interface for managing files in an AWS S3 bucket.
 * It includes methods for listing, retrieving, creating, updating, and deleting files.
 */

namespace RocksCoder\S3BucketManager;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

/**
 * S3BucketManager class
 * 
 * @since 1.0.0
 */
class S3BucketManager
{
    /**
     * @var S3Client
     * @since 1.0.0
     */
    private $s3;

    /**
     * @var string
     * @since 1.0.0
     */
    private $bucket_name;

    /**
     * @param array $config
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $config['aws_region'],
            'credentials' => [
                'key' => $config['aws_access_key_id'],
                'secret' => $config['aws_secret_access_key']
            ]
        ]);
        $this->bucket_name = $config['bucket_name'];
    }

    /**
     * @param string|null $prefix
     * @return array
     * @since 1.0.0
     */
    public function listFiles(?string $prefix = null): array
    {
        try {
            $response = $this->s3->listObjectsV2([
                'Bucket' => $this->bucket_name,
                'Prefix' => $prefix,
                'ACL' => 'public-read' // Added ACL
            ]);

            $files = [];
            foreach ($response['Contents'] as $object) {
                $files[] = $object['Key'];
            }

            Log::info("Files retrieved from S3 bucket: " . implode(', ', $files));
            return $files;
        } catch (S3Exception $e) {
            Log::error("Error fetching files from S3 bucket: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $file_name
     * @return ?string
     * @since 1.0.0
     */
    public function getFile(string $file_name): ?string
    {
        try {
            $result = $this->s3->getObject([
                'Bucket' => $this->bucket_name,
                'Key' => $this->sanitizeFileName($file_name),
                'ACL' => 'public-read' // Added ACL
            ]);
            Log::info("File content retrieved: {$file_name}");
            return $result['Body'];
        } catch (AwsException $e) {
            Log::error("Error reading file: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * @param string $file_name
     * @param string $file_content
     * @return string
     * @since 1.0.0
     */
    public function createFile(string $file_name, string $file_content): string
    {
        try {
            $this->s3->putObject([
                'Bucket' => $this->bucket_name,
                'Key' => $this->sanitizeFileName($file_name),
                'Body' => $file_content,
                'ACL' => 'public-read' // Added ACL
            ]);
            Log::info("File uploaded successfully: {$file_name}");
            return "File uploaded successfully: {$file_name}";
        } catch (AwsException $e) {
            Log::error("Error uploading file: {$e->getMessage()}");
            return "Error uploading file: {$e->getMessage()}";
        }
    }

    /**
     * @param string $file_name
     * @param string $new_file_content
     * @return string
     * @since 1.0.0
     */
    public function updateFile(string $file_name, string $new_file_content): string
    {
        try {
            $this->s3->putObject([
                'Bucket' => $this->bucket_name,
                'Key' => $this->sanitizeFileName($file_name),
                'Body' => $new_file_content,
                'ACL' => 'public-read' // Added ACL
            ]);
            Log::info("File updated successfully: {$file_name}");
            return "File updated successfully: {$file_name}";
        } catch (AwsException $e) {
            Log::error("Error updating file: {$e->getMessage()}");
            return "Error updating file: {$e->getMessage()}";
        }
    }

    /**
     * @param string $file_name
     * @return string
     * @since 1.0.0
     */
    public function deleteFile(string $file_name): string
    {
        try {
            $this->s3->deleteObject([
                'Bucket' => $this->bucket_name,
                'Key' => $this->sanitizeFileName($file_name),
                'ACL' => 'public-read' // Added ACL
            ]);
            Log::info("File deleted successfully: {$file_name}");
            return "File deleted successfully: {$file_name}";
        } catch (AwsException $e) {
            Log::error("Error deleting file: {$e->getMessage()}");
            return "Error deleting file: {$e->getMessage()}";
        }
    }

    /**
     * @param string $file_name
     * @return string
     * @since 1.0.0
     */
    private function sanitizeFileName(string $file_name): string
    {
        // Sanitize the file name to prevent directory traversal attacks
        return str_replace(['/', '\\', '..'], '', $file_name);
    }
}