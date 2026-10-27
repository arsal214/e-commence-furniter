<?php

/**
 * Editable site copy: the one place store wording lives.
 *
 * Each key is shown on Admin -> Site Text & SEO, where a value typed there overrides the
 * default below (blank = use this default). Tokens, replaced at render time:
 *   {shipping}  "Free US shipping"  or, when the free-shipping threshold is on, "Free US shipping on $35+"
 *   {delivery}  the delivery window, e.g. "5–7"
 *
 * Long-form pages (policies, FAQ answers, terms) are edited in their Blade files.
 */

$meta = [
    'home' => ['Home page', 'Shop Direct from Independent US Sellers | PeytonGhalib',
        'Shop direct from independent American sellers on PeytonGhalib. {shipping}, Buyer Protection on every order and easy returns.'],
    'shop' => ['Shop (all products)', 'Shop All Products | PeytonGhalib',
        'Browse products from verified US sellers on PeytonGhalib. Filter by category and price. Buyer Protection on every order.'],
    'categories' => ['All categories', 'Shop by Category | PeytonGhalib',
        'Find products by category from independent US sellers: fashion, home and kitchen, beauty, toys, pet supplies, jewelry and more.'],
    'about' => ['About', 'About PeytonGhalib | Gifts & Home Store in NJ',
        'PeytonGhalib is an online gift and home store based in Jersey City, NJ. Learn how we pick products, ship across the US and handle returns.'],
    'contact' => ['Contact', 'Contact Us | PeytonGhalib Customer Support',
        'Questions about an order, delivery or return? Email PeytonGhalib support and we reply within 24 hours, 7 days a week.'],
    'faq' => ['FAQ', 'FAQs: Shipping, Returns & Payments | PeytonGhalib',
        'Answers about PeytonGhalib orders: US shipping times, tracking, 30-day returns, refunds, payment methods and order changes.'],
    'shipping-policy' => ['Shipping policy', 'Shipping Policy: US Delivery & Tracking | PeytonGhalib',
        'How PeytonGhalib ships: processing time, {delivery} business-day delivery, tracking, holiday order dates and help with late or damaged orders.'],
    'return-policy' => ['Return policy', '30-Day Return Policy | PeytonGhalib',
        'Return unused items within 30 days of delivery for a refund or exchange. See what qualifies, how to start a return and refund timing.'],
    'refund-policy' => ['Refund policy', 'Refund Policy | PeytonGhalib',
        'How and when PeytonGhalib issues refunds, including timing for returned, damaged and canceled orders and how refunds reach your card.'],
    'track-order' => ['Track order', 'Track Your Order | PeytonGhalib',
        'Enter your order number or tracking number to see where your PeytonGhalib package is and when it should arrive.'],
];

// Category pages: used when the category has no meta of its own (a category's own meta, set in Admin -> Categories, wins).
$categories = [
    'toys' => ["Kids' Toys, STEM Kits & Games | PeytonGhalib",
        'Shop STEM building kits, puzzles, outdoor play sets and games for kids. Check the age guide on each toy. {shipping} and 30-day returns.'],
    'halloween' => ['Halloween Decorations, Lights & Costumes | PeytonGhalib',
        'Shop Halloween ghost décor, spider webs, solar yard lights, flameless candles, fog machines and costumes for your porch, yard or party.'],
    'gifts' => ['Gift Ideas for Every Occasion | PeytonGhalib',
        'Find thoughtful gifts for birthdays, holidays and housewarmings: keepsakes, accessories and fun finds. {shipping} and 30-day returns.'],
    'home-essentials' => ['Home Décor & Everyday Essentials | PeytonGhalib',
        'Shop vases, candle holders, figurines, wall décor, throw blankets and handy home helpers. {shipping} and easy 30-day returns.'],
    'kitchen-accessories' => ['Kitchen Accessories & Food Storage | PeytonGhalib',
        'Shop kitchen gadgets, food storage containers and handy cooking tools for everyday meals. {shipping} and 30-day returns.'],
    'car-accessories' => ['Car Accessories & Auto Gadgets | PeytonGhalib',
        'Shop car organizers, interior accessories and handy auto gadgets for daily drives and road trips. {shipping} and 30-day returns.'],
    'cushions' => ['Cushions & Throw Pillows | PeytonGhalib',
        'Shop cushions and throw pillows to refresh your sofa, bed or reading nook. {shipping} and easy 30-day returns at PeytonGhalib.'],
    'garden' => ['Garden Décor & Outdoor Accessories | PeytonGhalib',
        'Shop garden décor, outdoor lights and yard accessories for patios, porches and backyards. {shipping} and 30-day returns.'],
    'jewelry' => ['Jewelry & Everyday Accessories | PeytonGhalib',
        'Shop necklaces, bracelets, earrings and everyday jewelry that make easy gifts. {shipping} and 30-day returns at PeytonGhalib.'],
    'lamps' => ['Lamps, Night Lights & Décor Lighting | PeytonGhalib',
        'Shop lamps, night lights and decorative lighting like 3D moving sand art for bedrooms and offices. {shipping} and 30-day returns.'],
    'pet-supplies' => ['Pet Supplies for Dogs & Cats | PeytonGhalib',
        'Shop cat beds, dog beds, grooming brushes, pet hair removers and accessories for dogs and cats. {shipping} and 30-day returns.'],
    'storage-organization' => ['Storage & Home Organization | PeytonGhalib',
        'Shop storage boxes, organizers and space-saving solutions for closets, kitchens and bathrooms. {shipping} and 30-day returns.'],
    'water-bottles' => ['Water Bottles & Tumblers | PeytonGhalib',
        'Shop reusable water bottles and tumblers for the gym, school, office and travel. {shipping} and easy 30-day returns.'],
    // Wait for the matching category to exist (Admin -> Categories); then these apply automatically.
    'clothing-accessories' => ["Women's Clothing, Bags & Accessories | PeytonGhalib",
        "Shop women's coats, tote bags, sun hats and accessories. Size details on every listing, {shipping} and 30-day returns."],
    'electronics' => ['Electronics & Tech Accessories | PeytonGhalib',
        'Shop HDMI switches, action cameras, chargers and handy tech accessories. {shipping} and 30-day returns at PeytonGhalib.'],
];

$copy = [];
foreach ($meta as $key => [$label, $title, $description]) {
    $copy["meta.$key.title"] = ['group' => 'Page titles & descriptions', 'label' => "$label: title", 'default' => $title, 'max' => 70];
    $copy["meta.$key.description"] = ['group' => 'Page titles & descriptions', 'label' => "$label: description", 'default' => $description, 'long' => true, 'max' => 170];
}
foreach ($categories as $slug => [$title, $description]) {
    $copy["meta.category.$slug.title"] = ['group' => 'Category pages (fallback)', 'label' => "/category/$slug: title", 'default' => $title, 'max' => 70];
    $copy["meta.category.$slug.description"] = ['group' => 'Category pages (fallback)', 'label' => "/category/$slug: description", 'default' => $description, 'long' => true, 'max' => 170];
}

$text = fn (string $group, string $label, string $default, bool $long = false) => ['group' => $group, 'label' => $label, 'default' => $default, 'long' => $long, 'max' => 600];

return [
    'brand' => 'PeytonGhalib',

    // Admin -> Site Text & SEO shows one tab per page: slug => [label, URL to preview].
    'tabs' => [
        'site'            => ['Site-wide', '/'],
        'home'            => ['Home', '/'],
        'shop'            => ['Shop', '/shop'],
        'categories'      => ['Categories', '/categories'],
        'about'           => ['About', '/about'],
        'contact'         => ['Contact', '/contact'],
        'faq'             => ['FAQ', '/faq'],
        'shipping-policy' => ['Shipping policy', '/shipping-policy'],
        'return-policy'   => ['Return policy', '/return-policy'],
        'refund-policy'   => ['Refund policy', '/refund-policy'],
        'track-order'     => ['Track order', '/track-order'],
    ],

    'copy' => $copy + [
        'brand.description' => $text('Brand', 'One-sentence description (search results, structured data)',
            'PeytonGhalib is an online gift and home store based in Jersey City, NJ. Shop gifts, toys, home décor, kitchen helpers, pet supplies and seasonal finds, shipped across the US.', true),
        'footer.tagline' => $text('Footer', 'Footer tagline',
            'Your online gift and home store: gifts, toys, home décor, kitchen helpers and pet supplies, curated for quality and delivered across the USA.', true),
        'nav.new_arrivals_desc' => $text('Menu', 'New Arrivals menu blurb', 'Fresh gifts, home finds and seasonal picks, added every week.'),

        'home.h1' => $text('Home page', 'Main heading (H1)', 'Shop direct from independent American sellers'),
        'home.categories_heading' => $text('Home page', 'Category section heading', 'Shop by Category'),
        'home.categories_intro' => $text('Home page', 'Category section intro',
            'Find gifts and everyday finds by category, from toys and home décor to kitchen helpers, pet supplies, jewelry and more.', true),
        'home.new_arrivals_sub' => $text('Home page', 'New arrivals sub-heading', 'New gifts, home finds and seasonal picks added every week.'),
        'home.why_subtitle' => $text('Home page', 'Why-shop-with-us subtitle',
            'We keep buying simple: clear prices, {shipping} across the USA, and a team that helps from the moment you browse to the day your order arrives.', true),
        'home.return_card' => $text('Home page', 'Returns card text',
            'Not quite right? Return it unused in its original packaging within 30 days for a refund or exchange.', true),
        'home.bestsellers_heading' => $text('Home page', 'Best sellers heading', 'Customer Favorites'),
        'home.bestsellers_sub' => $text('Home page', 'Best sellers sub-heading',
            'The products our customers keep coming back for: the ones that sell fastest and get reordered most.', true),
        'home.explore_eyebrow' => $text('Home page', 'Explore banner: small label', "Find something you'll love"),
        'home.explore_heading' => $text('Home page', 'Explore banner: heading', 'Explore Our Range'),
        'home.explore_sub' => $text('Home page', 'Explore banner: text',
            'Gifts, toys, home décor, kitchen helpers and more: something for everyone on your list.', true),

        'shop.description_fallback' => $text('Shop', 'Short shop description (structured data)', 'Browse all gifts, toys, home essentials and more at PeytonGhalib.'),
        'contact.intro' => $text('Contact', 'Contact page intro', 'Have a question about an order, a product, or a return?'),

        'about.hero_title' => $text('About', 'Hero heading', 'Gifts and home finds worth giving.'),
        'about.hero_lead' => $text('About', 'Opening paragraph',
            "PeytonGhalib is a small online shop based in Jersey City, New Jersey. We pick gifts, home finds, toys and seasonal décor we'd be happy to give ourselves, ship them across the US, and answer every email within a day.", true),
        'about.owner_line' => $text('About', 'Owner / company line (shown only when filled in)', '', false),
        'about.story_title' => $text('About', 'Story heading', 'Why we started'),
        'about.story_1' => $text('About', 'Story paragraph 1',
            'PeytonGhalib started with a simple frustration: good gifts and everyday home finds were scattered across a dozen different shops, and hardly any were worth a second visit. So we built one place to find them.', true),
        'about.story_2' => $text('About', 'Story paragraph 2',
            "We stock what we'd happily give or keep ourselves, price it fairly, ship it across the US, and take it back without an argument if it isn't right.", true),
        'about.values_lede' => $text('About', 'Values intro', 'Fair prices, {shipping} across the USA, and a team that stands behind everything we sell.', true),
        'about.return_text' => $text('About', 'Returns value text', 'Return unused items in their original packaging within 30 days for a refund or exchange.', true),
        'about.support_title' => $text('About', 'Support value title', 'Email support'),
        'about.support_text' => $text('About', 'Support value text', 'Email our team any day of the week, and we reply within 24 hours.', true),
        'about.quality_text' => $text('About', 'Quality value text', 'We check each item before it ships and make it right if it does not match its description.', true),
        'about.cta_title' => $text('About', 'Closing heading', 'Ready to find the right gift?'),
        'about.cta_text' => $text('About', 'Closing text', "Browse the categories, or send us a note. We're happy to help you find the right gift.", true),
    ],
];
