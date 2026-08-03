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

    /*
     * The delivery promise, defined once.
     *
     * Both the product-page estimate and the shipping policy render from these
     * numbers, so the date a customer is shown on a product and the window they
     * read in the policy cannot disagree. Changing the promise is a change here
     * and nowhere else.
     */
    'delivery' => [
        'min_days' => env('DELIVERY_MIN_DAYS', 3),
        'max_days' => env('DELIVERY_MAX_DAYS', 5),
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

        'CA' => [
            'label'             => 'Canada',
            'subdivision_label' => 'Province / Territory',
            // Canadian postal codes exclude D, F, I, O, Q and U, and never start
            // with W or Z. A loose /[A-Z]\d[A-Z]/ would accept codes that cannot
            // exist and fail at the carrier instead of at checkout.
            'postal_regex'      => '/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] ?\d[ABCEGHJ-NPRSTV-Z]\d$/i',
            'postal_example'    => 'K1A 0B1',
            'postal_required'   => true,
            'subdivisions'      => [
                'AB' => 'Alberta',                    'BC' => 'British Columbia',
                'MB' => 'Manitoba',                   'NB' => 'New Brunswick',
                'NL' => 'Newfoundland and Labrador',  'NS' => 'Nova Scotia',
                'NT' => 'Northwest Territories',      'NU' => 'Nunavut',
                'ON' => 'Ontario',                    'PE' => 'Prince Edward Island',
                'QC' => 'Quebec',                     'SK' => 'Saskatchewan',
                'YT' => 'Yukon',
            ],
        ],

    ],
];
