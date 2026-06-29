<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Review;

class ReviewApiController extends Controller
{
    public function index(string $villaId): void
    {
        $reviewModel = new Review();
        $reviews = $reviewModel->getApprovedByVilla((int)$villaId);
        $avg = $reviewModel->getAverageRating((int)$villaId);

        $this->json([
            'success' => true,
            'data' => $reviews,
            'average_rating' => $avg,
            'total' => count($reviews),
        ]);
    }
}
