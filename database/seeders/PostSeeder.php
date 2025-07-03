<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan beberapa data contoh
        Post::create([
            'title' => 'Post Pertama',
            'body' => 'Ini adalah body dari post pertama.'
        ]);

        Post::create([
            'title' => 'Post Kedua',
            'body' => 'Ini adalah body dari post kedua.'
        ]);

        Post::create([
            'title' => 'Post Ketiga',
            'body' => 'Ini adalah body dari post ketiga.'
        ]);
    }
}
