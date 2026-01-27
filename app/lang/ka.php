<?php
/**
 * Georgian Language Translations
 * პროფესიონალური ქართული თარგმანები
 */

return [
    // General
    'app_name' => 'ავტო შეფასება',
    'welcome' => 'გამარჯობა',
    'home' => 'მთავარი',
    'dashboard' => 'პანელი',
    'save' => 'შენახვა',
    'cancel' => 'გაუქმება',
    'delete' => 'წაშლა',
    'edit' => 'რედაქტირება',
    'view' => 'ნახვა',
    'back' => 'უკან',
    'submit' => 'გაგზავნა',
    'search' => 'ძიება',
    'filter' => 'ფილტრი',
    'actions' => 'მოქმედება',
    'status' => 'სტატუსი',
    'date' => 'თარიღი',
    'download' => 'ჩამოტვირთვა',
    'yes' => 'დიახ',
    'no' => 'არა',
    'loading' => 'იტვირთება...',
    'error' => 'შეცდომა',
    'success' => 'წარმატება',
    'warning' => 'გაფრთხილება',
    'or' => 'ან',
    'all' => 'ყველა',
    'none' => 'არცერთი',
    'close' => 'დახურვა',
    'confirm' => 'დადასტურება',
    'continue' => 'გაგრძელება',
    'details' => 'დეტალები',

    // Authentication
    'auth' => [
        'login' => 'შესვლა',
        'logout' => 'გასვლა',
        'register' => 'რეგისტრაცია',
        'phone' => 'მობილური',
        'password' => 'პაროლი',
        'confirm_password' => 'გაიმეორეთ პაროლი',
        'password_confirmation' => 'გაიმეორეთ პაროლი',
        'name' => 'სახელი, გვარი',
        'email' => 'ელ-ფოსტა',
        'forgot_password' => 'დაგავიწყდათ პაროლი?',
        'reset_password' => 'პაროლის აღდგენა',
        'new_password' => 'ახალი პაროლი',
        'verify_otp' => 'კოდის დადასტურება',
        'otp_code' => 'SMS კოდი',
        'otp_sent' => 'კოდი გამოგზავნილია თქვენს ნომერზე',
        'resend_otp' => 'კოდის გაგზავნა',
        'invalid_credentials' => 'არასწორი ნომერი ან პაროლი',
        'invalid_otp' => 'არასწორი კოდი',
        'otp_expired' => 'კოდს ვადა გაუვიდა',
        'phone_exists' => 'ნომერი უკვე რეგისტრირებულია',
        'registration_success' => 'რეგისტრაცია დასრულდა',
        'login_success' => 'წარმატებით შეხვედით',
        'logout_success' => 'წარმატებით გახვედით',
        'password_changed' => 'პაროლი შეიცვალა',
        'session_timeout' => 'სესია ამოიწურა, შედით თავიდან',
        'remember_me' => 'დამახსოვრება',
        'have_account' => 'გაქვთ ანგარიში?',
        'no_account' => 'არ გაქვთ ანგარიში?',
        'enter_phone' => 'შეიყვანეთ მობილურის ნომერი',
        'enter_code' => 'შეიყვანეთ კოდი',
        'code_sent_to' => 'კოდი გაიგზავნა ნომერზე',
        'didnt_receive' => 'არ მიიღეთ კოდი?',
        'wait_before_resend' => 'დაელოდეთ :seconds წამი',
    ],

    // Vehicles
    'vehicle' => [
        'vehicles' => 'მანქანები',
        'my_vehicles' => 'ჩემი მანქანები',
        'add_vehicle' => 'მანქანის დამატება',
        'edit_vehicle' => 'რედაქტირება',
        'make' => 'მარკა',
        'model' => 'მოდელი',
        'year' => 'წელი',
        'plate_number' => 'ნომერი',
        'vin' => 'VIN',
        'select_vehicle' => 'აირჩიეთ მანქანა',
        'add_new' => 'დამატება',
        'no_vehicles' => 'მანქანა არ არის დამატებული',
        'vehicle_added' => 'მანქანა დაემატა',
        'vehicle_updated' => 'მანქანა განახლდა',
        'vehicle_deleted' => 'მანქანა წაიშალა',
        'confirm_delete' => 'წაშალოთ მანქანა?',
        'color' => 'ფერი',
        'mileage' => 'გარბენი',
    ],

    // Reports
    'report' => [
        'reports' => 'განაცხადები',
        'my_reports' => 'ჩემი განაცხადები',
        'new_report' => 'ახალი განაცხადი',
        'submit_report' => 'განაცხადის გაგზავნა',
        'ticket_number' => 'განაცხადი #',
        'description' => 'დაზიანების აღწერა',
        'damage_location' => 'დაზიანების ადგილი',
        'urgency' => 'პრიორიტეტი',
        'photos' => 'ფოტოები',
        'upload_photos' => 'ფოტოს ატვირთვა',
        'add_more_photos' => 'დამატება',
        'max_photos' => 'მაქს. :count ფოტო',
        'max_size' => 'მაქს. :size MB',
        'no_reports' => 'განაცხადი არ გაქვთ',
        'report_submitted' => 'განაცხადი გაიგზავნა',
        'download_pdf' => 'PDF',
        'view_report' => 'ნახვა',
        'report_details' => 'დეტალები',

        // Status
        'status_pending' => 'მოლოდინში',
        'status_under_review' => 'განხილვაში',
        'status_assessed' => 'შეფასებული',
        'status_closed' => 'დახურული',

        // Damage locations
        'location_front' => 'წინა',
        'location_rear' => 'უკანა',
        'location_left' => 'მარცხენა',
        'location_right' => 'მარჯვენა',
        'location_roof' => 'სახურავი',
        'location_hood' => 'კაპოტი',
        'location_trunk' => 'საბარგული',
        'location_windshield' => 'მინა',
        'location_other' => 'სხვა',

        // Urgency
        'urgency_standard' => 'ჩვეულებრივი',
        'urgency_urgent' => 'სასწრაფო',
    ],

    // Assessment
    'assessment' => [
        'assessment' => 'შეფასება',
        'total_cost' => 'ჯამი',
        'repair_items' => 'სამუშაოები',
        'item_description' => 'აღწერა',
        'item_cost' => 'ფასი',
        'comments' => 'კომენტარი',
        'recommendations' => 'რეკომენდაცია',
        'estimated_days' => 'ვადა (დღე)',
        'assessed_by' => 'შემფასებელი',
        'assessed_at' => 'შეფასების თარიღი',
        'no_assessment' => 'ჯერ არ არის შეფასებული',
        'add_item' => 'დამატება',
        'save_assessment' => 'შენახვა',
        'currency' => 'ლარი',
    ],

    // Profile
    'profile' => [
        'profile' => 'პროფილი',
        'my_profile' => 'პროფილი',
        'edit_profile' => 'რედაქტირება',
        'change_password' => 'პაროლის შეცვლა',
        'current_password' => 'მიმდინარე პაროლი',
        'profile_updated' => 'პროფილი განახლდა',
        'account_settings' => 'ანგარიშის პარამეტრები',
        'personal_info' => 'პირადი ინფორმაცია',
    ],

    // Admin
    'admin' => [
        'admin_panel' => 'ადმინ პანელი',
        'dashboard' => 'პანელი',
        'statistics' => 'სტატისტიკა',
        'total_reports' => 'სულ განაცხადი',
        'pending_reports' => 'მოლოდინში',
        'today_reports' => 'დღეს',
        'total_users' => 'მომხმარებელი',
        'users' => 'მომხმარებლები',
        'all_reports' => 'ყველა განაცხადი',
        'user_management' => 'მომხმარებლები',
        'block_user' => 'დაბლოკვა',
        'unblock_user' => 'განბლოკვა',
        'user_blocked' => 'მომხმარებელი დაიბლოკა',
        'user_unblocked' => 'მომხმარებელი განიბლოკა',
    ],

    // Estimate Flow
    'estimate' => [
        'upload_photos' => 'ატვირთეთ დაზიანების ფოტოები',
        'upload_first' => 'გთხოვთ ჯერ ატვირთოთ ფოტოები',
        'max_photos' => 'მაქსიმუმ 10 ფოტო დაშვებულია',
        'photos_uploaded' => 'ფოტო ატვირთული',
        'continue_register' => 'გაგრძელება რეგისტრაციაზე',
        'start_estimate' => 'დაიწყე შეფასება',
        'get_started' => 'დაიწყე ახლავე',
    ],

    // SMS Messages
    'sms' => [
        'otp_message' => 'OTO: თქვენი კოდია :otp (5 წუთი)',
        'report_submitted' => 'OTO: განაცხადი #:ticket მიღებულია. შეგატყობინებთ შეფასების შემდეგ.',
        'assessment_complete' => 'OTO: განაცხადი #:ticket შეფასდა - :total ლარი. ნახეთ დეტალები აპში.',
    ],

    // Errors
    'errors' => [
        'not_found' => 'გვერდი ვერ მოიძებნა',
        'unauthorized' => 'შედით სისტემაში',
        'forbidden' => 'წვდომა აკრძალულია',
        'server_error' => 'შეცდომა. სცადეთ მოგვიანებით.',
        'validation_failed' => 'შეამოწმეთ შეყვანილი მონაცემები',
    ],

    // Home page
    'home_page' => [
        'hero_title' => 'ავტომობილის დაზიანების შეფასება',
        'hero_subtitle' => 'სწრაფი და პროფესიონალური შეფასება',
        'get_started' => 'დაწყება',
        'how_it_works' => 'როგორ მუშაობს',
        'step1_title' => 'გადაიღეთ ფოტო',
        'step1_desc' => 'გადაუღეთ ფოტო დაზიანებულ ადგილს',
        'step2_title' => 'გააგზავნეთ',
        'step2_desc' => 'ატვირთეთ ფოტოები და აღწერეთ დაზიანება',
        'step3_title' => 'მიიღეთ შეფასება',
        'step3_desc' => 'ჩვენი ექსპერტები შეაფასებენ დაზიანებას',
    ],

    // Common actions
    'action' => [
        'add' => 'დამატება',
        'create' => 'შექმნა',
        'update' => 'განახლება',
        'remove' => 'წაშლა',
        'upload' => 'ატვირთვა',
        'send' => 'გაგზავნა',
        'save_changes' => 'შენახვა',
        'discard' => 'გაუქმება',
    ],
];
