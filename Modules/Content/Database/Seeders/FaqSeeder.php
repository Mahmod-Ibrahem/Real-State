<?php

namespace Modules\Content\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Content\Models\Faq;

class FaqSeeder extends Seeder
{
    /** Demo questions in English and Arabic (question and answer are translatable). */
    private const FAQS = [
        [
            'question' => ['en' => 'How do I find a property on Qeima?', 'ar' => 'كيف أجد عقارًا على قيمة؟'],
            'answer' => [
                'en' => 'Open Listings and filter by purpose (sale or rent), city, area, property type and price. Every result links to a page with photos, features and the location on the map.',
                'ar' => 'افتح صفحة العقارات واختر الغرض (بيع أو إيجار) والمدينة والمنطقة ونوع العقار والسعر. كل نتيجة تفتح صفحة بها الصور والمميزات والموقع على الخريطة.',
            ],
        ],
        [
            'question' => ['en' => 'Can foreigners buy property in Egypt?', 'ar' => 'هل يمكن للأجانب شراء عقار في مصر؟'],
            'answer' => [
                'en' => 'Yes. Foreigners can own up to two residential properties in Egypt, each up to 4,000 m², as long as they are not in restricted areas. Our brokers can walk you through the registration steps.',
                'ar' => 'نعم. يمكن للأجنبي تملك عقارين سكنيين في مصر بحد أقصى ٤٠٠٠ م² لكل منهما، بشرط ألا يكونا في مناطق محظورة. يمكن لوسطائنا شرح خطوات التسجيل لك.',
            ],
        ],
        [
            'question' => ['en' => 'How do I contact the owner or broker of a listing?', 'ar' => 'كيف أتواصل مع مالك العقار أو الوسيط؟'],
            'answer' => [
                'en' => 'Use the enquiry form on the property page. Your message reaches our team, and a broker gets back to you, usually within one working day.',
                'ar' => 'استخدم نموذج الاستفسار في صفحة العقار. تصل رسالتك إلى فريقنا ويتواصل معك أحد الوسطاء عادةً خلال يوم عمل واحد.',
            ],
        ],
        [
            'question' => ['en' => 'Are the prices on the site final?', 'ar' => 'هل الأسعار المعروضة نهائية؟'],
            'answer' => [
                'en' => 'Prices are the asking prices set by the owner, in Egyptian pounds. Most sellers are open to negotiation; your broker can advise on a fair offer.',
                'ar' => 'الأسعار هي الأسعار المطلوبة من المالك بالجنيه المصري. أغلب البائعين يقبلون التفاوض، ويمكن للوسيط مساعدتك في تقديم عرض مناسب.',
            ],
        ],
        [
            'question' => ['en' => 'How can I list my property for sale or rent?', 'ar' => 'كيف أعرض عقاري للبيع أو الإيجار؟'],
            'answer' => [
                'en' => 'Go to Sell and send us your property details. A broker contacts you to arrange photos and a visit, then publishes the listing.',
                'ar' => 'ادخل على صفحة "بيع" وأرسل لنا بيانات عقارك. سيتواصل معك أحد الوسطاء لترتيب التصوير والمعاينة ثم ينشر الإعلان.',
            ],
        ],
        [
            'question' => ['en' => 'Do you charge buyers or tenants a fee?', 'ar' => 'هل تحصلون على عمولة من المشتري أو المستأجر؟'],
            'answer' => [
                'en' => 'Browsing and contacting brokers is free. Any commission is agreed with your broker in writing before a deal is signed.',
                'ar' => 'التصفح والتواصل مع الوسطاء مجاني. يتم الاتفاق على أي عمولة كتابيًا مع الوسيط قبل توقيع العقد.',
            ],
        ],
        [
            'question' => ['en' => 'Can I save properties to compare later?', 'ar' => 'هل يمكنني حفظ العقارات للمقارنة لاحقًا؟'],
            'answer' => [
                'en' => 'Yes. Tap the heart on any property to add it to your favourites; they stay on this device even without an account.',
                'ar' => 'نعم. اضغط على أيقونة القلب في أي عقار لإضافته إلى المفضلة، وتبقى محفوظة على هذا الجهاز حتى بدون حساب.',
            ],
        ],
        [
            'question' => ['en' => 'What documents should I check before buying?', 'ar' => 'ما المستندات التي يجب مراجعتها قبل الشراء؟'],
            'answer' => [
                'en' => 'Ask for the title deed or the original sale contract chain, the building permit, and proof that utilities and maintenance fees are paid. A lawyer should review them before you sign.',
                'ar' => 'اطلب سند الملكية أو تسلسل عقود البيع الأصلية، ورخصة البناء، وما يثبت سداد المرافق ورسوم الصيانة. يُفضل أن يراجعها محامٍ قبل التوقيع.',
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::FAQS as $faq) {
            Faq::create($faq);
        }
    }
}
