import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Autocomplete  from "react-google-autocomplete";
import {toast, ToastContainer} from "react-toastify";


function unixToDatetimeLocal(unix) {
    if (!unix) return '';
    return new Date(unix * 1000).toISOString().slice(0, 16);
}

function datetimeLocalToUnix(dt) {
    return dt ? Math.floor(new Date(dt).getTime() / 1000) : null;
}


export default function AdminTracking() {
    const [shipments, setShipments] = useState([]);
    const [trackingStatuses, setTrackingStatuses] = useState([]);
    const [selectedShipment, setSelectedShipment] = useState(null);
    const [showSendModal, setShowSendModal] = useState(false);
    const [location, setLocation] = useState('');
    const [sendAt, setSendAt] = useState('');
    const [arriveAt, setArriveAt] = useState('');
    const [status, setStatus] = useState('');

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const [shipmentsRes, trackingRes] = await Promise.all([
                axios.get('http://localhost:8000/shipments', { withCredentials: true }),
                axios.get('http://localhost:8000/tracking-status', { withCredentials: true })
            ]);
            setShipments(shipmentsRes.data);
            setTrackingStatuses(trackingRes.data);
        } catch (err) {
            toast.error('Error fetching data:', err);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm('Are you sure you want to delete this shipment?')) return;
        try {
            await axios.delete(`http://localhost:8000/shipments/${id}`, { withCredentials: true });
            setShipments(shipments.filter(s => s.id !== id));
            setSelectedShipment(null);
            toast.success('Shipment successfully canceled');
        } catch (err) {
            toast.error('Delete failed:', err);
        }
    };

    const handleSend = async () => {
        if (!selectedShipment) return;

        const tracking = trackingStatuses.find(t => t.shipment_id === selectedShipment.id);
        if (!tracking) return;

        const payload = {};

        if (status) payload.status = status;
        if (location) payload.location = location;
        if (sendAt) payload.send_at = datetimeLocalToUnix(sendAt);
        if (arriveAt) payload.arrive_at = datetimeLocalToUnix(arriveAt);

        if (Object.keys(payload).length === 0) {
            toast.error("Please fill at least one field to update.");
            return;
        }

        try {
            await axios.patch(`http://localhost:8000/tracking-status/${tracking.id}`, payload, {
                withCredentials: true,
            });

            await fetchData();
            setShowSendModal(false);
            setLocation('');
            setSendAt('');
            setArriveAt('');
            setStatus('');
        } catch (err) {
            toast.error('Failed to update tracking:', err);
        }
    };

    const trackingForSelected = trackingStatuses.find(t => t.shipment_id === selectedShipment?.id);

    return (
        <div className="flex h-screen gap-6">
            <ToastContainer position="top-right" autoClose={3000} />
            <div className="w-1/3 shadow-md rounded-2xl overflow-y-auto p-4 bg-gray-50">
                <h2 className="text-xl font-bold mb-4">Shipments</h2>
                {shipments.map((shipment) => (
                    <div
                        key={shipment.id}
                        className={`p-4 flex justify-between mb-2 rounded cursor-pointer ${
                            selectedShipment?.id === shipment.id ? 'bg-blue-100' : 'bg-white'
                        } hover:bg-blue-50 shadow`}
                        onClick={() => setSelectedShipment(shipment)}
                    >
                        <div>
                            <p className="text-gray-700"><span className="font-semibold">Number:</span> {shipment.id}</p>
                            <p className="text-gray-700"><span className="font-semibold">Receiver:</span> {shipment.receiverName}</p>
                            <p className="text-gray-700"><span className="font-semibold">Sender:</span> {shipment.senderName || 'N/A'}</p>
                        </div>
                        <button
                            className="rounded-md bg-red-600 h-fit px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600"
                            onClick={(e) => {
                                e.stopPropagation();
                                handleDelete(shipment.id);
                            }}
                        >Cancel</button>
                    </div>
                ))}
            </div>

            <div className="w-2/3 p-6 overflow-y-auto shadow-md rounded-2xl text-lg bg-gray-50">
                {selectedShipment ? (
                    <div className="flex gap-6">
                        <div className="flex-1 p-6 bg-blue-50 rounded-2xl shadow-md flex flex-col gap-2">
                            <h4 className="text-2xl font-bold mb-4">Shipment Details</h4>
                            <p className="text-gray-700"><strong>Receiver:</strong> {selectedShipment.receiverName}</p>
                            <p className="text-gray-700"><strong>Sender:</strong> {selectedShipment.senderName || 'N/A'}</p>
                            <p className="text-gray-700"><strong>Weight:</strong> {selectedShipment.weight} kg</p>
                            <p className="text-gray-700"><strong>Type:</strong> {selectedShipment.type}</p>
                        </div>

                        <div className="flex-1 p-6 bg-blue-50 rounded-2xl shadow-md flex flex-col gap-2">
                            <h4 className="text-2xl font-bold mb-4">Tracking Info</h4>
                            {trackingForSelected ? (
                                <div className="space-y-2">
                                    <p className="text-gray-700"><strong>Status:</strong> {trackingForSelected.status}</p>
                                    <p className="text-gray-700"><strong>Location:</strong> {trackingForSelected.location || 'N/A'}</p>
                                    <p className="text-gray-700"><strong>Send At:</strong> { unixToDatetimeLocal(trackingForSelected.sendAt) || 'N/A'}</p>
                                    <p className="text-gray-700"><strong>Arrive At:</strong> {unixToDatetimeLocal(trackingForSelected.arriveAt) || 'N/A'}</p>
                                </div>
                            ) : (
                                <p className="text-gray-500">No tracking info available.</p>
                            )}
                        </div>
                    </div>
                ) : (
                    <p className="text-gray-500">Select a shipment to view details</p>
                )}

                {selectedShipment && (
                    <div className='flex justify-center'>
                        <button
                            onClick={() => setShowSendModal(true)}
                            className="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Update Status
                        </button>
                    </div>

                )}
            </div>


            {showSendModal && (
                <div className="fixed inset-0 backdrop-blur-sm flex justify-center items-center z-50">
                    <div className="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                        <h2 className="text-xl font-bold mb-4">Update Tracking</h2>

                        <label className="block mb-2">Status</label>
                        <select
                            value={status}
                            onChange={(e) => setStatus(e.target.value)}
                            className="w-full border p-2 rounded mb-4"
                        >
                            <option value="unsent">Unsent</option>
                            <option value="sent">Sent</option>
                            <option value="arrived">Arrived</option>
                        </select>

                        <label className="block mb-2">Location</label>
                        <Autocomplete
                            apiKey="AIzaSyBZEHh-vdK2z0VSdw_WgfuKPenZF1fC1GQ"
                            onPlaceSelected={(place) => {
                                setLocation(place.formatted_address || place.name);
                            }}
                            options={{
                                types: ['address'],
                                componentRestrictions: { country: 'ua' },
                            }}
                            className="w-full border p-2 rounded mb-4"
                            placeholder="Enter a location"
                        />

                        <label className="block mb-2">Send At</label>
                        <input
                            type="datetime-local"
                            value={sendAt}
                            onChange={(e) => setSendAt(e.target.value)}
                            className="w-full border p-2 rounded mb-4"
                        />

                        <label className="block mb-2">Arrive At</label>
                        <input
                            type="datetime-local"
                            value={arriveAt}
                            onChange={(e) => setArriveAt(e.target.value)}
                            className="w-full border p-2 rounded mb-4"
                        />

                        <div className="flex justify-end gap-4">
                            <button
                                onClick={() => setShowSendModal(false)}
                                className="text-gray-500 hover:underline"
                            >
                                Cancel
                            </button>
                            <button
                                onClick={handleSend}
                                className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            )}

        </div>
    );
} 
