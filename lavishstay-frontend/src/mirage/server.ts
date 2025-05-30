import { createServer, Response } from "miragejs";
import { sampleRooms } from "./models";
import { sampleUsers, mockCredentials } from "./users";
import { sampleRoomOptions } from "./roomoption";
import { sampleReviews } from "./reviews";
export function makeServer() {
  return createServer({
    routes() {
      this.namespace = "api";
      this.timing = 400; // Thêm độ trễ để mô phỏng API thật

      // API endpoint để lấy tất cả các phòng
      this.get("/rooms", () => {
        return {
          rooms: sampleRooms,
          count: sampleRooms.length
        };
      });

      // API endpoint để lấy phòng theo loại phòng
      this.get("/rooms-type/:roomType", (_, request) => {
        const roomType = request.params.roomType;
        const filteredRooms = sampleRooms.filter(room => room.roomType === roomType);

        return {
          rooms: filteredRooms,
          count: filteredRooms.length
        };
      });

      // API endpoint để lấy chi tiết phòng theo ID
      this.get("/rooms/:id", (_, request) => {
        const id = parseInt(request.params.id, 10);
        const room = sampleRooms.find(room => room.id === id);

        if (!room) {
          return new Response(404, {}, { error: "Phòng không tồn tại" });
        }

        // Add some mock data for display purposes
        const roomWithDetails = {
          ...room,
          rating: 9.2,
          reviewCount: Math.floor(Math.random() * 100) + 50,
          originalPriceVND: room.discount ? Math.round(room.priceVND / (1 - room.discount / 100)) : undefined
        };
        return { room: roomWithDetails };
      });      // API endpoint để lấy các tùy chọn/gói dịch vụ cho phòng
      this.get("/rooms/:id/options", (_, request) => {
        const roomId = parseInt(request.params.id, 10);
        const room = sampleRooms.find(room => room.id === roomId);

        if (!room) {
          return new Response(404, {}, { error: "Phòng không tồn tại" });
        }

        // Filter room options based on room type
        const roomOptions = sampleRoomOptions.filter((option: any) =>
          option.roomType === room.roomType
        );

        return {
          roomId,
          options: roomOptions
        };
      });// API endpoint để lấy phòng tương tự
      this.get("/rooms/:id/similar", (_, request) => {
        const roomId = parseInt(request.params.id, 10);
        const room = sampleRooms.find(room => room.id === roomId);

        if (!room) {
          return new Response(404, {}, { error: "Phòng không tồn tại" });
        }

        // Find similar rooms of same type, excluding current room
        const similarRooms = sampleRooms.filter(r =>
          r.roomType === room.roomType && r.id !== roomId
        ).slice(0, 4); // Limit to 4 similar rooms

        return {
          roomId,
          similarRooms,
          count: similarRooms.length
        };
      });

      // API endpoint để lấy đánh giá của phòng
      this.get("/rooms/:id/reviews", (_, request) => {
        const roomId = parseInt(request.params.id, 10);
        const room = sampleRooms.find(room => room.id === roomId);

        if (!room) {
          return new Response(404, {}, { error: "Phòng không tồn tại" });
        }

        // Filter reviews for the specific room
        const roomReviews = sampleReviews.filter(review => review.roomId === roomId); return {
          roomId,
          reviews: roomReviews,
          count: roomReviews.length,
          averageRating: roomReviews.length > 0
            ? roomReviews.reduce((sum, review) => sum + review.rating, 0) / roomReviews.length
            : 0
        };
      });

      // API endpoint cho đăng nhập
      this.post("/auth/login", (_, request) => {
        const { email, password } = JSON.parse(request.requestBody);

        // Kiểm tra thông tin đăng nhập
        const validCredential = mockCredentials.find(
          cred => cred.email === email && cred.password === password
        );

        if (!validCredential) {
          return new Response(401, {}, {
            error: "Email hoặc mật khẩu không đúng!"
          });
        }

        // Tìm user tương ứng
        const user = sampleUsers.find(u => u.email === email);

        if (!user) {
          return new Response(404, {}, {
            error: "Không tìm thấy người dùng!"
          });
        }

        // Tạo mock token
        const token = `mock_token_${user.id}_${Date.now()}`;

        return {
          user,
          token,
          token_type: "Bearer"
        };
      });

      // API endpoint cho đăng xuất
      this.post("/auth/logout", () => {
        return new Response(200, {}, { message: "Đăng xuất thành công!" });
      });

      // API endpoint để lấy thông tin user hiện tại
      this.get("/auth/user", (_, request) => {
        const authHeader = request.requestHeaders.Authorization;

        if (!authHeader || !authHeader.startsWith('Bearer ')) {
          return new Response(401, {}, { error: "Token không hợp lệ!" });
        }

        const token = authHeader.replace('Bearer ', '');

        // Parse user ID từ mock token
        const tokenParts = token.split('_');
        if (tokenParts.length < 3 || tokenParts[0] !== 'mock' || tokenParts[1] !== 'token') {
          return new Response(401, {}, { error: "Token không hợp lệ!" });
        }

        const userId = parseInt(tokenParts[2]);
        const user = sampleUsers.find(u => u.id === userId);

        if (!user) {
          return new Response(404, {}, { error: "Không tìm thấy người dùng!" });
        }

        return { user };
      });
    }
  });
}

// Khởi tạo server
export default makeServer;
