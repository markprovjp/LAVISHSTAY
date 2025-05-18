// Export tất cả các service từ một file duy nhất
import authService from './authService';
import userService from './userService';

export {
  authService,
  userService,
};

export default {
  auth: authService,
  user: userService,
};
