<?php

namespace Rawilk\LaravelBase\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Features;

/**
 * @property null|string $avatar_path
 *
 * @mixin \Eloquent
 */
trait HasAvatar
{
    public function updateAvatar(UploadedFile $photo): void
    {
        tap($this->avatar_path, function ($previous) use ($photo) {
            $this->forceFill([
                'avatar_path' => $photo->storePublicly('/', ['disk' => $this->avatarDisk()]),
            ])->save();

            if ($previous) {
                rescue(fn () => Storage::disk($this->avatarDisk())->delete($previous));
            }
        });
    }

    public function deleteAvatar(): void
    {
        if (! Features::managesAvatars()) {
            return;
        }

        if (! $this->avatar_path) {
            return;
        }

        rescue(fn () => Storage::disk($this->avatarDisk())->delete($this->avatar_path));

        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? $this->getStoredAvatarUrl()
            : $this->defaultAvatarUrl();
    }

    protected function defaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name->full) . '&color=7F9CF5&background=EBF4FF';
    }

    protected function getStoredAvatarUrl(): string
    {
        return Str::startsWith(Str::lower($this->avatar_path), 'http')
            ? $this->avatar_path
            : Storage::disk($this->avatarDisk())->url($this->avatar_path);
    }

    protected function avatarDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME'])
            ? 's3'
            : Config::get('laravel-base.avatar_disk', 'public');
    }
}
