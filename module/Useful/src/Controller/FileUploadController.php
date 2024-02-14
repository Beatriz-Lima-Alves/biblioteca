<?php

namespace Useful\Controller;

use Laminas\Math\Rand;

/**
 * Upload de arquivos
 *
 * @author Raquel
 *
 */
class FileUploadController
{

    const FILE_PRIVATE_DIR = 'data/tmp/';

    const FILE_PUBLIC_DIR = 'public/tmp/';

    /**
     * Upload
     *
     * @param $FILES
     * @param $prefix
     * @param bool $private
     * @param bool $s3
     * @param bool $optimize
     * @param bool $resize
     * @param int $desiredWidth
     * @param bool $move
     * @return array
     * @throws \Exception
     */
    public function uploadFile($FILES, $prefix, $private = false, $s3 = false, $optimize = false, $resize = false, $desiredWidth = 240, $move = true)
    {
        if (!isset($FILES['file']['tmp_name'])) {
            throw new \Exception('Invalid file temp.');
        } else {
            // Validate the file type
            $fileTypes = array(
                'jpg',
                'jpeg',
                'gif',
                'png',
                'pdf',
                'doc',
                'docx',
                'xls',
                'xlsx',
                'txt',
                'csv',
                'rtf'
            ); // File extensions
            $fileParts = pathinfo($FILES['file']['name']);
            $basename = $fileParts['basename'];
            $extension = strtolower($fileParts['extension']);
            $is_image = in_array($extension, [
                'jpg',
                'jpeg',
                'gif',
                'png'
            ]);
            if (in_array($extension, $fileTypes)) {
                // Setando o destino do arquivo dentro do bucket
                $filename = $prefix . "_" . Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz123456789', true) . '.' . $extension;
                $uploadfile = self::FILE_PRIVATE_DIR . $filename;
                if (!$private) {
                    $uploadfile = self::FILE_PUBLIC_DIR . $filename;
                }
                if (!$move) {
                    // S3
                    $file_url = null;
                    // Return
                    return array(
                        'file_name' => $basename,
                        'file_extesion' => $extension,
                        'file_type' => $is_image,
                        'file_url' => $file_url,
                        'extension' => $extension

                    );
                    // Movendo
                } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                    // Default
                    $file_url = $uploadfile;
                    //Is Image
                    if ($is_image) {
                        if ($resize) {
                            $uploadfile = $this->resizeImage($uploadfile, $desiredWidth);
                        }

                        if ($optimize) {
                            $this->optimizeImage($uploadfile);
                        }
                    }
                    // S3
                    $file_url = null;

                    // Return
                    return array(
                        'file_name' => $basename,
                        'file_extesion' => $extension,
                        'file_type' => $is_image,
                        'file_url' => $file_url,
                        'extension' => $extension

                    );
                } else {
                    throw new \Exception('Error Upload 1');
                }
            } else {
                throw new \Exception('Invalid file type 2');
            }
        }
        throw new \Exception('Invalid upload file in the system, check permission administrator.');
    }



    /**
     * Salva um base64 para arquivo fisico
     *
     * @param $base64Image
     * @param $prefix
     * @param bool $private
     * @return array|bool
     */
    public function uploadBase64($base64Image, $prefix, $private = true)
    {
        $img = $base64Image;
        $img = str_replace(array(
            'data:image/png;base64,',
            'data:image/jpg;base64,',
            'data:image/jpeg;base64,',
            'data:image/gif;base64,',
            ' ',
            '\s',
            '\t',
            '\n'
        ), '', $img);

        $data = base64_decode($img);
        $filename = $prefix . "_" . Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz123456789', true) . uniqid() . '.png';
        $uploadfile = self::FILE_PRIVATE_DIR . $filename;
        if (!$private) {
            $uploadfile = self::FILE_PUBLIC_DIR . $filename;
        }

        $result = file_put_contents($uploadfile, $data);
        if ($result) {
            return array(
                'file_url' => $uploadfile,
                'file_name' => $filename,
                'file_public' => str_replace('public', '', $uploadfile),
                'extension' => '.png'

            );
        }
        return false;
    }

    /**
     * Resizes the image, keeping its aspect ratio.
     * @author  https://olegkrivtsov.github.io/using-zend-framework-3-book/html/en/Uploading_Files_with_Forms/Example__Image_Gallery.html
     * @param $filePath
     * @param int $desiredWidth
     * @return bool|string
     */
    public function resizeImage($filePath, $desiredWidth = 240)
    {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Calculate aspect ratio
        $aspectRatio = $originalWidth / $originalHeight;
        // Calculate the resulting height
        $desiredHeight = $desiredWidth / $aspectRatio;

        // Get image info
        $fileInfo = $this->getImageFileInfo($filePath);

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) == 'image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Save the resized image to temporary location
        $tmpFileName = tempnam("/tmp", "FOO");
        imagejpeg($resultingImage, $tmpFileName, 80);

        // Return the path to resulting image.
        return $tmpFileName;
    }

    // Returns the path to the saved image file.
    public function getImagePathByName($fileName)
    {
        // Take some precautions to make file name secure.
        $fileName = str_replace("/", "", $fileName);  // Remove slashes.
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes.

        // Return concatenated directory name and file name.
        return $this->saveToDir . $fileName;
    }

    // Returns the image file content. On error, returns boolean false.
    public function getImageFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    // Retrieves the file information (size, MIME type) by image path.
    public function getImageFileInfo($filePath)
    {
        // Try to open file
        if (!is_readable($filePath)) {
            return false;
        }

        // Get file size in bytes.
        $fileSize = filesize($filePath);

        // Get MIME type of the file.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if ($mimeType === false)
            $mimeType = 'application/octet-stream';

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

    /**
     * Otimizando imagem
     * @param $pathToImage
     * @return bool
     */
    public function optimizeImage($pathToImage)
    {
        $optimizerChain = \Spatie\ImageOptimizer\OptimizerChainFactory::create();
        $optimizerChain->optimize($pathToImage);
        return true;
    }

    /**
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public function downloadFile(string $url): string
    {
        // Use basename() function to return the base name of file
        $name = basename($url);
        $filename = self::FILE_PRIVATE_DIR . $name;
        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        if (file_put_contents($filename, file_get_contents($url))) {
            return $filename;
        }
        throw new \Exception('Error Download File');
    }
}