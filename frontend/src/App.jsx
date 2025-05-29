import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import UserLayout from "./layouts/UserLayout";
import AdminLayout from "./layouts/AdminLayout";
import ProtectedRoute from "./routes/ProtectedRoute";
import Register from "./pages/Register.jsx";
import UserShipments from "./pages/UserPages/UserShipments.jsx";
import UserTracking from "./pages/UserPages/UserTracking.jsx";
import UserPostOffices from "./pages/UserPages/UserPostOffices.jsx";
import UserSupport from "./pages/UserPages/UserSupport.jsx";
import UserEdit from "./pages/UserPages/UserEdit.jsx";
import CreateShipment from "./pages/UserPages/CreateShipment.jsx";
import UserReceiving from "./pages/UserPages/UserReceiving.jsx";

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
                <Route path="profile" element={<UserEdit />} />
                <Route path="post-offices" element={<UserPostOffices />} />
                <Route path="support" element={<UserSupport />} />
            </Route>
          <Route
              path="/admin/*"
              element={<ProtectedRoute role="ROLE_ADMIN"><AdminLayout /></ProtectedRoute>}
          />
        </Routes>
      </BrowserRouter>
  );
}

export default App;
