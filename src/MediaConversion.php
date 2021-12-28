<?php

namespace Spatie\MediaLibrary;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\File;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaConversion
{
    /**
     * @var Media
     */
    private $model;

    /**
     * @var string
     */
    private $conversionName;

    /**
     * @var string|boolean
     */
    private $path;

    public $hash;
    public $width;
    public $height;
    public $url;
    public $size;
    public $sizeHuman;

    /**
     * @param Media $media
     * @param string $conversionName
     */
    public function __construct(Media $media, string $conversionName)
    {
        $this->model = $media;
        $this->conversionName = $conversionName;

        if (!empty($this->model)) {
            $this->perform();
        }
    }

    protected function perform()
    {
        $this->path = $this->model->getPath($this->conversionName);

        if (empty($this->path)) return;

        $this->setHash();
        $this->setUrl();
        $this->setDimensions();
        $this->setSize();
    }

    private function setUrl()
    {
        $urlGenerator = UrlGeneratorFactory::createForMedia($this->model, $this->conversionName);
        $this->url = $urlGenerator->getUrl(false) . '?hash=' . Str::limit($this->hash, config('media-library.conversion_hash_length', 10), '');
    }

    private function setHash()
    {
        $this->hash = md5_file($this->path);
    }

    private function setDimensions()
    {
        [$width, $height] = getimagesize($this->path);
        $this->width = $width;
        $this->height = $height;
    }

    private function setSize()
    {
        $this->size = filesize($this->path);
        $this->sizeHuman = File::getHumanReadableSize($this->size);
    }

    public function aspectRatio()
    {
        return round(((int) $this->height / (int) $this->width) * 100, 5) . '%';
    }
}
