import React from "react";
import { Routes, Route, Navigate } from "react-router-dom";

// Route components
import PrivateRoute from "./PrivateRoute";
import PublicRoute from "./PublicRoute";

// Layouts
import DefaultLayout from "../components/layouts/DefaultLayout";
import DashboardLayout from "../components/layouts/DashboardLayout";
import AuthLayout from "../components/layouts/AuthLayout";

// Pages
import Home from "../pages/Home";
import About from "../pages/About";
import SearchResults from "../pages/SearchResults";
import RoomDetailsPage from "../pages/RoomTypesDetailsPage";
import RoomTypesDemo from "../pages/RoomTypesDemo";
import RoomDetailDemo from "../components/demo/RoomDetailDemo";
// import Login from "../pages/Login";
// import Register from "../pages/Register";
import Dashboard from "../pages/dashboard/Dashboard";


import NotFound from "../pages/NotFound";

const AppRoutes: React.FC = () => {
  return (
    <Routes>
      {" "}      {/* Các tuyến công khai - Có sẵn cho tất cả người dùng */}      <Route element={<PublicRoute />}>
        <Route element={<DefaultLayout />}>
          <Route path="/" element={<Home />} />
          <Route path="/about" element={<About />} />
          <Route path="/search" element={<SearchResults />} />
          <Route path="/rooms/:id" element={<RoomDetailsPage />} />
          <Route path="/room-types/:slug" element={<RoomDetailsPage />} />
          <Route path="/room-types-demo" element={<RoomTypesDemo />} />
          <Route path="/room-detail-demo" element={<RoomDetailDemo />} />
        </Route>
      </Route>
      {/* Các tuyến đường Auth - Chỉ dành cho người dùng không xác định nha */}
      <Route element={<PublicRoute restricted={true} />}>
        <Route element={<AuthLayout />}>
          {/* <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} /> */}
        </Route>
      </Route>      {/* Private Routes - Available only to authenticated users */}
      <Route element={<PrivateRoute />}>
        <Route element={<DashboardLayout />}>
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/profile" element={<Profile />} />
          <Route path="/bookings" element={<Bookings />} />
          <Route path="/reception/schedule" element={<HotelScheduleManagement />} />
        </Route>
      </Route>

      {/* 404 Page */}
      <Route path="/404" element={<NotFound />} />
      {/* Redirect all unknown routes to 404 */}
      <Route path="*" element={<Navigate to="/404" replace />} />
    </Routes>
  );
};

export default AppRoutes;
