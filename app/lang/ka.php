<?php
/**
 * Georgian Language Translations
 */

return [
    // General
    'app_name' => 'ავტო დაზიანების შეფასება',
    'welcome' => 'კეთილი იყოს თქვენი მობრძანება',
    'home' => 'მთავარი',
    'dashboard' => 'მართვის პანელი',
    'save' => 'შენახვა',
    'cancel' => 'გაუქმება',
    'delete' => 'წაშლა',
    'edit' => 'რედაქტირება',
    'view' => 'ნახვა',
    'back' => 'უკან',
    'submit' => 'გაგზავნა',
    'search' => 'ძებნა',
    'filter' => 'ფილტრი',
    'actions' => 'მოქმედებები',
    'status' => 'სტატუსი',
    'date' => 'თარიღი',
    'download' => 'ჩამოტვირთვა',
    'yes' => 'დიახ',
    'no' => 'არა',
    'loading' => 'იტვირთება...',
    'error' => 'შეცდომა',
    'success' => 'წარმატება',
    'warning' => 'გაფრთხილება',

    // Authentication
    'auth' => [
        'login' => 'შესვლა',
        'logout' => 'გამოსვლა',
        'register' => 'რეგისტრაცია',
        'phone' => 'ტელეფონის ნომერი',
        'password' => 'პაროლი',
        'confirm_password' => 'დაადასტურეთ პაროლი',
        'name' => 'სახელი და გვარი',
        'email' => 'ელ-ფოსტა (არასავალდებულო)',
        'forgot_password' => 'დაგავიწყდათ პაროლი?',
        'reset_password' => 'პაროლის აღდგენა',
        'new_password' => 'ახალი პაროლი',
        'verify_otp' => 'SMS კოდის დადასტურება',
        'otp_code' => 'დადასტურების კოდი',
        'otp_sent' => 'დადასტურების კოდი გამოგზავნილია თქვენს ტელეფონზე',
        'resend_otp' => 'კოდის ხელახლა გაგზავნა',
        'invalid_credentials' => 'არასწორი ტელეფონის ნომერი ან პაროლი',
        'invalid_otp' => 'არასწორი დადასტურების კოდი',
        'otp_expired' => 'დადასტურების კოდს ვადა გაუვიდა',
        'phone_exists' => 'ეს ტელეფონის ნომერი უკვე დარეგისტრირებულია',
        'registration_success' => 'რეგისტრაცია წარმატებით დასრულდა',
        'login_success' => 'წარმატებით შეხვედით სისტემაში',
        'logout_success' => 'წარმატებით გახვედით სისტემიდან',
        'password_changed' => 'პაროლი წარმატებით შეიცვალა',
        'session_timeout' => 'თქვენი სესია ამოიწურა. გთხოვთ შეხვიდეთ თავიდან.',
        'remember_me' => 'დამიმახსოვრე',
    ],

    // Vehicles
    'vehicle' => [
        'vehicles' => 'ავტომობილები',
        'my_vehicles' => 'ჩემი ავტომობილები',
        'add_vehicle' => 'ავტომობილის დამატება',
        'edit_vehicle' => 'ავტომობილის რედაქტირება',
        'make' => 'მწარმოებელი',
        'model' => 'მოდელი',
        'year' => 'წელი',
        'plate_number' => 'სახელმწიფო ნომერი',
        'vin' => 'VIN კოდი',
        'select_vehicle' => 'აირჩიეთ ავტომობილი',
        'add_new' => 'ახლის დამატება',
        'no_vehicles' => 'თქვენ ჯერ არ გაქვთ დამატებული ავტომობილი',
        'vehicle_added' => 'ავტომობილი წარმატებით დაემატა',
        'vehicle_updated' => 'ავტომობილის ინფორმაცია განახლდა',
        'vehicle_deleted' => 'ავტომობილი წაიშალა',
        'confirm_delete' => 'დარწმუნებული ხართ, რომ გსურთ ამ ავტომობილის წაშლა?',
    ],

    // Reports
    'report' => [
        'reports' => 'მოხსენებები',
        'my_reports' => 'ჩემი მოხსენებები',
        'new_report' => 'ახალი მოხსენება',
        'submit_report' => 'მოხსენების გაგზავნა',
        'ticket_number' => 'ბილეთის ნომერი',
        'description' => 'დაზიანების აღწერა',
        'damage_location' => 'დაზიანების ადგილმდებარეობა',
        'urgency' => 'სასწრაფოობა',
        'photos' => 'ფოტოები',
        'upload_photos' => 'ფოტოების ატვირთვა',
        'add_more_photos' => 'დამატებითი ფოტოები',
        'max_photos' => 'მაქსიმუმ :count ფოტო',
        'max_size' => 'მაქსიმუმ :size MB თითოეული',
        'no_reports' => 'თქვენ ჯერ არ გაქვთ გაგზავნილი მოხსენება',
        'report_submitted' => 'მოხსენება წარმატებით გაიგზავნა',
        'download_pdf' => 'PDF ჩამოტვირთვა',

        // Status
        'status_pending' => 'მოლოდინში',
        'status_under_review' => 'განხილვაშია',
        'status_assessed' => 'შეფასებულია',
        'status_closed' => 'დახურულია',

        // Damage locations
        'location_front' => 'წინა',
        'location_rear' => 'უკანა',
        'location_left' => 'მარცხენა მხარე',
        'location_right' => 'მარჯვენა მხარე',
        'location_roof' => 'სახურავი',
        'location_hood' => 'კაპოტი',
        'location_trunk' => 'საბარგული',
        'location_windshield' => 'საქარე მინა',
        'location_other' => 'სხვა',

        // Urgency
        'urgency_standard' => 'სტანდარტული',
        'urgency_urgent' => 'სასწრაფო',
    ],

    // Assessment
    'assessment' => [
        'assessment' => 'შეფასება',
        'total_cost' => 'ჯამური ღირებულება',
        'repair_items' => 'შეკეთების სია',
        'item_description' => 'აღწერა',
        'item_cost' => 'ღირებულება',
        'comments' => 'კომენტარები',
        'recommendations' => 'რეკომენდაციები',
        'estimated_days' => 'სავარაუდო დრო (დღე)',
        'assessed_by' => 'შეფასებულია',
        'assessed_at' => 'შეფასების თარიღი',
        'no_assessment' => 'შეფასება ჯერ არ არის ხელმისაწვდომი',
        'add_item' => 'პუნქტის დამატება',
        'save_assessment' => 'შეფასების შენახვა',
    ],

    // Profile
    'profile' => [
        'profile' => 'პროფილი',
        'my_profile' => 'ჩემი პროფილი',
        'edit_profile' => 'პროფილის რედაქტირება',
        'change_password' => 'პაროლის შეცვლა',
        'current_password' => 'მიმდინარე პაროლი',
        'profile_updated' => 'პროფილი წარმატებით განახლდა',
    ],

    // Admin
    'admin' => [
        'admin_panel' => 'ადმინ პანელი',
        'dashboard' => 'მართვის პანელი',
        'statistics' => 'სტატისტიკა',
        'total_reports' => 'სულ მოხსენებები',
        'pending_reports' => 'მოლოდინში',
        'today_reports' => 'დღეს',
        'total_users' => 'სულ მომხმარებლები',
        'users' => 'მომხმარებლები',
        'all_reports' => 'ყველა მოხსენება',
        'user_management' => 'მომხმარებლების მართვა',
        'block_user' => 'დაბლოკვა',
        'unblock_user' => 'განბლოკვა',
        'user_blocked' => 'მომხმარებელი დაიბლოკა',
        'user_unblocked' => 'მომხმარებელი განიბლოკა',
    ],

    // SMS Messages
    'sms' => [
        'otp_message' => 'თქვენი დადასტურების კოდია: :otp. კოდი მოქმედებს 5 წუთის განმავლობაში.',
        'report_submitted' => 'თქვენი მოხსენება #:ticket წარმატებით მიიღება. შეფასების შემდეგ შეგატყობინებთ.',
        'assessment_complete' => 'თქვენი მოხსენება #:ticket შეფასებულია. ჯამური ღირებულება: :total GEL. დეტალებისთვის ეწვიეთ თქვენს პანელს.',
    ],

    // Errors
    'errors' => [
        'not_found' => 'გვერდი ვერ მოიძებნა',
        'unauthorized' => 'თქვენ არ ხართ ავტორიზებული',
        'forbidden' => 'წვდომა აკრძალულია',
        'server_error' => 'სერვერის შეცდომა. გთხოვთ, სცადოთ მოგვიანებით.',
        'validation_failed' => 'შეყვანილი მონაცემები არასწორია',
    ],
];
