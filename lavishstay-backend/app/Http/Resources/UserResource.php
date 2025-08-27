<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Resolve avatar: prefer stored profile_photo_path; fall back to avatar column which may contain
        // a full external URL (e.g. Google avatar) or a storage path.
        $avatar = null;
        if ($this->profile_photo_path) {
            $avatar = asset('storage/' . $this->profile_photo_path);
        } elseif ($this->avatar) {
            // If avatar is already a URL, return as-is; otherwise treat as storage path.
            if (str_starts_with($this->avatar, 'http')) {
                $avatar = $this->avatar;
            } else {
                $avatar = asset('storage/' . $this->avatar);
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $avatar,
            'google_id' => $this->google_id,
            'profile_photo_path' => $this->profile_photo_path,
            'roles' => $this->roles->pluck('name')->toArray(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
