<?php

namespace Spatie\MediaLibrary\MediaCollections\Models\Concerns;

use Spatie\MediaLibrary\MediaConversion;

trait HasConversions
{
    protected $conversions;

    public function storeConversions()
    {
        if (empty($this->conversions)) {
            $conversions = $this->getGeneratedConversions();

            foreach ($conversions as $key => $state) {
                if ($state) {
                    $this->conversions[$key] = new MediaConversion($this, $key);
                }
            }
        }

        return $this;
    }

    public function getConversionsAttribute()
    {
        $this->storeConversions();
        return $this->conversions;
    }

    public function getConversion($key)
    {
        $this->storeConversions();
        return $this->conversions[$key] ?? null;
    }
}
