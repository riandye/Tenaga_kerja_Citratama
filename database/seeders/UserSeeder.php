<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'masuk',
                'email' => 'masuk@example.com',
                'password' => Hash::make('password'), // Hash password
                'role' => 'pelamar_kerja',
                'info' => json_encode([
                    'summary' => 'Admin user summary',
                    'location' => 'Admin location',
                    'address' => 'Admin address',
                    'birth_date' => '2000-01-01',
                    'interested_field' => 'Administration',
                    'phone_number' => '1234567890',
                    'education' => [
                        [
                            'degree' => 'Admin Degree',
                            'institution' => 'Admin Institution',
                            'year' => '2020'
                        ]
                    ],
                    'career' => [
                        [
                            'position' => 'Admin Position',
                            'company' => 'Admin Company',
                            'year' => '2021'
                        ]
                    ],
                    'certifications' => [
                        [
                            'title' => 'Admin Certification',
                            'institution' => 'Admin Institution',
                            'year' => '2022'
                        ]
                    ],
                    'skills' => ['Administration', 'Management'],
                    'languages' => ['English']
                ])
                ]);
        // {
        //     User::create([
        //         'name' => 'Admin User',
        //         'email' => 'admin@example.com',
        //         'password' => Hash::make('password'), // Hash password
        //         'role' => 'admin',
        //         'info' => json_encode([
        //             'summary' => 'Admin user summary',
        //             'location' => 'Admin location',
        //             'address' => 'Admin address',
        //             'birth_date' => '2000-01-01',
        //             'interested_field' => 'Administration',
        //             'phone_number' => '1234567890',
        //             'education' => [
        //                 [
        //                     'degree' => 'Admin Degree',
        //                     'institution' => 'Admin Institution',
        //                     'year' => '2020'
        //                 ]
        //             ],
        //             'career' => [
        //                 [
        //                     'position' => 'Admin Position',
        //                     'company' => 'Admin Company',
        //                     'year' => '2021'
        //                 ]
        //             ],
        //             'certifications' => [
        //                 [
        //                     'title' => 'Admin Certification',
        //                     'institution' => 'Admin Institution',
        //                     'year' => '2022'
        //                 ]
        //             ],
        //             'skills' => ['Administration', 'Management'],
        //             'languages' => ['English']
        //         ])
        //     ]);
    
        //     User::create([
        //         'name' => 'Pelamar Kerja',
        //         'email' => 'pelamar@example.com',
        //         'password' => Hash::make('password'), // Hash password
        //         'role' => 'pelamar_kerja',
        //         'info' => json_encode([
        //             'summary' => 'Pelamar kerja summary',
        //             'location' => 'Pelamar kerja location',
        //             'address' => 'Pelamar kerja address',
        //             'birth_date' => '2002-03-29',
        //             'interested_field' => 'Teknik Perangkat Lunak',
        //             'phone_number' => '082389652694',
        //             'education' => [
        //                 [
        //                     'degree' => 'S1 Teknik Informatika',
        //                     'institution' => 'Universitas X',
        //                     'year' => '2023'
        //                 ]
        //             ],
        //             'career' => [
        //                 [
        //                     'position' => 'Programmer',
        //                     'company' => 'PT Y',
        //                     'year' => '2024'
        //                 ]
        //             ],
        //             'certifications' => [
        //                 [
        //                     'title' => 'Certified Java Developer',
        //                     'institution' => 'Oracle',
        //                     'year' => '2023'
        //                 ]
        //             ],
        //             'skills' => ['Java', 'Laravel', 'React'],
        //             'languages' => ['English', 'Indonesian']
        //         ])
        //     ]);
    
        //     User::create([
        //         'name' => 'Perusahaan Mitra',
        //         'email' => 'perusahaan@example.com',
        //         'password' => Hash::make('password'), // Hash password
        //         'role' => 'perusahaan_mitra',
        //         'info' => json_encode([
        //             'summary' => 'Perusahaan mitra summary',
        //             'location' => 'Perusahaan mitra location',
        //             'address' => 'Perusahaan mitra address',
        //             'birth_date' => '1990-01-01',
        //             'interested_field' => 'Partnership',
        //             'phone_number' => '0987654321',
        //             'education' => [
        //                 [
        //                     'degree' => 'Business Degree',
        //                     'institution' => 'Business School',
        //                     'year' => '2010'
        //                 ]
        //             ],
        //             'career' => [
        //                 [
        //                     'position' => 'CEO',
        //                     'company' => 'Mitra Company',
        //                     'year' => '2015'
        //                 ]
        //             ],
        //             'certifications' => [
        //                 [
        //                     'title' => 'Certified Business Leader',
        //                     'institution' => 'Business Institution',
        //                     'year' => '2018'
        //                 ]
        //             ],
        //             'skills' => ['Leadership', 'Management'],
        //             'languages' => ['English', 'Spanish']
        //         ])
        //     ]);
        // }
    }
}
