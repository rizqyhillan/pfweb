<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pet;
use App\Models\User;
use App\Models\Service;
use App\Models\DoctorSchedule;
use App\Models\Transaction;

class DoctorBooking extends Model
{
    protected $table = 'booking_dokter';

    protected $fillable = [
        'id_hewan',
        'id_dokter',
        'id_layanan',
        'id_jadwal',
        'id_transaksi',
        'tanggal_booking',
        'jam_booking',
        'keluhan',
        'catatan_dokter',
        'status',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'jam_booking' => 'datetime:H:i',
        'total_biaya' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function hewan()
    {
        return $this->belongsTo(Pet::class, 'id_hewan');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    public function layanan()
    {
        return $this->belongsTo(Service::class, 'id_layanan');
    }

    public function jadwal()
    {
        return $this->belongsTo(DoctorSchedule::class, 'id_jadwal');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi');
    }

    public function owner()
    {
        return $this->hasOneThrough(
            User::class,
            Pet::class,
            'id',
            'id',
            'id_hewan',
            'id_pemilik'
        );
    }
}