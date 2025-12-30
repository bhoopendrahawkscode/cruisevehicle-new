import { defineRule } from 'vee-validate';

defineRule('required', (value) => {
    if (!value) {
      return  'This field is required.';
    }
    return true;
  });

defineRule('requiredEmail', (value) => {
    const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    if (!value) {
        return 'This field is required.';
    }
    else if(!regex.test(value)){
        return 'This field must be a valid email';
    }
    return true;
  });

defineRule('email', (value) => {
    if (!value) {
        return 'This field is required.';
    }
    const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (!regex.test(value)) {
        return 'This field must be a valid email';
      }
    return true;
});

defineRule('stringRequired', (value) => {
    if (!value) {
        return 'This field is required.';
      }
    else if(typeof value !== 'string'){
        return 'Please enter a string value';
    }
   return true;
  });

  defineRule('confirmed', (value, [target]) => {
     if(value !== target){
        return 'The passwords do not match.';
     }
     return true;
  });

  defineRule('mobile', (value) => {
    const mobileNumberRegex = /^[0-9]{5,15}$/;
    if (!value) {
      return  'This field is required.';
    }
    if(!mobileNumberRegex.test(value)){
        return  'Please enter a valid mobile number.';
    }
    return true;
  });

  defineRule('countryCodeRequired', (value, [minLength,maxLength]) => {
    const mobileNumberRegex = /^[0-9]{2,3}$/;
    if (!value) {
      return  'This field is required.';
    }
    if(!mobileNumberRegex.test(value)){
        return  'Please enter a valid country code';
    }
    return true;
  });

  defineRule('otpCodeRequired', (value, [minLength,maxLength]) => {
    const mobileNumberRegex = /^[0-9]{4}$/;
    if (!value) {
      return  'This field is required.';
    }
    if(!mobileNumberRegex.test(value)){
        return  'Please enter a valid country code';
    }
    return true;
  });



