<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use CrudTrait;
    use HasFactory;

    const STATUS_PENDING = 'pending'; // На проверке
    const STATUS_ACTIVE = 'active'; // Активные
    const STATUS_INACTIVE = 'inactive'; // Неактивные
    const STATUS_REJECTED = 'rejected'; // Отклоненные

    protected $fillable = [
        'title',
        'category_id',
        'description',
        'photo',
        'price',
        'contact_phone',
        'contact_email',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
