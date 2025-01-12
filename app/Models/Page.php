<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $casts = [
        'seo_content' => 'object'
    ];

    public function removeSection($key)
    {
        $sections = json_decode($this->secs) ?? [];
        if (!in_array($key, $sections)) return;

        $filteredCollection = array_filter($sections, function ($item) use ($key) {
            return $item !== $key;
        });

        $filteredCollection = array_values($filteredCollection);
        $this->secs = json_encode($filteredCollection);
        $this->save();
    }
}
