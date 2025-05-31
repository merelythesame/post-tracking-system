import React, { useEffect, useState } from 'react';
import {Link, Outlet} from "react-router-dom";
import axios from 'axios';

export default function ShipmentsPage() {
    const [shipments, setShipments] = useState([]);
    const [trackingStatuses, setTrackingStatuses] = useState([]);
    const [postOffices, setPostOffices] = useState([]);
    const [loading, setLoading] = useState(true);
    const userId = JSON.parse(localStorage.getItem('user'))?.id;

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [shipmentsRes, trackingRes, postOfficesRes] = await Promise.all([
                    axios.get('http://localhost:8000/shipments', { withCredentials: true }),
                    axios.get('http://localhost:8000/tracking-status', { withCredentials: true }),
                    axios.get('http://localhost:8000/post-office', { withCredentials: true }),
                ]);

                const filteredShipments = shipmentsRes.data.filter(shipment => shipment.user_id === userId);

                setShipments(filteredShipments);
                setTrackingStatuses(trackingRes.data);
                setPostOffices(postOfficesRes.data);
                setLoading(false);
            } catch (error) {
                console.error('Error fetching data:', error);
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    if (loading) return <div className="text-center py-10 text-gray-500">Loading shipments...</div>;

    const combinedShipments = shipments.map((shipment) => {
        const tracking = trackingStatuses.find(status => status.shipment_id === shipment.id);
        const sendOffice = postOffices.find(office => office.id === shipment.sendOffice);
        const receiveOffice = postOffices.find(office => office.id === shipment.receiveOffice);

        return {
            ...shipment,
            status: tracking?.status || '-',
            location: tracking?.location || '-',
            sendOffice: sendOffice || {},
            receiveOffice: receiveOffice || {},
        };
    });

    return (
        <div className="max-w-6xl mx-auto px-4 py-10">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-3xl font-bold text-gray-800">My Shipments</h1>
                <Link
                    to="create"
                    className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600"
                >
                    Create shipment
                </Link>
            </div>



            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {combinedShipments.map((shipment) => (
                    <div key={shipment.id} className="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                        <h2 className="text-xl font-semibold text-indigo-600 mb-2">Shipment #{shipment.id}</h2>
                        <p className="text-gray-700"><span className="font-medium">To:</span> {shipment.receiverName}</p>
                        <p className="text-gray-700"><span className="font-medium">Weight:</span> {shipment.weight} kg</p>
                        <p className="text-gray-700"><span className="font-medium">Type:</span> {shipment.type}</p>
                        <p className="text-gray-700"><span className="font-medium">Status:</span> <span className="capitalize">{shipment.status}</span></p>
                        <p className="text-gray-700"><span className="font-medium">Sending branch:</span> {shipment.sendOffice.name}</p>
                        <p className="text-gray-700"><span className="font-medium">Receiving branch:</span> {shipment.receiveOffice.name}</p>
                    </div>
                ))}
            </div>
        </div>
    );
}
