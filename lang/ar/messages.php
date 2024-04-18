<?php

use App\Helpers\BaseTranslationHelper;

return [
    'created' => 'تم الإنشاء بنجاح',
    'updated' => 'تم التحديث بنجاح',
    'deleted' => 'تم الحذف بنجاح',
    'reset' => 'تمت إعادة التعيين بنجاح',
    'role' => 'الدور',
    'name' => 'الاسم',
    'user' => 'المستخدم',
    'logged' => 'تم تسجيل الدخول بنجاح',
    'logged_out' => 'تم تسجيل الخروج بنجاح',
    'wrong_credentials' => 'بيانات الاعتماد غير صحيحة',
    'verified' => 'تم التحقق بنجاح',
    'profile' => 'الملف الشخصي',
    'handle' => 'المعالجة',
    'password_reset_sent' => 'تم إرسال رابط إعادة تعيين كلمة المرور',
    'pass_code' => 'رمز المرور',
    'pass_code_created_before' => 'تم إنشاء رمز المرور من قبل',
    'wrong_pass_code' => 'رمز المرور غير صحيح',
    'mark_all_as_read' => 'تم وضع علامة على جميع الرسائل كمقروءة بنجاح',
    'message' => 'الرسالة',
    'record' => 'السجل',
    'chat' => 'الدردشة',
    'price_plan' => 'خطة الأسعار',
    'credit_card' => 'بطاقة الائتمان',
    'customer' => 'العميل',
    'invalid_stripe_token' => 'رمز بطاقة الائتمان غير صالح',
    'stripe_token_used_before' => 'تم استخدام رمز Stripe من قبل',
    'frozen' => 'مجمد',
    'active' => 'نشط الآن',
    'banned' => 'محظور',
    'cannot_move_to_same_plan' => 'لا يمكن الترقية إلى نفس خطة الأسعار',
    'cannot_upgrade_to_free_trial' => 'لا يمكن الترقية إلى التجربة المجانية',
    'customer_credit_card_not_found' => 'بطاقة الائتمان للعميل غير موجودة',
    'plan_changed_successfully' => 'تم تغيير الخطة بنجاح',
    'group' => 'المجموعة',
    'post' => 'المنشور',
    'member' => 'العضو',
    'contract' => 'العقد',
    'department' => 'القسم',
    'department_manger' => 'مدير القسم',
    'testimonial' => 'شهادة',
    'section' => 'القسم',
    'team_member' => 'عضو الفريق',
    'blog' => 'المدونة',
    'working_hours' => 'ساعات العمل',
    'appointment' => 'الموعد',
    'partner' => 'الشريك',
    'setting' => 'الإعدادات',
    'disabled' => 'معطل',
    'enabled' => 'مفعل',
    'about_us_management' => 'إدارة من نحن',
    'ad_management' => 'إدارة الإعلانات',
    'blog_management' => 'إدارة المدونة',
    'category_management' => 'إدارة الفئات',
    'contact_us_management' => 'إدارة اتصل بنا',
    'our_teams_management' => 'إدارة فرقنا',
    'product_management' => 'إدارة المنتجات',
    'section_management' => 'إدارة الأقسام',
    'settings_management' => 'إدارة الإعدادات',
    'testimonial_management' => 'إدارة الشهادات',
    'working_hour_management' => 'إدارة ساعات العمل',
    'price_plan_management' => 'إدارة خطط الأسعار',
    'appointment_management' => 'إدارة المواعيد',
    'partner_management' => 'إدارة الشركاء',
    'admin_management' => 'إدارة المشرفين',
    'about_us' => 'من نحن',
    'ad' => 'إعلان',
    'settings' => 'الإعدادات',
    'category' => 'الفئة',
    'product' => 'المنتج',
    'service' => 'الخدمة',
    'image' => 'الصورة',
    'forbidden' => 'الوصول مرفوض',
    'notifications' => 'الإشعارات',
    'read' => 'تم القراءة بنجاح',
    'failed_to_read_file' => 'فشل في قراءة الفاتورة',
    'invoice' => 'الفاتورة',
    'paid' => 'تم الدفع بنجاح',
    'sent' => 'تم الإرسال بنجاح',
    'due_invoice_alarm' => 'تنبيه الفاتورة المستحقة',
    'due_invoice_body' => 'تذكير، ستقوم بدفع هذه الفاتورة بعد :days يوم',
    'terms' => 'الشروط والأحكام',
    'store' => 'المتجر',
    'access_denied' => 'تم رفض الوصول',
    'cannot_proceed' => 'لا يمكن المتابعة',
    'status' => 'الحالة',
    'additional_addresses' => 'العناوين الإضافية',
    'active_address' => 'العنوان النشط',
    'employee' => 'الموظف',
    'finished' => 'تم الانتهاء بنجاح',
    ...BaseTranslationHelper::ar(),
    ...\Modules\Auth\Helpers\AuthTranslationHelper::ar(),
    ...\Modules\Coupon\Helpers\CouponTranslationHelper::ar(),
    ...\Modules\Activity\Helpers\ActivityTranslationHelper::ar(),
    \Modules\FcmNotification\Helpers\NotificationTranslationHelper::ar(),
    ...\Modules\Markable\Helpers\FavoriteTranslationHelper::ar(),
    'reviewed' => 'تمت المراجعة بنجاح',
    ...\Modules\Order\Helpers\OrderTranslationHelper::ar(),
    ...\Modules\Payment\Helpers\PaymentTranslationHelper::ar(),
];
