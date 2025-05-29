import React, { useEffect, useState } from 'react';
import axios from 'axios';
import ShipmentSelector from '../../components/ShipmentSelector.jsx';
import TrackingDetails from '../../components/TrackingDetails.jsx';

export default function UserTracking() {
    const [shipments, setShipments] = useState([]);
    const [trackingStatuses, setTrackingStatuses] = useState([]);
    const [postOffices, setPostOffices] = useState([]);
    const [selectedShipment, setSelectedShipment] = useState(null);
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

                setShipments(shipmentsRes.data);
                setTrackingStatuses(trackingRes.data);
                setPostOffices(postOfficesRes.data);
                setLoading(false);
            } catch (error) {
                console.error('Error fetching data:', error);
                setLoading(false);
            }
        };

        fetchData();
    }, [userId]);

    if (loading) return <div className="text-center py-10 text-gray-500">Loading tracking...</div>;

    return (
        <div className="max-w-4xl mx-auto py-10">
            <ShipmentSelector shipments={shipments} selectedShipment={selectedShipment} setSelectedShipment={setSelectedShipment} />
            {selectedShipment && (
                <>
                    <TrackingDetails shipment={selectedShipment} trackingStatuses={trackingStatuses} postOffices={postOffices} />
                </>
            )}
        </div>
    );
}