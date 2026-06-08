<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Create dummy customer accounts if they don't already exist
        $reviewers = [
            ['name' => 'Sarah Mitchell',  'email' => 'sarah.m@example.com'],
            ['name' => 'James Thornton',  'email' => 'james.t@example.com'],
            ['name' => 'Emily Rodriguez', 'email' => 'emily.r@example.com'],
            ['name' => 'David Chen',      'email' => 'david.c@example.com'],
            ['name' => 'Laura Williams',  'email' => 'laura.w@example.com'],
            ['name' => 'Michael Foster',  'email' => 'michael.f@example.com'],
            ['name' => 'Priya Nair',      'email' => 'priya.n@example.com'],
            ['name' => 'Tom Harrington',  'email' => 'tom.h@example.com'],
        ];

        $users = [];
        foreach ($reviewers as $r) {
            $users[] = User::firstOrCreate(
                ['email' => $r['email']],
                ['name' => $r['name'], 'password' => Hash::make('password'), 'role' => 'customer']
            );
        }

        $reviewPool = [
            // 5-star
            [5, "Absolutely love this piece — exceeded my expectations in every way. The quality is outstanding and it looks even better in person than in the photos. Delivery was fast and the packaging was excellent."],
            [5, "This is the best furniture purchase I've made in years. It fits perfectly in my living room and the build quality is exceptional. Highly recommend to anyone looking for premium furniture."],
            [5, "Stunning design and incredibly solid construction. I was worried about ordering furniture online but this arrived in perfect condition. The color is exactly as shown and the material feels luxurious."],
            [5, "Wow, I'm completely blown away. This piece has completely transformed my space. Worth every penny — premium quality that you can feel the moment you unbox it."],
            [5, "Perfect in every way. Beautiful craftsmanship, sturdy frame, and incredibly comfortable. My guests always compliment it. I couldn't be happier with this purchase."],
            // 4-star
            [4, "Really happy with this purchase overall. The quality is great and it looks fantastic in my home. Assembly was straightforward and only took about 30 minutes. Minor marks on delivery but customer service resolved it quickly."],
            [4, "Great product for the price. The design is elegant and it fits perfectly in my space. Only reason for 4 stars is that it took a few extra days to arrive, but the product itself is excellent."],
            [4, "Very pleased with this. Solid build, beautiful finish, and comfortable to use. Would give 5 stars but the instruction manual could be clearer. Overall a great buy."],
            [4, "Lovely piece, well made and stylish. The color is a touch darker than the photos suggest but still works really well. Very happy with the quality and would buy from PeytonGhalib again."],
            // 5-star (more)
            [5, "Incredible quality! This has completely elevated the look of my home. The attention to detail in the craftsmanship is remarkable. Fast delivery and secure packaging. 10/10."],
            [5, "I was looking for something special and this delivered. Premium feel, beautiful design, and exactly what I needed. My whole family loves it. Will definitely be ordering more."],
            [5, "Best purchase I've made this year. The quality speaks for itself — solid, beautiful, and built to last. Arrived on time and was exactly as described. Highly recommend PeytonGhalib!"],
        ];

        $products = Product::where('is_active', true)->get();

        foreach ($products as $product) {
            // Pick 5–8 unique reviews per product
            $count   = rand(5, 8);
            $indices = array_rand($reviewPool, min($count, count($reviewPool)));
            if (!is_array($indices)) $indices = [$indices];

            foreach ($indices as $idx) {
                [$rating, $comment] = $reviewPool[$idx];
                $user = $users[array_rand($users)];

                // Skip if this user already reviewed this product
                $exists = Review::where('product_id', $product->id)
                                ->where('user_id', $user->id)
                                ->exists();
                if ($exists) continue;

                Review::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'rating'     => $rating,
                    'comment'    => $comment,
                ]);
            }
        }
    }
}
