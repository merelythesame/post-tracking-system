import LayoutComponent from "./LayoutComponent.jsx";
import { Outlet } from "react-router-dom";

const navigation = [
    { name: 'Shipment', path: '/user/shipments', current: true },
    { name: 'Receiving', path: '/user/receiving', current: false },
    { name: 'Tracking', path: '/user/tracking', current: false },
    { name: 'Support', path: '/user/support', current: false },
]


export default function UserLayout() {
    return (
        <>
            <LayoutComponent navigation={navigation} />
            <main className="p-4">
                <Outlet />
            </main>
        </>
    );
}

