<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Villa;
use App\Models\Review;

class HomeController extends Controller
{
    private Villa $villaModel;
    private Review $reviewModel;

    public function __construct()
    {
        $this->villaModel = new Villa();
        $this->reviewModel = new Review();
    }

    public function index(): void
    {
        $villas = $this->villaModel->getFeatured();
        $testimonials = $this->reviewModel->getFeatured(5);

        $data = [
            'pageTitle' => 'Luxury Villa Rental in Zanzibar - Juney Villa Limited',
            'metaDescription' => 'Experience luxury villa rental in Zanzibar, Tanzania. Private pools, ocean views, and world-class service.',
            'villas' => $villas,
            'testimonials' => $testimonials,
        ];

        $this->renderWithLayout('public.home.index', $data, 'layouts.public');
    }

    public function about(): void
    {
        $data = [
            'pageTitle' => 'About Us - Juney Villa Limited',
            'metaDescription' => 'Learn about Juney Villa Limited, premier luxury villa rental company in Zanzibar, Tanzania.',
        ];
        $this->renderWithLayout('public.about', $data, 'layouts.public');
    }

    public function contact(): void
    {
        $data = [
            'pageTitle' => 'Contact Us - Juney Villa Limited',
            'metaDescription' => 'Get in touch with Juney Villa Limited for bookings and inquiries.',
        ];
        $this->renderWithLayout('public.contact', $data, 'layouts.public');
    }

    public function sendContact(): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['error' => 'Invalid token'], 419);
        }

        $data = $this->getAllInput();
        $errors = $this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'subject' => 'required|min:3',
            'message' => 'required|min:10',
        ]);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $_SESSION['old_input'] = $data;
            $this->back();
            return;
        }

        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? '', $data['subject'], $data['message'], $_SERVER['REMOTE_ADDR'] ?? '']);

            $this->setFlash('success', 'Thank you! Your message has been sent successfully.');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Sorry, there was an error sending your message. Please try again.');
        }

        $this->back();
    }

    public function subscribe(): void
    {
        $email = $this->getInput('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Please enter a valid email address.']);
            return;
        }

        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT IGNORE INTO newsletters (email, subscribed_at, token) VALUES (?, NOW(), ?)");
            $stmt->execute([$email, bin2hex(random_bytes(16))]);
            $this->json(['success' => true, 'message' => 'Thank you for subscribing!']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Subscription failed. Please try again.']);
        }
    }
}
