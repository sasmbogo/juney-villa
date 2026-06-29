<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Villa extends Model
{
    protected static string $table = 'villas';
    protected static array $fillable = [
        'name', 'slug', 'tagline', 'description', 'short_description', 'villa_type',
        'bedrooms', 'bathrooms', 'max_guests', 'size_sqm', 'floor_plan', 'virtual_tour_url',
        'video_url', 'featured_image', 'base_price', 'weekend_price', 'weekly_discount',
        'monthly_discount', 'cleaning_fee', 'security_deposit', 'extra_guest_fee',
        'check_in_time', 'check_out_time', 'min_nights', 'max_nights', 'address',
        'latitude', 'longitude', 'google_map_embed', 'rules', 'cancellation_policy',
        'nearby_attractions', 'status', 'is_featured', 'sort_order', 'meta_title',
        'meta_description', 'meta_keywords'
    ];

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function getActive(): array
    {
        return $this->where(['status' => 'active'], 'sort_order', 'ASC');
    }

    public function getFeatured(): array
    {
        return $this->rawQuery(
            "SELECT * FROM villas WHERE status = 'active' AND is_featured = 1 ORDER BY sort_order ASC"
        );
    }

    public function getWithDetails(int $id): ?array
    {
        $villa = $this->find($id);
        if (!$villa) {
            return null;
        }
        $villa['images'] = $this->getImages($id);
        $villa['amenities'] = $this->getAmenities($id);
        $villa['videos'] = $this->getVideos($id);
        return $villa;
    }

    public function getBySlugWithDetails(string $slug): ?array
    {
        $villa = $this->findBySlug($slug);
        if (!$villa) {
            return null;
        }
        $villa['images'] = $this->getImages((int)$villa['id']);
        $villa['amenities'] = $this->getAmenities((int)$villa['id']);
        $villa['videos'] = $this->getVideos((int)$villa['id']);
        $villa['reviews'] = $this->getReviews((int)$villa['id']);
        $this->incrementViewCount((int)$villa['id']);
        return $villa;
    }

    public function getImages(int $villaId): array
    {
        return $this->rawQuery(
            "SELECT * FROM villa_images WHERE villa_id = :id ORDER BY sort_order ASC",
            ['id' => $villaId]
        );
    }

    public function getAmenities(int $villaId): array
    {
        return $this->rawQuery(
            "SELECT a.* FROM amenities a JOIN villa_amenities va ON a.id = va.amenity_id WHERE va.villa_id = :id ORDER BY a.sort_order",
            ['id' => $villaId]
        );
    }

    public function getVideos(int $villaId): array
    {
        return $this->rawQuery(
            "SELECT * FROM villa_videos WHERE villa_id = :id ORDER BY sort_order",
            ['id' => $villaId]
        );
    }

    public function getReviews(int $villaId, int $limit = 10): array
    {
        return $this->rawQuery(
            "SELECT * FROM reviews WHERE villa_id = :id AND is_approved = 1 ORDER BY created_at DESC LIMIT {$limit}",
            ['id' => $villaId]
        );
    }

    public function checkAvailability(int $villaId, string $checkIn, string $checkOut): bool
    {
        $result = $this->rawQuery(
            "SELECT COUNT(*) as count FROM bookings WHERE villa_id = :villa_id AND status NOT IN ('cancelled','refunded') AND ((check_in <= :check_in AND check_out > :check_in) OR (check_in < :check_out AND check_out >= :check_out) OR (check_in >= :check_in2 AND check_out <= :check_out2))",
            ['villa_id' => $villaId, 'check_in' => $checkIn, 'check_out' => $checkOut, 'check_in2' => $checkIn, 'check_out2' => $checkOut]
        );
        return ($result[0]['count'] ?? 0) === 0;
    }

    public function getPriceForDates(int $villaId, string $checkIn, string $checkOut): float
    {
        $villa = $this->find($villaId);
        if (!$villa) {
            return 0;
        }

        $start = new \DateTime($checkIn);
        $end = new \DateTime($checkOut);
        $totalPrice = 0;

        while ($start < $end) {
            $date = $start->format('Y-m-d');
            $dayOfWeek = (int)$start->format('N');

            // Check for special pricing
            $specialPrice = $this->rawQuery(
                "SELECT price FROM villa_pricing WHERE villa_id = :id AND :date BETWEEN start_date AND end_date AND is_active = 1 ORDER BY price_type LIMIT 1",
                ['id' => $villaId, 'date' => $date]
            );

            if (!empty($specialPrice)) {
                $totalPrice += (float)$specialPrice[0]['price'];
            } elseif ($dayOfWeek >= 6 && $villa['weekend_price']) {
                $totalPrice += (float)$villa['weekend_price'];
            } else {
                $totalPrice += (float)$villa['base_price'];
            }

            $start->modify('+1 day');
        }

        return $totalPrice;
    }

    private function incrementViewCount(int $villaId): void
    {
        $this->rawExecute("UPDATE villas SET view_count = view_count + 1 WHERE id = :id", ['id' => $villaId]);
    }
}
