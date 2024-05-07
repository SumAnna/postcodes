<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lat',
        'long',
        'is_open',
        'store_type',
        'max_delivery_distance',
        'user_id',
    ];

    /**
     * Scope a query to include stores within a specific distance in miles.
     *
     * @param  Builder   $query
     * @param  float     $latitude
     * @param  float     $longitude
     * @param  bool      $canDeliver
     * @param  float|int $radius
     *
     * @return Collection
     */
    public static function scopeNearCoordinates(
        Builder $query,
        float $latitude,
        float $longitude,
        bool $canDeliver,
        float|int $radius = 1
    ): Collection {
        $degreesPerMileLat = 1 / 69.0;
        $degreesPerMileLon = 1 / (cos(deg2rad($latitude)) * 69.0);

        if ($canDeliver) {
            return $query->select('stores.*')
                ->where('is_open', 1)
                ->whereRaw("? BETWEEN 
                    GREATEST(-90, `lat` - `max_delivery_distance` * ?) AND
                    LEAST(90, `lat` + `max_delivery_distance` * ?)", [
                        $latitude,
                        $degreesPerMileLat,
                        $degreesPerMileLat,
                ])
                ->whereRaw("? BETWEEN
                    GREATEST(-180, `long` - `max_delivery_distance` * ?) AND
                    LEAST(180, `long` + `max_delivery_distance` * ?)", [
                        $longitude,
                        $degreesPerMileLon,
                        $degreesPerMileLon
                ])
                ->get();
        }

        $minLat = max(-90, $latitude - $radius * $degreesPerMileLat);
        $maxLat = min(90, $latitude + $radius * $degreesPerMileLat);
        $minLong = max(-180, $longitude - $radius * $degreesPerMileLon);
        $maxLong = min(180, $longitude + $radius * $degreesPerMileLon);

        return $query->whereRaw("`lat`
                BETWEEN ? AND ?", [$minLat, $maxLat])
            ->whereRaw("`long`
                BETWEEN ? AND ?", [$minLong, $maxLong])
            ->get();
    }
}
