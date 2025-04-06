<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{

    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $resources = [
            'student',
            'user',
            'course',
            'session',
            'branch',
            'centre',
            'category',
            'exam',
            'result',
            'admin',
            'attendance',
            'form',
            'form-responses',
            'sms-template',
        ];
        $actions = [
            'create',
            'read',
            'update',
            'delete',
            'status'
        ];
        $specialStudentActions = [
            'shortlist',
            'admit',
            'bulk-sms',
            'bulk-email'
        ];
        $specialPermissions = [
            'monitor',
            'config',
            'page-editor',
            'manager',
            'permission'
        ];

        $roles = [
            'admission-officer',
            'notification-officer',
            'administrator',
            'app-administrator',
            'page-builder',
            'super-admin'
        ];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $name = "$resource.$action";
                Permission::findOrCreate(
                    $name,
                    "admin"
                );
            }
        }

        foreach ($specialStudentActions as $action) {
            $name = "student.$action";
            Permission::findOrCreate(
                $name,
                "admin"
            );
        }

        foreach ($specialPermissions as $action) {
            $name = "manage.$action";
            Permission::findOrCreate(
                $name,
                "admin"
            );
        }

        Role::findOrCreate(
            "student",
            "web"
        );

        foreach ($roles as $role) {
            Role::findOrCreate(
                $role,
                "admin"
            );
        }

        // ADMISSION OFFICER ROLE
        $admissionOfficerRole = Role::findByName('admission-officer', 'admin');
        // give permission roles
        $admissionOfficerPermissions = $this->findResourcePermissions(['student'], ['shortlist', 'admit']);
        $admissionOfficerRole->syncPermissions($admissionOfficerPermissions);



        // NOTIFICATION OFFICER ROLE
        $notificationOfficerRole = Role::findByName('notification-officer', 'admin');
        // give permissions
        $notificationOfficerPermissions = $this->findResourcePermissions([
            'sms-template'
        ], ['read', 'update', 'create', 'delete']);
        $specialPermissions = $this->findResourcePermissions(['student'], ['bulk-sms', 'bulk-email']);
        $notificationOfficerPermissions->append($specialPermissions->all());
        $notificationOfficerRole->syncPermissions($notificationOfficerPermissions);

        // ADMINISTRATOR ROLE
        $administratorRole = Role::findByName('administrator', 'admin');
        // give permissions
        $administratorPermissions = $this->findResourcePermissions([
            'student',
            'course',
            'session',
            'branch',
            'centre',
            'category',
            'exam',
            'result',
            'admin',
            'attendance',
            'form',
            'form-responses',
            'sms-template',
        ], array_merge($actions, $specialStudentActions));
        $administratorRole->syncPermissions($administratorPermissions);

        // APP ADMINISTRATOR ROLE
        $appAdministratorRole = Role::findByName('app-administrator', 'admin');
        // give permissions
        $appAdministratorPermissions = $this->findResourcePermissions(['manage'], [
            'monitor',
            'page-editor',
            'manager',
        ]);
        $appAdministratorRole->syncPermissions($appAdministratorPermissions);


        // PAGE BUILDER ROLE
        $pageBuilderRole = Role::findByName('page-builder', 'admin');
        // give permissions
        $pageBuilderPermissions = $this->findResourcePermissions(['manage'], ['page-editor']);
        $pageBuilderRole->syncPermissions($pageBuilderPermissions);



        // SUPER ADMIN ROLE
        $superAdminRole = Role::findByName('super-admin', 'admin');
        // give permissions
        $superAdministratorPermissions = Permission::all();
        $superAdminRole->syncPermissions($superAdministratorPermissions);
    }


    private function findResourcePermissions($resources, $actions)
    {
        $names = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $name = "$resource.$action";
                $names[] = $name;
            }
        }
        return Permission::whereIn('name', $names)->get();
    }
}
