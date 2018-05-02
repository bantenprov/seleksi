<?php

namespace Bantenprov\Seleksi\Models\Bantenprov\Seleksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seleksi extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'seleksis';
    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'pendaftaran_id',
        'user_id',
        'nomor_un',
        'nilai_id'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo('Bantenprov\Pendaftaran\Models\Bantenprov\Pendaftaran\Pendaftaran','pendaftaran_id');
    }

    public function siswa()
    {
        return $this->belongsTo('Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa','nomor_un','nomor_un');
    }

    public function nilai()
    {
        return $this->belongsTo('Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai','nilai_id','nomor_un');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
