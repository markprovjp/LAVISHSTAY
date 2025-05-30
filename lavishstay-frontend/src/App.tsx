import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { ConfigProvider, FloatButton, App as AntApp, Layout } from "antd";
import { useSelector } from "react-redux";
import { RootState } from "./store";
import ThemeProvider from "./providers/ThemeProvider";
import QueryProvider from "./providers/QueryProvider";
import { createAntdTheme } from "./styles/theme";

// Import i18n configuration
import "./i18n";

// Nhập và khởi động máy chủ MirageJS
import makeServer from "./mirage/server";
// Khởi tạo server Mirage
makeServer();

// Import layouts
import Header from "./components/layouts/Header";
import Footer from "./components/layouts/Footer";
import Breadcrumb from "./components/ui/Breadcrumb";

// Import pages
import Home from "./pages/Home";
import About from "./pages/About";
import Payment from "./pages/Payment"; // Assuming Payment.tsx exports a default component
import NotFound from "./pages/NotFound";
import RoomDetailsPage from "./pages/RoomDetailsPage";
import AuthTest from "./pages/AuthTest";
// import HotelListingPage from "./pages/HotelListingPage";
// import HotelDetailsPage from "./pages/HotelDetailsPage";
// import ErrorPage from "./pages/ErrorPage";
// import ErrorTestPage from "./pages/ErrorTestPage";
// Placeholder for pages not yet created
const PlaceholderPage: React.FC<{ title: string }> = ({ title }) => (
  <div style={{ padding: "20px" }}>
    <h1>{title}</h1>
    <p>Trang này đang được xây dựng , từ từ thôi ku!!!!.</p>
  </div>
);
const UserProfile = () => <PlaceholderPage title="User Profile" />;
const UserBookings = () => <PlaceholderPage title="User Bookings" />;
const UserWishlist = () => <PlaceholderPage title="User Wishlist" />;
const UserSettings = () => <PlaceholderPage title="User Settings" />;
const Contact = () => <PlaceholderPage title="Contact Us" />;
const Destinations = () => (
  <PlaceholderPage title="Điểm đến của ku em tại đây ha , NHƯNG " />
);
const NotificationsPage = () => <PlaceholderPage title="Notifications" />;

// Import CSS
import "./App.css";
import "./index.css"; // Đảm bảo tailwindcss được nhập khẩu

const { Content } = Layout;

const App: React.FC = () => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  // Sử dụng hàm helper để tạo theme dựa trên isDarkMode
  const currentTheme = createAntdTheme(isDarkMode);
  return (
    <ConfigProvider theme={currentTheme}>
      <ThemeProvider>
        <QueryProvider>
          <AntApp className={isDarkMode ? "dark" : "light"}>
            <Router>
              <Header />
              <Breadcrumb />
              <Content
                style={{
                  paddingTop: "64px", // Chiều cao của tiêu đề cố định
                  background: currentTheme.token?.colorBgBase, // Sử dụng mã thông báo chủ đề cho nền
                  minHeight: "calc(100vh - 64px - 70px)", // Điều chỉnh Minheight dựa trên chiều cao tiêu đề và chân trang
                }}
              >
                {" "}
                <Routes>
                  {" "}
                  <Route path="/" element={<Home />} />
                  <Route path="/about" element={<About />} />
                  <Route path="/rooms/:id" element={<RoomDetailsPage />} />
                  {/* <Route path="/hotels" element={<HotelListingPage />} />
                  <Route path="/hotels/:id" element={<HotelDetailsPage />} /> */}
                  <Route path="/profile" element={<UserProfile />} />
                  <Route path="/bookings" element={<UserBookings />} />
                  <Route path="/wishlist" element={<UserWishlist />} />
                  <Route path="/settings" element={<UserSettings />} />
                  <Route path="/contact" element={<Contact />} />                  <Route path="/destinations" element={<Destinations />} />
                  <Route path="/payment" element={<Payment />} />
                  <Route path="/auth-test" element={<AuthTest />} />
                  <Route
                    path="/notifications"
                    element={<NotificationsPage />}
                  />
                  {/* Route error là test lỗi 404 thôi */}
                  {/* {
                    {
                      {
                        {
                          {
                            {
                              {
                                {
                                  {
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  } */}
                  {/* <Route path="/error" element={<ErrorPage />} />
                <Route path="/error-test" element={<ErrorTestPage />} /> */}
                  <Route path="*" element={<NotFound />} />
                </Routes>{" "}
                <FloatButton.BackTop />
              </Content>
              <Footer />
            </Router>
          </AntApp>
        </QueryProvider>
      </ThemeProvider>
    </ConfigProvider>
  );
};

export default App;
