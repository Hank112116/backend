<?php
namespace Backend\Model\Eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @refer hwp/application/schema/perk.php
 * @table perk
 * @primaryKey perk_id
 * @useful columns
 *        project_id, perk_title, perk_description, perk_amount, perk_total, perk_get,
 *        perk_delivery_date, is_pro, perk_image,
 *        has_shipping_fee, shipping_fee_us, shipping_fee_intl, update_time
 * @trashed columns
 *        coupon_id, coupon_status, perk_limit
 **/

class Perk extends Eloquent
{

    protected $table = 'perk';
    protected $primaryKey = 'perk_id';

    public $timestamps = false;
    public static $unguarded = true;

    private $attrs = [];

    public function isEditable()
    {
        if (!array_key_exists('is_editable', $this->attrs)) {
            $perk = Perk::where('perk_id', $this->perk_id)
                ->where('perk_get', '>', 0)
                ->first();

            $this->attrs['is_editable'] = ($perk === null);
        }

        return $this->attrs['is_editable'];
    }

    public function setIsEditable($is_editable)
    {
        $this->attrs['is_editable'] = $is_editable;
    }
}
