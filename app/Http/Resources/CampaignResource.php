<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'current_amount' => $this->current_amount,
            'image' => $this->image,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'formatted_end_date' => $this->end_date ? $this->end_date->format('d M Y') : null,
            'status' => $this->status,
            'kategori' => $this->kategori,
            'yayasan' => $this->yayasan,
            'recipient_description' => $this->recipient_description,
            'jenis_penerima' => $this->jenis_penerima,
            'penyaluran' => $this->penyaluran,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'lokasi' => $this->lokasi,
            'provinsi' => $this->provinsi,
            'kabupaten' => $this->kabupaten,
            'kecamatan' => $this->kecamatan,
            'alamat_lengkap' => $this->alamat_lengkap,
            'kode_pos' => $this->kode_pos,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
