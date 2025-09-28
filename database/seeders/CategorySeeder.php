<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('categories')->truncate();

        $categories = [
            "Action",
            "Adventure",
            "Comedy",
            "Drama",
            "Fantasy",
            "Horror",
            "Mystery",
            "Romance",
            "Sci-Fi",
            "Thriller",
            "Animation",
            "Documentary"
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'title' => $category,
                'slug' => Str::of($category)->slug('-'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
