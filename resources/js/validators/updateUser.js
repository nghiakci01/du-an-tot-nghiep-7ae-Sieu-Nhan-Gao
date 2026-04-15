import * as yup from 'yup';

const updateUserSchema = yup.object().shape({
  name: yup.string().max(255).required(),
  email: yup.string().email().max(255).required(),
  password: yup.string().min(8).nullable(),
  phone: yup.string().max(20).nullable(),
  address: yup.string().nullable(),
  role: yup.string().oneOf(['admin', 'user']).required(),
}).noUnknown(true);

export default updateUserSchema;
