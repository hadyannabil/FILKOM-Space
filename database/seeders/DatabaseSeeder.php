<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@filkom.ub.ac.id'],
            [
                'name'     => 'Admin FILKOM',
                'role'     => 'admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@student.ub.ac.id'],
            [
                'name'     => 'Budi Santoso',
                'role'     => 'user',
                'password' => Hash::make('password123'),
            ]
        );

        $rooms = [
            ['name' => 'Room F2.4',  'building' => 'F Building', 'floor' => '2nd Floor', 'capacity' => 40,  'type' => 'classroom',    'facilities' => ['Projector','AC','Whiteboard']],
            ['name' => 'Room F3.4',  'building' => 'F Building', 'floor' => '3rd Floor', 'capacity' => 40,  'type' => 'classroom',    'facilities' => ['Projector','AC','Whiteboard']],
            ['name' => 'Room F4.4',  'building' => 'F Building', 'floor' => '4th Floor', 'capacity' => 40,  'type' => 'classroom',    'facilities' => ['Projector','AC','Whiteboard']],
            ['name' => 'Room F2.5',  'building' => 'F Building', 'floor' => '2nd Floor', 'capacity' => 35,  'type' => 'classroom',    'facilities' => ['Projector','AC']],
            ['name' => 'Lab G1.3',   'building' => 'G Building', 'floor' => '1st Floor', 'capacity' => 30,  'type' => 'lab',          'facilities' => ['Komputer','AC','Projector']],
            ['name' => 'Lab G1.6',   'building' => 'G Building', 'floor' => '1st Floor', 'capacity' => 30,  'type' => 'lab',          'facilities' => ['Komputer','AC','Projector']],
            ['name' => 'GKM4.3',     'building' => 'GKM Building','floor'=> '4th Floor', 'capacity' => 80,  'type' => 'meeting_room', 'facilities' => ['Projector','AC','Meja Rapat']],
            ['name' => 'Algorithm Auditorium','building' => 'G Building','floor' => '2nd Floor','capacity' => 150,'type' => 'auditorium','facilities' => ['Sound System','Projector','AC','Podium']],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['name' => $room['name']], $room);
        }
    }
}
