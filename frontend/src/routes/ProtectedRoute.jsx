import { Navigate } from "react-router-dom";

const getStoredUser = () => {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
};

export default function ProtectedRoute({ children, role }) {
    const user = getStoredUser();

    if (!user) return <Navigate to="/" />;
    if (user.role !== role) return <Navigate to="/" />;

    return children;
}