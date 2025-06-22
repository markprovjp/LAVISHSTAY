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



// Import layouts
import Header from "./components/layouts/Header";
import Footer from "./components/layouts/Footer";
import Breadcrumb from "./components/ui/Breadcrumb";
import ScrollToTop from "./utils/ScrollToTop";

// Import pages
import Home from "./pages/Home";
import About from "./pages/About";
import Payment from "./pages/Payment";
import AdminPayment from "./pages/AdminPayment";
import NotFound from "./pages/NotFound";
import AuthTest from "./pages/AuthTest";
import SearchResults from "./pages/SearchResults";
import RoomTypesDetailsPage from "./pages/RoomTypesDetailsPage";

// Import reception components
import ReceptionLayout from "./components/reception/ReceptionLayout";
import ReceptionDashboard from "./pages/reception/ReceptionDashboard";
import RoomManagementToday from "./pages/reception/room-management/RoomManagementToday";
import CheckInManagement from "./pages/reception/room-management/CheckInManagement";
import CheckOutManagement from "./pages/reception/room-management/CheckOutManagement";
import MaintenanceManagement from "./pages/reception/room-management/MaintenanceManagement";

// Import profile components
import {
  ProfileLayout,
  PersonalInfo,
  BookingManagement,
  ForgotPassword,
  Wishlist,
  Notifications,
  Settings,
  ChangePassword
} from "./components/profile";

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

// Import CSS vào nhé
import "./App.css";
import "./index.css";
import "./styles/theme-transitions.css";
import "./styles/global.css";
import "./styles/performance-optimizations.css";

const { Content } = Layout;

const App: React.FC = React.memo(() => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

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
          <Routes>            {/* Main Pages */}
            <Route path="/" element={<Home />} />
            <Route path="/about" element={<About />} />
            <Route path="/room-types/:id" element={<RoomTypesDetailsPage />} />
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

            {/* Legacy routes for backward compatibility */}            {/* Reception Routes with nested routing */}
            <Route path="/reception" element={<ReceptionLayout />}>
              <Route index element={<ReceptionDashboard />} />
              <Route path="dashboard" element={<ReceptionDashboard />} />
              <Route path="room-management" element={<RoomManagementToday />} />
              <Route path="room-management/check-in" element={<CheckInManagement />} />
              <Route path="room-management/check-out" element={<CheckOutManagement />} />
              <Route path="room-management/maintenance" element={<MaintenanceManagement />} />
            </Route>            {/* Other routes */}
            <Route path="/contact" element={<Contact />} />
            <Route path="/destinations" element={<Destinations />} />
            <Route path="/payment" element={<Payment />} />
            <Route path="/admin/payment" element={<AdminPayment />} />
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