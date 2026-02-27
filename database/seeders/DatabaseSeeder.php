<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(10)->create();
        Tag::factory(10)->create();

        $this->call(RolePermissionSeeder::class);

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Editor
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@mail.com',
            'password' => bcrypt('password'),
        ]);
        $editor->assignRole('editor');

        // Reporter
        $reporter = User::create([
            'name' => 'Reporter',
            'email' => 'reporter@mail.com',
            'password' => bcrypt('password'),
        ]);
        $reporter->assignRole('reporter');
    }
}
