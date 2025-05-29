import React from 'react';
import {unixToDatetimeLocal} from "./dateConvertors.js";

export default function TrackingDetails({ shipment, trackingStatuses, postOffices }) {
    const tracking = trackingStatuses.find(t => t.shipment_id === shipment.id);
    const sendOffice = postOffices.find(p => p.id === shipment.sendOffice);
    const receiveOffice = postOffices.find(p => p.id === shipment.receiveOffice);

    return (
        <>
            <div className="bg-white p-4 rounded shadow mb-6">
                <h2 className="text-xl font-bold mb-2">Tracking Details</h2>
                <p>Status: {tracking?.status || 'Unknown'}</p>
                {tracking?.location !== '' ?
                    <div>
                        <p>Send At: {unixToDatetimeLocal(tracking?.sendAt) || 'N/A'}</p>
                        <p>Arrive At: {unixToDatetimeLocal(tracking?.arriveAt) || 'N/A'}</p>
                    </div>
                    : ''
                }
            </div>
            <div className="flex flex-wrap gap-6 items-start">
                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Sending branch:</p>
                    <p>{sendOffice?.name || 'Unknown'}</p>
                    <p>{sendOffice?.address || 'Unknown'}</p>
                    <p>{sendOffice?.city || 'Unknown'}</p>
                    <p>Postal Code: {sendOffice?.postalCode || 'Unknown'}</p>
                </div>

                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Current location:</p>
                    <p>{tracking?.location || 'Unsent'}</p>
                </div>

                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Receiving branch:</p>
                    <p>{receiveOffice?.name || 'Unknown'}</p>
                    <p>{receiveOffice?.address || 'Unknown'}</p>
                    <p>{receiveOffice?.city || 'Unknown'}</p>
                    <p>Postal Code: {receiveOffice?.postalCode || 'Unknown'}</p>
                </div>
            </div>

        </>
    );
}

