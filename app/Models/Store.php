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
        'geo_coordinates',
        'is_open',
        'store_type',
        'max_delivery_distance',
        'user_id',
    ];

    final const MILES_RAD = 3959;

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

        $minLat = max(-90, $latitude - $radius * $degreesPerMileLat);
        $maxLat = min(90, $latitude + $radius * $degreesPerMileLat);
        $minLong = max(-180, $longitude - $radius * $degreesPerMileLon / cos(deg2rad($latitude)));
        $maxLong = min(180, $longitude + $radius * $degreesPerMileLon / cos(deg2rad($latitude)));

        if ($canDeliver) {
            return $query->select('stores.*')
                ->selectRaw("(? * acos(cos(radians(?)) *
                    cos(radians(CAST(SUBSTRING_INDEX(geo_coordinates, ',', 1) AS DECIMAL(10,6)))) *
                    cos(radians(CAST(SUBSTRING_INDEX(geo_coordinates, ',', -1) AS DECIMAL(10,6))) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(CAST(SUBSTRING_INDEX(geo_coordinates, ',', 1) AS DECIMAL(10,6))))))
                    AS distance", [
                        self::MILES_RAD,
                        $latitude,
                        $longitude,
                        $latitude,
                ])
                ->havingRaw("distance <= max_delivery_distance")
                ->where('is_open', 1)
                ->get();
        }

        return $query->whereRaw("CAST(SUBSTRING_INDEX(geo_coordinates, ',', 1) AS DECIMAL(10,6))
                BETWEEN ? AND ?", [$minLat, $maxLat])
            ->whereRaw("CAST(SUBSTRING_INDEX(geo_coordinates, ',', -1) AS DECIMAL(10,6))
                BETWEEN ? AND ?", [$minLong, $maxLong])
            ->get();
    }
}
