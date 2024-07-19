<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\berita::factory()->create([
            'title' => 'Berita Baru',
            'content' => 'Konten berita baru.'
        ]);
    }
}
