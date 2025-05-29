import LayoutComponent from "./LayoutComponent.jsx";
import {Outlet} from "react-router-dom";

const navigation = [
    { name: 'Tracking', path: '/admin/tracking', current: true },
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

