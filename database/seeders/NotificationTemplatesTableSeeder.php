<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'type' => 'user_registration',
                'subject' => 'Welcome to JAPY TAG, {user_name}! Your registration is successful.',
                'body' => '<p>Hello {user_name},</p><p>Thank you for registering at JAPY TAG. We are excited to have you on board!</p>',
                'variables' => json_encode(['user_name']),
            ],
            [
                'type' => 'user_verification',
                'subject' => 'Verify your JAPY TAG account',
                'body' => '<p>Hello {user_name},</p><p>Please verify your email by clicking <a href="{verification_link}">here</a>.</p>',
                'variables' => json_encode(['user_name', 'verification_link']),
            ],
            [
                'type' => 'admin_new_user_registered',
                'subject' => 'A new user has registered: {user_name}',
                'body' => '<p>Admin,</p><p>A new user ({user_name}) has registered on JAPY TAG.</p>',
                'variables' => json_encode(['user_name']),
            ],
            [
                'type' => 'password_reset',
                'subject' => 'Reset your JAPY TAG password',
                'body' => '<p>Hello {user_name},</p><p>Click <a href="{reset_link}">here</a> to reset your password.</p>',
                'variables' => json_encode(['user_name', 'reset_link']),
            ],
            [
                'type' => 'plan_expiration',
                'subject' => 'Your JAPY TAG plan is about to expire',
                'body' => '<p>Hello {user_name},</p><p>Your plan will expire on {expiration_date}. Please renew to continue enjoying our services.</p>',
                'variables' => json_encode(['user_name', 'expiration_date']),
            ],
            [
                'type' => 'appointment_confirmation',
                'subject' => 'Appointment Confirmed',
                'body' => '<p>Hello {user_name},</p><p>Your appointment on {appointment_date} is confirmed.</p>',
                'variables' => json_encode(['user_name', 'appointment_date']),
            ],
            [
                'type' => 'appointment_notification_owner',
                'subject' => 'New Appointment Booked',
                'body' => '<p>Hello {owner_name},</p><p>{user_name} has booked an appointment on {appointment_date}.</p>',
                'variables' => json_encode(['owner_name', 'user_name', 'appointment_date']),
            ],
            [
                'type' => 'product_order_customer',
                'subject' => 'Order Confirmation - JAPY TAG',
                'body' => '<p>Hello {customer_name},</p><p>Your order for {product_name} has been received.</p>',
                'variables' => json_encode(['customer_name', 'product_name']),
            ],
            [
                'type' => 'product_order_owner',
                'subject' => 'New Product Order Received',
                'body' => '<p>Hello {owner_name},</p><p>You have received a new order for {product_name} from {customer_name}.</p>',
                'variables' => json_encode(['owner_name', 'product_name', 'customer_name']),
            ],
            [
                'type' => 'withdrawal_approved',
                'subject' => 'Withdrawal Request Approved',
                'body' => '<p>Hello {user_name},</p><p>Your withdrawal request for {amount} has been approved.</p>',
                'variables' => json_encode(['user_name', 'amount']),
            ],
            [
                'type' => 'withdrawal_rejected',
                'subject' => 'Withdrawal Request Rejected',
                'body' => '<p>Hello {user_name},</p><p>Your withdrawal request for {amount} has been rejected. Reason: {rejection_note}</p>',
                'variables' => json_encode(['user_name', 'amount', 'rejection_note']),
            ],
            [
                'type' => 'enquiry_notification',
                'subject' => 'New Enquiry Received',
                'body' => '<p>Hello {owner_name},</p><p>You have received a new enquiry from {user_name}.</p>',
                'variables' => json_encode(['owner_name', 'user_name']),
            ],
            [
                'type' => 'nfc_order_admin',
                'subject' => 'New NFC Order Received',
                'body' => '<p>Admin,</p><p>A new NFC order has been placed by {user_name} for {card_type}.</p>',
                'variables' => json_encode(['user_name', 'card_type']),
            ],
            [
                'type' => 'nfc_order_status_user',
                'subject' => 'NFC Order Status Update',
                'body' => '<p>Hello {user_name},</p><p>Your NFC order status is now: {status}.</p>',
                'variables' => json_encode(['user_name', 'status']),
            ],
            [
                'type' => 'affiliate_invite',
                'subject' => 'You have been invited to JAPY TAG',
                'body' => '<p>Hello,</p><p>{username} has invited you to join JAPY TAG. Register using this link: {referral_url}</p>',
                'variables' => json_encode(['username', 'referral_url']),
            ],
            [
                'type' => 'custom_bulk_email',
                'subject' => '{subject}',
                'body' => '{description}',
                'variables' => json_encode(['subject', 'description']),
            ],
        ];
        foreach ($templates as $tpl) {
            NotificationTemplate::updateOrCreate(['type' => $tpl['type']], $tpl);
        }
    }
} 