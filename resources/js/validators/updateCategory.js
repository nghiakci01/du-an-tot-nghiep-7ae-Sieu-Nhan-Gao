import * as yup from 'yup';

const updateCategorySchema = yup.object().shape({
  name: yup.string().max(50).required(),
  parent_id: yup.number().integer().min(1).nullable(),
}).noUnknown(true);

export default updateCategorySchema;
