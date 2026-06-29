<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Review extends Model
{
    protected static string $table = 'reviews';
    protected static array $fillable = [
        'villa_id', 'booking_id', 'user_id', 'guest_name', 'guest_email',
        'rating', 'title', 'comment', 'cleanliness_rating', 'location_rating',
        'value_rating', 'service_rating', 'photos', 'admin_reply', 'admin_reply_at',
        'is_verified', 'is_approved', 'is_featured'
    ];

    public function getApprovedByVilla(int $villaId): array
    {
        return $this->rawQuery(
            "SELECT * FROM reviews WHERE villa_id = :id AND is_approved = 1 ORDER BY created_at DESC",
            ['id' => $villaId]
        );
    }

    public function getAverageRating(int $villaId): float
    {
        $result = $this->rawQuery(
            "SELECT AVG(rating) as avg_rating FROM reviews WHERE villa_id = :id AND is_approved = 1",
            ['id' => $villaId]
        );
        return round((float)($result[0]['avg_rating'] ?? 0), 1);
    }

    public function getPending(): array
    {
        return $this->rawQuery(
            "SELECT r.*, v.name as villa_name FROM reviews r JOIN villas v ON r.villa_id = v.id WHERE r.is_approved = 0 ORDER BY r.created_at DESC"
        );
    }

    public function getFeatured(int $limit = 5): array
    {
        return $this->rawQuery(
            "SELECT r.*, v.name as villa_name FROM reviews r JOIN villas v ON r.villa_id = v.id WHERE r.is_approved = 1 AND r.is_featured = 1 ORDER BY r.created_at DESC LIMIT {$limit}"
        );
    }
}
