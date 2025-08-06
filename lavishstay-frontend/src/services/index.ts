// Export tất cả các service từ một file duy nhất
import authService from './authService';
import userService from './userService';
import { searchService } from './searchService';

export {
  authService,
  userService,
  searchService,
};

export default {
  auth: authService,
  user: userService,
  search: searchService,
};
