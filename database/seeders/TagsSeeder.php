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

        $projectTags = [
            'E-commerce',
            'Social Network',
            'Blog',
            'CMS',
            'Learning Management',
            'Project Management',
            'Analytics',
            ''
        ];

        collect($types)->each(function ($type) {
            Tag::findOrCreate('my tag', $type);
        });
    }
}
