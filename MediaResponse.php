<?php

namespace nova\plugin\media;

use nova\framework\request\Response;
use nova\framework\request\ResponseType;

class MediaResponse extends Response
{
    public function __construct(string $filePath, string $fileName, array $header = [])
    {
        parent::__construct('', 200, ResponseType::FILE, $header);
        $this->withFile($filePath, $fileName);
    }

    public function withFile(string $filePath, string $fileName): void
    {
        parent::withFile($filePath, $fileName);
        if ($this->code === 404) {
            return;
        }

        $this->header['Content-Disposition'] = 'inline; filename="' . $fileName . '"';
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $this->header['Content-Type'] = $this->getMimeTypeForExtension($extension);
    }

    private function getMimeTypeForExtension($extension): string
    {
        $mimeTypes = [
            'mp4'  => 'video/mp4',
            'mkv'  => 'video/x-matroska',
            'mov'  => 'video/quicktime',
            'avi'  => 'video/x-msvideo',
            'wmv'  => 'video/x-ms-wmv',
            'flv'  => 'video/x-flv',
            'webm' => 'video/webm',
            'ts'   => 'video/mp2t',
            'm3u8' => 'application/x-mpegURL',

            // 音频格式
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'ogg'  => 'audio/ogg',
            'flac' => 'audio/flac',
            'aac'  => 'audio/aac',
            'm4a'  => 'audio/mp4',
            'wma'  => 'audio/x-ms-wma',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
