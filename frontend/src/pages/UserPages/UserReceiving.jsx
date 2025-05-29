import React, { useEffect, useState } from 'react';
import axios from 'axios';

export default function UserReceiving() {
    const [shipments, setShipments] = useState([]);
    const [trackingStatuses, setTrackingStatuses] = useState([]);
    const [loading, setLoading] = useState(true);
    const userId = JSON.parse(localStorage.getItem('user'))?.id;

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [shipmentsRes, trackingRes, postOfficesRes] = await Promise.all([
                    axios.get('http://localhost:8000/shipments', { withCredentials: true }),
                    axios.get('http://localhost:8000/tracking-status', { withCredentials: true }),
                ]);

                const filteredShipments = shipmentsRes.data.filter(shipment => shipment.receiver_id === userId);

                setShipments(filteredShipments);
                setTrackingStatuses(trackingRes.data);
                setLoading(false);
            } catch (error) {
                console.error('Error fetching data:', error);
                setLoading(false);
            }
        };


        fetchData();
    }, []);

    if (loading) return <div className="text-center py-10 text-gray-500">Loading receiving...</div>;

    const combinedShipments = shipments.map((shipment) => {
        const tracking = trackingStatuses.find(status => status.shipment_id === shipment.id);

        return {
            ...shipment,
            status: tracking?.status || 'Unknown',
            location: tracking?.location || 'Unknown',
        };
    });

    return (
        <div className="max-w-6xl mx-auto px-4 py-10">
            <h1 className="text-3xl font-bold text-gray-800 mb-6">My Receiving</h1>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {combinedShipments.map((shipment) => (
                    <div key={shipment.id} className="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                        <h2 className="text-xl font-semibold text-indigo-600 mb-2">Shipment #{shipment.id}</h2>
                        <p className="text-gray-700"><span className="font-medium">From:</span> {shipment.senderName}</p>
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