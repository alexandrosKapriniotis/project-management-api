<?php

namespace App\Models;

use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory, HasUlids;

    protected $casts = [
        'type' => ProjectType::class,
    ];
    protected $fillable = ['name', 'description', 'type', 'company_id', 'budget', 'timeline'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
