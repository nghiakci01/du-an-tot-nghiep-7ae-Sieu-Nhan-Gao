import * as yup from 'yup';

const updateCouponSchema = yup.object().shape({
  code: yup.string().max(50).required(),
  type: yup.string().oneOf(['percentage', 'fixed']).required(),
  value: yup.number().min(0).required(),
  min_order_amount: yup.number().min(0).nullable(),
  max_discount_amount: yup.number().min(0).nullable(),
  usage_limit: yup.number().integer().min(1).nullable(),
  start_date: yup.string().nullable(),
  end_date: yup.string().nullable(),
  is_active: yup.boolean(),
  description: yup.string().max(500).nullable(),
  user_id: yup.number().integer().min(1).nullable(),
}).noUnknown(true);

export default updateCouponSchema;
