import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { ConfigProvider, FloatButton, App as AntApp, Layout } from "antd";
import { useSelector } from "react-redux";
import { RootState } from "./store";
import ThemeProvider from "./providers/ThemeProvider";
import QueryProvider from "./providers/QueryProvider";
import { SearchProvider } from "./contexts/SearchContext";
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
import ScrollToTop from "./utils/ScrollToTop";

// Import pages
import Home from "./pages/Home";
import About from "./pages/About";
import Payment from "./pages/Payment";
import NotFound from "./pages/NotFound";
<<<<<<< HEAD
import RoomDetailsPage from "./pages/RoomDetailsPage";
import AuthTest from "./pages/AuthTest";
import SearchResults from "./pages/SearchResults";
import ChangePassword from "./components/profile/ChangePassword";

// Import profile components
import {
  ProfileLayout,
  PersonalInfo,
  BookingManagement,
  ForgotPassword,
  Wishlist,
  Notifications,
  Settings
} from "./components/profile";

=======
import PaymentPage from "./pages/PaymentPage";
// import ErrorPage from "./pages/ErrorPage";
// import ErrorTestPage from "./pages/ErrorTestPage";
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
// Placeholder for pages not yet created
const PlaceholderPage: React.FC<{ title: string }> = ({ title }) => (
  <div style={{ padding: "20px" }}>
    <h1>{title}</h1>
    <p>Trang này đang được xây dựng , từ từ thôi ku!!!!.</p>
  </div>
);

const Contact = () => <PlaceholderPage title="Contact Us" />;
const Destinations = () => (
  <PlaceholderPage title="Điểm đến của ku em tại đây ha , NHƯNG " />
);

// Import CSS
import "./App.css";
import "./index.css";
import "./styles/theme-transitions.css";

const { Content } = Layout;

const App: React.FC = React.memo(() => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

<<<<<<< HEAD
  // Sử dụng hàm helper để tạo theme dựa trên isDarkMode
  const currentTheme = createAntdTheme(isDarkMode);

  // Memoize provider wrapper to reduce re-renders
  const ProviderWrapper: React.FC<{ children: React.ReactNode }> = React.useMemo(
    () =>
      ({ children }) => (
        <ConfigProvider theme={currentTheme}>
          <ThemeProvider>
            <QueryProvider>
              <SearchProvider>
                <AntApp className={isDarkMode ? "dark" : "light"}>
                  {children}
                </AntApp>
              </SearchProvider>
            </QueryProvider>
          </ThemeProvider>
        </ConfigProvider>
      ),
    [currentTheme, isDarkMode]
=======
  // Create a dynamic theme based on isDarkMode
  const currentTheme = {
    ...appTheme, // Start with the base theme
    token: {
      ...appTheme.token,
      // Override colors based on dark mode using our custom palette
      colorPrimary: isDarkMode ? "#3b82f6" : "#152C5B",
      colorLink: isDarkMode ? "#3b82f6" : "#152C5B",
      colorSuccess: isDarkMode ? "#4ade80" : "#2ca01c",
      colorWarning: isDarkMode ? "#f59e0b" : "#d97706",
      colorError: isDarkMode ? "#ef4444" : "#b91c1c",
      colorInfo: isDarkMode ? "#60a5fa" : "#0284c7",
      colorBgBase: isDarkMode ? "#0f172a" : "#f8fafc",
      colorTextBase: isDarkMode ? "#f1f5f9" : "#1e293b",
    },
    components: {
      ...appTheme.components,
      // Override component styles for dark mode with our theme colors
      Button: {
        ...appTheme.components?.Button,
        colorPrimary: isDarkMode ? "#3b82f6" : "#152C5B",
        colorPrimaryHover: isDarkMode ? "#60a5fa" : "#1e40af",
      },
      Card: {
        ...appTheme.components?.Card,
        colorBgContainer: isDarkMode ? "#1e293b" : "#ffffff",
        boxShadowTertiary: isDarkMode
          ? "0 4px 12px rgba(0, 0, 0, 0.3)"
          : "0 4px 12px rgba(0, 0, 0, 0.06)",
      },
      Menu: {
        ...appTheme.components?.Menu,
        colorPrimary: isDarkMode ? "#3b82f6" : "#152C5B",
        itemHeight: 50,
        itemHoverColor: isDarkMode ? "#60a5fa" : "#1e40af",
        itemSelectedColor: "#ffffff",
        itemSelectedBg: isDarkMode ? "#3b82f6" : "#152C5B",
        itemColor: isDarkMode ? "#f1f5f9" : "#1e293b",
        subMenuItemBg: isDarkMode ? "#0f172a" : "#f8fafc",
      },
      Layout: {
        colorBgHeader: isDarkMode ? "#1e293b" : "#ffffff",
        colorBgBody: isDarkMode ? "#0f172a" : "#f8fafc",
      },
      Typography: {
        colorTextHeading: isDarkMode ? "#f1f5f9" : "#1e293b",
        colorText: isDarkMode ? "#e2e8f0" : "#4b5563",
        colorTextSecondary: isDarkMode ? "#cbd5e1" : "#64748b",
      },
      Input: {
        colorBgContainer: isDarkMode ? "#1e293b" : "#ffffff",
        colorBorder: isDarkMode ? "#3b82f6" : "#152C5B",
      },
    },
    algorithm: isDarkMode
      ? antdTheme.darkAlgorithm
      : antdTheme.defaultAlgorithm, // Use Ant Design's dark/default algorithm
  };
  return (
    <ConfigProvider theme={currentTheme}>
      <ThemeProvider>
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
              <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/about" element={<About />} />
                <Route path="/hotels" element={<HotelListing />} />
                <Route path="/hotels/:id" element={<HotelDetails />} />
                <Route path="/profile" element={<UserProfile />} />
                <Route path="/bookings" element={<UserBookings />} />
                <Route path="/wishlist" element={<UserWishlist />} />
                <Route path="/settings" element={<UserSettings />} />
                <Route path="/contact" element={<Contact />} />
                <Route path="/destinations" element={<Destinations />} />
                <Route path="/notifications" element={<NotificationsPage />} />
                <Route path="/payment" element={<PaymentPage />} />
                {/* Các trang khác chưa được tạo */}
                {/* Route error là test lỗi 404 thôi */}
                {/* <Route path="/error" element={<ErrorPage />} />
                <Route path="/error-test" element={<ErrorTestPage />} /> */}
                <Route path="*" element={<NotFound />} />
              </Routes>
              <FloatButton.BackTop />
            </Content>
            <Footer />
          </Router>
        </AntApp>
      </ThemeProvider>
    </ConfigProvider>
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
  );

  return (
    <ProviderWrapper>
      <Router>
        <ScrollToTop />
        <Header />
        <Breadcrumb />
        <Content
          style={{
            paddingTop: "64px", // Chiều cao của tiêu đề cố định
            background: currentTheme.token?.colorBgBase, // Sử dụng mã thông báo chủ đề cho nền
            minHeight: "calc(100vh - 64px - 70px)", // Điều chỉnh Minheight dựa trên chiều cao tiêu đề và chân trang
          }}
        >
          <Routes>
            {/* Main Pages */}
            <Route path="/" element={<Home />} />
            <Route path="/about" element={<About />} />
            <Route path="/rooms/:id" element={<RoomDetailsPage />} />
            <Route path="/search" element={<SearchResults />} />

            {/* Profile Routes with nested routing */}
            <Route path="/profile" element={<ProfileLayout />}>
              <Route index element={<PersonalInfo />} />
              <Route path="personal-info" element={<PersonalInfo />} />
              <Route path="bookings" element={<BookingManagement />} />
              <Route path="wishlist" element={<Wishlist />} />
              <Route path="notifications" element={<Notifications />} />
              <Route path="settings" element={<Settings />} />
              <Route path="forgot-password" element={<ForgotPassword />} />
              <Route path="change-password" element={<ChangePassword />} />
            </Route>                    {/* Standalone routes */}

            {/* Legacy routes for backward compatibility */}
            <Route path="/bookings" element={<BookingManagement />} />
            <Route path="/settings" element={<Settings />} />

            {/* Other routes */}
            <Route path="/contact" element={<Contact />} />
            <Route path="/destinations" element={<Destinations />} />
            <Route path="/payment" element={<Payment />} />
            <Route path="/auth-test" element={<AuthTest />} />

            {/* 404 Route */}
            <Route path="*" element={<NotFound />} />
          </Routes>
          <FloatButton.BackTop />
        </Content>
        <Footer />
      </Router>
    </ProviderWrapper>
  );
});

export default App;
