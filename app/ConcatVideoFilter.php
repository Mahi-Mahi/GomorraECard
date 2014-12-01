<?php

error_log(__FILE__);

use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Filters\Video\VideoFilterInterface;

class ConcatVideoFilter implements VideoFilterInterface
{
    private $files;
    private $priority;

    public function __construct(array $files = [], $priority = 0)
    {
        $this->files = $files;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function addFile($file)
    {
        $this->files[] = $file;

        return $this;
    }

    public function addFiles(array $files)
    {
        foreach ($files as $file) {
            $this->addFile($file);
        }

        return $this;
    }

    public function deleteFile($fileToDelete)
    {
        $this->files = array_values(array_filter($this->files, function($file) use ($fileToDelete) {
            return $fileToDelete !== $file;
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $Video, VideoInterface $format)
    {
        $params = [];
        $count = count($this->files) + 1;

        foreach ($this->files as $i => $file) {
            $params[] = '-i';
            $params[] = $file;
        }

        $params[] = '-filter_complex';
        $params[] = 'concat=n='.$count.':v=0:a=1 [a]';
        $params[] = '-map';
        $params[] = '[a]';

        return $params;
    }
}
