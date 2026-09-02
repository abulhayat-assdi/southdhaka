<?php
/**
 * Default content seed for first theme activation.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Insert or update a WordPress post with meta values.
 */
function south_city_upsert_seed_post(string $post_type, string $title, string $slug, array $meta = [], string $content = ''): int
{
    $existing = get_page_by_path($slug, OBJECT, $post_type);

    $post_data = [
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => $post_type,
    ];

    if ($existing instanceof WP_Post) {
        $post_data['ID'] = $existing->ID;
        $post_id = wp_update_post(wp_slash($post_data), true);
    } else {
        $post_id = wp_insert_post(wp_slash($post_data), true);
    }

    if (is_wp_error($post_id)) {
        return 0;
    }

    foreach ($meta as $key => $value) {
        update_post_meta((int) $post_id, $key, $value);
    }

    return (int) $post_id;
}

/**
 * Seed the converted Astro/Sanity content into WordPress once.
 */
function south_city_seed_default_content(): void
{
    if (get_option('south_city_default_content_seeded')) {
        return;
    }

    $home = get_page_by_path('home', OBJECT, 'page');

    if (! $home instanceof WP_Post) {
        $home_id = wp_insert_post([
            'post_title'   => 'Home',
            'post_name'    => 'home',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);
    } else {
        $home_id = $home->ID;
    }

    if ($home_id && ! is_wp_error($home_id)) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $home_id);

        $home_meta = [
            'hero_headline_en' => 'Where Your Dreams Find Their Address',
            'hero_headline_bn' => 'যেখানে আপনার স্বপ্নেরা তার ঠিকানা খুঁজে পায়',
            'hero_subline_en' => 'A ~600-bigha planned township in Sayedpur Union - beside the Eastern Bypass, 5 minutes from the Dhaka-Mawa Expressway.',
            'hero_subline_bn' => 'সৈয়দপুর ইউনিয়নে প্রায় ৬০০ বিঘার পরিকল্পিত টাউনশিপ - ইস্টার্ন বাইপাস সংলগ্ন, ঢাকা-মাওয়া এক্সপ্রেসওয়ে থেকে ৫ মিনিটের দূরত্বে।',
            'hero_chips' => [
                ['text_en' => 'Prime Location', 'text_bn' => 'প্রাইম লোকেশন'],
                ['text_en' => 'Legal Security', 'text_bn' => 'আইনি নিরাপত্তা'],
                ['text_en' => 'High Growth', 'text_bn' => 'উচ্চ প্রবৃদ্ধি'],
                ['text_en' => 'Family Focused', 'text_bn' => 'পরিবারবান্ধব'],
                ['text_en' => 'Premium Amenities', 'text_bn' => 'প্রিমিয়াম সুবিধা'],
            ],
            'overview_paragraph_en' => 'South City is a ~600-bigha planned residential and commercial land development on the banks of the Dhaleshwari river in Sayedpur Union, South Keraniganj - beside the Eastern Bypass and 5 minutes from the Dhaka-Mawa Expressway. Four thoughtfully laid-out sectors, 25-60 ft roads, and everyday facilities like schools, a central mosque and a health centre are growing into a complete township, while installment plans of up to 5 years keep ownership within reach.',
            'overview_paragraph_bn' => 'সাউথ সিটি - দক্ষিণ কেরানীগঞ্জের সৈয়দপুর ইউনিয়নে, ধলেশ্বরী নদীর তীরে প্রায় ৬০০ বিঘার একটি পরিকল্পিত আবাসিক ও বাণিজ্যিক ল্যান্ড ডেভেলপমেন্ট প্রকল্প - ইস্টার্ন বাইপাস সংলগ্ন, ঢাকা-মাওয়া এক্সপ্রেসওয়ে থেকে ৫ মিনিটের দূরত্বে। চারটি সুপরিকল্পিত সেক্টর, ২৫-৬০ ফুট প্রশস্ত রাস্তা, আর স্কুল, কেন্দ্রীয় মসজিদ ও হেলথ সেন্টারসহ দৈনন্দিন সব সুবিধা নিয়ে গড়ে উঠছে একটি পূর্ণাঙ্গ টাউনশিপ; সঙ্গে রয়েছে ৫ বছর পর্যন্ত সহজ কিস্তির সুবিধা।',
            'overview_counters' => [
                ['end' => 600, 'display_en' => '600', 'display_bn' => '৬০০', 'label_en' => 'Bigha planned township', 'label_bn' => 'বিঘা পরিকল্পিত প্রকল্প'],
                ['end' => 4, 'display_en' => '4', 'display_bn' => '৪', 'label_en' => 'Residential & commercial sectors', 'label_bn' => 'আবাসিক ও বাণিজ্যিক সেক্টর'],
                ['end' => '', 'display_en' => '3-40', 'display_bn' => '৩-৪০', 'label_en' => 'Katha plot sizes', 'label_bn' => 'কাঠা প্লট সাইজ'],
                ['end' => 5, 'display_en' => '5', 'display_bn' => '৫', 'label_en' => 'Years of easy installments', 'label_bn' => 'বছর পর্যন্ত সহজ কিস্তি'],
            ],
            'location_distances' => [
                ['place_en' => 'Dhaka-Mawa Expressway', 'place_bn' => 'ঢাকা-মাওয়া এক্সপ্রেসওয়ে', 'value_en' => '5 min drive', 'value_bn' => '৫ মিনিটের দূরত্বে'],
                ['place_en' => 'Eastern Bypass', 'place_bn' => 'ইস্টার্ন বাইপাস', 'value_en' => 'Adjacent', 'value_bn' => 'সংলগ্ন'],
                ['place_en' => 'Keraniganj', 'place_bn' => 'কেরানীগঞ্জ', 'value_en' => '6 km', 'value_bn' => '৬ কিমি'],
                ['place_en' => 'Padma Bridge', 'place_bn' => 'পদ্মা সেতু', 'value_en' => '12 km', 'value_bn' => '১২ কিমি'],
                ['place_en' => 'Dhaka city', 'place_bn' => 'ঢাকা শহর', 'value_en' => '22 km', 'value_bn' => '২২ কিমি'],
                ['place_en' => 'Hazrat Shahjalal Airport', 'place_bn' => 'হযরত শাহজালাল বিমানবন্দর', 'value_en' => '30-35 min', 'value_bn' => '৩০-৩৫ মিনিট'],
            ],
            'project_boundaries' => [
                ['side_en' => 'West', 'side_bn' => 'পশ্চিমে', 'value_en' => 'KC Road', 'value_bn' => 'কেসি রোড'],
                ['side_en' => 'East', 'side_bn' => 'পূর্বে', 'value_en' => 'Dhaleshwari River & embankment road', 'value_bn' => 'ধলেশ্বরী নদী ও বাঁধ সড়ক'],
                ['side_en' => 'North', 'side_bn' => 'উত্তরে', 'value_en' => 'Sayedpur Para', 'value_bn' => 'সায়েদপুর পাড়া'],
                ['side_en' => 'South', 'side_bn' => 'দক্ষিণে', 'value_en' => 'Nimtali', 'value_bn' => 'নিমতলী'],
            ],
            'master_plan_hotspots' => [
                ['hotspot_id' => 'sector-a', 'x' => 28.5, 'y' => 15.5, 'name_en' => 'Sector A', 'name_bn' => 'সেক্টর এ', 'desc_en' => 'North-west residential sector, on 25 ft internal roads and the 40 ft collector road.', 'desc_bn' => 'উত্তর-পশ্চিমের আবাসিক সেক্টর - ২৫ ফুট অভ্যন্তরীণ রাস্তা ও ৪০ ফুট কালেক্টর রোডের ওপর।'],
                ['hotspot_id' => 'sector-b', 'x' => 68, 'y' => 15, 'name_en' => 'Sector B', 'name_bn' => 'সেক্টর বি', 'desc_en' => 'North-east residential sector, beside the central mosque and the health centre.', 'desc_bn' => 'উত্তর-পূর্বের আবাসিক সেক্টর - কেন্দ্রীয় মসজিদ ও হেলথ সেন্টারের পাশে।'],
                ['hotspot_id' => 'school', 'x' => 20, 'y' => 34.5, 'name_en' => 'School Zone', 'name_bn' => 'স্কুল জোন', 'desc_en' => 'Dedicated zone for schools and educational facilities.', 'desc_bn' => 'স্কুল ও শিক্ষা প্রতিষ্ঠানের জন্য নির্ধারিত জোন।'],
                ['hotspot_id' => 'park', 'x' => 48.5, 'y' => 50, 'name_en' => 'Central Green Park', 'name_bn' => 'কেন্দ্রীয় সবুজ পার্ক', 'desc_en' => 'Central green park with walking trails and a children play area.', 'desc_bn' => 'ওয়াকিং ট্রেইল ও শিশুদের খেলার জায়গাসহ কেন্দ্রীয় সবুজ পার্ক।'],
            ],
            'web3forms_key' => '094a49de-078c-48fd-9ec2-676a3626bf2b',
        ];

        foreach ($home_meta as $key => $value) {
            update_post_meta((int) $home_id, $key, $value);
        }
    }

    $options = [
        'company_name_en' => 'South Dhaka Properties & Housing Ltd.',
        'company_name_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
        'phone' => '+8801886175263',
        'phone_display' => '01886-175263, 01770-191675',
        'whatsapp' => '8801886175263',
        'email' => 'info@southdhaka.com',
        'address_en' => 'Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000, Bangladesh',
        'address_bn' => 'রহমান ম্যানশন (৪র্থ তলা) ১৬১, মতিঝিল সি/এ, ঢাকা-১০০০, বাংলাদেশ',
        'facebook_url' => 'https://www.facebook.com/SouthDhakaHousing.Ltd',
        'youtube_url' => '',
        'linkedin_url' => '',
        'brochure_pdf' => SOUTH_CITY_THEME_URI . '/assets/brochure.pdf',
        'map_query' => 'South City Sayedpur Keraniganj Dhaka',
        'whatsapp_message_en' => "Assalamu Alaikum, I'm interested in South City plots.",
        'whatsapp_message_bn' => 'আসসালামু আলাইকুম, আমি সাউথ সিটির প্লট সম্পর্কে জানতে আগ্রহী।',
    ];

    foreach ($options as $key => $value) {
        update_option('options_' . $key, $value);
    }

    $trust_badges = [
        ['Valid Trade License', 'trust-valid-trade-license', ['icon' => 'license', 'label_en' => 'Valid Trade License', 'label_bn' => 'বৈধ ট্রেড লাইসেন্স', 'order_rank' => 1]],
        ['Transparent Documentation', 'trust-transparent-documentation', ['icon' => 'document', 'label_en' => 'Transparent Documentation', 'label_bn' => 'স্বচ্ছ ডকুমেন্টেশন', 'order_rank' => 2]],
        ['Registration on Full Payment', 'trust-registration-on-full-payment', ['icon' => 'stamp', 'label_en' => 'Registration on Full Payment', 'label_bn' => 'সম্পূর্ণ মূল্য পরিশোধে রেজিস্ট্রেশন', 'order_rank' => 3]],
        ['Up to 5-Year Installments', 'trust-up-to-five-year-installments', ['icon' => 'calendar', 'label_en' => 'Up to 5-Year Installments', 'label_bn' => '৫ বছর পর্যন্ত কিস্তি সুবিধা', 'order_rank' => 4]],
        ['Own Purchased Land', 'trust-own-purchased-land', ['icon' => 'landcheck', 'label_en' => 'Own Purchased Land', 'label_bn' => 'ক্রয়কৃত নিজস্ব জমি', 'order_rank' => 5]],
        ['Dhaleshwari Riverside', 'trust-dhaleshwari-riverside', ['icon' => 'river', 'label_en' => 'Dhaleshwari Riverside', 'label_bn' => 'ধলেশ্বরী নদীতীরবর্তী', 'order_rank' => 6]],
    ];

    foreach ($trust_badges as $badge) {
        south_city_upsert_seed_post('southcity_badge', $badge[0], $badge[1], $badge[2]);
    }

    $facts = [
        ['Project', 'fact-project', ['label_en' => 'Project', 'label_bn' => 'প্রকল্প', 'value_en' => 'South City', 'value_bn' => 'সাউথ সিটি', 'order_rank' => 1]],
        ['Developer', 'fact-developer', ['label_en' => 'Developer', 'label_bn' => 'ডেভেলপার', 'value_en' => 'South Dhaka Properties & Housing Ltd.', 'value_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড', 'order_rank' => 2]],
        ['Location', 'fact-location', ['label_en' => 'Location', 'label_bn' => 'অবস্থান', 'value_en' => 'Sayedpur Union, South Keraniganj, Dhaka', 'value_bn' => 'সৈয়দপুর ইউনিয়ন, দক্ষিণ কেরানীগঞ্জ, ঢাকা', 'order_rank' => 3]],
        ['Total Area', 'fact-total-area', ['label_en' => 'Total area', 'label_bn' => 'মোট আয়তন', 'value_en' => '~600 Bigha (planned)', 'value_bn' => 'প্রায় ৬০০ বিঘা (পরিকল্পিত)', 'order_rank' => 4]],
        ['Sectors', 'fact-sectors', ['label_en' => 'Sectors', 'label_bn' => 'সেক্টর', 'value_en' => '4 (A, B, C, D)', 'value_bn' => '৪টি (এ, বি, সি, ডি)', 'order_rank' => 5]],
        ['Plot Types', 'fact-plot-types', ['label_en' => 'Plot types', 'label_bn' => 'প্লটের ধরন', 'value_en' => 'Residential & Commercial', 'value_bn' => 'আবাসিক ও বাণিজ্যিক', 'order_rank' => 6]],
        ['Plot Sizes', 'fact-plot-sizes', ['label_en' => 'Plot sizes', 'label_bn' => 'প্লট সাইজ', 'value_en' => '3 · 5 · 10 · 20 · 30 · 40 Katha', 'value_bn' => '৩ · ৫ · ১০ · ২০ · ৩০ · ৪০ কাঠা', 'order_rank' => 7]],
        ['Road Width', 'fact-road-width', ['label_en' => 'Road width', 'label_bn' => 'রাস্তার প্রশস্ততা', 'value_en' => '25 / 30 / 40 / 60 / 80 ft', 'value_bn' => '২৫ / ৩০ / ৪০ / ৬০ / ৮০ ফুট', 'order_rank' => 8]],
    ];

    foreach ($facts as $fact) {
        south_city_upsert_seed_post('southcity_fact', $fact[0], $fact[1], $fact[2]);
    }

    $plots = [
        ['3 Katha', 'plot-3-katha', ['katha_en' => '3 Katha', 'katha_bn' => '৩ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'sqft_en' => '2,160 sq ft', 'sqft_bn' => '২,১৬০ বর্গফুট', 'dimensions_en' => 'Approx. 36 ft x 60 ft', 'dimensions_bn' => 'প্রায় ৩৬ ফুট x ৬০ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 1]],
        ['5 Katha', 'plot-5-katha', ['katha_en' => '5 Katha', 'katha_bn' => '৫ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'sqft_en' => '3,600 sq ft', 'sqft_bn' => '৩,৬০০ বর্গফুট', 'dimensions_en' => 'Approx. 50 ft x 72 ft', 'dimensions_bn' => 'প্রায় ৫০ ফুট x ৭২ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 2]],
        ['10 Katha', 'plot-10-katha', ['katha_en' => '10 Katha', 'katha_bn' => '১০ কাঠা', 'zone_en' => 'Exclusive Zone', 'zone_bn' => 'এক্সক্লুসিভ জোন', 'sqft_en' => '7,200 sq ft', 'sqft_bn' => '৭,২০০ বর্গফুট', 'dimensions_en' => 'Approx. 72 ft x 100 ft', 'dimensions_bn' => 'প্রায় ৭২ ফুট x ১০০ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 3]],
        ['20 Katha', 'plot-20-katha', ['katha_en' => '20 Katha', 'katha_bn' => '২০ কাঠা', 'zone_en' => 'Duplex Zone', 'zone_bn' => 'ডুপ্লেক্স জোন', 'sqft_en' => '14,400 sq ft', 'sqft_bn' => '১৪,৪০০ বর্গফুট', 'dimensions_en' => 'Approx. 100 ft x 144 ft', 'dimensions_bn' => 'প্রায় ১০০ ফুট x ১৪৪ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 4]],
        ['30 Katha', 'plot-30-katha', ['katha_en' => '30 Katha', 'katha_bn' => '৩০ কাঠা', 'zone_en' => 'Villa Zone', 'zone_bn' => 'ভিলা জোন', 'sqft_en' => '21,600 sq ft', 'sqft_bn' => '২১,৬০০ বর্গফুট', 'dimensions_en' => 'Approx. 120 ft x 180 ft', 'dimensions_bn' => 'প্রায় ১২0 ফুট x ১৮০ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 5]],
        ['40 Katha', 'plot-40-katha', ['katha_en' => '40 Katha', 'katha_bn' => '৪০ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'sqft_en' => '28,800 sq ft', 'sqft_bn' => '২৮,৮০০ বর্গফুট', 'dimensions_en' => 'Approx. 144 ft x 200 ft', 'dimensions_bn' => 'প্রায় ১৪৪ ফুট x ২০০ ফুট', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 6]],
    ];

    foreach ($plots as $plot) {
        south_city_upsert_seed_post('southcity_plot', $plot[0], $plot[1], $plot[2]);
    }

    $amenities = [
        ['Schools', 'amenity-schools', ['icon' => 'school', 'label_en' => 'Schools', 'label_bn' => 'স্কুল', 'order_rank' => 1]],
        ['Mosques', 'amenity-mosques', ['icon' => 'mosque', 'label_en' => 'Mosques', 'label_bn' => 'মসজিদ', 'order_rank' => 2]],
        ['Health Centre', 'amenity-health-centre', ['icon' => 'health', 'label_en' => 'Health Centre', 'label_bn' => 'হেলথ সেন্টার', 'order_rank' => 3]],
        ['Super Shop', 'amenity-super-shop', ['icon' => 'shop', 'label_en' => 'Super Shop', 'label_bn' => 'সুপার শপ', 'order_rank' => 4]],
        ['Gym', 'amenity-gym', ['icon' => 'gym', 'label_en' => 'Gym', 'label_bn' => 'জিম', 'order_rank' => 5]],
        ['Coffee Shop', 'amenity-coffee-shop', ['icon' => 'coffee', 'label_en' => 'Coffee Shop', 'label_bn' => 'কফি শপ', 'order_rank' => 6]],
        ['Walking Trails', 'amenity-walking-trails', ['icon' => 'trail', 'label_en' => 'Walking Trails', 'label_bn' => 'ওয়াকিং ট্রেইল', 'order_rank' => 7]],
        ['Green Open Spaces', 'amenity-green-open-spaces', ['icon' => 'park', 'label_en' => 'Green Open Spaces', 'label_bn' => 'সবুজ উন্মুক্ত স্থান', 'order_rank' => 8]],
        ['24/7 Security', 'amenity-security', ['icon' => 'security', 'label_en' => '24/7 Security', 'label_bn' => '২৪/৭ নিরাপত্তা', 'order_rank' => 9]],
        ['Wide Roads', 'amenity-wide-roads', ['icon' => 'road', 'label_en' => 'Wide Roads', 'label_bn' => 'প্রশস্ত রাস্তা', 'order_rank' => 10]],
        ['Backup Utilities', 'amenity-backup-utilities', ['icon' => 'utility', 'label_en' => 'Backup Utilities', 'label_bn' => 'ব্যাকআপ ইউটিলিটি', 'order_rank' => 11]],
        ['Children Play Area', 'amenity-children-play-area', ['icon' => 'play', 'label_en' => 'Children Play Area', 'label_bn' => 'শিশুদের খেলার জায়গা', 'order_rank' => 12]],
    ];

    foreach ($amenities as $amenity) {
        south_city_upsert_seed_post('southcity_amenity', $amenity[0], $amenity[1], $amenity[2]);
    }

    $landmarks = [
        ['Connectivity', 'landmark-connectivity', ['label_en' => 'Connectivity', 'label_bn' => 'যোগাযোগ', 'image_key' => 'connectivity', 'items' => [
            ['name_en' => 'KC Road / Eastern Bypass', 'name_bn' => 'কেসি রোড / ইস্টার্ন বাইপাস', 'note_en' => 'Adjacent west boundary', 'note_bn' => 'সংলগ্ন পশ্চিম সীমানা'],
            ['name_en' => 'Dhaka-Mawa Expressway', 'name_bn' => 'ঢাকা-মাওয়া এক্সপ্রেসওয়ে', 'note_en' => '5 minutes by road', 'note_bn' => 'সড়কপথে ৫ মিনিট'],
            ['name_en' => 'Padma Bridge', 'name_bn' => 'পদ্মা সেতু', 'note_en' => '12 km', 'note_bn' => '১২ কিমি'],
        ], 'order_rank' => 1]],
        ['Education', 'landmark-education', ['label_en' => 'Education', 'label_bn' => 'শিক্ষা', 'image_key' => 'education', 'items' => [
            ['name_en' => 'On-site school zone', 'name_bn' => 'প্রকল্পের নিজস্ব স্কুল জোন', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Two modern schools per sector', 'name_bn' => 'প্রতিটি সেক্টরে দুটি আধুনিক বিদ্যালয়', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Keraniganj schools & colleges', 'name_bn' => 'কেরানীগঞ্জের স্কুল ও কলেজ', 'note_en' => 'Within 6 km', 'note_bn' => '৬ কিমির মধ্যে'],
        ], 'order_rank' => 2]],
        ['Health', 'landmark-health', ['label_en' => 'Health', 'label_bn' => 'স্বাস্থ্যসেবা', 'image_key' => 'health', 'items' => [
            ['name_en' => 'On-site health centre', 'name_bn' => 'প্রকল্পের নিজস্ব হেলথ সেন্টার', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'A health centre in every sector', 'name_bn' => 'প্রতিটি সেক্টরে স্বাস্থ্যকেন্দ্র', 'note_en' => 'Primary care & emergency support', 'note_bn' => 'প্রাথমিক স্বাস্থ্যসেবা ও জরুরি সহায়তা'],
            ['name_en' => 'Dhaka hospitals', 'name_bn' => 'ঢাকার হাসপাতালসমূহ', 'note_en' => 'About 30 min drive', 'note_bn' => 'গাড়িতে প্রায় ৩০ মিনিট'],
        ], 'order_rank' => 3]],
        ['Daily Needs', 'landmark-daily-needs', ['label_en' => 'Daily Needs', 'label_bn' => 'দৈনন্দিন প্রয়োজন', 'image_key' => 'daily', 'items' => [
            ['name_en' => 'On-site super shop & commercial area', 'name_bn' => 'প্রকল্পের সুপার শপ ও বাণিজ্যিক এলাকা', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Local bazaars', 'name_bn' => 'স্থানীয় বাজার', 'note_en' => 'Walking distance', 'note_bn' => 'হাঁটা দূরত্বে'],
            ['name_en' => 'Banks & services, Keraniganj', 'name_bn' => 'ব্যাংক ও সেবা, কেরানীগঞ্জ', 'note_en' => 'Within 6 km', 'note_bn' => '৬ কিমির মধ্যে'],
        ], 'order_rank' => 4]],
    ];

    foreach ($landmarks as $landmark) {
        south_city_upsert_seed_post('southcity_landmark', $landmark[0], $landmark[1], $landmark[2]);
    }

    $gallery_captions = [
        ['South City main gateway', 'সাউথ সিটির প্রধান প্রবেশদ্বার'],
        ['60 ft main boulevard & super shop', '৬০ ফুট প্রধান সড়ক ও সুপার শপ'],
        ['Gateway & fountain at dusk', 'সন্ধ্যায় প্রবেশদ্বার ও ফোয়ারা'],
        ['Central green park & lake', 'কেন্দ্রীয় সবুজ পার্ক ও লেক'],
        ['Township aerial view', 'টাউনশিপের আকাশচিত্র'],
        ['Developed plots & internal road', 'উন্নয়নকৃত প্লট ও অভ্যন্তরীণ রাস্তা'],
        ['Project land - development in progress', 'প্রকল্পের জমি - চলমান উন্নয়ন কাজ'],
        ['Central mosque', 'কেন্দ্রীয় মসজিদ'],
        ['Children play area', 'শিশুদের খেলার জায়গা'],
    ];

    foreach ($gallery_captions as $index => $caption) {
        $number = $index + 1;
        south_city_upsert_seed_post('southcity_gallery', $caption[0], 'gallery-' . $number, [
            'image' => SOUTH_CITY_THEME_URI . '/assets/img/gallery-' . $number . '.webp',
            'caption_en' => $caption[0],
            'caption_bn' => $caption[1],
            'order_rank' => $number,
        ]);
    }

    update_option('south_city_default_content_seeded', 1);
}
