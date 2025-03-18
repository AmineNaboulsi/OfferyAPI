<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
USE App\Models\Competences;
class CompetencesSeeder extends Seeder
{
    public function run(): void
    {
        Competences::create([
            'name' => 'Laravel',
            'description' => 'Laravel is a free, open-source PHP web framework, created by Taylor Otwell and intended for the development of web applications following the model–view–controller architectural pattern.',
            'type' => 'framework',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'JavaScript',
            'description' => 'JavaScript is a programming language that conforms to the ECMAScript specification.',
            'type' => 'language',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Vue.js',
            'description' => 'Vue.js is an open-source model–view–viewmodel front end JavaScript framework for building user interfaces and single-page applications.',
            'type' => 'framework',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'React',
            'description' => 'React is an open-source, front end, JavaScript library for building user interfaces or UI components.',
            'type' => 'library',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Angular',
            'description' => 'Angular is a TypeScript-based open-source web application framework led by the Angular Team at Google and by a community of individuals and corporations.',
            'type' => 'framework',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Node.js',
            'description' => 'Node.js is an open-source, cross-platform, back-end JavaScript runtime environment that runs on the V8 engine and executes JavaScript code outside a web browser.',
            'type' => 'runtime',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Python',
            'description' => 'Python is an interpreted high-level general-purpose programming language.',
            'type' => 'language',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Django',
            'description' => 'Django is a high-level Python web framework that encourages rapid development and clean, pragmatic design.',
            'type' => 'framework',
            'level' => 'advanced',
            'user_id' => 1
        ]);
        Competences::create([
            'name' => 'Ruby',
            'description' => 'Ruby is an interpreted, high-level, general-purpose programming language.',
            'type' => 'language',
            'level' => 'advanced',
            'user_id' => 1
        ]);
    }
}
