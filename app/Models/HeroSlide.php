<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/** One slide of the homepage hero carousel. Edited by admin under Website > Homepage Slides. */
class HeroSlide extends Model
{
    protected $fillable = [
        'sort_order', 'tag', 'heading_before', 'heading_accent', 'heading_after',
        'text', 'image_path', 'image_alt', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Full path of the photo on disk (works on the live server, where the web root is public_html). */
    public function imageFile(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        $root = rtrim(request()->server('DOCUMENT_ROOT') ?: public_path(), '/');

        return $root.'/'.ltrim($this->image_path, '/');
    }

    public function hasImage(): bool
    {
        $file = $this->imageFile();

        return $file !== null && is_file($file);
    }

    /** Whether the photo was uploaded through admin (stored in storage/app/public/hero). */
    public function isUploadedImage(): bool
    {
        return str_starts_with((string) $this->image_path, 'storage/hero/');
    }

    public function headingPlain(): string
    {
        return trim(implode(' ', array_filter([$this->heading_before, $this->heading_accent, $this->heading_after])));
    }

    /**
     * Slides for the homepage, as the arrays the carousel partial uses. Falls back
     * to null when the table doesn't exist yet (between deploy and migration), so
     * the partial can use its built-in slides and the homepage never breaks.
     */
    public static function forHomepage(): ?array
    {
        try {
            if (! Schema::hasTable('hero_slides')) {
                return null;
            }

            return static::where('is_active', true)->ordered()->get()->map(fn (HeroSlide $s) => [
                'image' => $s->image_path,
                'alt' => $s->image_alt ?: $s->headingPlain(),
                'tag' => (string) $s->tag,
                'before' => (string) $s->heading_before,
                'accent' => (string) $s->heading_accent,
                'after' => (string) $s->heading_after,
                'text' => (string) $s->text,
            ])->all();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
