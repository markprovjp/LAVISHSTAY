import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { ConfigProvider, FloatButton, App as AntApp, Layout } from "antd";
import { useSelector } from "react-redux";
import { RootState } from "./store";
import ThemeProvider from "./providers/ThemeProvider";
import QueryProvider from "./providers/QueryProvider";
import GoogleAuthProviderWrapper from "./providers/GoogleAuthProvider";
import { SearchProvider } from "./contexts/SearchContext";
import { RoomTypesProvider } from "./contexts/RoomTypesContext";
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
import SearchResults from "./pages/SearchResults";
import RoomTypesDetailsPage from "./pages/RoomTypesDetailsPage";
import BookingConfirmation from "./pages/BookingConfirmation"
import RoomTypesDemo from "./pages/RoomTypesDemo";
import ReviewBooking from "./pages/ReviewBooking";
// Import reception components
import ReceptionLayout from "./components/reception/ReceptionLayout";
import ReceptionDashboard from "./pages/reception/ReceptionDashboard";

import ConfirmRepresentativePayment from "./pages/reception/room-management/ConfirmRepresentativePayment";
import PaymentBookingReception from "./pages/reception/room-management/PaymentBookingReception";
import RoomManagementDashboard from "./pages/reception/room-management/RoomManagementDashboard";
import BookingManagementPage from "./pages/reception/booking-management";
import PaymentSuccess from "./pages/reception/room-management/PaymentSuccess";
import ChatBot from "./components/ui/ChatBot";
import ContactFloatButton from './components/ui/ContactFloatButton';
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

// Import Google OAuth callback
import GoogleOAuthCallback from "./components/auth/GoogleOAuthCallback";

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
import NewsPage from "./pages/NewsPage";
import NewsDetailPage from "./pages/NewsDetailPage";

const { Content } = Layout;

const App: React.FC = React.memo(() => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  // Sử dụng hàm helper để tạo theme dựa trên isDarkMode
  const currentTheme = createAntdTheme(isDarkMode);

  // Memoize provider wrapper to reduce re-renders
  const ProviderWrapper: React.FC<{ children: React.ReactNode }> = React.useMemo(
    () =>
      ({ children }) => (
        <GoogleAuthProviderWrapper>
          <ConfigProvider theme={currentTheme}>
            <ThemeProvider>
              <QueryProvider>
                <SearchProvider>
                  <SearchProvider>
                    <RoomTypesProvider>
                      <AntApp className={isDarkMode ? "dark" : "light"}>
                        {children}
                      </AntApp>
                    </RoomTypesProvider>
                  </SearchProvider>
                </SearchProvider>
              </QueryProvider>
            </ThemeProvider>
          </ConfigProvider>
        </GoogleAuthProviderWrapper>
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
  
            background: currentTheme.token?.colorBgBase, // Sử dụng mã thông báo chủ đề cho nền
           
          }}
        >
          <Routes>            {/* Main Pages */}
            <Route path="/" element={<Home />} />
            <Route path="/about" element={<About />} />
            <Route path="/room-types/:id" element={<RoomTypesDetailsPage />} />
            <Route path="/search" element={<SearchResults />} />
            <Route path="/tin-tuc" element={<NewsPage />} />
            <Route path="/tin-tuc/:slug" element={<NewsDetailPage />} />
            <Route path="/booking/confirmation" element={<BookingConfirmation />} />
            <Route path="/room-types-demo" element={<RoomTypesDemo />} />
            <Route path="/review-booking" element={<ReviewBooking />} />

            {/* Auth Routes */}
            <Route path="/auth/google/callback" element={<GoogleOAuthCallback />} />

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
              <Route path="room-management-list" element={<RoomManagementDashboard />} />
              <Route path="booking-management" element={<BookingManagementPage />} />
              <Route path="confirm-representative-payment" element={<ConfirmRepresentativePayment />} />
              <Route path="payment-booking" element={<PaymentBookingReception />} />
              <Route path="room-management/today" element={<RoomManagementDashboard />} />
              <Route path="payment-success" element={<PaymentSuccess />} />
            </Route>     
                   {/* Other routes */}
            <Route path="/contact" element={<Contact />} />
            <Route path="/destinations" element={<Destinations />} />
            <Route path="/payment" element={<Payment />} />
            <Route path="/admin/payment" element={<AdminPayment />} />

            {/* 404 Route */}
            <Route path="*" element={<NotFound />} />
          </Routes>
          <ContactFloatButton
            phoneNumber="+84123456789"
            facebookUrl="https://www.facebook.com/yourhotel"
            zaloUrl="https://zalo.me/yourphonenumber"
            emailAddress="contact@lavishstay.com"
          />
          <ChatBot />
          <FloatButton.BackTop />
        </Content>
        <Footer />
      </Router>
    </ProviderWrapper>
  );
});

export default App;