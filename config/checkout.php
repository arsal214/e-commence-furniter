<?php

/**
 * Shipping destinations offered at checkout.
 *
 * Each entry drives the country dropdown, the state/province dropdown, and both
 * halves of ZIP validation (the client-side hint and the server-side rule), so a
 * new destination is added here and nowhere else.
 *
 *   label            Shown in the country dropdown.
 *   subdivision_label What this country calls the second level — the state field
 *                    relabels itself, because "State" over a Canadian form is wrong.
 *   subdivisions     code => name. An empty array hides the field entirely for
 *                    countries that have no meaningful subdivision.
 *   postal_regex     Server-side validation. Anchored, case-insensitive.
 *   postal_example   Placeholder text and the error message's example.
 *   postal_required  Some countries have no postal code at all.
 *
 * NOTE ON SHIPPING: adding a country here makes it selectable at checkout. The
 * shipping-cost table in CheckoutController is flat and country-agnostic, so any
 * destination listed is offered at the same rate. Confirm you actually ship
 * somewhere before adding it — removing a country is a one-line delete.
 */
return [

    'default_country' => 'US',

    // Legacy flow B: silently create an account for every new email at checkout.
    // Off = true guest checkout; the thank-you page offers an optional account.
    'auto_create_account' => (bool) env('CHECKOUT_AUTO_CREATE_ACCOUNT', false),

    // Master switch for the new-customer welcome offer. The offer's own is_active flag
    // (admin screen) is the everyday switch; this one is for emergencies.
    'welcome_offer_enabled' => (bool) env('WELCOME_OFFER_ENABLED', true),

    // The free-shipping welcome ticket only means something once shipping has a fee
    // (Phase 3 threshold). Until then the ticket is hidden and ignored.
    // OFF = free shipping on every order (today's promise). ON = free at/above the threshold,
    // flat rate below it, add-on item rules active. One flag so the switch is one env line.
    'shipping_fee_enabled' => (bool) env('SHIPPING_FEE_ENABLED', false),
    'free_shipping_threshold' => (float) env('SHIPPING_FREE_THRESHOLD', 35),
    'flat_shipping_rate' => (float) env('SHIPPING_FLAT_RATE', 4.99),

    // "N sold" on product cards, from real paid orders in the last 30 days; hidden below the minimum.
    'sold_badge_enabled' => (bool) env('SOLD_BADGE_ENABLED', false),
    'sold_badge_min' => (int) env('SOLD_BADGE_MIN', 10),

    // Domains refused for the welcome claim (throwaway inboxes). Extend as needed.
    'disposable_email_domains' => ['mailinator.com', 'guerrillamail.com', '10minutemail.com', 'tempmail.com', 'yopmail.com', 'trashmail.com', 'sharklasers.com'],

    // Show Apple Pay / Google Pay in copy and icons only after the wallets work
    // (Stripe dashboard + Apple Pay domain verification).
    'apple_pay' => (bool) env('APPLE_PAY_ENABLED', false),

    /*
     * The delivery promise, defined once.
     *
     * Both the product-page estimate and the shipping policy render from these
     * numbers, so the date a customer is shown on a product and the window they
     * read in the policy cannot disagree. Changing the promise is a change here
     * and nowhere else.
     */
    'delivery' => [
        // Business days before it ships (US weekends and federal holidays skipped).
        'processing_days' => env('DELIVERY_PROCESSING_DAYS', 1),
        'min_days' => env('DELIVERY_MIN_DAYS', 5),
        'max_days' => env('DELIVERY_MAX_DAYS', 7),
    ],

    'countries' => [

        'US' => [
            'label'             => 'United States',
            'subdivision_label' => 'State',
            'postal_regex'      => '/^\d{5}(-\d{4})?$/',
            'postal_example'    => '07305',
            'postal_required'   => true,
            'subdivisions'      => [
                'AL' => 'Alabama',            'AK' => 'Alaska',
                'AZ' => 'Arizona',            'AR' => 'Arkansas',
                'CA' => 'California',         'CO' => 'Colorado',
                'CT' => 'Connecticut',        'DE' => 'Delaware',
                'DC' => 'District of Columbia',
                'FL' => 'Florida',            'GA' => 'Georgia',
                'HI' => 'Hawaii',             'ID' => 'Idaho',
                'IL' => 'Illinois',           'IN' => 'Indiana',
                'IA' => 'Iowa',               'KS' => 'Kansas',
                'KY' => 'Kentucky',           'LA' => 'Louisiana',
                'ME' => 'Maine',              'MD' => 'Maryland',
                'MA' => 'Massachusetts',      'MI' => 'Michigan',
                'MN' => 'Minnesota',          'MS' => 'Mississippi',
                'MO' => 'Missouri',           'MT' => 'Montana',
                'NE' => 'Nebraska',           'NV' => 'Nevada',
                'NH' => 'New Hampshire',      'NJ' => 'New Jersey',
                'NM' => 'New Mexico',         'NY' => 'New York',
                'NC' => 'North Carolina',     'ND' => 'North Dakota',
                'OH' => 'Ohio',               'OK' => 'Oklahoma',
                'OR' => 'Oregon',             'PA' => 'Pennsylvania',
                'RI' => 'Rhode Island',       'SC' => 'South Carolina',
                'SD' => 'South Dakota',       'TN' => 'Tennessee',
                'TX' => 'Texas',              'UT' => 'Utah',
                'VT' => 'Vermont',            'VA' => 'Virginia',
                'WA' => 'Washington',         'WV' => 'West Virginia',
                'WI' => 'Wisconsin',          'WY' => 'Wyoming',
                // Territories and military addresses ship through USPS like any
                // other state; omitting them silently blocks those customers.
                'AS' => 'American Samoa',     'GU' => 'Guam',
                'MP' => 'Northern Mariana Islands',
                'PR' => 'Puerto Rico',        'VI' => 'U.S. Virgin Islands',
                'AA' => 'Armed Forces Americas',
                'AE' => 'Armed Forces Europe',
                'AP' => 'Armed Forces Pacific',
            ],
        ],

    ],
];
