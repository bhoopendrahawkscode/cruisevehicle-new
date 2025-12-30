<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {

        define("USER_MANAGEMENT", 'User Management');
        define("SUB_ADMIN_MANAGEMENT", 'Sub Admin Management');
        define("SERVICE_PROVIDER_MANAGEMENT", 'Service Provider Management');
        define("NOTIFICATION_MANAGEMENT", 'Notification Management');
        define("GOOD_NEWS_MANAGEMENT", 'Good News Management');
        define("QUESTIONS_MANAGEMENT", 'Mood Questions Management');
        define("QUOTES_MANAGEMENT", 'Quotes Management');
        define("EMAIL_TEMPLATE_MANAGER", 'Email Template Management');
        define("CMS_MANAGER", 'CMS Manager');
        define("FAQ_MANAGEMENT", 'FAQ Manager');
        define("SETTING_MANAGEMENT", 'Settings Management');
        define("TRANSACTIONS_MANAGEMENT", 'Transaction Management');
        define("POSTS_MANAGEMENT", 'Posts Management');
        define("REPORTS_COMMUNITIES_MANAGEMENT", 'Report Communities Management');
        define("REPORTS_POSTS_MANAGEMENT", 'Report Posts Management');
        define("REPORTS_COMMENTS_MANAGEMENT", 'Report Comments Management');
        define("VIDEOS_MANAGEMENT", 'Videos Management');
        define("AUDIOS_MANAGEMENT", 'Audios Management');
        define("COMMUNITIES_MANAGEMENT", 'Communities Management');
        define("CONTACT_MANAGEMENT", 'Help & Support');
        define("SUPPORT_MANAGEMENT", 'Support Management');
        define("REPORT_MANAGEMENT", 'Report Management');

        define("VEHICLE_MANAGEMENT", 'Vehicle Management');
        define("FUEL_USED_MANAGEMENT", 'Fuel Used Management');
        define("TRANSMISSION_TYPE_MANAGEMENT", 'Transmission Type Management');
        define("ENGINE_CAPACITY_MANAGEMENT", 'Engine Capacity Management');
        define("MODEL_MANAGEMENT", 'Model Management');
        define("BRAND_MANAGEMENT", 'Brand Management');

        define("INSURANCE_MANAGEMENT", 'Insurance Company Management');
        define("INSURANCE_QUOTE_MANAGEMENT", 'Insurance Quote Request Management');
        define("INSURANCE_QUOTE_REPLY", 'Insurance Quote Reply Management');
        define("INSURANCE_QUOTE_CONFIRMATION_MANAGEMENT", 'Insurance Quote Confirmation Management');
        define("INSURANCE_COVER_TYPE_MANAGEMENT", 'Insurance Cover Type Management');
        define("INSURANCE_COVER_PERIOD_MANAGEMENT", 'Insurance Cover Period Management');
        define("INSURANCE_RENEWAL", 'Insurance Renewal Management');

        define("CHANGE_STATUS", 'Change Status');
        define("ROLE", 'Role');
        define('TESTIMONIALS_MANAGEMENT','Testimonial Manager');
        define('COUPON_MANAGEMENT','COUPON Management');
        define('SYSTEM_ACTIVITY_LOGS','System Activity Logs');
        define('GALLERY_MANAGEMENT','Gallery Management');
        define('MEDIA_MANAGEMENT','Media Management');
        define('PAGE_MANAGEMENT','Page Management');
        define('BANNER_MANAGEMENT','Banner Management');
        define('PORTFOLIO_MANAGEMENT','Portfolio Management');
        define('META_MANAGEMENT','Meta Management');


        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [


            $this->newPermission('List', 'USER_LIST', USER_MANAGEMENT),
            $this->newPermission('Add', 'USER_ADD', USER_MANAGEMENT),
            $this->newPermission('Edit', 'USER_EDIT', USER_MANAGEMENT),
            $this->newPermission('View', 'USER_VIEW', USER_MANAGEMENT),
            $this->newPermission('Delete', 'USER_DELETE', USER_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'USER_CHANGE_STATUS', USER_MANAGEMENT),

            $this->newPermission('List', 'BRAND_LIST', BRAND_MANAGEMENT),
            $this->newPermission('Add', 'BRAND_ADD', BRAND_MANAGEMENT),
            $this->newPermission('Edit', 'BRAND_EDIT', BRAND_MANAGEMENT),
            $this->newPermission('Delete', 'BRAND_DELETE', BRAND_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'BRAND_CHANGE_STATUS', BRAND_MANAGEMENT),

            $this->newPermission('List', 'MODEL_LIST', MODEL_MANAGEMENT),
            $this->newPermission('Add', 'MODEL_ADD', MODEL_MANAGEMENT),
            $this->newPermission('Edit', 'MODEL_EDIT', MODEL_MANAGEMENT),
            $this->newPermission('Delete', 'MODEL_DELETE', MODEL_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'MODEL_CHANGE_STATUS', MODEL_MANAGEMENT),

            $this->newPermission('List', 'FUEL_USED_LIST', FUEL_USED_MANAGEMENT),
            $this->newPermission('Add', 'FUEL_USED_ADD', FUEL_USED_MANAGEMENT),
            $this->newPermission('Edit', 'FUEL_USED_EDIT', FUEL_USED_MANAGEMENT),
            $this->newPermission('Delete', 'FUEL_USED_DELETE', FUEL_USED_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'FUEL_USED_CHANGE_STATUS', FUEL_USED_MANAGEMENT),

            $this->newPermission('List', 'TRANSMISSION_TYPE_LIST', TRANSMISSION_TYPE_MANAGEMENT),
            $this->newPermission('Add', 'TRANSMISSION_TYPE_ADD', TRANSMISSION_TYPE_MANAGEMENT),
            $this->newPermission('Edit', 'TRANSMISSION_TYPE_EDIT', TRANSMISSION_TYPE_MANAGEMENT),
            $this->newPermission('View', 'TRANSMISSION_TYPE_VIEW', TRANSMISSION_TYPE_MANAGEMENT),
            $this->newPermission('Delete', 'TRANSMISSION_TYPE_DELETE', TRANSMISSION_TYPE_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'TRANSMISSION_TYPE_CHANGE_STATUS', TRANSMISSION_TYPE_MANAGEMENT),

            $this->newPermission('List', 'ENGINE_CAPACITY_LIST', ENGINE_CAPACITY_MANAGEMENT),
            $this->newPermission('Add', 'ENGINE_CAPACITY_ADD', ENGINE_CAPACITY_MANAGEMENT),
            $this->newPermission('Edit', 'ENGINE_CAPACITY_EDIT', ENGINE_CAPACITY_MANAGEMENT),
            $this->newPermission('Delete', 'ENGINE_CAPACITY_DELETE', ENGINE_CAPACITY_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'ENGINE_CAPACITY_CHANGE_STATUS', ENGINE_CAPACITY_MANAGEMENT),

            $this->newPermission('List', 'VEHICLE_LIST', VEHICLE_MANAGEMENT),
            $this->newPermission('Add', 'VEHICLE_ADD', VEHICLE_MANAGEMENT),
            $this->newPermission('Edit', 'VEHICLE_EDIT', VEHICLE_MANAGEMENT),
            $this->newPermission('View', 'VEHICLE_VIEW', VEHICLE_MANAGEMENT),
            $this->newPermission('Delete', 'VEHICLE_DELETE', VEHICLE_MANAGEMENT),


            $this->newPermission('List', 'INSURANCE_LIST', INSURANCE_MANAGEMENT),
            $this->newPermission('Add', 'INSURANCE_ADD', INSURANCE_MANAGEMENT),
            $this->newPermission('Edit', 'INSURANCE_EDIT', INSURANCE_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'INSURANCE_CHANGE_STATUS', INSURANCE_MANAGEMENT),

            $this->newPermission('List', 'INSURANCE_QUOTE_LIST', INSURANCE_QUOTE_MANAGEMENT),
            $this->newPermission('View', 'INSURANCE_QUOTE_VIEW', INSURANCE_QUOTE_MANAGEMENT),

            $this->newPermission('List', 'INSURANCE_QUOTE_REPLY_LIST', INSURANCE_QUOTE_REPLY),
            $this->newPermission('Edit', 'INSURANCE_QUOTE_REPLY_EDIT', INSURANCE_QUOTE_REPLY),
            $this->newPermission('View', 'INSURANCE_QUOTE_REPLY_VIEW', INSURANCE_QUOTE_REPLY),
            $this->newPermission('Reply', 'INSURANCE_QUOTE_REPLY_USER', INSURANCE_QUOTE_REPLY),

            $this->newPermission('List', 'INSURANCE_QUOTE_CONFIRMATION_LIST', INSURANCE_QUOTE_CONFIRMATION_MANAGEMENT),
            $this->newPermission('View', 'INSURANCE_QUOTE_CONFIRMATION_VIEW', INSURANCE_QUOTE_CONFIRMATION_MANAGEMENT),

            $this->newPermission('List', 'INSURANCE_COVER_TYPE_LIST', INSURANCE_COVER_TYPE_MANAGEMENT),
            $this->newPermission('View', 'INSURANCE_COVER_TYPE_VIEW', INSURANCE_COVER_TYPE_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'INSURANCE_COVER_TYPE_STATUS', INSURANCE_COVER_TYPE_MANAGEMENT),

            $this->newPermission('List', 'INSURANCE_COVER_PERIOD_LIST', INSURANCE_COVER_PERIOD_MANAGEMENT),
            $this->newPermission('View', 'INSURANCE_COVER_PERIOD_VIEW', INSURANCE_COVER_PERIOD_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'INSURANCE_COVER_PERIOD_STATUS', INSURANCE_COVER_PERIOD_MANAGEMENT),


            $this->newPermission('List', 'INSURANCE_RENEWAL_LIST', INSURANCE_RENEWAL),
            $this->newPermission('Add',  'INSURANCE_RENEWAL_ADD', INSURANCE_RENEWAL),
            $this->newPermission('Edit', 'INSURANCE_RENEWAL_EDIT', INSURANCE_RENEWAL),

            $this->newPermission('List', 'SERVICE_PROVIDER_LIST', SERVICE_PROVIDER_MANAGEMENT),
            $this->newPermission('Add', 'SERVICE_PROVIDER_ADD', SERVICE_PROVIDER_MANAGEMENT),
            $this->newPermission('Edit', 'SERVICE_PROVIDER_EDIT', SERVICE_PROVIDER_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'SERVICE_PROVIDER_CHANGE_STATUS', SERVICE_PROVIDER_MANAGEMENT),


            $this->newPermission('List', 'CMS_MANGER_LIST', CMS_MANAGER),
            $this->newPermission('Edit', 'CMS_MANGER_EDIT', CMS_MANAGER),
            $this->newPermission('List', 'EMAIL_TEMPLATE_MANGER_LIST', EMAIL_TEMPLATE_MANAGER),
            $this->newPermission('Edit', 'EMAIL_TEMPLATE_MANGER_EDIT', EMAIL_TEMPLATE_MANAGER),

            $this->newPermission('List', 'CONTACT_LIST', CONTACT_MANAGEMENT),
            $this->newPermission('View', 'CONTACT_VIEW', CONTACT_MANAGEMENT),

            $this->newPermission('History', 'NOTIFICATION_LIST', NOTIFICATION_MANAGEMENT),
            $this->newPermission('Send', 'NOTIFICATION_SEND', NOTIFICATION_MANAGEMENT),

            $this->newPermission('List', 'SETTING_MANAGEMENT_LIST', SETTING_MANAGEMENT),
            $this->newPermission('Edit', 'SETTING_MANAGEMENT_EDIT', SETTING_MANAGEMENT),

            $this->newPermission('List', 'SUB_ADMIN_LIST', SUB_ADMIN_MANAGEMENT),
            $this->newPermission('Add', 'SUB_ADMIN_ADD', SUB_ADMIN_MANAGEMENT),
            $this->newPermission('Edit', 'SUB_ADMIN_EDIT', SUB_ADMIN_MANAGEMENT),
            $this->newPermission('View', 'SUB_ADMIN_VIEW', SUB_ADMIN_MANAGEMENT),
            $this->newPermission('Delete', 'SUB_ADMIN_DELETE', SUB_ADMIN_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'SUB_ADMIN_CHANGE_STATUS', SUB_ADMIN_MANAGEMENT),

            $this->newPermission('List', 'FAQ_LIST', FAQ_MANAGEMENT),
            $this->newPermission('Add', 'FAQ_ADD', FAQ_MANAGEMENT),
            $this->newPermission('Edit', 'FAQ_EDIT', FAQ_MANAGEMENT),
            $this->newPermission('Delete', 'FAQ_DELETE', FAQ_MANAGEMENT),
            $this->newPermission(CHANGE_STATUS, 'FAQ_CHANGE_STATUS', FAQ_MANAGEMENT),

        ];

        DB::table('permissions')->insert($data);


    }

    public function newPermission($name, $action = '', $category = '')
    {
        return array(
            'name' => $name,
            'action' => $action,
            'group_name' => $category
        );
    }
}
