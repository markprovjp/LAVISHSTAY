// src/hooks/useFormValidation.ts
import { Rule } from 'antd/es/form';

interface ValidationRules {
  required?: Rule;
  email?: Rule;
  password?: Rule;
  confirmPassword?: (field: string) => Rule;
  phone?: Rule;
  number?: Rule;
  minLength?: (min: number) => Rule;
  maxLength?: (max: number) => Rule;
  pattern?: (pattern: RegExp, message: string) => Rule;
  custom?: (validator: (value: any) => Promise<void>) => Rule;
}

export const useFormValidation = (): ValidationRules => {
  return {
    required: { required: true, message: 'Trường này là bắt buộc' },
    
    email: { 
      type: 'email', 
      message: 'Vui lòng nhập đúng định dạng email' 
    },
    
    password: { 
      pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W]{8,}$/, 
      message: 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm ký tự hoa, thường và số' 
    },
    
    confirmPassword: (field: string) => ({ 
      validator: async (_: any, value: string) => {
        if (!value || value === field) {
          return Promise.resolve();
        }
        return Promise.reject(new Error('Mật khẩu xác nhận không khớp!'));
      } 
    }),
    
    phone: { 
      pattern: /^(0|\+84)(3|5|7|8|9)[0-9]{8}$/, 
      message: 'Vui lòng nhập đúng định dạng số điện thoại Việt Nam' 
    },
    
    number: { 
      type: 'number', 
      message: 'Vui lòng nhập số' 
    },
    
    minLength: (min: number) => ({ 
      min, 
      message: `Vui lòng nhập ít nhất ${min} ký tự` 
    }),
    
    maxLength: (max: number) => ({ 
      max, 
      message: `Vui lòng nhập không quá ${max} ký tự` 
    }),
    
    pattern: (pattern: RegExp, message: string) => ({ 
      pattern, 
      message 
    }),
    
    custom: (validator: (value: any) => Promise<void>) => ({ 
      validator 
    }),
  };
};

export default useFormValidation;
