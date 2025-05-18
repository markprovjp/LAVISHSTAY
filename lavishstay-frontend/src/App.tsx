import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { ConfigProvider, App as AntApp, Layout, theme as antdTheme } from "antd";
import { useSelector } from "react-redux";
import { RootState } from "./store";
import appTheme from "./theme"; // Corrected: Import default export

// Import layouts
import Header from "./components/layouts/Header";
import Footer from "./components/layouts/Footer";
import Breadcrumb from "./components/ui/Breadcrumb";

// Import pages
import Home from "./pages/Home";
import LoginPage from "./pages/Login"; // Assuming Login.tsx exports a default component
import About from "./pages/About";

// Placeholder for pages not yet created
const PlaceholderPage: React.FC<{ title: string }> = ({ title }) => (
  <div style={{ padding: "20px" }}>
    <h1>{title}</h1>
    <p>This page is under construction.</p>
  </div>
);
const HotelListing = () => <PlaceholderPage title="Hotel Listing" />;
const HotelDetails = () => <PlaceholderPage title="Hotel Details" />;
const UserProfile = () => <PlaceholderPage title="User Profile" />;
const UserBookings = () => <PlaceholderPage title="User Bookings" />;
const UserWishlist = () => <PlaceholderPage title="User Wishlist" />;
const UserSettings = () => <PlaceholderPage title="User Settings" />;
const Contact = () => <PlaceholderPage title="Contact Us" />;
const Destinations = () => <PlaceholderPage title="Destinations" />;
const NotificationsPage = () => <PlaceholderPage title="Notifications" />;
const NotFound = () => <PlaceholderPage title="404 - Page Not Found" />;

// Import CSS
import "./App.css";
import "./index.css"; // Ensure Tailwind is imported

const { Content } = Layout;

const App: React.FC = () => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  // Create a dynamic theme based on isDarkMode
  const currentTheme = {
    ...appTheme, // Start with the base theme
    token: {
      ...appTheme.token,
      // Override colors based on dark mode
      colorBgBase: isDarkMode ? '#141414' : '#ffffff',
      colorTextBase: isDarkMode ? '#e0e0e0' : '#1f1f1f',
      // Add other dark mode specific token overrides if needed
    },
    components: {
      ...appTheme.components,
      // Override component styles for dark mode if needed
      Card: {
        ...appTheme.components?.Card,
        colorBgContainer: isDarkMode ? '#1f1f1f' : '#ffffff',
      },
      Menu: {
        ...appTheme.components?.Menu,
        itemSelectedBg: isDarkMode ? '#003a8c' : appTheme.token?.colorPrimary, // Darker blue for selected item in dark mode
        itemColor: isDarkMode ? '#e0e0e0' : undefined,
        itemHoverColor: isDarkMode ? '#ffffff' : undefined,
        subMenuItemBg: isDarkMode ? '#141414' : '#ffffff',
      },
      Layout: {
        colorBgHeader: isDarkMode ? '#1f1f1f' : appTheme.token?.colorPrimary, // Example: Darker header for dark mode
        colorBgBody: isDarkMode ? '#141414' : '#f0f2f5',
      }
    },
    algorithm: isDarkMode ? antdTheme.darkAlgorithm : antdTheme.defaultAlgorithm, // Use Ant Design's dark/default algorithm
  };

  return (
    <ConfigProvider theme={currentTheme}>
      <AntApp className={isDarkMode ? "dark" : "light"}>
        <Router>
          <Header />
          <Breadcrumb />
          <Content
            style={{
              paddingTop: "64px", // Height of the fixed header
              background: currentTheme.token?.colorBgBase, // Use theme token for background
              minHeight: "calc(100vh - 64px - 70px)", // Adjust minHeight based on header and footer height
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
              <Route path="*" element={<NotFound />} />
            </Routes>
          </Content>
          <Footer />
        </Router>
      </AntApp>
    </ConfigProvider>
  );
};

export default App;
