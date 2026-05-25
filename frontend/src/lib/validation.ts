// src/lib/validation.ts
import { ValidationError } from './errors';

export interface ValidationRule {
  required?: boolean;
  minLength?: number;
  maxLength?: number;
  pattern?: RegExp;
  email?: boolean;
  url?: boolean;
  custom?: (value: any) => boolean | string;
}

export interface ValidationResult {
  isValid: boolean;
  errors: string[];
}

export const validateField = (value: any, rules: ValidationRule): ValidationResult => {
  const errors: string[] = [];

  if (rules.required && (value === null || value === undefined || value === '')) {
    errors.push('This field is required');
  }

  if (value !== null && value !== undefined && value !== '') {
    if (rules.minLength && typeof value === 'string' && value.length < rules.minLength) {
      errors.push(`Must be at least ${rules.minLength} characters`);
    }

    if (rules.maxLength && typeof value === 'string' && value.length > rules.maxLength) {
      errors.push(`Must be no more than ${rules.maxLength} characters`);
    }

    if (rules.pattern && typeof value === 'string' && !rules.pattern.test(value)) {
      errors.push('Invalid format');
    }

    if (rules.email && typeof value === 'string') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        errors.push('Invalid email address');
      }
    }

    if (rules.url && typeof value === 'string') {
      try {
        new URL(value);
      } catch {
        errors.push('Invalid URL');
      }
    }

    if (rules.custom) {
      const result = rules.custom(value);
      if (result === false) {
        errors.push('Invalid value');
      } else if (typeof result === 'string') {
        errors.push(result);
      }
    }
  }

  return {
    isValid: errors.length === 0,
    errors,
  };
};

export const validateForm = (data: Record<string, any>, rules: Record<string, ValidationRule>): ValidationResult => {
  const allErrors: string[] = [];
  let isValid = true;

  for (const [field, fieldRules] of Object.entries(rules)) {
    const result = validateField(data[field], fieldRules);
    if (!result.isValid) {
      isValid = false;
      allErrors.push(...result.errors.map(error => `${field}: ${error}`));
    }
  }

  return {
    isValid,
    errors: allErrors,
  };
};

// Common validation rules
export const validationRules = {
  email: { required: true, email: true },
  password: { required: true, minLength: 8 },
  name: { required: true, minLength: 2, maxLength: 100 },
  title: { required: true, minLength: 3, maxLength: 200 },
  description: { maxLength: 1000 },
  url: { url: true },
  slug: { required: true, pattern: /^[a-z0-9-]+$/ },
} as const;