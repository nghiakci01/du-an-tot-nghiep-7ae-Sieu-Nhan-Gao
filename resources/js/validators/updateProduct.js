import * as yup from 'yup';

const updateProductSchema = yup.object().shape({
  name: yup.string().max(255).required(),
  category_id: yup.number().integer().min(1).required(),
  price: yup.number().min(0).nullable(),
  sale_price: yup.number().min(0).nullable(),
  short_description: yup.string().max(500).nullable(),
  description: yup.string().max(5000).nullable(),
  image: yup.mixed().nullable(),
  gallery_images: yup.array().of(yup.mixed()).max(6).nullable(),
  is_active: yup.boolean(),
  variants: yup.array().min(1).nullable(),
}).noUnknown(true);

export default updateProductSchema;
