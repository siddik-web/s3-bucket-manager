<?php
/**
 * @package RocksCoder\S3BucketManager
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 RocksCoder
 * @license MIT
 * @link https://github.com/siddik-web/s3-bucket-manager
 * @version 1.0.0
 * @since 1.0.0
 */

use PHPUnit\Framework\TestCase;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use RocksCoder\S3BucketManager\S3BucketManager;
use Illuminate\Support\Facades\Log;

/**
 * S3BucketManagerTest class
 * 
 * @since 1.0.0
 */
class S3BucketManagerTest extends TestCase
{
    /**
     * @var S3Client
     * @since 1.0.0
     */ 
    private $s3ClientMock;

    /**
     * @var S3BucketManager
     * @since 1.0.0
     */
    private $bucketManager;

    /**
     * @var string
     * @since 1.0.0
     */
    private $bucketName = 'test-bucket';

    /**
     * @since 1.0.0
     */
    protected function setUp(): void
    {
        // Mock S3Client
        $this->s3ClientMock = $this->createMock(S3Client::class);
        
        // Inject the mock S3 client into the S3BucketManager
        $this->bucketManager = new S3BucketManager([
            'aws_region' => 'us-east-1',
            'aws_access_key_id' => 'test-key',
            'aws_secret_access_key' => 'test-secret',
            'bucket_name' => $this->bucketName
        ]);
        
        $reflection = new \ReflectionClass(S3BucketManager::class);
        $property = $reflection->getProperty('s3');
        $property->setAccessible(true);
        $property->setValue($this->bucketManager, $this->s3ClientMock);
    }

    /**
     * @since 1.0.0
     */
    public function testListFiles()
    {
        // Arrange
        $this->s3ClientMock->expects($this->once())
            ->method('listObjectsV2')
            ->with(['Bucket' => $this->bucketName, 'Prefix' => null])
            ->willReturn([
                'Contents' => [
                    ['Key' => 'file1.txt'],
                    ['Key' => 'file2.txt'],
                ]
            ]);

        // Act
        $files = $this->bucketManager->listFiles();

        // Assert
        $this->assertEquals(['file1.txt', 'file2.txt'], $files);
    }

    /**
     * @since 1.0.0
     */
    public function testGetFile()
    {
        $fileName = 'file.txt';
        $fileContent = 'This is the file content.';

        $this->s3ClientMock->expects($this->once())
            ->method('getObject')
            ->with(['Bucket' => $this->bucketName, 'Key' => $fileName])
            ->willReturn(['Body' => $fileContent]);

        $content = $this->bucketManager->getFile($fileName);

        $this->assertEquals($fileContent, $content);
    }

    /**
     * @since 1.0.0
     */
    public function testCreateFile()
    {
        $fileName = 'newfile.txt';
        $fileContent = 'New file content';

        $this->s3ClientMock->expects($this->once())
            ->method('putObject')
            ->with([
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'Body' => $fileContent
            ]);

        $result = $this->bucketManager->createFile($fileName, $fileContent);

        $this->assertEquals("File uploaded successfully: {$fileName}", $result);
    }

    /**
     * @since 1.0.0
     */
    public function testUpdateFile()
    {
        $fileName = 'updatefile.txt';
        $newContent = 'Updated content';

        $this->s3ClientMock->expects($this->once())
            ->method('putObject')
            ->with([
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'Body' => $newContent
            ]);

        $result = $this->bucketManager->updateFile($fileName, $newContent);

        $this->assertEquals("File updated successfully: {$fileName}", $result);
    }

    /**
     * @since 1.0.0
     */
    public function testDeleteFile()
    {
        $fileName = 'deletefile.txt';

        $this->s3ClientMock->expects($this->once())
            ->method('deleteObject')
            ->with(['Bucket' => $this->bucketName, 'Key' => $fileName]);

        $result = $this->bucketManager->deleteFile($fileName);

        $this->assertEquals("File deleted successfully: {$fileName}", $result);
    }

    /**
     * @since 1.0.0
     */
    public function testListFilesWithException()
    {
        $this->s3ClientMock->expects($this->once())
            ->method('listObjectsV2')
            ->willThrowException(new S3Exception('Error fetching files', new \Aws\Command('ListObjectsV2')));

        Log::shouldReceive('error')->once()->with('Error fetching files from S3 bucket: Error fetching files');
        
        $files = $this->bucketManager->listFiles();

        $this->assertEmpty($files);
    }

    /**
     * @since 1.0.0
     */
    public function testGetFileWithException()
    {
        $fileName = 'nonexistentfile.txt';

        $this->s3ClientMock->expects($this->once())
            ->method('getObject')
            ->willThrowException(new S3Exception('Error reading file', new \Aws\Command('GetObject')));

        Log::shouldReceive('error')->once()->with('Error reading file: Error reading file');
        
        $content = $this->bucketManager->getFile($fileName);

        $this->assertNull($content);
    }
}
