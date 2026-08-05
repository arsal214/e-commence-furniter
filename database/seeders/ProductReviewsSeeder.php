<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Every transcribed product review, in one command.
 *
 * Each child is idempotent and skips itself when its product is absent, so this
 * is safe to run repeatedly and safe on a database that only holds some of the
 * catalogue. Add a new product's reviews by listing its seeder here.
 *
 * Run:  php artisan db:seed --class=ProductReviewsSeeder
 */
class ProductReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SandArtReviewSeeder::class,
            ThinkerStatueReviewSeeder::class,
            GreenFlowerLampReviewSeeder::class,
            ChunkyKnitThrowReviewSeeder::class,
            FakeSucculentsReviewSeeder::class,
        ]);
    }
}
