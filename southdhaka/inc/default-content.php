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
/**
 * Bump this whenever the seed content below changes so an already-active
 * site re-applies it on the next request (see the init hook in functions.php).
 */
const SOUTH_CITY_SEED_VERSION = 6;

function south_city_upsert_seed_post(string $post_type, string $title, string $slug, array $meta = [], string $content = '', array $term_slugs = [], string $taxonomy = ''): int
{
    // Look up by exact post_name (not get_page_by_path, which is unreliable
    // for non-hierarchical custom post types) across every status, oldest
    // match first, so a re-seed always updates the same post instead of
    // occasionally inserting a duplicate with a "-2" suffixed slug.
    $matches = get_posts([
        'post_type'      => $post_type,
        'name'           => $slug,
        'post_status'    => ['publish', 'draft', 'pending', 'future', 'private', 'trash'],
        'numberposts'    => 1,
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'suppress_filters' => true,
    ]);
    $existing = $matches[0] ?? null;

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

    if ($taxonomy !== '' && ! empty($term_slugs)) {
        wp_set_object_terms((int) $post_id, $term_slugs, $taxonomy, false);
    }

    return (int) $post_id;
}

/**
 * Seed the converted Astro/Sanity content into WordPress once.
 */
function south_city_seed_default_content(): void
{
    if ((int) get_option('south_city_seed_version', 0) >= SOUTH_CITY_SEED_VERSION) {
        return;
    }

    south_city_register_amenity_group_terms();

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
            'hero_subline_en' => 'A ~800-bigha planned township in Sayedpur Union - beside the Eastern Bypass, 2 minutes from the Dhaka-Mawa Expressway (High Road).',
            'hero_subline_bn' => 'সৈয়দপুর ইউনিয়নে প্রায় ৮০০ বিঘার পরিকল্পিত টাউনশিপ - ইস্টার্ন বাইপাস সংলগ্ন, ঢাকা-মাওয়া এক্সপ্রেসওয়ে (হাই রোড) থেকে ২ মিনিটের দূরত্বে।',
            'hero_chips' => [
                ['text_en' => 'Prime Location', 'text_bn' => 'প্রাইম লোকেশন'],
                ['text_en' => 'Legal Security', 'text_bn' => 'আইনি নিরাপত্তা'],
                ['text_en' => 'High Growth', 'text_bn' => 'উচ্চ প্রবৃদ্ধি'],
                ['text_en' => 'Premium Planning', 'text_bn' => 'প্রিমিয়াম প্ল্যানিং'],
                ['text_en' => 'Green & Sustainable', 'text_bn' => 'সবুজ ও টেকসই'],
                ['text_en' => 'Family Friendly', 'text_bn' => 'পরিবার-বান্ধব'],
            ],
            'overview_paragraph_en' => 'South City is a ~800-bigha planned residential and commercial land development in Sayedpur Union, South Keraniganj — the Dhaleshwari river along its north edge, the Eastern Bypass to the west and the 6-lane Dhaka-Mawa Expressway to the east, 2 minutes from the expressway. Four thoughtfully laid-out sectors, 25 / 30 / 40 / 60 ft roads, and everyday facilities like schools, a central mosque and a health centre are growing into a complete township, while installment plans of up to 5 years keep ownership within reach.',
            'overview_paragraph_bn' => 'সাউথ সিটি — দক্ষিণ কেরানীগঞ্জের সৈয়দপুর ইউনিয়নে প্রায় ৮০০ বিঘার একটি পরিকল্পিত আবাসিক ও বাণিজ্যিক ল্যান্ড ডেভেলপমেন্ট প্রকল্প। উত্তরে ধলেশ্বরী নদী, পশ্চিমে ইস্টার্ন বাইপাস আর পূর্বে ৬ লেনের ঢাকা-মাওয়া এক্সপ্রেসওয়ে; এক্সপ্রেসওয়ে থেকে দূরত্ব মাত্র ২ মিনিট। চারটি সুপরিকল্পিত সেক্টর, ২৫ / ৩০ / ৪০ / ৬০ ফুট প্রশস্ত রাস্তা, আর স্কুল, কেন্দ্রীয় মসজিদ ও হেলথ সেন্টারসহ দৈনন্দিন সব সুবিধা নিয়ে গড়ে উঠছে একটি পূর্ণাঙ্গ টাউনশিপ; সঙ্গে রয়েছে ৫ বছর পর্যন্ত সহজ কিস্তির সুবিধা।',
            'overview_counters' => [
                ['end' => 800, 'display_en' => '800', 'display_bn' => '৮০০', 'label_en' => 'Bigha planned township', 'label_bn' => 'বিঘা পরিকল্পিত প্রকল্প'],
                ['end' => 4, 'display_en' => '4', 'display_bn' => '৪', 'label_en' => 'Residential & commercial sectors', 'label_bn' => 'আবাসিক ও বাণিজ্যিক সেক্টর'],
                ['end' => '', 'display_en' => '3-40', 'display_bn' => '৩-৪০', 'label_en' => 'Katha plot sizes', 'label_bn' => 'কাঠা প্লট সাইজ'],
                ['end' => 5, 'display_en' => '5', 'display_bn' => '৫', 'label_en' => 'Years of easy installments', 'label_bn' => 'বছর পর্যন্ত সহজ কিস্তি'],
            ],
            'location_distances' => [
                ['place_en' => 'Motijheel', 'place_bn' => 'মতিঝিল', 'value_en' => '20 min drive', 'value_bn' => '২০ মিনিটের দূরত্বে'],
                ['place_en' => 'Mohammadpur, Basila', 'place_bn' => 'মোহাম্মদপুর, বসিলা', 'value_en' => '25 min drive', 'value_bn' => '২৫ মিনিটের দূরত্বে'],
                ['place_en' => 'Hazrat Shahjalal International Airport', 'place_bn' => 'হযরত শাহজালাল আন্তর্জাতিক বিমানবন্দর', 'value_en' => '30-35 min drive', 'value_bn' => '৩০-৩৫ মিনিটের দূরত্বে'],
            ],
            'project_boundaries' => [
                ['side_en' => 'North', 'side_bn' => 'উত্তরে', 'value_en' => 'Dhaleshwari River', 'value_bn' => 'ধলেশ্বরী নদী'],
                ['side_en' => 'East', 'side_bn' => 'পূর্বে', 'value_en' => 'Dhaka-Mawa Expressway (6-lane) & Dhaleshwari A Bridge 2', 'value_bn' => 'ঢাকা-মাওয়া এক্সপ্রেসওয়ে (৬ লেন) ও ধলেশ্বরী এ ব্রিজ ২'],
                ['side_en' => 'West', 'side_bn' => 'পশ্চিমে', 'value_en' => 'Eastern Bypass Road', 'value_bn' => 'ইস্টার্ন বাইপাস রোড'],
                ['side_en' => 'South', 'side_bn' => 'দক্ষিণে', 'value_en' => 'Eastern Bypass Road / Nimtali', 'value_bn' => 'ইস্টার্ন বাইপাস রোড / নিমতলী'],
            ],
            'master_plan_hotspots' => [
                ['hotspot_id' => 'sector-01', 'x' => 0, 'y' => 0, 'name_en' => 'Sector 01', 'name_bn' => 'সেক্টর ০১', 'desc_en' => 'North-east riverfront sector beside the college and central facilities — 3 & 5 katha plots on 25–40 ft roads.', 'desc_bn' => 'উত্তর-পূর্বের নদীতীরবর্তী সেক্টর — কলেজ ও কেন্দ্রীয় সুবিধার পাশে; ২৫–৪০ ফুট রাস্তায় ৩ ও ৫ কাঠার প্লট।'],
                ['hotspot_id' => 'sector-02', 'x' => 0, 'y' => 0, 'name_en' => 'Sector 02', 'name_bn' => 'সেক্টর ০২', 'desc_en' => 'North-west residential sector with 10 katha exclusive plots, community centre and school zone.', 'desc_bn' => 'উত্তর-পশ্চিমের আবাসিক সেক্টর — ১০ কাঠার এক্সক্লুসিভ প্লট, কমিউনিটি সেন্টার ও স্কুল জোন।'],
                ['hotspot_id' => 'sector-03', 'x' => 0, 'y' => 0, 'name_en' => 'Sector 03', 'name_bn' => 'সেক্টর ০৩', 'desc_en' => 'The largest residential sector, on the 60 ft main boulevard beside the central mosque and lake.', 'desc_bn' => 'সবচেয়ে বড় আবাসিক সেক্টর — ৬০ ফুট প্রধান বুলেভার্ডে, কেন্দ্রীয় মসজিদ ও লেকের পাশে।'],
                ['hotspot_id' => 'sector-04', 'x' => 0, 'y' => 0, 'name_en' => 'Sector 04', 'name_bn' => 'সেক্টর ০৪', 'desc_en' => 'South-east premium sector with villa & 20 katha duplex plots, lake park and a green edge along the expressway.', 'desc_bn' => 'দক্ষিণ-পূর্বের প্রিমিয়াম সেক্টর — ভিলা ও ২০ কাঠার ডুপ্লেক্স প্লট, লেক পার্ক এবং এক্সপ্রেসওয়ে ঘেঁষা সবুজ প্রান্ত।'],
            ],
            'web3forms_key' => '094a49de-078c-48fd-9ec2-676a3626bf2b',

            // --- Chairman's Message (brochure p.3) ---
            'chairman_name_en' => 'South Dhaka Properties & Housing Ltd.',
            'chairman_name_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
            'chairman_body_en' => "Dear customer,\n\nA beautiful future is built on sound planning, honesty and commitment. At South Dhaka Properties & Housing Ltd. we believe every person carries a dream — an address of their own, a safe environment, and a lasting asset for the next generation.\n\nSouth City is a sincere effort to realise that dream. With well-planned infrastructure, modern civic amenities, wide roads, green surroundings and harmony with nature, we are committed to building a sustainable and dignified community in this planned residential project.\n\nOur greatest strength is commitment. We believe trust is earned only by keeping promises. That is why we work to hand over every customer's plot within the scheduled time — and, wherever possible, ahead of it.\n\nYour trust and cooperation are the inspiration that carries us forward.\nBuilding Landmark, Creating Legacy.",
            'chairman_body_bn' => "প্রিয় গ্রাহক,\n\nএকটি সুন্দর ভবিষ্যৎ গড়ে ওঠে সঠিক পরিকল্পনা, সততা ও প্রতিশ্রুতির ওপর। South Dhaka Properties & Housing Ltd.-এ আমরা বিশ্বাস করি, প্রতিটি মানুষের একটি স্বপ্ন থাকে — যা হবে নিজস্ব ঠিকানা, নিরাপদ পরিবেশ এবং আগামী প্রজন্মের জন্য একটি স্থায়ী সম্পদ।\n\nSouth City সেই স্বপ্ন বাস্তবায়নের একটি আন্তরিক প্রচেষ্টা। সুপরিকল্পিত অবকাঠামো, উন্নত নাগরিক সুবিধা, প্রশস্ত সড়ক, সবুজ পরিবেশ এবং প্রকৃতির সাথে সামঞ্জস্যপূর্ণ এই পরিকল্পিত আবাসন প্রকল্পে আমরা একটি টেকসই ও মর্যাদাপূর্ণ কমিউনিটি গড়ে তুলতে প্রতিশ্রুতিবদ্ধ।\n\nআমাদের সবচেয়ে বড় শক্তি হলো Commitment। আমরা বিশ্বাস করি, আস্থা অর্জন করা যায় কেবল প্রতিশ্রুতি রক্ষার মাধ্যমে। তাই, প্রতিটি গ্রাহকের বিনিয়োগকে সর্বোচ্চ গুরুত্ব দিয়ে নির্ধারিত সময়ের মধ্যেই, বরং সম্ভব হলে তারও আগে, প্লট হস্তান্তরের লক্ষ্য নিয়ে আমরা কাজ করে যাচ্ছি।\n\nআপনাদের বিশ্বাস ও সহযোগিতাই আমাদের এগিয়ে চলার প্রেরণা।\nBuilding Landmark, Creating Legacy.",

            // --- Project Summary grid (brochure p.3) ---
            'project_summary' => [
                ['icon' => 'landplot', 'value_en' => '~800', 'value_bn' => 'প্রায় ৮০০', 'label_en' => 'Bigha planned project', 'label_bn' => 'বিঘা পরিকল্পিত প্রকল্প'],
                ['icon' => 'map', 'value_en' => '4', 'value_bn' => '৪', 'label_en' => 'Planned sectors (01–04)', 'label_bn' => 'পরিকল্পিত সেক্টর (০১–০৪)'],
                ['icon' => 'building', 'value_en' => '3 · 5 · 10 · 20 · 40', 'value_bn' => '৩ · ৫ · ১০ · ২০ · ৪০', 'label_en' => 'Katha plot sizes', 'label_bn' => 'কাঠা প্লট সাইজ'],
                ['icon' => 'road', 'value_en' => "25' · 30' · 40' · 60'", 'value_bn' => "২৫' · ৩০' · ৪০' · ৬০'", 'label_en' => 'ft road widths', 'label_bn' => 'ফুট রাস্তার প্রস্থ'],
                ['icon' => 'landcheck', 'value_en' => 'Own', 'value_bn' => 'নিজস্ব', 'label_en' => 'Purchased land', 'label_bn' => 'ক্রয়কৃত জমি'],
                ['icon' => 'stamp', 'value_en' => 'Instant', 'value_bn' => 'তাৎক্ষণিক', 'label_en' => 'Registration on full payment', 'label_bn' => 'এককালীন সম্পূর্ণ পেমেন্টে রেজিস্ট্রেশন'],
                ['icon' => 'calendar', 'value_en' => '5 years', 'value_bn' => '৫ বছর', 'label_en' => 'Easy installments', 'label_bn' => 'পর্যন্ত সহজ কিস্তি'],
                ['icon' => 'scale', 'value_en' => 'Transparent', 'value_bn' => 'স্বচ্ছ', 'label_en' => 'Dealings', 'label_bn' => 'লেনদেন'],
            ],

            // --- Managing Director's Message (brochure p.4) ---
            'md_name_en' => 'South Dhaka Properties & Housing Ltd.',
            'md_name_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
            'md_body_en' => "South City is not just a real estate project; it is the dream of building a modern, safe and sustainable society for present and future generations.\n\nSpread over 800 bigha of prime land in Sayedpur Union and beautifully connected to the Dhaka-Mawa Expressway and the Eastern Bypass, South City is planned with wide roads, green spaces, modern amenities and essential services to ensure a premium lifestyle.\n\nWe believe in transparency, commitment and on-time delivery. Our goal is to hand over plots ahead of schedule and to create long-term value for our customers.\n\nWhere your dreams find their address. Welcome to South City.",
            'md_body_bn' => "সাউথ সিটি শুধু একটি রিয়েল এস্টেট প্রকল্প নয়; এটি বর্তমান ও ভবিষ্যৎ প্রজন্মের জন্য একটি আধুনিক, নিরাপদ এবং টেকসই সমাজ গড়ে তোলার স্বপ্ন।\n\nসৈয়দপুর ইউনিয়নে ৮০০ বিঘা উৎকৃষ্ট জমির উপর বিস্তৃত এবং ঢাকা-মাওয়া এক্সপ্রেসওয়ে ও ইস্টার্ন বাইপাসের সাথে চমৎকার সংযোগযুক্ত সাউথ সিটি একটি প্রিমিয়াম জীবনধারা নিশ্চিত করার লক্ষ্যে প্রশস্ত রাস্তা, সবুজ স্থান, আধুনিক সুযোগ-সুবিধা এবং অত্যাবশ্যকীয় পরিষেবা দিয়ে পরিকল্পিত।\n\nআমরা স্বচ্ছতা, প্রতিশ্রুতি এবং সময়মতো সরবরাহে বিশ্বাসী। আমাদের লক্ষ্য হলো নির্ধারিত সময়ের আগেই প্লট হস্তান্তর করা এবং গ্রাহকের জন্য দীর্ঘমেয়াদী মূল্য তৈরি করা।\n\nযেখানে আপনার স্বপ্নেরা তার ঠিকানা খুঁজে পায়। সাউথ সিটিতে আপনাকে স্বাগতম।",

            // --- Company Profile (brochure p.4) ---
            'about_text_en' => 'South Dhaka Properties & Housing Ltd. is a trusted real estate company dedicated to developing well-planned, modern and sustainable residential projects. Our core aims are quality, on-time handover and customer satisfaction.',
            'about_text_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড একটি বিশ্বস্ত রিয়েল এস্টেট কোম্পানি, যা সুপরিকল্পিত, আধুনিক এবং টেকসই আবাসিক প্রকল্প উন্নয়নে নিবেদিত। আমাদের মূল লক্ষ্য হলো গুণমান, সময়মতো হস্তান্তর এবং গ্রাহক সন্তুষ্টি।',
            'vision_text_en' => 'To become the most trusted real estate brand in Bangladesh, recognised for creating sustainable communities that enrich quality, innovation and life.',
            'vision_text_bn' => 'গুণমান, উদ্ভাবন এবং জীবনকে সমৃদ্ধ করে এমন টেকসই কমিউনিটি তৈরির জন্য স্বীকৃত, বাংলাদেশের সবচেয়ে বিশ্বস্ত রিয়েল এস্টেট ব্র্যান্ড হওয়া।',
            'mission_text_en' => 'To build planned communities with modern infrastructure and essential amenities that ensure long-term value and an improved standard of living for our customers.',
            'mission_text_bn' => 'আধুনিক অবকাঠামো ও অত্যাবশ্যকীয় সুযোগ-সুবিধাসহ পরিকল্পিত কমিউনিটি গড়ে তোলা, যা আমাদের গ্রাহকদের জন্য দীর্ঘমেয়াদী মূল্য এবং উন্নত জীবনমান নিশ্চিত করবে।',
            'core_values' => [
                ['value_en' => 'Commitment', 'value_bn' => 'অঙ্গীকার'],
                ['value_en' => 'Honesty', 'value_bn' => 'সততা'],
                ['value_en' => 'Transparency', 'value_bn' => 'স্বচ্ছতা'],
                ['value_en' => 'Quality', 'value_bn' => 'গুণমান'],
                ['value_en' => 'Customer Focus', 'value_bn' => 'গ্রাহক ফোকাস'],
                ['value_en' => 'Sustainability', 'value_bn' => 'স্থায়িত্ব'],
            ],

            // --- Why South City? (brochure p.5) ---
            'why_points' => [
                ['icon' => 'location', 'title_en' => 'Strategic Location', 'title_bn' => 'কৌশলগত অবস্থান', 'body_en' => 'Located in Sayedpur Union, South Keraniganj, with excellent connectivity to the main highway and key city destinations.', 'body_bn' => 'দক্ষিণ কেরানীগঞ্জের সৈয়দপুর ইউনিয়নে অবস্থিত, যেখান থেকে প্রধান মহাসড়ক এবং শহরের গুরুত্বপূর্ণ স্থানগুলোর সাথে চমৎকার সংযোগ রয়েছে।'],
                ['icon' => 'road', 'title_en' => 'Wide, Well-Planned Roads', 'title_bn' => 'প্রশস্ত ও সুপরিকল্পিত রাস্তা', 'body_en' => "25', 30', 40' and 60' wide roads for smooth connectivity and modern living.", 'body_bn' => "নির্বিঘ্ন যোগাযোগ ও আধুনিক জীবনযাপনের জন্য ২৫', ৩০', ৪০' এবং ৬০' প্রশস্ত রাস্তা।"],
                ['icon' => 'growth', 'title_en' => 'High Investment Potential', 'title_bn' => 'উচ্চ বিনিয়োগ সম্ভাবনা', 'body_en' => 'A fast-developing area with the assurance of strong investment returns and future growth.', 'body_bn' => 'দ্রুত উন্নয়নশীল একটি ক্ষেত্র, যেখানে উচ্চ বিনিয়োগ ফেরত এবং ভবিষ্যৎ প্রবৃদ্ধির নিশ্চয়তা রয়েছে।'],
                ['icon' => 'leaf', 'title_en' => 'Green & Sustainable Living', 'title_bn' => 'সবুজ ও টেকসই জীবনযাপন', 'body_en' => 'Wide open spaces, greenery and an eco-friendly environment for healthy living.', 'body_bn' => 'সুস্থ জীবনযাপনের জন্য প্রশস্ত খোলা জায়গা, সবুজ এবং পরিবেশবান্ধব পরিবেশ।'],
                ['icon' => 'shield', 'title_en' => 'Safe & Secure Community', 'title_bn' => 'নিরাপদ ও সুরক্ষিত কমিউনিটি', 'body_en' => 'A community with 24/7 security, planned layout and modern infrastructure.', 'body_bn' => '২৪/৭ নিরাপত্তা, পরিকল্পিত বিন্যাস এবং আধুনিক অবকাঠামো সহ একটি কমিউনিটি।'],
                ['icon' => 'family', 'title_en' => 'Complete Family Environment', 'title_bn' => 'সম্পূর্ণ পারিবারিক পরিবেশ', 'body_en' => 'All the amenities needed for a comfortable and happy life, within easy reach.', 'body_bn' => 'আরামদায়ক ও সুখী জীবনের জন্য প্রয়োজনীয় সকল সুযোগ-সুবিধা হাতের নাগালে।'],
            ],

            // --- Investment section (brochure p.7) ---
            'investment_intro_en' => 'South City is not just a residential project — it is a planned smart township that offers long-term investment potential alongside a place to live.',
            'investment_intro_bn' => 'সাউথ সিটি শুধু একটি আবাসন প্রকল্প নয় — এটি একটি পরিকল্পিত স্মার্ট টাউনশিপ, যেখানে বসবাসের পাশাপাশি দীর্ঘমেয়াদি বিনিয়োগের সম্ভাবনাও রয়েছে।',
            'investment_points' => [
                ['icon' => 'location', 'title_en' => 'Prime Location', 'title_bn' => 'প্রাইম লোকেশন', 'body_en' => 'Close to the Dhaka-Mawa Expressway and adjacent to the Eastern Bypass — strengthening future development potential.', 'body_bn' => 'ঢাকা-মাওয়া এক্সপ্রেসওয়ের নিকটে এবং ইস্টার্ন বাইপাস সংলগ্ন অবস্থান, যা ভবিষ্যতের উন্নয়ন সম্ভাবনাকে আরও শক্তিশালী করে।'],
                ['icon' => 'growth', 'title_en' => 'High Growth Potential', 'title_bn' => 'উচ্চ বৃদ্ধির সম্ভাবনা', 'body_en' => 'Located in a fast-developing area, with strong potential for long-term asset appreciation.', 'body_bn' => 'দ্রুত উন্নয়নশীল এলাকায় অবস্থানের কারণে দীর্ঘমেয়াদে সম্পদের মূল্য বৃদ্ধির সম্ভাবনা।'],
                ['icon' => 'doc', 'title_en' => 'Legal Security', 'title_bn' => 'আইনি নিরাপত্তা', 'body_en' => 'Instant registration on one-time full payment, making the investment even safer.', 'body_bn' => 'এককালীন মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন, যা বিনিয়োগকে আরও নিরাপদ করে।'],
                ['icon' => 'wallet', 'title_en' => 'Flexible Payment', 'title_bn' => 'নমনীয় পেমেন্ট', 'body_en' => 'Easy installments of up to 5 years for planned investment.', 'body_bn' => 'সর্বোচ্চ ৫ বছরের সহজ কিস্তি সুবিধা, যাতে পরিকল্পিতভাবে বিনিয়োগ করা যায়।'],
                ['icon' => 'crane', 'title_en' => 'Development Progress', 'title_bn' => 'উন্নয়ন অগ্রগতি', 'body_en' => 'Development work on the project is progressing at an increasing pace.', 'body_bn' => 'প্রকল্পের উন্নয়ন কাজ ক্রমবর্ধমান হারে চলমান।'],
                ['icon' => 'home', 'title_en' => 'Ready Development Vision', 'title_bn' => 'প্রস্তুত উন্নয়ন ভিশন', 'body_en' => 'A goal to hand over developed 3, 5, 10, 20 & 40 katha plots with modern infrastructure as planned.', 'body_bn' => 'উন্নত অবকাঠামো ও পরিকল্পনা অনুযায়ী ৩, ৫, ১০, ২০ ও ৪০ কাঠার উন্নত প্লট হস্তান্তরের লক্ষ্য।'],
            ],

            // --- Ownership Process (brochure p.7) ---
            'ownership_steps' => [
                ['icon' => 'location', 'title_en' => 'Choose your plot', 'title_bn' => 'আপনার প্লট পছন্দ করুন'],
                ['icon' => 'wallet', 'title_en' => 'Select a payment option', 'title_bn' => 'পেমেন্ট বিকল্প নির্বাচন করুন'],
                ['icon' => 'doc', 'title_en' => 'Registration & documentation', 'title_bn' => 'রেজিস্ট্রেশন এবং ডকুমেন্টেশন'],
                ['icon' => 'crane', 'title_en' => 'Development progress', 'title_bn' => 'উন্নয়ন অগ্রগতি'],
                ['icon' => 'home', 'title_en' => 'Plot handover', 'title_bn' => 'প্লট হস্তান্তর'],
            ],
        ];

        foreach ($home_meta as $key => $value) {
            update_post_meta((int) $home_id, $key, $value);
        }
    }

    $options = [
        'company_name_en' => 'South Dhaka Properties & Housing Ltd.',
        'company_name_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
        'phone' => '+8801886175263',
        'phone_display' => '+880 1886 175 263, +880 1770 191 675',
        'whatsapp' => '8801886175263',
        'email' => 'info@southdhaka.com',
        'website_url' => 'https://www.southdhaka.com',
        'address_en' => 'Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000, Bangladesh',
        'address_bn' => 'রহমান ম্যানশন (৪র্থ তলা) ১৬১, মতিঝিল সি/এ, ঢাকা-১০০০, বাংলাদেশ',
        'facebook_url' => 'https://www.facebook.com/SouthDhakaHousing.Ltd',
        'youtube_url' => '',
        'linkedin_url' => '',
        'brochure_pdf' => '',
        'map_query' => 'South City Sayedpur Keraniganj Dhaka',
        'whatsapp_message_en' => "Assalamu Alaikum, I'm interested in South City plots.",
        'whatsapp_message_bn' => 'আসসালামু আলাইকুম, আমি সাউথ সিটির প্লট সম্পর্কে জানতে আগ্রহী।',
    ];

    foreach ($options as $key => $value) {
        update_option('options_' . $key, $value);
    }

    $trust_badges = [
        ['Valid Trade License', 'trust-valid-trade-license', ['icon' => 'license', 'label_en' => 'Valid Trade License', 'label_bn' => 'বৈধ ট্রেড লাইসেন্স', 'order_rank' => 1]],
        ['Transparent & Orderly Documentation', 'trust-transparent-documentation', ['icon' => 'document', 'label_en' => 'Transparent & Orderly Documentation', 'label_bn' => 'স্বচ্ছ ও সুশৃঙ্খল ডকুমেন্টেশন', 'order_rank' => 2]],
        ['Instant Registration on Full Payment', 'trust-registration-on-full-payment', ['icon' => 'stamp', 'label_en' => 'Instant Registration on Full Payment', 'label_bn' => 'সম্পূর্ণ পেমেন্টে তাৎক্ষণিক রেজিস্ট্রেশন', 'order_rank' => 3]],
        ['Up to 5-Year Easy Installments', 'trust-up-to-five-year-installments', ['icon' => 'calendar', 'label_en' => 'Up to 5-Year Easy Installments', 'label_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি', 'order_rank' => 4]],
        ['Own Purchased Land', 'trust-own-purchased-land', ['icon' => 'landcheck', 'label_en' => 'Own Purchased Land', 'label_bn' => 'ক্রয়কৃত নিজস্ব জমি', 'order_rank' => 5]],
        ['Handover Before Deadline', 'trust-handover-before-deadline', ['icon' => 'check', 'label_en' => 'Handover Before Deadline', 'label_bn' => 'নির্ধারিত সময়ের আগেই হস্তান্তর', 'order_rank' => 6]],
    ];

    $badge_slugs = [];
    foreach ($trust_badges as $badge) {
        south_city_upsert_seed_post('southcity_badge', $badge[0], $badge[1], $badge[2]);
        $badge_slugs[] = $badge[1];
    }
    south_city_prune_seed_posts('southcity_badge', $badge_slugs);

    $facts = [
        ['Project', 'fact-project', ['label_en' => 'Project', 'label_bn' => 'প্রকল্প', 'value_en' => 'South City', 'value_bn' => 'সাউথ সিটি', 'order_rank' => 1]],
        ['Developer', 'fact-developer', ['label_en' => 'Developer', 'label_bn' => 'ডেভেলপার', 'value_en' => 'South Dhaka Properties & Housing Ltd.', 'value_bn' => 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড', 'order_rank' => 2]],
        ['Location', 'fact-location', ['label_en' => 'Location', 'label_bn' => 'অবস্থান', 'value_en' => 'Sayedpur Union, South Keraniganj, Dhaka', 'value_bn' => 'সৈয়দপুর ইউনিয়ন, দক্ষিণ কেরানীগঞ্জ, ঢাকা', 'order_rank' => 3]],
        ['Total Area', 'fact-total-area', ['label_en' => 'Total area', 'label_bn' => 'মোট আয়তন', 'value_en' => '~800 Bigha (planned)', 'value_bn' => 'প্রায় ৮০০ বিঘা (পরিকল্পিত)', 'order_rank' => 4]],
        ['Sectors', 'fact-sectors', ['label_en' => 'Sectors', 'label_bn' => 'সেক্টর', 'value_en' => '4 (Sector 01–04)', 'value_bn' => '৪টি (সেক্টর ০১–০৪)', 'order_rank' => 5]],
        ['Plot Types', 'fact-plot-types', ['label_en' => 'Plot types', 'label_bn' => 'প্লটের ধরন', 'value_en' => 'Residential & Commercial', 'value_bn' => 'আবাসিক ও বাণিজ্যিক', 'order_rank' => 6]],
        ['Plot Sizes', 'fact-plot-sizes', ['label_en' => 'Plot sizes', 'label_bn' => 'প্লট সাইজ', 'value_en' => '3 · 5 · 10 · 20 · 40 Katha', 'value_bn' => '৩ · ৫ · ১০ · ২০ · ৪০ কাঠা', 'order_rank' => 7]],
        ['Road Width', 'fact-road-width', ['label_en' => 'Road width', 'label_bn' => 'রাস্তার প্রশস্ততা', 'value_en' => "25' / 30' / 40' / 60'", 'value_bn' => "২৫' / ৩০' / ৪০' / ৬০'", 'order_rank' => 8]],
    ];

    $fact_slugs = [];
    foreach ($facts as $fact) {
        south_city_upsert_seed_post('southcity_fact', $fact[0], $fact[1], $fact[2]);
        $fact_slugs[] = $fact[1];
    }
    south_city_prune_seed_posts('southcity_fact', $fact_slugs);

    $plots = [
        ['3 Katha', 'plot-3-katha', ['katha_en' => '3 Katha', 'katha_bn' => '৩ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 1]],
        ['5 Katha', 'plot-5-katha', ['katha_en' => '5 Katha', 'katha_bn' => '৫ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 2]],
        ['10 Katha', 'plot-10-katha', ['katha_en' => '10 Katha', 'katha_bn' => '১০ কাঠা', 'zone_en' => 'Exclusive Zone', 'zone_bn' => 'এক্সক্লুসিভ জোন', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 3]],
        ['20 Katha', 'plot-20-katha', ['katha_en' => '20 Katha', 'katha_bn' => '২০ কাঠা', 'zone_en' => 'Duplex Zone', 'zone_bn' => 'ডুপ্লেক্স জোন', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 4]],
        ['40 Katha', 'plot-40-katha', ['katha_en' => '40 Katha', 'katha_bn' => '৪০ কাঠা', 'zone_en' => '', 'zone_bn' => '', 'price_en' => '', 'price_bn' => '', 'booking_en' => 'Call for details', 'booking_bn' => 'বিস্তারিত জানতে কল করুন', 'installment_en' => 'Easy installments up to 5 years - or register instantly on full payment.', 'installment_bn' => '৫ বছর পর্যন্ত সহজ কিস্তি - অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।', 'order_rank' => 5]],
    ];

    $plot_slugs = [];
    foreach ($plots as $plot) {
        south_city_upsert_seed_post('southcity_plot', $plot[0], $plot[1], $plot[2]);
        $plot_slugs[] = $plot[1];
        $plot_post = get_page_by_path($plot[1], OBJECT, 'southcity_plot');
        if ($plot_post instanceof WP_Post) {
            foreach (['sqft_en', 'sqft_bn', 'dimensions_en', 'dimensions_bn'] as $retired_key) {
                delete_post_meta($plot_post->ID, $retired_key);
            }
        }
    }
    south_city_prune_seed_posts('southcity_plot', $plot_slugs);

    // Amenities grouped exactly as the brochure (p.2): core facilities,
    // modern infrastructure, and security & community.
    $amenities = [
        // --- World-Class Facilities ---
        ['School', 'amenity-school', 'core', ['icon' => 'school', 'label_en' => 'School', 'label_bn' => 'বিদ্যালয়', 'desc_en' => 'Two modern schools near every sector for quality education.', 'desc_bn' => 'মানসম্মত শিক্ষা নিশ্চিত করতে প্রতিটি সেক্টরে কাছাকাছি দুটি আধুনিক বিদ্যালয় রয়েছে।', 'order_rank' => 1]],
        ['Mosque / Prayer Hall', 'amenity-mosque', 'core', ['icon' => 'mosque', 'label_en' => 'Mosque / Prayer Hall', 'label_bn' => 'মসজিদ / উপাসনালয়', 'desc_en' => 'A well-planned mosque / prayer hall and community centre in every sector.', 'desc_bn' => 'দৈনন্দিন নামাজ ও সামাজিক সমাবেশের জন্য প্রতিটি সেক্টরে সুপরিকল্পিত মসজিদ/উপাসনালয় ও কমিউনিটি সেন্টার রয়েছে।', 'order_rank' => 2]],
        ['Health Centre', 'amenity-health-centre', 'core', ['icon' => 'health', 'label_en' => 'Health Centre', 'label_bn' => 'স্বাস্থ্য কেন্দ্র', 'desc_en' => 'Sector-based health centres for primary and emergency care.', 'desc_bn' => 'প্রাথমিক স্বাস্থ্যসেবা ও জরুরী সেবার জন্য খাতভিত্তিক স্বাস্থ্যকেন্দ্র।', 'order_rank' => 3]],
        ['Super Shop', 'amenity-super-shop', 'core', ['icon' => 'shop', 'label_en' => 'Super Shop', 'label_bn' => 'সুপার শপ', 'desc_en' => 'Modern retail stores and a super shop for every daily need within the area.', 'desc_bn' => 'এলাকার মধ্যে দৈনন্দিন সকল প্রয়োজনের জন্য আধুনিক খুচরা দোকান ও সুপারশপ।', 'order_rank' => 4]],
        ['Gymnasium', 'amenity-gymnasium', 'core', ['icon' => 'gym', 'label_en' => 'Gymnasium', 'label_bn' => 'জিমনেসিয়াম', 'desc_en' => 'A fully-equipped gym to maintain a healthy, active lifestyle.', 'desc_bn' => 'সুস্থ ও সক্রিয় জীবনধারা বজায় রাখার জন্য রয়েছে সম্পূর্ণ সুসজ্জিত জিমের সুবিধা।', 'order_rank' => 5]],
        ['Coffee Shop', 'amenity-coffee-shop', 'core', ['icon' => 'coffee', 'label_en' => 'Coffee Shop', 'label_bn' => 'কফি শপ', 'desc_en' => 'A pleasant coffee shop and lounge to spend relaxed time with family.', 'desc_bn' => 'পরিবারের সাথে আরাম ও সুন্দর সময় কাটানোর জন্য রয়েছে চমৎকার কফি শপ ও লাউঞ্জ।', 'order_rank' => 6]],
        ['Walking Trails', 'amenity-walking-trails', 'core', ['icon' => 'trail', 'label_en' => 'Walking Trails', 'label_bn' => 'হাঁটার পথ', 'desc_en' => 'Tree-lined walking trails and pedestrian streets for a calm, healthy life.', 'desc_bn' => 'সুস্থ ও শান্তিপূর্ণ জীবনের জন্য বৃক্ষশোভিত হাঁটার পথ এবং পথচারী চলাচলের রাস্তা।', 'order_rank' => 7]],
        ['Open Green Spaces', 'amenity-open-green-spaces', 'core', ['icon' => 'park', 'label_en' => 'Open Green Spaces', 'label_bn' => 'খোলা সবুজ স্থান', 'desc_en' => 'Large parks, gardens and open space for recreation, events and family time.', 'desc_bn' => 'বিনোদন, অনুষ্ঠান ও পারিবারিক সময় কাটানোর জন্য বড় পার্ক, বাগান এবং খোলা জায়গা।', 'order_rank' => 8]],
        ['Children Play Area', 'amenity-children-play-area', 'core', ['icon' => 'play', 'label_en' => 'Children Play Area', 'label_bn' => 'শিশুদের খেলার জায়গা', 'desc_en' => 'Safe, modern play areas for children to learn, play and grow.', 'desc_bn' => 'শিশুদের আনন্দের সাথে শিখতে, খেলতে ও বেড়ে ওঠার জন্য নিরাপদ এবং আধুনিক খেলার জায়গা।', 'order_rank' => 9]],
        // --- Modern Infrastructure ---
        ['25 ft internal roads', 'infra-25ft-roads', 'infrastructure', ['icon' => 'road', 'label_en' => "25' internal roads", 'label_bn' => '২৫ ফুট অভ্যন্তরীণ রাস্তা', 'order_rank' => 10]],
        ['30 ft side roads', 'infra-30ft-roads', 'infrastructure', ['icon' => 'road', 'label_en' => "30' side roads", 'label_bn' => '৩০ ফুট পার্শ্ববর্তী রাস্তা', 'order_rank' => 11]],
        ['40 ft connecting road', 'infra-40ft-road', 'infrastructure', ['icon' => 'road', 'label_en' => "40' connecting road", 'label_bn' => '৪০ ফুট কানেক্টিং রোড', 'order_rank' => 12]],
        ['60 ft main boulevard', 'infra-60ft-boulevard', 'infrastructure', ['icon' => 'road', 'label_en' => "60' main boulevard", 'label_bn' => '৬০ ফুট প্রধান বুলেভার্ড', 'order_rank' => 13]],
        ['Underground utility planning', 'infra-underground-utility', 'infrastructure', ['icon' => 'utility', 'label_en' => 'Underground utility planning', 'label_bn' => 'ভূগর্ভস্থ ইউটিলিটি পরিকল্পনা', 'order_rank' => 14]],
        ['Modern drainage system', 'infra-drainage', 'infrastructure', ['icon' => 'water', 'label_en' => 'Modern drainage system', 'label_bn' => 'আধুনিক নিষ্কাশন ব্যবস্থা', 'order_rank' => 15]],
        ['Street lighting', 'infra-street-lighting', 'infrastructure', ['icon' => 'sparkle', 'label_en' => 'Street lighting', 'label_bn' => 'রাস্তার আলো', 'order_rank' => 16]],
        ['Wide footpaths', 'infra-wide-footpaths', 'infrastructure', ['icon' => 'route', 'label_en' => 'Wide footpaths', 'label_bn' => 'চওড়া ফুটপাথ', 'order_rank' => 17]],
        ['Planned green buffer', 'infra-green-buffer', 'infrastructure', ['icon' => 'leaf', 'label_en' => 'Planned green buffer', 'label_bn' => 'পরিকল্পিত সবুজ বাফার', 'order_rank' => 18]],
        // --- Security & Community ---
        ['Planned residential environment', 'sec-planned-environment', 'security', ['icon' => 'map', 'label_en' => 'Planned residential environment', 'label_bn' => 'পরিকল্পিত আবাসিক পরিবেশ', 'order_rank' => 19]],
        ['Organized sector-based development', 'sec-sector-development', 'security', ['icon' => 'building', 'label_en' => 'Organized sector-based development', 'label_bn' => 'সংগঠিত খাত-ভিত্তিক উন্নয়ন', 'order_rank' => 20]],
        ['Safe internal road network', 'sec-safe-road-network', 'security', ['icon' => 'route', 'label_en' => 'Safe internal road network', 'label_bn' => 'নিরাপদ অভ্যন্তরীণ সড়ক নেটওয়ার্ক', 'order_rank' => 21]],
        ['24/7 security', 'sec-24-7-security', 'security', ['icon' => 'security', 'label_en' => '24/7 security', 'label_bn' => '২৪/৭ নিরাপত্তা ব্যবস্থা', 'order_rank' => 22]],
        ['Community-based zoning', 'sec-community-zoning', 'security', ['icon' => 'family', 'label_en' => 'Community-based zoning', 'label_bn' => 'সম্প্রদায় ভিত্তিক এলাকা', 'order_rank' => 23]],
        ['Family-friendly open space', 'sec-family-open-space', 'security', ['icon' => 'park', 'label_en' => 'Family-friendly open space', 'label_bn' => 'পরিবার-বান্ধব খোলা জায়গা', 'order_rank' => 24]],
    ];

    $amenity_slugs = [];
    foreach ($amenities as $amenity) {
        south_city_upsert_seed_post('southcity_amenity', $amenity[0], $amenity[1], $amenity[3], '', [$amenity[2]], 'southcity_amenity_group');
        $amenity_slugs[] = $amenity[1];
    }
    south_city_prune_seed_posts('southcity_amenity', $amenity_slugs);

    $landmarks = [
        ['Connectivity', 'landmark-connectivity', ['label_en' => 'Connectivity', 'label_bn' => 'যোগাযোগ', 'image_key' => 'connectivity', 'items' => [
            ['name_en' => 'Eastern Bypass Road', 'name_bn' => 'ইস্টার্ন বাইপাস রোড', 'note_en' => 'Adjacent west boundary', 'note_bn' => 'সংলগ্ন পশ্চিম সীমানা'],
            ['name_en' => 'Dhaka-Mawa Expressway', 'name_bn' => 'ঢাকা-মাওয়া এক্সপ্রেসওয়ে', 'note_en' => '2 minutes by road', 'note_bn' => 'সড়কপথে ২ মিনিট'],
            ['name_en' => 'Padma Bridge', 'name_bn' => 'পদ্মা সেতু', 'note_en' => 'Easy access via expressway', 'note_bn' => 'এক্সপ্রেসওয়ে দিয়ে সহজ যোগাযোগ'],
        ], 'order_rank' => 1]],
        ['Education', 'landmark-education', ['label_en' => 'Education', 'label_bn' => 'শিক্ষা', 'image_key' => 'education', 'items' => [
            ['name_en' => 'On-site school zone', 'name_bn' => 'প্রকল্পের নিজস্ব স্কুল জোন', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Two modern schools per sector', 'name_bn' => 'প্রতিটি সেক্টরে দুটি আধুনিক বিদ্যালয়', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Keraniganj schools & colleges', 'name_bn' => 'কেরানীগঞ্জের স্কুল ও কলেজ', 'note_en' => 'Nearby', 'note_bn' => 'নিকটেই'],
        ], 'order_rank' => 2]],
        ['Health', 'landmark-health', ['label_en' => 'Health', 'label_bn' => 'স্বাস্থ্যসেবা', 'image_key' => 'health', 'items' => [
            ['name_en' => 'On-site health centre', 'name_bn' => 'প্রকল্পের নিজস্ব হেলথ সেন্টার', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'A health centre in every sector', 'name_bn' => 'প্রতিটি সেক্টরে স্বাস্থ্যকেন্দ্র', 'note_en' => 'Primary care & emergency support', 'note_bn' => 'প্রাথমিক স্বাস্থ্যসেবা ও জরুরি সহায়তা'],
            ['name_en' => 'Dhaka hospitals', 'name_bn' => 'ঢাকার হাসপাতালসমূহ', 'note_en' => 'Short drive via expressway', 'note_bn' => 'এক্সপ্রেসওয়ে দিয়ে অল্প সময়ে'],
        ], 'order_rank' => 3]],
        ['Daily Needs', 'landmark-daily-needs', ['label_en' => 'Daily Needs', 'label_bn' => 'দৈনন্দিন প্রয়োজন', 'image_key' => 'daily', 'items' => [
            ['name_en' => 'On-site super shop & commercial area', 'name_bn' => 'প্রকল্পের সুপার শপ ও বাণিজ্যিক এলাকা', 'note_en' => 'Planned within South City', 'note_bn' => 'সাউথ সিটির ভেতরে পরিকল্পিত'],
            ['name_en' => 'Local bazaars', 'name_bn' => 'স্থানীয় বাজার', 'note_en' => 'Walking distance', 'note_bn' => 'হাঁটা দূরত্বে'],
            ['name_en' => 'Banks & services, Keraniganj', 'name_bn' => 'ব্যাংক ও সেবা, কেরানীগঞ্জ', 'note_en' => 'Nearby', 'note_bn' => 'নিকটেই'],
        ], 'order_rank' => 4]],
    ];

    $landmark_slugs = [];
    foreach ($landmarks as $landmark) {
        south_city_upsert_seed_post('southcity_landmark', $landmark[0], $landmark[1], $landmark[2]);
        $landmark_slugs[] = $landmark[1];
    }
    south_city_prune_seed_posts('southcity_landmark', $landmark_slugs);

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

    $gallery_slugs = [];
    foreach ($gallery_captions as $index => $caption) {
        $number = $index + 1;
        $gallery_slug = 'gallery-' . $number;
        south_city_upsert_seed_post('southcity_gallery', $caption[0], $gallery_slug, [
            'image' => SOUTH_CITY_THEME_URI . '/assets/img/gallery-' . $number . '.webp',
            'caption_en' => $caption[0],
            'caption_bn' => $caption[1],
            'order_rank' => $number,
        ]);
        $gallery_slugs[] = $gallery_slug;
    }
    south_city_prune_seed_posts('southcity_gallery', $gallery_slugs);

    update_option('south_city_default_content_seeded', 1);
    update_option('south_city_seed_version', SOUTH_CITY_SEED_VERSION);
}

/**
 * Trash published seed posts of a type whose slug is not in the current set.
 *
 * Used when a seed revision restructures a whole CPT (e.g. amenities were
 * re-grouped and re-slugged for the brochure alignment).
 */
function south_city_prune_seed_posts(string $post_type, array $keep_slugs): void
{
    $posts = get_posts([
        'post_type'      => $post_type,
        'post_status'    => ['publish', 'draft', 'pending'],
        'numberposts'    => -1,
        'fields'         => 'ids',
    ]);

    foreach ($posts as $post_id) {
        if (! in_array(get_post_field('post_name', $post_id), $keep_slugs, true)) {
            wp_trash_post($post_id);
        }
    }
}

/**
 * Ensure the amenity group terms exist with readable names.
 */
function south_city_register_amenity_group_terms(): void
{
    if (! taxonomy_exists('southcity_amenity_group')) {
        return;
    }

    $groups = [
        'core'           => 'World-Class Facilities',
        'infrastructure' => 'Modern Infrastructure',
        'security'       => 'Security & Community',
    ];

    foreach ($groups as $slug => $name) {
        if (! term_exists($slug, 'southcity_amenity_group')) {
            wp_insert_term($name, 'southcity_amenity_group', ['slug' => $slug]);
        }
    }
}
