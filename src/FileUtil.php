<?php

namespace Dicicip\FileUpload;

use Dicicip\FileUpload\Model\DicicipFileInfo;
use Intervention\Image\Facades\Image;

class FileUtil
{

    public $rootFilesDirectory = '';
    public $relativeFilesDirectory = '';
    public $directoryPermission = 0777;

    public function __construct($rootFilesDirectory, $relativeFilesDirectory, $directoryPermission = 0777)
    {
        $this->rootFilesDirectory = $rootFilesDirectory;
        $this->relativeFilesDirectory = $relativeFilesDirectory;
        $this->directoryPermission = $directoryPermission;
    }

    /**
     * Store String Base64 File to Temporary Folder
     *
     * @param string $str64 Fill with a string Base64
     *
     * @return DicicipFileInfo
     *
     */
    public function storeBase64ToTemp($strBase64, $thumbQuality = 15)
    {

        /*Is Directory Exist ?*/
        if (!is_dir("{$this->rootFilesDirectory}/{$this->relativeFilesDirectory}")) {
            mkdir("{$this->rootFilesDirectory}/{$this->relativeFilesDirectory}", $this->directoryPermission, true);
        }

        if (!is_dir("{$this->rootFilesDirectory}/thumb/{$this->relativeFilesDirectory}/")) {
            mkdir("{$this->rootFilesDirectory}/thumb/{$this->relativeFilesDirectory}/", $this->directoryPermission, true);
        }

        /*Is Image File ?*/
        $ext = $this->base64Extension($strBase64);
        $filename = time() . '.' . time() . '.' . $ext;

        $path = "{$this->rootFilesDirectory}/{$this->relativeFilesDirectory}/{$filename}";
        $relativeFilePath = "{$this->relativeFilesDirectory}/{$filename}";

        if (strpos($this->getMIMEType($strBase64), 'image') === false) {

            /*file*/
            file_put_contents($path, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $strBase64)));

            return new DicicipFileInfo(
                $path,
                "",
                $relativeFilePath,
                "",
                $filename
            );

        } else {

            /*image*/
            $file = Image::make($strBase64);

            $thumbPath = "{$this->rootFilesDirectory}/thumb/{$this->relativeFilesDirectory}/{$filename}";
            $relativeThumbPath = "thumb/{$this->relativeFilesDirectory}/{$filename}";

            $file->save($path);
            $file->save($thumbPath, $thumbQuality);

            return new DicicipFileInfo(
                $path,
                $thumbPath,
                $relativeFilePath,
                $relativeThumbPath,
                $filename
            );

        }

    }

    /**
     * Move temporary uploaded file and thumbnail to real directory
     *
     * @param string $tempFileRelativePath Path To Temporary File
     * @param string $targetRelativeDirectory Path To Real Folder
     *
     * @return DicicipFileInfo
     *
     */
    public function storeTempFileTo($tempFileRelativePath, $targetRelativeDirectory)
    {

        $tempFullPath = "{$this->rootFilesDirectory}/{$tempFileRelativePath}";

        if (empty($tempFileRelativePath)) {
            return null;
        }

        /*Is Directory Exist ?*/
        if (!is_dir("{$this->rootFilesDirectory}/{$targetRelativeDirectory}")) {
            mkdir("{$this->rootFilesDirectory}/{$targetRelativeDirectory}", $this->directoryPermission, true);
        }

        if (!is_dir("{$this->rootFilesDirectory}/thumb/{$targetRelativeDirectory}")) {
            mkdir("{$this->rootFilesDirectory}/thumb/{$targetRelativeDirectory}", $this->directoryPermission, true);
        }

        /*Is Image File ?*/
        $ext = pathinfo($tempFullPath, PATHINFO_EXTENSION);
        $filename = time() . '.' . time() . '.' . $ext;

        $path = "{$this->rootFilesDirectory}/{$targetRelativeDirectory}/{$filename}";
        $relativeFilePath = "{$targetRelativeDirectory}/{$filename}";

        if (strpos(mime_content_type($tempFullPath), 'image') === false) {

            /*file*/
            rename($tempFullPath, $path);

            return new DicicipFileInfo(
                $path,
                "",
                $relativeFilePath,
                "",
                $filename
            );

        } else {

            $thumbPath = "{$this->rootFilesDirectory}/thumb/{$targetRelativeDirectory}/{$filename}";
            $relativeThumbPath = "thumb/{$targetRelativeDirectory}/{$filename}";

            /*image*/
            rename($tempFullPath, $path);
            rename("{$this->rootFilesDirectory}/thumb/{$tempFileRelativePath}", $thumbPath);

            return new DicicipFileInfo(
                $path,
                $thumbPath,
                $relativeFilePath,
                $relativeThumbPath,
                $filename
            );

        }

    }

    /**
     * Get File Mime Type From A Base64 String
     *
     * @param string $str64 Fill with a string Base64
     *
     * @return string File mime type
     *
     */
    public static function getMIMEType($base64string): string
    {
        try {
            preg_match("/^data:(.*);base64/", $base64string, $match);
            return $match[1];
        } catch (\Exception $e) {
            return "";
        }
    }

    /**
     * Get File Extension From A Base64 String
     *
     * @param string $str64 Fill with a string Base64
     *
     * @return string File extension
     *
     */
    public static function base64Extension($str64)
    {
        return explode(";", explode("/", $str64)[1])[0];
    }

}
