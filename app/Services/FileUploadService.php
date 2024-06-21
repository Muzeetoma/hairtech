<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class FileUploadService{

    public static int $ImageWidth = 350;
    public static int $ImageHeight = 350;
    public static string $ImageExt = 'webp';
    public static $ImageFormat = IMAGETYPE_WEBP;

    public function uploadImages(?array $files){
        if(empty($files)){
            throw new \Exception('No files found');
        }
        $uploadedImages = [];
        foreach($files as $file){
            $image = $this->uploadImage($file);
            $uploadedImages[] = $image;
        }
        return $uploadedImages;
    }
    private function uploadImage(UploadedFile $file){
        try{
             if(empty($file)){
                 throw new \Exception('Image file does not exist');
             }
             $uniqueName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
             $destinationPath = 'uploads';
             $file->move($destinationPath, $uniqueName);
             $sourcePath = $destinationPath . '/' . $uniqueName;
             $finalName = Str::uuid()->toString() . '.' . FileUploadService::$ImageExt;
             $finalPath = $destinationPath . '/' . $finalName;

             Log::info("Source Path: " . $sourcePath);
             Log::info("Final Path: " . $finalPath);

             $this->resizeImage(
                $sourcePath, $finalPath, 
                FileUploadService::$ImageWidth, 
                FileUploadService::$ImageHeight,
                FileUploadService::$ImageFormat
                );

             return $finalName;
        } catch (\Exception $ex) {
            Log::error('File upload error: ' . $ex->getMessage());
            session()->flash('error', 'Something went wrong while uploading file');
        }
     }
     function resizeImage($sourcePath, $destPath, $newWidth, $newHeight, $outputFormat = null, $quality = 80) {
        if (!file_exists($sourcePath)) {
            throw new \Exception("Source image file not found: $sourcePath");
        }

        $imageInfo = getimagesize($sourcePath);
        $type = $imageInfo[2];
    
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                imagealphablending($sourceImage, false);
                imagesavealpha($sourceImage, true);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception("Unsupported image type: $type");
        }
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageInfo[0], $imageInfo[1]);
    
        if ($outputFormat === null) {
            $outputFormat = $type;
        }
    
        switch ($outputFormat) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $destPath, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $destPath);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage, $destPath);
                break;
            case IMAGETYPE_WEBP:
                if (!function_exists('imagewebp')) {
                    throw new \Exception("WebP support is not enabled in your PHP installation.");
                }
                imagewebp($newImage, $destPath, $quality);
                break;
            default:
                throw new \Exception("Unsupported output format: $outputFormat");
        }
    
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        $this->deleteImage($sourcePath);
    
        Log::info("Image resized and saved as " . basename($destPath) . ".");
    } 
    
    function deleteImage($filePath) {
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                Log::error("File deleted: $filePath");
            } else {
                Log::error("Unable to delete the file: $filePath");
            }
        } else {
            Log::error("File does not exist: $filePath");
        }
    }

}