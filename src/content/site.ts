/**
 * Local site content — the single source of truth when Sanity is not
 * connected, and the shape mirror of the Sanity schemas (sanity/schemas).
 * Every visitor-facing string is a bilingual { en, bn } object.
 *
 * 🔴 PLACEHOLDERS pending owner confirmation (see spec §10) are marked below.
 */
import type { L10n } from '../i18n/ui';

export interface Counter {
  end: number | null; // numeric target for the count-up animation (null = static)
  display: L10n; // final text shown (supports Bangla numerals / non-numeric)
  label: L10n;
}

export const settings = {
  brand: 'South City',
  companyName: {
    en: 'South Dhaka Properties & Housing Ltd.',
    bn: 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
  } as L10n,
  // contact block below is taken from the 2026 brochure (back cover)
  phone: '+8801886175263',
  phoneDisplay: '01886-175263',
  whatsapp: '8801886175263', // wa.me format: no +, no spaces
  email: 'info@southdhaka.com',
  address: {
    en: 'Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000, Bangladesh',
    bn: 'রহমান ম্যানশন (৪র্থ তলা) ১৬১, মতিঝিল সি/এ, ঢাকা-১০০০, বাংলাদেশ',
  } as L10n,
  social: {
    facebook: 'https://www.facebook.com/', // 🔴 placeholder URL
    youtube: 'https://www.youtube.com/', // 🔴 placeholder URL
    linkedin: 'https://www.linkedin.com/', // 🔴 placeholder URL
  },
  brochureUrl: '/brochure/south-city-brochure.pdf',
  // 🔴 still approximate — the brochure's map page shows the Google Maps place
  // "South City – a project by South Dhaka Properties". Replace with the exact
  // plus-code or lat/lng for a precise pin.
  mapQuery: 'South City Sayedpur Keraniganj Dhaka',
  waText: {
    en: "Assalamu Alaikum, I'm interested in South City plots.",
    bn: 'আসসালামু আলাইকুম, আমি সাউথ সিটির প্লট সম্পর্কে জানতে আগ্রহী।',
  } as L10n,
};

export const waLink = (lang: 'en' | 'bn', text?: string) =>
  `https://wa.me/${settings.whatsapp}?text=${encodeURIComponent(text ?? settings.waText[lang])}`;

/* ------------------------------------------------------------------ hero */
export const hero = {
  // brochure tagline, verbatim
  headline: {
    en: 'Where Your Dreams Find Their Address',
    bn: 'যেখানে আপনার স্বপ্নেরা তার ঠিকানা খুঁজে পায়',
  } as L10n,
  subline: {
    en: 'A ~600-bigha planned township in Sayedpur Union — beside the Eastern Bypass, 5 minutes from the Dhaka–Mawa Expressway.',
    bn: 'সৈয়দপুর ইউনিয়নে প্রায় ৬০০ বিঘার পরিকল্পিত টাউনশিপ — ইস্টার্ন বাইপাস সংলগ্ন, ঢাকা–মাওয়া এক্সপ্রেসওয়ে থেকে ৫ মিনিটের দূরত্বে।',
  } as L10n,
  chips: [
    { en: 'Prime Location', bn: 'প্রাইম লোকেশন' },
    { en: 'Legal Security', bn: 'আইনি নিরাপত্তা' },
    { en: 'High Growth', bn: 'উচ্চ প্রবৃদ্ধি' },
    { en: 'Family Focused', bn: 'পরিবারবান্ধব' },
    { en: 'Premium Amenities', bn: 'প্রিমিয়াম সুবিধা' },
  ] as L10n[],
};

/* -------------------------------------------------------------- overview */
export const overview = {
  paragraph: {
    en: 'South City is a ~600-bigha planned residential & commercial land development on the banks of the Dhaleshwari river in Sayedpur Union, South Keraniganj — beside the Eastern Bypass and 5 minutes from the Dhaka–Mawa Expressway. Four thoughtfully laid-out sectors, 25–60 ft roads, and everyday facilities like schools, a central mosque and a health centre are growing into a complete township, while installment plans of up to 5 years keep ownership within reach.',
    bn: 'সাউথ সিটি — দক্ষিণ কেরানীগঞ্জের সৈয়দপুর ইউনিয়নে, ধলেশ্বরী নদীর তীরে প্রায় ৬০০ বিঘার একটি পরিকল্পিত আবাসিক ও বাণিজ্যিক ল্যান্ড ডেভেলপমেন্ট প্রকল্প — ইস্টার্ন বাইপাস সংলগ্ন, ঢাকা–মাওয়া এক্সপ্রেসওয়ে থেকে ৫ মিনিটের দূরত্বে। চারটি সুপরিকল্পিত সেক্টর, ২৫–৬০ ফুট প্রশস্ত রাস্তা, আর স্কুল, কেন্দ্রীয় মসজিদ ও হেলথ সেন্টারসহ দৈনন্দিন সব সুবিধা নিয়ে গড়ে উঠছে একটি পূর্ণাঙ্গ টাউনশিপ; সঙ্গে রয়েছে ৫ বছর পর্যন্ত সহজ কিস্তির সুবিধা।',
  } as L10n,
  counters: [
    {
      end: 600,
      display: { en: '600', bn: '৬০০' },
      label: { en: 'Bigha planned township', bn: 'বিঘা পরিকল্পিত প্রকল্প' },
    },
    {
      end: 4,
      display: { en: '4', bn: '৪' },
      label: { en: 'Residential & commercial sectors', bn: 'আবাসিক ও বাণিজ্যিক সেক্টর' },
    },
    {
      end: null,
      display: { en: '3–40', bn: '৩–৪০' },
      label: { en: 'Katha plot sizes', bn: 'কাঠা প্লট সাইজ' },
    },
    {
      end: 5,
      display: { en: '5', bn: '৫' },
      label: { en: 'Years of easy installments', bn: 'বছর পর্যন্ত সহজ কিস্তি' },
    },
  ] as Counter[],
};

/* ------------------------------------------------------------- trust row */
// 🔴 Spec rule: NO "RAJUK Approved" badge, NO REHAB logo. Verifiable items only.
export const trustBadges: { icon: string; label: L10n }[] = [
  { icon: 'license', label: { en: 'Valid Trade License', bn: 'বৈধ ট্রেড লাইসেন্স' } },
  { icon: 'document', label: { en: 'Transparent Documentation', bn: 'স্বচ্ছ ডকুমেন্টেশন' } },
  { icon: 'stamp', label: { en: 'Registration on Full Payment', bn: 'সম্পূর্ণ মূল্য পরিশোধে রেজিস্ট্রেশন' } },
  { icon: 'calendar', label: { en: 'Up to 5-Year Installments', bn: '৫ বছর পর্যন্ত কিস্তি সুবিধা' } },
  { icon: 'landcheck', label: { en: 'Own Purchased Land', bn: 'ক্রয়কৃত নিজস্ব জমি' } },
  { icon: 'river', label: { en: 'Dhaleshwari Riverside', bn: 'ধলেশ্বরী নদীতীরবর্তী' } },
];

/* ---------------------------------------------------------------- facts */
export const facts: { label: L10n; value: L10n }[] = [
  { label: { en: 'Project', bn: 'প্রকল্প' }, value: { en: 'South City', bn: 'সাউথ সিটি' } },
  {
    label: { en: 'Developer', bn: 'ডেভেলপার' },
    value: {
      en: 'South Dhaka Properties & Housing Ltd.',
      bn: 'সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড',
    },
  },
  {
    label: { en: 'Location', bn: 'অবস্থান' },
    value: {
      en: 'Sayedpur Union, South Keraniganj, Dhaka',
      bn: 'সৈয়দপুর ইউনিয়ন, দক্ষিণ কেরানীগঞ্জ, ঢাকা',
    },
  },
  {
    label: { en: 'Total area', bn: 'মোট আয়তন' },
    value: { en: '~600 Bigha (planned)', bn: 'প্রায় ৬০০ বিঘা (পরিকল্পিত)' },
  },
  { label: { en: 'Sectors', bn: 'সেক্টর' }, value: { en: '4 (A, B, C, D)', bn: '৪টি (এ, বি, সি, ডি)' } },
  {
    label: { en: 'Plot types', bn: 'প্লটের ধরন' },
    value: { en: 'Residential & Commercial', bn: 'আবাসিক ও বাণিজ্যিক' },
  },
  {
    label: { en: 'Plot sizes', bn: 'প্লট সাইজ' },
    value: {
      en: '3 · 5 · 10 · 20 · 30 · 40 Katha',
      bn: '৩ · ৫ · ১০ · ২০ · ৩০ · ৪০ কাঠা',
    },
  },
  {
    label: { en: 'Road width', bn: 'রাস্তার প্রশস্ততা' },
    value: {
      en: '25 / 30 / 40 / 60 ft (60 ft main boulevard)',
      bn: '২৫ / ৩০ / ৪০ / ৬০ ফুট (৬০ ফুট প্রধান সড়ক)',
    },
  },
  {
    label: { en: 'Payment', bn: 'পেমেন্ট' },
    value: {
      en: 'Registration on full payment · installments up to 5 years',
      bn: 'সম্পূর্ণ মূল্য পরিশোধে রেজিস্ট্রেশন · ৫ বছর পর্যন্ত কিস্তি',
    },
  },
];

/* ------------------------------------------------------------ master plan */
export interface Hotspot {
  id: string;
  x: number; // % from left
  y: number; // % from top
  name: L10n;
  desc: L10n;
}

// x/y are % of src/assets/img/masterplan.webp (the brochure sector-layout
// illustration) — re-measure them if that image is ever replaced.
export const hotspots: Hotspot[] = [
  {
    id: 'sector-a', x: 28.5, y: 15.5,
    name: { en: 'Sector A', bn: 'সেক্টর এ' },
    desc: {
      en: 'North-west residential sector, on 25 ft internal roads and the 40 ft collector road.',
      bn: 'উত্তর-পশ্চিমের আবাসিক সেক্টর — ২৫ ফুট অভ্যন্তরীণ রাস্তা ও ৪০ ফুট কালেক্টর রোডের ওপর।',
    },
  },
  {
    id: 'sector-b', x: 68, y: 15,
    name: { en: 'Sector B', bn: 'সেক্টর বি' },
    desc: {
      en: 'North-east residential sector, beside the central mosque and the health centre.',
      bn: 'উত্তর-পূর্বের আবাসিক সেক্টর — কেন্দ্রীয় মসজিদ ও হেলথ সেন্টারের পাশে।',
    },
  },
  {
    id: 'sector-c', x: 15.5, y: 62,
    name: { en: 'Sector C', bn: 'সেক্টর সি' },
    desc: {
      en: 'South-west sector — residential plots plus the commercial area on the 60 ft boulevard.',
      bn: 'দক্ষিণ-পশ্চিমের সেক্টর — আবাসিক প্লটের সাথে ৬০ ফুট প্রধান সড়কে বাণিজ্যিক এলাকা।',
    },
  },
  {
    id: 'sector-d', x: 80.5, y: 60.5,
    name: { en: 'Sector D', bn: 'সেক্টর ডি' },
    desc: {
      en: 'South-east residential sector, wrapped around the Coffee Plaza and the lake.',
      bn: 'দক্ষিণ-পূর্বের আবাসিক সেক্টর — কফি প্লাজা ও লেক ঘিরে।',
    },
  },
  {
    id: 'mosque', x: 48.5, y: 25,
    name: { en: 'Central Mosque', bn: 'কেন্দ্রীয় মসজিদ' },
    desc: {
      en: 'A landmark central mosque at the heart of the project.',
      bn: 'প্রকল্পের কেন্দ্রস্থলে দৃষ্টিনন্দন কেন্দ্রীয় মসজিদ।',
    },
  },
  {
    id: 'school', x: 20, y: 34.5,
    name: { en: 'School Zone', bn: 'স্কুল জোন' },
    desc: {
      en: 'Dedicated zone for schools and educational facilities, with its own playing field.',
      bn: 'স্কুল ও শিক্ষা প্রতিষ্ঠানের জন্য নির্ধারিত জোন — সঙ্গে নিজস্ব খেলার মাঠ।',
    },
  },
  {
    id: 'health', x: 75, y: 36,
    name: { en: 'Health Centre', bn: 'হেলথ সেন্টার' },
    desc: {
      en: 'On-site health centre for everyday medical needs.',
      bn: 'দৈনন্দিন চিকিৎসা সেবার জন্য প্রকল্পের নিজস্ব হেলথ সেন্টার।',
    },
  },
  {
    id: 'commercial', x: 25.5, y: 72.5,
    name: { en: 'Commercial Area', bn: 'বাণিজ্যিক এলাকা' },
    desc: {
      en: 'Shops, offices and a super shop on the 60 ft boulevard.',
      bn: '৬০ ফুট সড়কে দোকান, অফিস ও সুপার শপ।',
    },
  },
  {
    id: 'park', x: 48.5, y: 50,
    name: { en: 'Central Green Park', bn: 'কেন্দ্রীয় সবুজ পার্ক' },
    desc: {
      en: 'The project’s central green park with walking trails and a children’s play area.',
      bn: 'ওয়াকিং ট্রেইল ও শিশুদের খেলার জায়গাসহ প্রকল্পের কেন্দ্রীয় সবুজ পার্ক।',
    },
  },
  {
    id: 'coffee-plaza', x: 65, y: 70,
    name: { en: 'Coffee Plaza', bn: 'কফি প্লাজা' },
    desc: {
      en: 'Lakeside coffee shop and lounge in Sector D — the project’s leisure corner.',
      bn: 'সেক্টর ডি-তে লেকের পাশে কফি শপ ও লাউঞ্জ — প্রকল্পের অবসর কাটানোর জায়গা।',
    },
  },
];

/* ----------------------------------------------------------------- plots */
export interface Plot {
  id: string;
  katha: L10n;
  zone?: L10n; // brochure zone name, shown as a badge (10/20/30 Katha only)
  sqft: L10n;
  dimensions: L10n;
  price: L10n | null; // null → "Call for price"
  booking: L10n;
  installment: L10n;
}

// 🔴 prices unconfirmed → all "Call for price" (spec §10)
export const plots: Plot[] = [
  {
    id: 'katha-3',
    katha: { en: '3 Katha', bn: '৩ কাঠা' },
    sqft: { en: '2,160 sq ft', bn: '২,১৬০ বর্গফুট' },
    dimensions: { en: '≈ 36 ft × 60 ft', bn: '≈ ৩৬ ফুট × ৬০ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
  {
    id: 'katha-5',
    katha: { en: '5 Katha', bn: '৫ কাঠা' },
    sqft: { en: '3,600 sq ft', bn: '৩,৬০০ বর্গফুট' },
    dimensions: { en: '≈ 50 ft × 72 ft', bn: '≈ ৫০ ফুট × ৭২ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
  {
    id: 'katha-10',
    katha: { en: '10 Katha', bn: '১০ কাঠা' },
    zone: { en: 'Exclusive Zone', bn: 'এক্সক্লুসিভ জোন' },
    sqft: { en: '7,200 sq ft', bn: '৭,২০০ বর্গফুট' },
    dimensions: { en: '≈ 72 ft × 100 ft', bn: '≈ ৭২ ফুট × ১০০ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
  {
    id: 'katha-20',
    katha: { en: '20 Katha', bn: '২০ কাঠা' },
    zone: { en: 'Duplex Zone', bn: 'ডুপ্লেক্স জোন' },
    sqft: { en: '14,400 sq ft', bn: '১৪,৪০০ বর্গফুট' },
    dimensions: { en: '≈ 100 ft × 144 ft', bn: '≈ ১০০ ফুট × ১৪৪ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
  {
    id: 'katha-30',
    katha: { en: '30 Katha', bn: '৩০ কাঠা' },
    zone: { en: 'Villa Zone', bn: 'ভিলা জোন' },
    sqft: { en: '21,600 sq ft', bn: '২১,৬০০ বর্গফুট' },
    dimensions: { en: '≈ 120 ft × 180 ft', bn: '≈ ১২০ ফুট × ১৮০ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
  {
    id: 'katha-40',
    katha: { en: '40 Katha', bn: '৪০ কাঠা' },
    sqft: { en: '28,800 sq ft', bn: '২৮,৮০০ বর্গফুট' },
    dimensions: { en: '≈ 144 ft × 200 ft', bn: '≈ ১৪৪ ফুট × ২০০ ফুট' },
    price: null,
    booking: { en: 'Call for details', bn: 'বিস্তারিত জানতে কল করুন' },
    installment: {
      en: 'Easy installments up to 5 years — or register instantly on full payment.',
      bn: '৫ বছর পর্যন্ত সহজ কিস্তি — অথবা সম্পূর্ণ মূল্য পরিশোধে তাৎক্ষণিক রেজিস্ট্রেশন।',
    },
  },
];

/* -------------------------------------------------------------- location */
export const distances: { place: L10n; value: L10n }[] = [
  {
    place: { en: 'Dhaka–Mawa Expressway', bn: 'ঢাকা–মাওয়া এক্সপ্রেসওয়ে' },
    value: { en: '5 min drive', bn: '৫ মিনিটের দূরত্বে' },
  },
  {
    place: { en: 'Eastern Bypass', bn: 'ইস্টার্ন বাইপাস' },
    value: { en: 'Adjacent', bn: 'সংলগ্ন' },
  },
  { place: { en: 'Keraniganj', bn: 'কেরানীগঞ্জ' }, value: { en: '6 km', bn: '৬ কিমি' } },
  { place: { en: 'Padma Bridge', bn: 'পদ্মা সেতু' }, value: { en: '12 km', bn: '১২ কিমি' } },
  { place: { en: 'Dhaka city', bn: 'ঢাকা শহর' }, value: { en: '22 km', bn: '২২ কিমি' } },
  {
    place: { en: 'Hazrat Shahjalal Airport', bn: 'হযরত শাহজালাল বিমানবন্দর' },
    value: { en: '30–35 min', bn: '৩০–৩৫ মিনিট' },
  },
];

export const boundaries: { side: L10n; value: L10n }[] = [
  { side: { en: 'West', bn: 'পশ্চিমে' }, value: { en: 'KC Road', bn: 'কেসি রোড' } },
  {
    side: { en: 'East', bn: 'পূর্বে' },
    value: { en: 'Dhaleshwari River & embankment road', bn: 'ধলেশ্বরী নদী ও বাঁধ সড়ক' },
  },
  { side: { en: 'North', bn: 'উত্তরে' }, value: { en: 'Sayedpur Para', bn: 'সায়েদপুর পাড়া' } },
  { side: { en: 'South', bn: 'দক্ষিণে' }, value: { en: 'Nimtali', bn: 'নিমতলী' } },
];

/* ------------------------------------------------------------- landmarks */
export interface LandmarkTab {
  id: string;
  label: L10n;
  image: 'connectivity' | 'education' | 'health' | 'daily';
  items: { name: L10n; note: L10n }[];
}

export const landmarkTabs: LandmarkTab[] = [
  {
    id: 'connectivity',
    label: { en: 'Connectivity', bn: 'যোগাযোগ' },
    image: 'connectivity',
    items: [
      { name: { en: 'KC Road / Eastern Bypass', bn: 'কেসি রোড / ইস্টার্ন বাইপাস' }, note: { en: 'Adjacent (west boundary)', bn: 'সংলগ্ন (পশ্চিম সীমানা)' } },
      { name: { en: 'Dhaka–Mawa Expressway', bn: 'ঢাকা–মাওয়া এক্সপ্রেসওয়ে' }, note: { en: '5 minutes by road', bn: 'সড়কপথে ৫ মিনিট' } },
      { name: { en: 'Padma Bridge', bn: 'পদ্মা সেতু' }, note: { en: '12 km — direct link to the south', bn: '১২ কিমি — দক্ষিণাঞ্চলের সাথে সরাসরি যোগাযোগ' } },
      { name: { en: 'Bashundhara Riverview & Rajuk Jhilmil', bn: 'বসুন্ধরা রিভারভিউ ও রাজউক ঝিলমিল' }, note: { en: 'Neighbouring projects', bn: 'পার্শ্ববর্তী প্রকল্প' } },
      { name: { en: 'Dhaka city centre', bn: 'ঢাকা শহরকেন্দ্র' }, note: { en: '22 km', bn: '২২ কিমি' } },
    ],
  },
  {
    id: 'education',
    label: { en: 'Education', bn: 'শিক্ষা' },
    image: 'education',
    items: [
      { name: { en: 'On-site school zone', bn: 'প্রকল্পের নিজস্ব স্কুল জোন' }, note: { en: 'Planned within South City', bn: 'সাউথ সিটির ভেতরে পরিকল্পিত' } },
      { name: { en: 'Two modern schools per sector', bn: 'প্রতিটি সেক্টরে দুটি আধুনিক বিদ্যালয়' }, note: { en: 'Planned within South City', bn: 'সাউথ সিটির ভেতরে পরিকল্পিত' } },
      { name: { en: 'Keraniganj schools & colleges', bn: 'কেরানীগঞ্জের স্কুল ও কলেজ' }, note: { en: 'Within 6 km', bn: '৬ কিমির মধ্যে' } },
      { name: { en: 'Dhaka universities', bn: 'ঢাকার বিশ্ববিদ্যালয়সমূহ' }, note: { en: 'Via expressway, ~30 min', bn: 'এক্সপ্রেসওয়ে হয়ে ~৩০ মিনিট' } },
    ],
  },
  {
    id: 'health',
    label: { en: 'Health', bn: 'স্বাস্থ্যসেবা' },
    image: 'health',
    items: [
      { name: { en: 'On-site health centre', bn: 'প্রকল্পের নিজস্ব হেলথ সেন্টার' }, note: { en: 'Planned within South City', bn: 'সাউথ সিটির ভেতরে পরিকল্পিত' } },
      { name: { en: 'A health centre in every sector', bn: 'প্রতিটি সেক্টরে স্বাস্থ্যকেন্দ্র' }, note: { en: 'Primary care & emergency support', bn: 'প্রাথমিক স্বাস্থ্যসেবা ও জরুরি সহায়তা' } },
      { name: { en: 'Keraniganj health facilities', bn: 'কেরানীগঞ্জের স্বাস্থ্যকেন্দ্র' }, note: { en: 'Within 6 km', bn: '৬ কিমির মধ্যে' } },
      { name: { en: 'Dhaka hospitals', bn: 'ঢাকার হাসপাতালসমূহ' }, note: { en: '~30 min drive', bn: 'গাড়িতে ~৩০ মিনিট' } },
    ],
  },
  {
    id: 'daily',
    label: { en: 'Daily Needs', bn: 'দৈনন্দিন প্রয়োজন' },
    image: 'daily',
    items: [
      { name: { en: 'On-site super shop & commercial area', bn: 'প্রকল্পের সুপার শপ ও বাণিজ্যিক এলাকা' }, note: { en: 'Planned within South City', bn: 'সাউথ সিটির ভেতরে পরিকল্পিত' } },
      { name: { en: 'Local bazaars (Sayedpur / Nimtali)', bn: 'স্থানীয় বাজার (সায়েদপুর / নিমতলী)' }, note: { en: 'Walking distance', bn: 'হাঁটা দূরত্বে' } },
      { name: { en: 'Bazaars & shopping malls', bn: 'বাজার ও শপিং মল' }, note: { en: 'Within easy reach', bn: 'হাতের নাগালেই' } },
      { name: { en: 'Banks & services, Keraniganj', bn: 'ব্যাংক ও সেবা, কেরানীগঞ্জ' }, note: { en: 'Within 6 km', bn: '৬ কিমির মধ্যে' } },
    ],
  },
];

/* ------------------------------------------------------------- amenities */
export const amenities: { icon: string; label: L10n }[] = [
  { icon: 'school', label: { en: 'Schools', bn: 'স্কুল' } },
  { icon: 'mosque', label: { en: 'Mosques', bn: 'মসজিদ' } },
  { icon: 'health', label: { en: 'Health Centre', bn: 'হেলথ সেন্টার' } },
  { icon: 'shop', label: { en: 'Super Shop', bn: 'সুপার শপ' } },
  { icon: 'gym', label: { en: 'Gym', bn: 'জিম' } },
  { icon: 'coffee', label: { en: 'Coffee Shop', bn: 'কফি শপ' } },
  { icon: 'trail', label: { en: 'Walking Trails', bn: 'ওয়াকিং ট্রেইল' } },
  { icon: 'park', label: { en: 'Green Open Spaces', bn: 'সবুজ উন্মুক্ত স্থান' } },
  { icon: 'security', label: { en: '24/7 Security', bn: '২৪/৭ নিরাপত্তা' } },
  { icon: 'road', label: { en: 'Wide Roads', bn: 'প্রশস্ত রাস্তা' } },
  { icon: 'utility', label: { en: 'Backup Utilities', bn: 'ব্যাকআপ ইউটিলিটি' } },
  { icon: 'play', label: { en: "Children's Play Area", bn: 'শিশুদের খেলার জায়গা' } },
];

/* --------------------------------------------------------------- gallery */
// order matches src/assets/img/gallery-1…9.webp (all cropped from the brochure)
export const galleryCaptions: L10n[] = [
  { en: 'South City main gateway', bn: 'সাউথ সিটির প্রধান প্রবেশদ্বার' },
  { en: '60 ft main boulevard & super shop', bn: '৬০ ফুট প্রধান সড়ক ও সুপার শপ' },
  { en: 'Gateway & fountain at dusk', bn: 'সন্ধ্যায় প্রবেশদ্বার ও ফোয়ারা' },
  { en: 'Central green park & lake', bn: 'কেন্দ্রীয় সবুজ পার্ক ও লেক' },
  { en: 'Township aerial view', bn: 'টাউনশিপের আকাশচিত্র' },
  { en: 'Developed plots & internal road', bn: 'উন্নয়নকৃত প্লট ও অভ্যন্তরীণ রাস্তা' },
  { en: 'Project land — development in progress', bn: 'প্রকল্পের জমি — চলমান উন্নয়ন কাজ' },
  { en: 'Central mosque', bn: 'কেন্দ্রীয় মসজিদ' },
  { en: "Children's play area", bn: 'শিশুদের খেলার জায়গা' },
];

/* -------------------------------------------------- lead form / Web3Forms */
// 🔴 placeholder — create a free key at https://web3forms.com and set it here
// or via the PUBLIC_WEB3FORMS_KEY environment variable.
export const WEB3FORMS_KEY =
  import.meta.env.PUBLIC_WEB3FORMS_KEY ?? 'YOUR_WEB3FORMS_ACCESS_KEY';
