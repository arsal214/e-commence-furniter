<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

/**
 * Starter copy for the offer emails.
 *
 * Seeded so the compose screen is useful on first open rather than presenting an
 * empty template list. Matched on name, so re-running the seeder after staff
 * have edited a template does not overwrite their wording.
 */
class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->templates() as $template) {
            EmailTemplate::firstOrCreate(['name' => $template['name']], $template);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function templates(): array
    {
        $shop = url('/shop');

        return [
            [
                'name'       => 'Seasonal sale',
                'sort_order' => 10,
                'subject'    => '{{first_name}}, 20% off everything this week',
                'eyebrow'    => 'Limited Time Offer',
                'heading'    => 'Our seasonal sale is here',
                'body_html'  => <<<'HTML'
<p>For one week only, every piece in our collection is <strong>20% off</strong> — sofas, dining sets, bedroom, the lot.</p>
<p>These are the same pieces we build year round, just at a price we can only hold for a few days. If something has been sitting on your wishlist, this is the moment.</p>
<ul>
<li>Free delivery on orders over $500</li>
<li>30-day returns, no questions asked</li>
<li>Every piece covered by our workmanship guarantee</li>
</ul>
HTML,
                'promo_code' => 'SEASON20',
                'promo_note' => 'Enter at checkout. One use per customer.',
                'cta_label'  => 'Shop the Sale',
                'cta_url'    => $shop,
            ],
            [
                'name'       => 'New arrivals',
                'sort_order' => 20,
                'subject'    => 'Just landed: new pieces we think you\'ll like',
                'eyebrow'    => 'New Arrivals',
                'heading'    => 'Fresh in the showroom',
                'body_html'  => <<<'HTML'
<p>Hi again — we have just added a new run of pieces to the collection, and a few of them felt like your kind of thing.</p>
<p>They are made the same way everything else here is: solid frames, proper joinery, fabrics chosen to still look right in five years.</p>
<p>Have a look while the first run is still in stock.</p>
HTML,
                'cta_label'  => 'See What\'s New',
                'cta_url'    => $shop,
            ],
            [
                'name'       => 'Win-back — we miss you',
                'sort_order' => 30,
                'subject'    => 'A little something to bring you back, {{first_name}}',
                'eyebrow'    => 'Just For You',
                'heading'    => 'It has been a while',
                'body_html'  => <<<'HTML'
<p>We noticed it has been some time since your last order, and we would like to fix that.</p>
<p>Here is <strong>15% off</strong> your next purchase — no minimum spend, and it works on sale items too.</p>
<p>If there was something about your last order that did not sit right, reply to this email and tell us. We would rather hear it than not.</p>
HTML,
                'promo_code' => 'WELCOME15',
                'promo_note' => 'Valid for 30 days. Works on sale items.',
                'cta_label'  => 'Browse the Collection',
                'cta_url'    => $shop,
            ],
            [
                'name'       => 'Abandoned cart nudge',
                'sort_order' => 40,
                'subject'    => 'You left something behind',
                'eyebrow'    => 'Still Available',
                'heading'    => 'Your basket is waiting',
                'body_html'  => <<<'HTML'
<p>You had a few things in your basket last time you visited and did not finish checking out.</p>
<p>They are still available — but stock moves, and we cannot hold them indefinitely. Picking up where you left off takes a minute.</p>
<p>If something stopped you (delivery timing, a size question, anything), just reply and we will sort it.</p>
HTML,
                'cta_label'  => 'Finish Checkout',
                'cta_url'    => url('/cart'),
            ],
            [
                'name'       => 'Flash deal — 48 hours',
                'sort_order' => 50,
                'subject'    => '48 hours only: {{first_name}}, this one is worth opening',
                'eyebrow'    => '48 Hours Only',
                'heading'    => 'Flash deal starts now',
                'body_html'  => <<<'HTML'
<p>This one runs for <strong>48 hours</strong> and then it is gone.</p>
<p>No waiting list, no early access — the code below works from the moment you read this until Thursday midnight.</p>
HTML,
                'promo_code' => 'FLASH48',
                'promo_note' => 'Expires in 48 hours. Cannot be combined with other offers.',
                'cta_label'  => 'Claim the Deal',
                'cta_url'    => $shop,
            ],
        ];
    }
}
