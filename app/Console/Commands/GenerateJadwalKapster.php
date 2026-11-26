<?php

namespace App\Console\Commands;

use App\Models\JadwalKapster;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateJadwalKapster extends Command
{
    protected $signature = 'jadwal:generate';

    protected $description = 'Generate jadwal kapster otomatis';

    public function handle()
    {
        $kapsters = [
            1 => 30, // Andi - 30 menit
            2 => 20, // Budi - 20 menit
            3 => 45, // Rizky - 45 menit
        ];

        $jamMulai = '09:00';
        $jamSelesai = '17:00';

        // Membuat jadwal untuk 30 hari ke depan
        for ($hari = 0; $hari < 30; $hari++) {

            $tanggal = Carbon::today()->addDays($hari)->toDateString();

            foreach ($kapsters as $idKapster => $durasi) {

                $waktu = Carbon::createFromFormat(
                    'H:i',
                    $jamMulai
                );

                $batas = Carbon::createFromFormat(
                    'H:i',
                    $jamSelesai
                );

                while ($waktu->copy()->addMinutes($durasi)->lte($batas)) {

                    $mulai = $waktu->format('H:i:s');

                    $selesai = $waktu
                        ->copy()
                        ->addMinutes($durasi)
                        ->format('H:i:s');

                    JadwalKapster::firstOrCreate(
                        [
                            'id_kapster' => $idKapster,
                            'tanggal' => $tanggal,
                            'jam_mulai' => $mulai,
                            'jam_selesai' => $selesai,
                        ],
                        [
                            'status_slot' => 'tersedia',
                        ]
                    );

                    $waktu->addMinutes($durasi);
                }
            }
        }

        $this->info('Jadwal kapster berhasil dibuat untuk 30 hari ke depan.');

        return Command::SUCCESS;
    }
}