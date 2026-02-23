<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'FutureBooks',
                'type' => 'text',
                'group' => 'general',
                'display_name' => 'Site Name',
                'instructions' => 'The name of your website.'
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Your Gateway to Digital Knowledge',
                'type' => 'text',
                'group' => 'general',
                'display_name' => 'Site Tagline',
                'instructions' => 'A short description of your site.'
            ],
            [
                'key' => 'site_logo_light',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'display_name' => 'Site Logo (Light Mode)',
                'instructions' => 'Upload a logo for light backgrounds.'
            ],
            [
                'key' => 'site_logo_dark',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'display_name' => 'Site Logo (Dark Mode)',
                'instructions' => 'Upload a logo for dark backgrounds.'
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'display_name' => 'Favicon',
                'instructions' => 'The small icon in the browser tab.'
            ],
            [
                'key' => 'default_currency',
                'value' => 'BDT',
                'type' => 'text',
                'group' => 'general',
                'display_name' => 'Default Currency',
                'instructions' => 'Example: BDT, USD'
            ],
            [
                'key' => 'currency_symbol',
                'value' => '৳',
                'type' => 'text',
                'group' => 'general',
                'display_name' => 'Currency Symbol',
                'instructions' => 'Example: ৳, $'
            ],
            [
                'key' => 'footer_copyright',
                'value' => 'FutureBooks. All rights reserved.',
                'type' => 'text',
                'group' => 'general',
                'display_name' => 'Footer Copyright',
                'instructions' => 'Copyright text in the footer.'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'display_name' => 'Maintenance Mode',
                'instructions' => 'Toggle maintenance mode on/off.'
            ],

            // SEO Settings
            [
                'key' => 'default_meta_title',
                'value' => 'FutureBooks - Best Digital Book Store',
                'type' => 'text',
                'group' => 'seo',
                'display_name' => 'Default Meta Title',
                'instructions' => 'The title tag for your homepage.'
            ],
            [
                'key' => 'default_meta_description',
                'value' => 'Discover and buy the best digital books and resources.',
                'type' => 'textarea',
                'group' => 'seo',
                'display_name' => 'Default Meta Description',
                'instructions' => 'The meta description tag.'
            ],
            [
                'key' => 'default_meta_keywords',
                'value' => 'ebooks, digital books, pdf, study materials',
                'type' => 'text',
                'group' => 'seo',
                'display_name' => 'Default Meta Keywords',
                'instructions' => 'Comma-separated keywords.'
            ],
            [
                'key' => 'google_analytics_id',
                'value' => null,
                'type' => 'text',
                'group' => 'seo',
                'display_name' => 'Google Analytics ID',
                'instructions' => 'Paste your tracking ID here (e.g., G-XXXXXXXXXX).'
            ],
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nAllow: /",
                'type' => 'textarea',
                'group' => 'seo',
                'display_name' => 'Robots.txt Content',
                'instructions' => 'Manage search engine crawling.'
            ],

            // Payment Settings
            [
                'key' => 'bkash_number',
                'value' => '01700000000',
                'type' => 'text',
                'group' => 'payment',
                'display_name' => 'bKash Personal Number',
                'instructions' => 'The number to receive bKash payments.'
            ],
            [
                'key' => 'payment_instructions',
                'value' => 'Please send money to our personal bKash/Nagad number and upload the transaction screenshot.',
                'type' => 'textarea',
                'group' => 'payment',
                'display_name' => 'Payment Instructions',
                'instructions' => 'Visible to users on checkout.'
            ],

            // Email Settings
            [
                'key' => 'smtp_host',
                'value' => 'sandbox.smtp.mailtrap.io',
                'type' => 'text',
                'group' => 'email',
                'display_name' => 'SMTP Host',
                'instructions' => 'Your mail server host.',
                'is_encrypted' => true
            ],
            [
                'key' => 'from_email',
                'value' => 'no-reply@futurebooks.com',
                'type' => 'text',
                'group' => 'email',
                'display_name' => 'From Email',
                'instructions' => 'The email address used for outgoing mail.'
            ],

            // Download Settings
            [
                'key' => 'download_expiry_days',
                'value' => '30',
                'type' => 'text',
                'group' => 'download',
                'display_name' => 'Download Expiry (Days)',
                'instructions' => 'How many days a download link remains valid for paid orders.'
            ],
            [
                'key' => 'allow_guest_free_download',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'download',
                'display_name' => 'Allow Guest Free Download',
                'instructions' => 'If enabled, users can download free books without logging in.'
            ],
            [
                'key' => 'free_download_expiry_minutes',
                'value' => '30',
                'type' => 'text',
                'group' => 'download',
                'display_name' => 'Free Download Link Expiry (Minutes)',
                'instructions' => 'Minutes until a signed download link expires.'
            ],
            [
                'key' => 'free_download_limit',
                'value' => '5',
                'type' => 'text',
                'group' => 'download',
                'display_name' => 'Free Download Limit',
                'instructions' => 'Maximum number of times a free book can be downloaded per link.'
            ],
            [
                'key' => 'require_email_for_free',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'download',
                'display_name' => 'Require Email for Free Download',
                'instructions' => 'Force email collection if guest download is allowed.'
            ],

            // Store Settings
            [
                'key' => 'refund_policy',
                'value' => 'Digital products are non-refundable once downloaded.',
                'type' => 'textarea',
                'group' => 'store',
                'display_name' => 'Refund Policy',
                'instructions' => 'Content of the Refund Policy page.'
            ],

            // Security Settings
            [
                'key' => 'max_file_upload_size',
                'value' => '51200', // 50MB
                'type' => 'text',
                'group' => 'security',
                'display_name' => 'Max Library Upload Size (KB)',
                'instructions' => 'Limit in kilobytes.'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
