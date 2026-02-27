<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Post Core
            'post.create',
            'post.update',
            'post.update.any',
            'post.delete',
            'post.restore',
            'post.force.delete',
            'post.view',
            // Category
            'category.view',
            'category.create',
            'category.update',
            'category.delete',
            // Tag
            'tag.view',
            'tag.create',
            'tag.update',
            'tag.delete',

            // Workflow
            'post.submit',
            'post.change.status',
            'post.review.editor',
            'post.review.super_admin',
            'post.publish',
            'post.schedule',

            // Other
            'comment.moderate',

            // Dashboard
            'dashboard.view_admin',
            'dashboard.view_editor',
            'dashboard.view_super_admin',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $user = Role::firstOrCreate(['name' => 'user']);
        $reporter = Role::firstOrCreate(['name' => 'reporter']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

        // USER
        $user->syncPermissions([
            'post.create',
            'post.submit',
            'post.view',
            'post.update',
        ]);

        // REPORTER
        $reporter->syncPermissions([
            'post.create',
            'post.update',
            'post.submit',
            'post.view',
        ]);

        // EDITOR
        $editor->syncPermissions([
            'post.view',
            'post.review.editor',
            'post.change.status',
            'dashboard.view_editor',
        ]);

        // ADMIN
        $admin->syncPermissions([
            'post.view',
            'post.publish',
            'post.schedule',
            'post.delete',
            'post.restore',
            'comment.moderate',
            'dashboard.view_admin',
            // Category
            'category.view',
            'category.create',
            'category.update',
            'category.delete',

        // Tag
            'tag.view',
            'tag.create',
            'tag.update',
            'tag.delete',
        ]);

        // SUPER ADMIN
        $superAdmin->syncPermissions([
            'post.view',
            'post.review.super_admin',
            'post.update.any',
            'post.delete',
            'post.restore',
            'post.force.delete',
            'dashboard.view_super_admin',
            'post.change.status',
        ]);
    }
}