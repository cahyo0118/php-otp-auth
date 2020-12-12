<?php
namespace Dicicip\FileUpload\Model;

class DicicipFileInfo
{

    public $full_path;
    public $full_thumb_path;
    public $path;
    public $thumb_path;
    public $filename;

    public function __construct(
        $full_path,
        $full_thumb_path,
        $path,
        $thumb_path,
        $filename
    )
    {
        $this->full_path = $full_path;
        $this->full_thumb_path = $full_thumb_path;
        $this->path = $path;
        $this->thumb_path = $thumb_path;
        $this->filename = $filename;
    }

}