import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import UserLayout from "./layouts/UserLayout";
import AdminLayout from "./layouts/AdminLayout";
import ProtectedRoute from "./routes/ProtectedRoute";
import Register from "./pages/Register.jsx";
import UserShipments from "./pages/UserPages/UserShipments.jsx";
import UserTracking from "./pages/UserPages/UserTracking.jsx";
import UserSupport from "./pages/UserPages/UserSupport.jsx";
import Profile from "./pages/Profile.jsx";
import CreateShipment from "./pages/UserPages/CreateShipment.jsx";
import UserReceiving from "./pages/UserPages/UserReceiving.jsx";
import AdminTracking from "./pages/AdminPages/AdminTracking.jsx";
import AdminUsers from "./pages/AdminPages/AdminUsers.jsx";
import AdminTickets from "./pages/AdminPages/AdminTickets.jsx";
import AdminPostOffices from "./pages/AdminPages/AdminPostOffices.jsx";

function App() {
  return (
      <BrowserRouter>
        <Routes>
            <Route path="/" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route
                path="/user"
                element={<ProtectedRoute role="ROLE_USER"><UserLayout /></ProtectedRoute>}
            >
                <Route path="shipments" element={<UserShipments />}/>
                <Route path="receiving" element={<UserReceiving />}/>
                <Route path="shipments/create" element={<CreateShipment />} />
                <Route path="tracking" element={<UserTracking />} />
                <Route path="profile" element={<Profile />} />
                <Route path="support" element={<UserSupport />} />
            </Route>
          <Route
              path="/admin"
              element={<ProtectedRoute role="ROLE_ADMIN"><AdminLayout /></ProtectedRoute>}
          >
              <Route path="tracking" element={<AdminTracking />} />
              <Route path="profile" element={<Profile />} />
              <Route path="users" element={<AdminUsers />} />
              <Route path="tickets" element={<AdminTickets />} />
              <Route path="post-offices" element={<AdminPostOffices />} />
          </Route>
        </Routes>
      </BrowserRouter>
  );
}

export default App;
