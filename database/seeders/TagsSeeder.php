<?php

namespace Database\Seeders;

use Spatie\Tags\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'project',
            'package'
        ];

        $tags = [
            // Authentication & Authorization
        ];

        collect($types)->each(function ($type) {
            Tag::findOrCreate('my tag', $type);
        });
    }
}
