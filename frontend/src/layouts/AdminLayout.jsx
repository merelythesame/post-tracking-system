import LayoutComponent from "./LayoutComponent.jsx";
import {Outlet} from "react-router-dom";

const navigation = [
    { name: 'Tracking', path: '/admin/tracking', current: true },
    { name: 'User', path: '/admin/users', current: true },
    { name: 'Post offices', path: '/admin/post-offices', current: true },
    { name: 'Support tickets', path: '/admin/tickets', current: true },
]
export default function AdminLayout() {
    return (
        <>
            <LayoutComponent navigation={navigation} />
            <main className="p-4">
                <Outlet />
            </main>
        </>
    );
}

