import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import UserLayout from "./layouts/UserLayout";
import AdminLayout from "./layouts/AdminLayout";
import ProtectedRoute from "./routes/ProtectedRoute";
import Register from "./pages/Register.jsx";

function App() {
  return (
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />

          <Route
              path="/user/*"
              element={<ProtectedRoute role="ROLE_USER"><UserLayout /></ProtectedRoute>}
          />
          <Route
              path="/admin/*"
              element={<ProtectedRoute role="ROLE_ADMIN"><AdminLayout /></ProtectedRoute>}
          />
        </Routes>
      </BrowserRouter>
  );
}

export default App;
