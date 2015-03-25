<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @refer  hwp/application/schema/transaction
 * @table transaction
 * @pk      transaction_id
 * @useful columns
 *        user_id, project_id, perk_id, name, email, host_ip,
 *        preapproval_key, preapproval_total_amount
 * @unknown columns
 *    amount, listing_fee, pay_fee, comment, paypal_email, transaction_date_time,
 *        amazon_transaction_id, paypal_paykey, paypal_adaptive_status,
 *    preapproval_pay_key, preapproval_status,
 *    qty, perk_amount, shipping_fee,
 *    shipping_address, shipping_phone_number, paypal_transaction_id,
 *    wallet_transaction_id, wallet_payment_status, trans_anonymous,
 *    credit_card_transaction_id, credit_card_capture_transaction_id, credit_card_payment_status
 * @trashed columns
 **/
class Transaction extends Eloquent
{

    protected $table = 'transaction';
    protected $primaryKey = 'transaction_id';

    public $timestamps = false;
    public static $unguarded = true;

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function perk()
    {
        return $this->belongsTo(Perk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function textUserName()
    {
        if (!$this->user) {
            return 'Not exist';
        }

        return $this->user->last_name . ' ' . $this->user->user_name;

    }

    public function textProjectTitle()
    {
        if (!$this->project) {
            return 'Not exist';
        } else {
            return $this->project->project_title;
        }
    }
}
